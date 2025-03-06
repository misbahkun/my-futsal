import './bootstrap';

import Alpine from 'alpinejs';

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
            if (this.items.length >= 4) {
                alert('Maksimal hanya dapat memilih 4 slot dalam 1 transaksi.');
                return;
            }
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

document.addEventListener('DOMContentLoaded', () => {
  Alpine.start();
});