// Cart functionality
class DigitalMenuCart {
    constructor() {
        this.cart = JSON.parse(localStorage.getItem('digitalMenuCart')) || [];
        this.addingItem = false;
        this.init();
    }

    init() {
        this.updateCartCount();
        this.bindEvents();
    }

    bindEvents() {
    // Cart triggers
        document.querySelectorAll('.cart-trigger').forEach(trigger => {
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                this.openCart();
            });
        });

        // Close cart
        document.querySelector('.close-cart').addEventListener('click', () => this.closeCart());
        document.querySelector('.cart-overlay').addEventListener('click', () => this.closeCart());

        // Send to WhatsApp - BUTTON MIGHT NOT EXIST YET, SO WE'LL BIND IT WHEN CART OPENS
    }

    // Add this method to bind the WhatsApp button when cart opens
    openCart() {
        document.querySelector('.cart-sidebar').classList.add('active');
        document.querySelector('.cart-overlay').classList.add('active');
        this.renderCartItems();
        
        // Bind WhatsApp button AFTER cart is open and button exists
        this.bindWhatsAppButton();
    }

    bindWhatsAppButton() {
        const whatsappBtn = document.querySelector('.btn-send-whatsapp');
        if (whatsappBtn) {
            // Remove any existing event listeners
            whatsappBtn.replaceWith(whatsappBtn.cloneNode(true));
            
            // Get the new button reference
            const newWhatsappBtn = document.querySelector('.btn-send-whatsapp');
            newWhatsappBtn.addEventListener('click', (e) => {
                e.preventDefault();
                console.log('WhatsApp button clicked!');
                this.sendToWhatsApp();
            });
            console.log('WhatsApp button bound successfully');
        } else {
            console.error('WhatsApp button not found in cart');
        }
    }

    addItem(item) {
        console.log('addItem called - Stack trace:', new Error().stack);
        
        // Find existing item with same ID, variations, and addons
        const existingItem = this.cart.find(cartItem => 
            cartItem.id === item.id && 
            JSON.stringify(cartItem.variations) === JSON.stringify(item.variations) &&
            JSON.stringify(cartItem.addons) === JSON.stringify(item.addons)
        );

        if (existingItem) {
            // ADD the new quantity to existing quantity
            existingItem.quantity += item.quantity;
            console.log('Updated existing item:', existingItem.quantity);
        } else {
            // Add as new item
            this.cart.push(item);
            console.log('Added new item:', item.quantity);
        }

        this.saveCart();
        this.updateCartCount();
        this.showAddToCartAnimation();
    }

    removeItem(index) {
        this.cart.splice(index, 1);
        this.saveCart();
        this.updateCartCount();
        this.renderCartItems();
    }

    updateQuantity(index, change) {
        // Validate index
        if (index < 0 || index >= this.cart.length) {
            console.error('Invalid index');
            return;
        }
        
        const currentQuantity = this.cart[index].quantity;
        const newQuantity = currentQuantity + change;
        
        console.log(`Quantity change: ${currentQuantity} + ${change} = ${newQuantity}`);
        
        // ONLY BLOCK if quantity would go below 1
        if (newQuantity < 1) {
            console.log('BLOCKED: Cannot go below 1');
            return;
        }
        
        // REMOVED: No more 10-item limit check
        
        // Only update if validation passed
        this.cart[index].quantity = newQuantity;
        this.saveCart();
        this.updateCartCount();
        this.renderCartItems(); // Re-render to update button states
    }

    saveCart() {
        localStorage.setItem('digitalMenuCart', JSON.stringify(this.cart));
    }

    updateCartCount() {
        const totalItems = this.cart.reduce((sum, item) => sum + item.quantity, 0);
        document.querySelectorAll('.cart-count, .mobile-cart-count').forEach(element => {
            element.textContent = totalItems;
        });
    }

    closeCart() {
        document.querySelector('.cart-sidebar').classList.remove('active');
        document.querySelector('.cart-overlay').classList.remove('active');
    }

    renderCartItems() {
        const cartItemsContainer = document.querySelector('.cart-items');
        const totalAmountElement = document.querySelector('.total-amount');

        if (this.cart.length === 0) {
            cartItemsContainer.innerHTML = `
                <div class="text-center text-muted py-5">
                    <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                    <p>Your cart is empty</p>
                </div>
            `;
            totalAmountElement.textContent = '0.00';
            return;
        }

        let totalAmount = 0;
        cartItemsContainer.innerHTML = this.cart.map((item, index) => {
            const itemTotal = (item.basePrice + 
                (item.variations?.reduce((sum, v) => sum + v.price, 0) || 0) +
                (item.addons?.reduce((sum, a) => sum + a.price, 0) || 0)) * item.quantity;
            
            totalAmount += itemTotal;

            const canDecrease = item.quantity > 1;
            // REMOVED: const canIncrease = item.quantity < 10; // No limit anymore

            return `
                <div class="cart-item animate-slide-up" data-index="${index}">
                    <div class="cart-item-info">
                        <h6>${item.name}</h6>
                        ${item.variations?.length ? `<small>${item.variations.map(v => v.value).join(', ')}</small><br>` : ''}
                        ${item.addons?.length ? `<small>Addons: ${item.addons.map(a => a.name).join(', ')}</small>` : ''}
                        <div class="text-primary fw-bold">$${itemTotal.toFixed(2)}</div>
                    </div>
                    <div class="cart-item-controls">
                        <button class="quantity-btn ${canDecrease ? '' : 'disabled'}" 
                                data-action="decrease" data-index="${index}">
                            <i class="fas fa-minus"></i>
                        </button>
                        <span class="mx-2 fw-bold">${item.quantity}</span>
                        <button class="quantity-btn" 
                                data-action="increase" data-index="${index}">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger ms-2" data-action="remove" data-index="${index}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        }).join('');

        totalAmountElement.textContent = totalAmount.toFixed(2);
        
        // Bind events after rendering
        this.bindCartItemEvents();
    }

    // Add this new method to handle cart item events
    bindCartItemEvents() {
        // Remove all existing event listeners from cart items container first
        const cartItemsContainer = document.querySelector('.cart-items');
        
        // Clone the container to remove all existing event listeners
        const newContainer = cartItemsContainer.cloneNode(true);
        cartItemsContainer.parentNode.replaceChild(newContainer, cartItemsContainer);
        
        // Use event delegation - one listener on the container
        document.querySelector('.cart-items').addEventListener('click', (e) => {
            const button = e.target.closest('button');
            if (!button) return;
            
            const action = button.getAttribute('data-action');
            const index = parseInt(button.getAttribute('data-index'));
            
            if (isNaN(index)) return;
            
            e.preventDefault();
            e.stopPropagation();
            
            console.log(`Button clicked: ${action} for index ${index}`);
            
            switch(action) {
                case 'decrease':
                    this.updateQuantity(index, -1);
                    break;
                case 'increase':
                    this.updateQuantity(index, 1);
                    break;
                case 'remove':
                    this.removeItem(index);
                    break;
            }
        });
    }

    sendToWhatsApp() {
        console.log('=== WHATSAPP BUTTON CLICKED ===');
        console.log('Cart contents:', this.cart);
        
        if (this.cart.length === 0) {
            this.showNotification('Your cart is empty!', 'warning');
            return;
        }

        // Check if business settings are available
        if (!window.businessSettings) {
            console.error('Business settings not found');
            this.showNotification('Business settings not loaded!', 'error');
            return;
        }

        const phone = window.businessSettings.whatsapp_number;
        console.log('Using WhatsApp number:', phone);

        if (!phone) {
            this.showNotification('WhatsApp number not configured!', 'error');
            return;
        }

        let message = `Hello! I would like to order:\n\n`;
        
        this.cart.forEach((item, index) => {
            console.log(`Processing item ${index + 1}:`, item);
            
            const variationsTotal = item.variations?.reduce((sum, v) => sum + (parseFloat(v.price) || 0), 0) || 0;
            const addonsTotal = item.addons?.reduce((sum, a) => sum + (parseFloat(a.price) || 0), 0) || 0;
            const itemTotal = (parseFloat(item.basePrice) + variationsTotal + addonsTotal) * item.quantity;
            
            message += `• ${item.quantity}x ${item.name}\n`;
            
            if (item.variations && item.variations.length > 0) {
                message += `  🎛️ Variations: ${item.variations.map(v => v.name).join(', ')}\n`;
            }
            
            if (item.addons && item.addons.length > 0) {
                message += `  ➕ Addons: ${item.addons.map(a => a.name).join(', ')}\n`;
            }
            
            message += `  💰 Item Total: $${itemTotal.toFixed(2)}\n\n`;
        });

        const totalAmount = this.cart.reduce((sum, item) => {
            const variationsTotal = item.variations?.reduce((sum, v) => sum + (parseFloat(v.price) || 0), 0) || 0;
            const addonsTotal = item.addons?.reduce((sum, a) => sum + (parseFloat(a.price) || 0), 0) || 0;
            return sum + (parseFloat(item.basePrice) + variationsTotal + addonsTotal) * item.quantity;
        }, 0);

        message += `💰 Grand Total: $${totalAmount.toFixed(2)}`;
        message += `\n\nThank you! 🎉`;

        console.log('Final message to send:', message);

        const encodedMessage = encodeURIComponent(message);
        const cleanPhone = phone.replace(/\D/g, ''); // Remove non-digit characters
        const whatsappUrl = `https://wa.me/${cleanPhone}?text=${encodedMessage}`;
        
        console.log('Opening WhatsApp URL:', whatsappUrl);
        
        // Open WhatsApp in new tab
        window.open(whatsappUrl, '_blank');
        
        // Optional: Close cart after sending
        setTimeout(() => {
            this.closeCart();
        }, 1000);
    }

    showAddToCartAnimation() {
        const animation = document.createElement('div');
        animation.innerHTML = '<i class="fas fa-check-circle"></i> Added to cart!';
        animation.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--success-color);
            color: white;
            padding: 15px 25px;
            border-radius: 25px;
            z-index: 3000;
            animation: fadeIn 0.5s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        `;
        
        document.body.appendChild(animation);
        
        setTimeout(() => {
            animation.style.animation = 'fadeOut 0.3s ease forwards';
            setTimeout(() => animation.remove(), 500);
        }, 2000);
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        const icon = type === 'success' ? 'fa-check-circle' : 
                    type === 'warning' ? 'fa-exclamation-triangle' : 
                    type === 'error' ? 'fa-times-circle' : 'fa-info-circle';
        
        const bgColor = type === 'success' ? '#28a745' :
                    type === 'warning' ? '#ffc107' :
                    type === 'error' ? '#dc3545' : '#17a2b8';
        
        notification.innerHTML = `
            <i class="fas ${icon} me-2"></i>
            ${message}
        `;
        
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${bgColor};
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            z-index: 10000;
            animation: slideInRight 0.3s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            max-width: 300px;
            font-weight: 500;
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease forwards';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 4000);
    }
}

// Initialize cart when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.cart = new DigitalMenuCart();
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Add animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.category-card, .item-card').forEach(el => {
        observer.observe(el);
    });
});

// Utility functions
function formatPrice(price) {
    return parseFloat(price).toFixed(2);
}

function animateValue(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        element.innerHTML = formatPrice(progress * (end - start) + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

