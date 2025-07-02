import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Swal = Swal;
window.Alpine = Alpine;

Alpine.store('cart', {
    items: JSON.parse(localStorage.getItem('cart')) || [],

    addToCart(data) {
        // Check if the slot is booked
        if (data.status === 'booked') {
            Swal.fire({
                icon: 'info',
                title: 'Slot sudah terisi',
                text: 'Silakan pilih slot lain.',
            });
            return;
        }

        // Check if the item is already in the cart
        const existingItemIndex = this.items.findIndex(
            item => item.date === data.date && item.field === data.field
        );

        if (existingItemIndex !== -1) {
            // Remove the item if it's already in the cart
            this.items.splice(existingItemIndex, 1);
        } else {
            // Add the item to the cart
            // Add original price to the data object
            const userRole = window.user_role;
            console.log(userRole);

            if (userRole === 'member' || userRole === 'admin') {
                data.originalPrice = this.calculateOriginalPrice(data.price);
            }
            this.items.push(data);
        }

        // Update local storage
        localStorage.setItem('cart', JSON.stringify(this.items));
    },

    calculateOriginalPrice(discountedPrice) {
        return Math.round(discountedPrice / 0.9);
    },

    removeFromCart(index) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                if (index !== -1) {
                    this.items.splice(index, 1);
                    localStorage.setItem('cart', JSON.stringify(this.items));
                }

                Swal.fire({
                    title: "Deleted!",
                    text: "Your item has been deleted.",
                    icon: "success"
                });
            }
        });
    },

    clearCart() {
        this.items = [];
        localStorage.removeItem('cart');
    },

    getCartCount() {
        return this.items.length;
    },

    isInCart(date, field) {
        return this.items.some(item => item.date === date && item.field === field);
    },

    formatPrice(price) {
        return 'Rp ' + price.toLocaleString('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    },
});

Alpine.store('menu', {
    open: false,
    auth: false,

    handleOpen() {
        this.open = !this.open;
    },
    handleAuthClick() {
        this.auth = !this.auth;
    }
});

