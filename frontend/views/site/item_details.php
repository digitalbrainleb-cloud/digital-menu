<?php
use yii\helpers\Html;
use yii\helpers\Url;

// DEBUG: Check what variations are loaded
echo "<!-- DEBUG: Variations count: " . count($item->variations) . " -->\n";
echo "<!-- DEBUG: Item ID: " . $item->id . " -->\n";
if (!empty($item->variations)) {
    foreach ($item->variations as $index => $variation) {
        echo "<!-- DEBUG: Variation $index - Type: " . $variation->type . ", Value: " . $variation->value . " -->\n";
    }
} else {
    echo "<!-- DEBUG: No variations found -->\n";
}

?>

<!-- Item Details Header -->
<section class="hero-section" style="padding: 120px 0 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="hero-content text-center">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="item-detail-image" 
                         style="background-image: url('<?= $item->getImageUrl() ?: 'https://via.placeholder.com/400x300?text=No+Image' ?>')"
                         onclick="openImageModal('<?= $item->getImageUrl() ?: 'https://via.placeholder.com/1200x800?text=No+Image' ?>', '<?= Html::encode($item->name) ?>')">
                        <div class="image-overlay">
                            <i class="fas fa-expand-arrows-alt fa-2x"></i>
                            <p>Click to view full size</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 text-white text-md-start text-center">
                    <h1 class="display-4 fw-bold mb-3"><?= Html::encode($item->name) ?></h1>
                    <p class="lead mb-4"><?= Html::encode($item->description) ?></p>
                    <div class="base-price-section">
                        <h3 class="text-warning">$<span id="base-price"><?= number_format($item->base_price, 2) ?></span></h3>
                        <small class="text-light">Base Price</small>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <a href="<?= Url::to(['/site/category', 'id' => $item->category_id]) ?>" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-arrow-left me-2"></i> Back to Item
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Item Customization Section -->
<section class="customization-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <!-- Variations Section -->
                        <?php if (!empty($item->variations)): ?>
                        <div class="variations-section mb-5">
                            <h4 class="fw-bold mb-4 text-primary">
                                <i class="fas fa-list me-2"></i>Customize Your Order
                            </h4>
                            
                            <?php
                            $variationsByType = [];
                            foreach ($item->variations as $variation) {
                                $variationsByType[$variation->type][] = $variation;
                            }
                            ?>
                            
                            <?php foreach ($variationsByType as $type => $variations): ?>
                            <div class="variation-group mb-4">
                                <label class="form-label fw-bold h5"><?= Html::encode($type) ?></label>
                                <select class="form-select form-select-lg variation-select" data-type="<?= Html::encode($type) ?>">
                                    <option value="" data-price="0">Choose <?= Html::encode($type) ?></option>
                                    <?php foreach ($variations as $variation): ?>
                                    <?php 
                                        // Get the actual price from the variation object
                                        $price = isset($variation->price) ? $variation->price : 0;
                                        $priceDisplay = $price > 0 ? '+$' . number_format($price, 2) : '(+$0.00)';
                                    ?>
                                    <option value="<?= $variation->id ?>" data-price="<?= $price ?>">
                                        <?= Html::encode($variation->value) ?> <?= $priceDisplay ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Addons Section -->
                        <?php if (!empty($item->itemAddons)): ?>
                        <div class="addons-section mb-5">
                            <h4 class="fw-bold mb-4 text-success">
                                <i class="fas fa-plus me-2"></i>Add Extra Toppings
                            </h4>
                            <div class="addons-grid">
                                <?php foreach ($item->itemAddons as $addon): ?>
                                <div class="addon-item">
                                    <div class="form-check">
                                        <input class="form-check-input addon-checkbox" type="checkbox" 
                                               id="addon-<?= $addon->id ?>" data-id="<?= $addon->id ?>" 
                                               data-price="<?= $addon->price ?>" data-name="<?= Html::encode($addon->name) ?>">
                                        <label class="form-check-label" for="addon-<?= $addon->id ?>">
                                            <span class="addon-name"><?= Html::encode($addon->name) ?></span>
                                            <span class="addon-price">+$<?= number_format($addon->price, 2) ?></span>
                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Quantity Selector -->
                        <div class="quantity-section">
                            <h4 class="fw-bold mb-4 text-info">
                                <i class="fas fa-sort-amount-up me-2"></i>Quantity
                            </h4>
                            <div class="quantity-selector d-flex align-items-center gap-3">
                                <button class="btn btn-outline-secondary quantity-btn" id="decrease-qty">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" id="quantity" class="form-control text-center" 
                                    value="1" min="1" style="max-width: 80px;">
                                <button class="btn btn-outline-secondary quantity-btn" id="increase-qty">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <span class="text-muted ms-3"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 100px;">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-primary text-white py-3">
                            <h4 class="mb-0"><i class="fas fa-receipt me-2"></i>Order Summary</h4>
                        </div>
                        <div class="card-body">
                            <!-- Price Breakdown -->
                            <div class="price-breakdown mb-4">
                                <div class="price-row d-flex justify-content-between mb-2">
                                    <span>Base Price:</span>
                                    <strong>$<span id="summary-base"><?= number_format($item->base_price, 2) ?></span></strong>
                                </div>
                                
                                <div class="price-row d-flex justify-content-between mb-2 variations-breakdown" style="display: none !important;">
                                    <span>Variations:</span>
                                    <strong class="text-success">+$<span id="summary-variations">0.00</span></strong>
                                </div>
                                
                                <div class="price-row d-flex justify-content-between mb-2 addons-breakdown" style="display: none !important;">
                                    <span>Addons:</span>
                                    <strong class="text-success">+$<span id="summary-addons">0.00</span></strong>
                                </div>
                                
                                <hr>
                                <div class="price-row d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <strong>$<span id="summary-subtotal"><?= number_format($item->base_price, 2) ?></span></strong>
                                </div>
                                
                                <div class="price-row d-flex justify-content-between mb-2">
                                    <span>Quantity:</span>
                                    <strong><span id="summary-quantity">1</span>x</strong>
                                </div>
                                
                                <hr>
                                <div class="total-price d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Total:</h5>
                                    <h3 class="mb-0 text-primary">$<span id="summary-total"><?= number_format($item->base_price, 2) ?></span></h3>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="action-buttons d-grid gap-3">
                                <button class="btn btn-success btn-lg py-3 fw-bold" id="quick-order-btn">
                                    <i class="fab fa-whatsapp me-2"></i>Quick Order Now
                                </button>
                                <button class="btn btn-primary btn-lg py-3 fw-bold" id="add-to-cart-btn" onclick="addToCart()">
                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>

                            <!-- Cart Notification -->
                            <div id="cart-notification" class="alert alert-success mt-3" style="display: none;">
                                <i class="fas fa-check-circle me-2"></i>
                                <span id="notification-message"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Full Screen Image Modal (Same as before) -->
