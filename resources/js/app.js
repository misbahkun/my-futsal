import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Swal = Swal;
window.Alpine = Alpine

Alpine.store('cart', {
    items: JSON.parse(localStorage.getItem('cart')) || [],

    addToCart(data) {
        if (data.status === 'booked') {
            alert('Slot sudah terisi.');
            return;
        }
        if (this.items.some(item => item.date === data.date && item.field === data.field)) {
            this.items = this.items.filter(item => item.date !== data.date || item.field !== data.field);
        } else {
            this.items.push(data);
        }
        localStorage.setItem('cart', JSON.stringify(this.items));
    },

    removeFromCart(index) {
        const y = confirm('Ingin hapus slot ini ?')

        if (y) {
            if (index !== -1) {
                this.items.splice(index, 1);
                localStorage.setItem('cart', JSON.stringify(this.items));
            }
        }
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
        this.open = !this.open
    },
    handleAuthClick() {
        this.auth = !this.auth
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
        // Basic email validation
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            return false;
        }

        // Get the domain part
        const domain = email.split('@')[1].toLowerCase();

        // Check for Indonesian TLD (.id, .co.id, .ac.id, etc.)
        if (domain.endsWith('.id')) return true;

        // List of common Indonesian email providers or universities
        const indonesianDomains = [
            'gmail.com',        // Common internationally, but widely used in Indonesia
            'yahoo.com',        // Common internationally, but widely used in Indonesia
            'yahoo.co.id',      // Indonesian Yahoo domain
            'telkom.net.id',    // Telkom Indonesia
            'telkomsel.co.id',  // Telkomsel
            // Add other Indonesian email domains as needed
        ];

        return indonesianDomains.includes(domain);
    },

    showVerificationSelector() {
        // Check if payment method is selected
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        let isMethodSelected = false;

        for (let i = 0; i < paymentMethods.length; i++) {
            if (paymentMethods[i].checked) {
                isMethodSelected = true;
                break;
            }
        }

        if (!isMethodSelected) {
            // Show SweetAlert for payment method validation
            Swal.fire({
                title: 'Metode Pembayaran Diperlukan',
                text: 'Silakan pilih metode pembayaran terlebih dahulu',
                icon: 'warning',
                confirmButtonColor: '#DC2626',
                confirmButtonText: 'OK'
            });

            return;
        }

        const phoneInput = document.getElementById('phone_number');
        this.currentPhoneNumber = phoneInput ? phoneInput.value.trim() : '';

        const emailInput = document.getElementById('email');
        this.currentEmail = emailInput ? emailInput.value.trim() : '';

        // Indonesian phone number validation
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

        // Basic email validation
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
                title: 'Email Non-Indonesia',
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

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        // Prepare data based on verification method
        const requestData = {
            method: method
        };

        // Only send relevant data based on selected method
        if (method === 'email') {
            requestData.email = document.getElementById('email').value;
        } else { // whatsapp or miscall
            requestData.phone_number = this.currentPhoneNumber;
        }

        // Show loading
        Swal.fire({
            title: 'Memeriksa data...',
            text: 'Mohon tunggu sebentar...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        // Check if user exists with this specific verification method
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
                    // User exists with this method, show login prompt
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
                    // User doesn't exist with this method, proceed with verification
                    this.showVerificationMethodModal = false;

                    // Clear verification code digits
                    this.verificationCodeDigits = ['', '', '', '', '', ''];

                    // Send verification code
                    window.sendVerificationCode(method,
                        document.getElementById('phone_number').value,
                        document.getElementById('email').value,
                        () => {
                            // Success callback - show code entry modal and start timer
                            this.showVerificationCodeModal = true;
                            this.startVerificationTimer();

                            // Focus the first digit input
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
        // Reset timer
        clearInterval(this.verificationTimer);
        this.verificationCountdown = 86; // 1:26 in seconds

        // Start countdown
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
        // Get the digit
        const digit = event.target.value;
        console.log(`Digit input at index ${index}: ${digit}`);

        // If not a digit, clear the input and return
        if (!/^[0-9]$/.test(digit)) {
            event.target.value = '';
            this.verificationCodeDigits[index] = '';
            return;
        }

        // Store the digit - ensure we're updating the array properly
        this.verificationCodeDigits[index] = digit;
        console.log("Updated digits array:", this.verificationCodeDigits);

        // Move to next input if available
        if (index < 5 && digit !== '') {
            const inputs = document.querySelectorAll('.verification-code-input input');
            if (inputs.length > index + 1) {
                inputs[index + 1].focus();
            }
        }
    },

    onDigitKeydown(index, event) {
        // Handle backspace - move to previous input
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

        // Move focus with arrow keys
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

        // Ensure verificationCodeDigits exists and is properly initialized
        if (!this.verificationCodeDigits || !Array.isArray(this.verificationCodeDigits)) {
            this.verificationCodeDigits = ['', '', '', '', '', ''];
        }

        // Get directly from input fields as a backup if the array is not working
        if (this.verificationCodeDigits.every(d => d === '')) {
            const inputs = document.querySelectorAll('.verification-code-input input');
            if (inputs && inputs.length === 6) {
                for (let i = 0; i < 6; i++) {
                    this.verificationCodeDigits[i] = inputs[i].value || '';
                }
            }
        }

        console.log("Final verification code digits:", this.verificationCodeDigits);

        // Check if all digits are filled
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

        // Get verification code
        const verificationCode = this.verificationCodeDigits.join('');
        console.log("Submitting verification code:", verificationCode);

        // Show loading
        Swal.fire({
            title: 'Memverifikasi kode',
            text: 'Mohon tunggu sebentar...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        // Get form data for creating user if needed
        const formData = new FormData(document.getElementById('paymentForm'));

        // Add verification code and method
        formData.append('verification_code', verificationCode);
        formData.append('verification_method', this.selectedMethod);

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        // Send to verify-and-book endpoint
        fetch('/verify-and-book', {
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
                    // Close the verification modal
                    this.showVerificationCodeModal = false;

                    // Store verification info in hidden inputs for form submission
                    const codeInput = document.createElement('input');
                    codeInput.type = 'hidden';
                    codeInput.name = 'verification_code';
                    codeInput.value = verificationCode;

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = 'verification_method';
                    methodInput.value = this.selectedMethod;

                    // Add inputs to form
                    const form = document.getElementById('paymentForm');
                    form.appendChild(codeInput);
                    form.appendChild(methodInput);

                    // Show success message and handle submission after
                    Swal.fire({
                        title: 'Verifikasi Berhasil',
                        text: 'Memproses pembayaran...',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000, // Longer timer
                        didClose: () => {
                            Alpine.store('cart').clearCart();
                            form.submit();
                        }
                    });
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

        // Call the external function to resend the code
        window.sendVerificationCode(
            this.selectedMethod,
            document.getElementById('phone_number').value,
            document.getElementById('email').value,
            () => {
                // Reset timer on success
                this.startVerificationTimer();
            }
        );
    },
});

// User Management Alpine.js Store
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

    // Function to view user details
    viewUser(userId) {
        console.log('Viewing user:', userId);

        // Show loading state
        Swal.fire({
            title: 'Loading...',
            text: 'Fetching user details',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        // Get the Alpine component and set the viewModal to true
        const viewComponent = Alpine.$data(document.querySelector('[x-data*="viewModal"]'));

        // Fetch user data via AJAX
        fetch(`/admin/users/${userId}`)
            .then(response => response.json())
            .then(data => {
                Swal.close();
                viewComponent.viewUser = data;
                viewComponent.viewModal = true;
            })
            .catch(error => {
                console.error('Error fetching user data:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to load user details. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#DC2626',
                    confirmButtonText: 'OK'
                });
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
        fetch(`/admin/users/${userId}/edit`)
            .then(response => response.json())
            .then(data => {
                Swal.close();
                // Populate the form
                document.getElementById('edit_full_name').value = data.full_name;
                document.getElementById('edit_username').value = data.username;
                document.getElementById('edit_email').value = data.email;
                document.getElementById('edit_phone_number').value = data.phone_number.replace('+62', '');
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
    },
});

document.addEventListener('DOMContentLoaded', () => {
    Alpine.start();

    // Setup CSRF token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Add CSRF token to all fetch requests headers
    const originalFetch = window.fetch;
    window.fetch = function () {
        let [resource, config] = arguments;
        config = config || {};
        config.headers = config.headers || {};

        if (!(config.headers instanceof Headers)) {
            config.headers['X-CSRF-TOKEN'] = csrfToken;
        }

        return originalFetch(resource, config);
    };
});