Alpine.store('payment', {
    // State properties
    showVerificationMethodModal: false,
    showVerificationCodeModal: false,
    selectedMethod: '',
    formValid: false,
    verificationCountdown: 86,
    verificationTimer: null,
    verificationCodeDigits: ['', '', '', '', '', ''],
    currentPhoneNumber: '',
    currentEmail: '',

    // Methods
    checkForm() {
        const form = document.getElementById('paymentForm');
        return form.checkValidity();
    },

    isIndonesianEmail(email) {
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            return false;
        }
        const domain = email.split('@')[1].toLowerCase();
        if (domain.endsWith('.id')) return true;
        const indonesianDomains = ['gmail.com', 'yahoo.com', 'yahoo.co.id', 'telkom.net.id', 'telkomsel.co.id'];
        return indonesianDomains.includes(domain);
    },

    showVerificationSelector() {
        const phoneInput = document.getElementById('phone_number');
        this.currentPhoneNumber = phoneInput ? phoneInput.value.trim() : '';

        const emailInput = document.getElementById('email');
        this.currentEmail = emailInput ? emailInput.value.trim() : '';

        const indonesianPhoneRegex = /^((\+?62|62|0)8[1-9][0-9]{6,10})$/;
        if (!indonesianPhoneRegex.test(this.currentPhoneNumber)) {
            Swal.fire({
                title: 'Nomor Telepon Tidak Valid',
                text: 'Silakan masukkan nomor telepon Indonesia yang valid (contoh: 085959270811, 6285959270811)',
                icon: 'warning',
                confirmButtonColor: '#DC2626',
                confirmButtonText: 'OK'
            });
            if (phoneInput) phoneInput.focus();
            return;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(this.currentEmail)) {
            Swal.fire({
                title: 'Email Tidak Valid',
                text: 'Silakan masukkan alamat email yang valid',
                icon: 'warning',
                confirmButtonColor: '#DC2626',
                confirmButtonText: 'OK'
            });
            if (emailInput) emailInput.focus();
            return;
        }

        if (!this.isIndonesianEmail(this.currentEmail)) {
            Swal.fire({
                title: 'Email Tidak Valid',
                text: 'Silakan gunakan alamat email yang benar',
                icon: 'warning',
                confirmButtonColor: '#DC2626',
                confirmButtonText: 'OK'
            });
            if (emailInput) emailInput.focus();
            return;
        }

        if (this.checkForm()) {
            if (window.isLoggedIn) {
                // For logged-in users, submit the form directly
                document.getElementById('paymentForm').submit();
            } else {
                this.showVerificationMethodModal = true;
            }
        } else {
            document.getElementById('paymentForm').reportValidity();
        }
    },

    selectVerificationMethod(method) {
        this.selectedMethod = method;
        this.verificationCodeDigits = ['', '', '', '', '', ''];

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const requestData = { method };
        if (method === 'email') {
            requestData.email = document.getElementById('email').value;
        } else {
            requestData.phone_number = this.currentPhoneNumber;
        }

        Swal.fire({
            title: 'Memeriksa data...',
            text: 'Mohon tunggu sebentar...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        fetch('/check-user-exists', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(requestData)
        })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.exists) {
                    const message = method === 'email' ?
                        'Email ini sudah terdaftar. Silakan login untuk melanjutkan.' :
                        'Nomor telepon ini sudah terdaftar. Silakan login untuk melanjutkan.';
                    Swal.fire({
                        title: 'Akun sudah terdaftar',
                        text: message,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#DC2626',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Login',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/auth/login';
                        }
                    });
                } else {
                    this.showVerificationMethodModal = false;
                    this.verificationCodeDigits = ['', '', '', '', '', ''];
                    window.sendVerificationCode(method,
                        document.getElementById('phone_number').value,
                        document.getElementById('email').value,
                        () => {
                            this.showVerificationCodeModal = true;
                            this.startVerificationTimer();
                            setTimeout(() => {
                                const inputs = document.querySelectorAll('.verification-code-input input');
                                if (inputs.length > 0) inputs[0].focus();
                            }, 500);
                        });
                }
            })
            .catch(error => {
                Swal.close();
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat memeriksa data. Silakan coba lagi.',
                    icon: 'error',
                    confirmButtonColor: '#DC2626',
                    confirmButtonText: 'OK'
                });
                console.error('Error checking user:', error);
            });
    },

    startVerificationTimer() {
        clearInterval(this.verificationTimer);
        this.verificationCountdown = 86;
        this.verificationTimer = setInterval(() => {
            this.verificationCountdown--;
            if (this.verificationCountdown <= 0) {
                clearInterval(this.verificationTimer);
            }
        }, 1000);
    },

    formatCountdown() {
        const minutes = Math.floor(this.verificationCountdown / 60);
        const seconds = this.verificationCountdown % 60;
        return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    },

    onDigitInput(index, event) {
        const digit = event.target.value;
        if (!/^[0-9]$/.test(digit)) {
            event.target.value = '';
            this.verificationCodeDigits[index] = '';
            return;
        }
        this.verificationCodeDigits[index] = digit;
        console.log("Updated digits array:", this.verificationCodeDigits);
        if (index < 5 && digit !== '') {
            const inputs = document.querySelectorAll('.verification-code-input input');
            if (inputs.length > index + 1) {
                inputs[index + 1].focus();
            }
        }
    },

    onDigitKeydown(index, event) {
        if (event.key === 'Backspace') {
            if (this.verificationCodeDigits[index] === '') {
                if (index > 0) {
                    this.verificationCodeDigits[index - 1] = '';
                    const inputs = document.querySelectorAll('.verification-code-input input');
                    if (inputs.length > index - 1) {
                        inputs[index - 1].focus();
                    }
                }
            } else {
                this.verificationCodeDigits[index] = '';
            }
        }
        if (event.key === 'ArrowLeft' && index > 0) {
            const inputs = document.querySelectorAll('.verification-code-input input');
            if (inputs.length > index - 1) {
                inputs[index - 1].focus();
            }
        }
        if (event.key === 'ArrowRight' && index < 5) {
            const inputs = document.querySelectorAll('.verification-code-input input');
            if (inputs.length > index + 1) {
                inputs[index + 1].focus();
            }
        }
    },

    submitVerificationCode() {
        console.log("Verification code digits:", this.verificationCodeDigits);

        if (!this.verificationCodeDigits || !Array.isArray(this.verificationCodeDigits)) {
            this.verificationCodeDigits = ['', '', '', '', '', ''];
        }

        if (this.verificationCodeDigits.every(d => d === '')) {
            const inputs = document.querySelectorAll('.verification-code-input input');
            if (inputs && inputs.length === 6) {
                for (let i = 0; i < 6; i++) {
                    this.verificationCodeDigits[i] = inputs[i].value || '';
                }
            }
        }

        console.log("Final verification code digits:", this.verificationCodeDigits);

        const allFilled = this.verificationCodeDigits.every(d => d !== '' && d !== null && d !== undefined);
        if (!allFilled) {
            Swal.fire({
                title: 'Kode tidak lengkap',
                text: 'Mohon masukkan 6 digit kode verifikasi',
                icon: 'error',
                confirmButtonColor: '#DC2626',
                confirmButtonText: 'OK'
            });
            return;
        }

        const verificationCode = this.verificationCodeDigits.join('');
        console.log("Submitting verification code:", verificationCode);

        Swal.fire({
            title: 'Memverifikasi kode',
            text: 'Mohon tunggu sebentar...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        const formData = new FormData(document.getElementById('paymentForm'));
        formData.append('verification_code', verificationCode);
        formData.append('verification_method', this.selectedMethod);

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        fetch('/book', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Verification failed');
                    });
                }
                return response.json();
            })
            .then(data => {
                Swal.close();
                if (data.success) {
                    this.showVerificationCodeModal = false;
                    if (data.snapToken) {
                        window.snap.pay(data.snapToken, {
                            onSuccess: function (result) {
                                console.log('Payment success:', result);
                                Alpine.store('cart').clearCart();
                                window.location.href = "{{ route('home') }}";
                            },
                            onPending: function (result) {
                                console.log('Payment pending:', result);
                                window.location.href = "{{ route('home') }}";
                            },
                            onError: function (result) {
                                console.log('Payment error:', result);
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan saat pembayaran.',
                                    icon: 'error',
                                    confirmButtonColor: '#DC2626',
                                    confirmButtonText: 'OK'
                                });
                            },
                            onClose: function () {
                                console.log('Payment popup closed');
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Token pembayaran tidak tersedia.',
                            icon: 'error',
                            confirmButtonColor: '#DC2626',
                            confirmButtonText: 'OK'
                        });
                    }
                } else {
                    Swal.fire({
                        title: 'Verifikasi Gagal',
                        text: data.message || 'Kode verifikasi tidak valid',
                        icon: 'error',
                        confirmButtonColor: '#DC2626',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                Swal.close();
                Swal.fire({
                    title: 'Error!',
                    text: error.message || 'Terjadi kesalahan saat verifikasi',
                    icon: 'error',
                    confirmButtonColor: '#DC2626',
                    confirmButtonText: 'OK'
                });
            });
    },

    resendVerificationCode() {
        if (this.verificationCountdown > 0) return;
        window.sendVerificationCode(
            this.selectedMethod,
            document.getElementById('phone_number').value,
            document.getElementById('email').value,
            () => {
                this.startVerificationTimer();
            }
        );
    },
});