<div id="imageModal" class="image-modal">
    <span class="close-modal" onclick="closeImageModal()">&times;</span>
    <div class="modal-content">
        <img id="modalImage" src="" alt="">
        <div class="image-caption">
            <h4 id="modalCaption"></h4>
        </div>
    </div>
    <div class="modal-actions">
        <button class="btn btn-light" onclick="closeImageModal()">
            <i class="fas fa-times me-2"></i>Close
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const basePrice = <?= $item->base_price ?>;
    let variationsTotal = 0;
    let addonsTotal = 0;
    let quantity = 1;
    let selectedVariations = [];
    let selectedAddons = [];

    // Quantity Controls
    document.getElementById('increase-qty').addEventListener('click', function() {
        const qtyInput = document.getElementById('quantity');
        if (qtyInput.value < 100) {
            qtyInput.value = parseInt(qtyInput.value) + 1;
            quantity = qtyInput.value;
            updateSummary();
        }
    });

    document.getElementById('decrease-qty').addEventListener('click', function() {
        const qtyInput = document.getElementById('quantity');
        if (qtyInput.value > 1) {
            qtyInput.value = parseInt(qtyInput.value) - 1;
            quantity = qtyInput.value;
            updateSummary();
        }
    });

    // Variation Selection
    document.querySelectorAll('.variation-select').forEach(select => {
        select.addEventListener('change', function() {
            const type = this.getAttribute('data-type');
            const selectedOption = this.options[this.selectedIndex];
            const variationId = selectedOption.value;
            const variationText = selectedOption.textContent;
            const variationPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            
            console.log('Variation selected:', { type, variationId, variationPrice, variationText });
            
            // Remove existing variation of same type
            selectedVariations = selectedVariations.filter(v => v.type !== type);
            
            // Add new variation if selected
            if (variationId) {
                // Extract variation name without price
                const variationName = variationText.split(' (+')[0].trim();
                
                selectedVariations.push({
                    id: variationId,
                    type: type,
                    name: variationName,
                    price: variationPrice
                });
            }
            
            console.log('Selected variations:', selectedVariations);
            updateSummary();
        });
    });

    // Addon Selection
    document.querySelectorAll('.addon-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const addonId = this.getAttribute('data-id');
            const addonPrice = parseFloat(this.getAttribute('data-price'));
            const addonName = this.getAttribute('data-name');
            
            if (this.checked) {
                selectedAddons.push({
                    id: addonId,
                    name: addonName,
                    price: addonPrice
                });
            } else {
                selectedAddons = selectedAddons.filter(addon => addon.id !== addonId);
            }
            
            updateSummary();
        });
    });

    // Quick Order Button
    document.getElementById('quick-order-btn').addEventListener('click', function() {
        const message = generateOrderMessage();
        const phone = '<?= preg_replace('/\D/', '', $businessSettings->whatsapp_number) ?>';
        const whatsappUrl = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
        window.open(whatsappUrl, '_blank');
    });

    // Add to Cart Button
    document.getElementById('add-to-cart-btn').addEventListener('click', function() {
        const cartItem = {
            id: <?= $item->id ?>,
            name: '<?= addslashes($item->name) ?>',
            basePrice: basePrice,
            variations: selectedVariations,
            addons: selectedAddons,
            quantity: parseInt(quantity), // Convert to number
            image: '<?= $item->getImageUrl() ?>'
        };

        if (window.cart) {
            window.cart.addItem(cartItem);
        } else {
            console.error('Cart system not initialized');
        }
    });

    function updateSummary() {
        // Calculate totals
        variationsTotal = selectedVariations.reduce((sum, variation) => sum + parseFloat(variation.price), 0);
        addonsTotal = selectedAddons.reduce((sum, addon) => sum + parseFloat(addon.price), 0);
        
        const subtotal = parseFloat(basePrice) + variationsTotal + addonsTotal;
        const total = subtotal * quantity;
        
        console.log('Price calculation:', {
            basePrice,
            variationsTotal,
            addonsTotal,
            subtotal,
            quantity,
            total
        });
        
        // Update display
        document.getElementById('summary-base').textContent = parseFloat(basePrice).toFixed(2);
        document.getElementById('summary-variations').textContent = variationsTotal.toFixed(2);
        document.getElementById('summary-addons').textContent = addonsTotal.toFixed(2);
        document.getElementById('summary-subtotal').textContent = subtotal.toFixed(2);
        document.getElementById('summary-quantity').textContent = quantity;
        document.getElementById('summary-total').textContent = total.toFixed(2);
        
        // Show/hide breakdown sections
        const variationsBreakdown = document.querySelector('.variations-breakdown');
        const addonsBreakdown = document.querySelector('.addons-breakdown');
        
        if (variationsBreakdown) {
            variationsBreakdown.style.display = variationsTotal > 0 ? 'flex' : 'none';
        }
        
        if (addonsBreakdown) {
            addonsBreakdown.style.display = addonsTotal > 0 ? 'flex' : 'none';
        }
    }

    function generateOrderMessage() {
        let message = "Hello! I would like to order:\n\n";
        message += `• ${quantity}x <?= addslashes($item->name) ?>\n`;
        
        if (selectedVariations.length > 0) {
            message += "  Customizations:\n";
            selectedVariations.forEach(variation => {
                message += `    - ${variation.type}: ${variation.name}\n`;
            });
        }
        
        if (selectedAddons.length > 0) {
            message += "  Addons:\n";
            selectedAddons.forEach(addon => {
                message += `    - ${addon.name} (+$${addon.price.toFixed(2)})\n`;
            });
        }
        
        const total = (basePrice + variationsTotal + addonsTotal) * quantity;
        message += `\nTotal: $${total.toFixed(2)}\n\n`;
        message += "Thank you!";
        
        return message;
    }

    function showNotification(message, type = 'success') {
        const notification = document.getElementById('cart-notification');
        const messageEl = document.getElementById('notification-message');
        
        messageEl.textContent = message;
        notification.className = `alert alert-${type} mt-3`;
        notification.style.display = 'block';
        
        // Auto hide after 3 seconds
        setTimeout(() => {
            notification.style.display = 'none';
        }, 3000);
    }

    // Initialize summary
    updateSummary();
});

