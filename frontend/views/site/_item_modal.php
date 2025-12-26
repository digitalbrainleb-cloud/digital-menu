<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="item-modal-image" 
     style="background-image: url('<?= $item->getImageUrl() ?: 'https://via.placeholder.com/800x400?text=Item' ?>')"
     onclick="openImageModal('<?= $item->getImageUrl() ?: 'https://via.placeholder.com/1200x800?text=Item' ?>', '<?= Html::encode($item->name) ?>')">
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-md-8">
            <h3 class="fw-bold mb-2"><?= Html::encode($item->name) ?></h3>
            <p class="text-muted mb-3"><?= Html::encode($item->description) ?></p>
            
            <!-- Variations -->
            <?php if (!empty($item->variations)): ?>
            <div class="variation-section">
                <h6 class="fw-bold mb-3">Choose Variations:</h6>
                <div class="variation-options">
                    <?php
                    $variationsByType = [];
                    foreach ($item->variations as $variation) {
                        $variationsByType[$variation->type][] = $variation;
                    }
                    ?>
                    
                    <?php foreach ($variationsByType as $type => $variations): ?>
                    <div class="mb-4">
                        <label class="form-label fw-bold"><?= Html::encode($type) ?></label>
                        <?php foreach ($variations as $variation): ?>
                        <div class="variation-option" data-variation-id="<?= $variation->id ?>">
                            <div>
                                <strong><?= Html::encode($variation->value) ?></strong>
                            </div>
                            <div class="text-success fw-bold">
                                +$<?= number_format(0, 2) ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Addons -->
            <?php if (!empty($item->itemAddons)): ?>
            <div class="addons-section">
                <h6 class="fw-bold mb-3">Addons:</h6>
                <div class="addon-options">
                    <?php foreach ($item->itemAddons as $addon): ?>
                    <div class="addon-option" data-addon-id="<?= $addon->id ?>">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   id="addon-<?= $addon->id ?>">
                            <label class="form-check-label" for="addon-<?= $addon->id ?>">
                                <?= Html::encode($addon->name) ?>
                            </label>
                        </div>
                        <div class="text-success fw-bold">
                            +$<?= number_format($addon->price, 2) ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="col-md-4">
            <div class="sticky-top" style="top: 20px;">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h5 class="card-title">Order Details</h5>
                        
                        <div class="price-section mb-3">
                            <div class="base-price">
                                <span>Base Price:</span>
                                <strong class="float-end">$<?= number_format($item->base_price, 2) ?></strong>
                            </div>
                            
                            <div class="variations-price d-none">
                                <span>Variations:</span>
                                <strong class="float-end text-success">+$<span id="variations-total">0.00</span></strong>
                            </div>
                            
                            <div class="addons-price d-none">
                                <span>Addons:</span>
                                <strong class="float-end text-success">+$<span id="addons-total">0.00</span></strong>
                            </div>
                            
                            <hr>
                            <div class="total-price">
                                <span class="fw-bold">Total:</span>
                                <strong class="float-end text-primary fs-5">$<span id="item-total"><?= number_format($item->base_price, 2) ?></span></strong>
                            </div>
                        </div>

                        <div class="quantity-selector mb-3">
                            <label class="form-label fw-bold">Quantity</label>
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-outline-secondary quantity-btn" id="decrease-qty">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" id="quantity" class="form-control text-center" 
                                       value="1" min="1" max="10">
                                <button class="btn btn-outline-secondary quantity-btn" id="increase-qty">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="action-buttons d-grid gap-2">
                            <button class="btn btn-primary btn-lg" id="add-to-cart">
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </button>
                            <a href="https://wa.me/<?= preg_replace('/\D/', '', $businessSettings->whatsapp_number) ?>?text=<?= urlencode("Hello! I would like to order 1x {$item->name}. Price: \${$item->base_price}. Thank you!") ?>" 
                                target="_blank" 
                                class="btn btn-success btn-lg">
                                    <i class="fab fa-whatsapp"></i> Quick Order
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const basePrice = <?= $item->base_price ?>;
    let variationsTotal = 0;
    let addonsTotal = 0;
    let quantity = 1;

    // Quantity controls
    document.getElementById('increase-qty').addEventListener('click', function() {
        const qtyInput = document.getElementById('quantity');
        if (qtyInput.value < 10) {
            qtyInput.value = parseInt(qtyInput.value) + 1;
            quantity = qtyInput.value;
            updateTotal();
        }
    });

    document.getElementById('decrease-qty').addEventListener('click', function() {
        const qtyInput = document.getElementById('quantity');
        if (qtyInput.value > 1) {
            qtyInput.value = parseInt(qtyInput.value) - 1;
            quantity = qtyInput.value;
            updateTotal();
        }
    });

    // Variation selection
    document.querySelectorAll('.variation-option').forEach(option => {
        option.addEventListener('click', function() {
            const type = this.closest('.mb-4');
            
            // Deselect other options in same type
            type.querySelectorAll('.variation-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            
            // Select current option
            this.classList.add('selected');
            
            variationsTotal = 0;
            updateVariationsDisplay();
            updateTotal();
        });
    });

    // Addons selection
    document.querySelectorAll('.addon-option input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const addonOption = this.closest('.addon-option');
            const price = parseFloat(addonOption.querySelector('.text-success').textContent.match(/[\d.]+/)[0]);
            
            if (this.checked) {
                addonsTotal += price;
                addonOption.classList.add('selected');
            } else {
                addonsTotal -= price;
                addonOption.classList.remove('selected');
            }
            
            updateAddonsDisplay();
            updateTotal();
        });
    });

    // Add to cart
    document.getElementById('add-to-cart').addEventListener('click', function() {
        const selectedVariations = [];
        document.querySelectorAll('.variation-option.selected').forEach(option => {
            selectedVariations.push({
                id: option.getAttribute('data-variation-id'),
                value: option.querySelector('strong').textContent.trim(),
                price: 0
            });
        });

        const selectedAddons = [];
        document.querySelectorAll('.addon-option input[type="checkbox"]:checked').forEach(checkbox => {
            const addonOption = checkbox.closest('.addon-option');
            const price = parseFloat(addonOption.querySelector('.text-success').textContent.match(/[\d.]+/)[0]);
            selectedAddons.push({
                id: addonOption.getAttribute('data-addon-id'),
                name: addonOption.querySelector('label').textContent.trim(),
                price: price
            });
        });

        const cartItem = {
            id: <?= $item->id ?>,
            name: '<?= addslashes($item->name) ?>',
            basePrice: basePrice,
            variations: selectedVariations,
            addons: selectedAddons,
            quantity: quantity
        };

        if (window.cart) {
            window.cart.addItem(cartItem);
            bootstrap.Modal.getInstance(document.getElementById('itemModal')).hide();
        }
    });

    function updateVariationsDisplay() {
        const variationsPriceElement = document.querySelector('.variations-price');
        if (variationsTotal > 0) {
            variationsPriceElement.classList.remove('d-none');
            document.getElementById('variations-total').textContent = variationsTotal.toFixed(2);
        } else {
            variationsPriceElement.classList.add('d-none');
        }
    }

    function updateAddonsDisplay() {
        const addonsPriceElement = document.querySelector('.addons-price');
        if (addonsTotal > 0) {
            addonsPriceElement.classList.remove('d-none');
            document.getElementById('addons-total').textContent = addonsTotal.toFixed(2);
        } else {
            addonsPriceElement.classList.add('d-none');
        }
    }

    function updateTotal() {
        const total = (basePrice + variationsTotal + addonsTotal) * quantity;
        document.getElementById('item-total').textContent = total.toFixed(2);
    }

    // Initialize
    updateVariationsDisplay();
    updateAddonsDisplay();
    updateTotal();
});
</script>