Alpine.store('userManagement', {
    // Function to confirm deletion with SweetAlert2
    confirmDelete(userId, userName) {
        Swal.fire({
            title: 'Confirm Deletion',
            text: `Are you sure you want to delete the user "${userName}"? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DC2626',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the hidden form
                document.getElementById(`delete-form-${userId}`).submit();
            }
        });
    },

    // Function to edit user
    editUser(userId) {
        // Show loading state
        Swal.fire({
            title: 'Loading...',
            text: 'Fetching user data for editing',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        // Get the Alpine component and set the editModal to true
        const editComponent = Alpine.$data(document.querySelector('[x-data*="editModal"]'));
        editComponent.editUserId = userId;

        // Fetch user data via AJAX
        fetch(`/dashboard/users/${userId}/edit`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                Swal.close();

                // Populate the form
                document.getElementById('edit_full_name').value = data.full_name;
                document.getElementById('edit_username').value = data.username;
                document.getElementById('edit_email').value = data.email;

                // Format phone number to remove +62 prefix for the input field
                let phoneNumber = data.phone_number;
                if (phoneNumber.startsWith('+62')) {
                    phoneNumber = phoneNumber.substring(3);
                }
                document.getElementById('edit_phone_number').value = phoneNumber;

                document.getElementById('edit_address').value = data.address || '';
                document.getElementById('edit_role').value = data.role;
                document.getElementById('edit_verified').checked = data.email_verified_at !== null;

                // Open the modal
                editComponent.editModal = true;
            })
            .catch(error => {
                console.error('Error fetching user data:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to load user data for editing. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#DC2626',
                    confirmButtonText: 'OK'
                });
            });
    }
});

document.addEventListener('DOMContentLoaded', () => {
    Alpine.start();
});