// Full Screen Image Modal Functions (Same as before)
function openImageModal(imageSrc, caption) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const captionText = document.getElementById('modalCaption');
    
    modal.style.display = 'block';
    modalImg.src = imageSrc;
    captionText.textContent = caption;
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

</script>

<style>
/* Custom Styles for Item Details Page */
.item-detail-image {
    height: 300px;
    background-size: cover;
    background-position: center;
    border-radius: 20px;
    cursor: zoom-in;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.item-detail-image .image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
    color: white;
    text-align: center;
}

.item-detail-image:hover .image-overlay {
    opacity: 1;
}

.addons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
}

.addon-item {
    background: white;
    padding: 15px;
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.addon-item:hover {
    border-color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.addon-item .form-check-label {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    cursor: pointer;
}

.addon-name {
    font-weight: 500;
}

.addon-price {
    color: var(--success-color);
    font-weight: 600;
}

.quantity-btn {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.price-breakdown {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
}

.total-price {
    border-top: 2px solid #dee2e6;
    padding-top: 15px;
}

/* Full Screen Image Modal Styles (Same as before) */
.image-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.95);
}

.close-modal {
    position: absolute;
    top: 20px;
    right: 35px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    z-index: 10000;
}

.modal-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    padding: 20px;
}

#modalImage {
    max-width: 90%;
    max-height: 70vh;
    object-fit: contain;
    border-radius: 15px;
}

.image-caption {
    margin-top: 20px;
    text-align: center;
    color: white;
}

.modal-actions {
    position: absolute;
    bottom: 30px;
    left: 0;
    right: 0;
    text-align: center;
}

@media (max-width: 768px) {
    .item-detail-image {
        height: 200px;
        margin-bottom: 20px;
    }
    
    .addons-grid {
        grid-template-columns: 1fr;
    }
}
</style>
<?php include 'footer.php'; ?>