<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<!-- Category Header -->
<section class="hero-section" style="padding: 120px 0 60px; background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);">
    <div class="container">
        <div class="hero-content text-center">
            <h1 class="display-4 fw-bold text-white mb-3 animate-fade-in">
                <?= Html::encode($category->name) ?>
            </h1>
            <?php if ($category->description): ?>
            <p class="lead text-white animate-fade-in" style="animation-delay: 0.2s">
                <?= Html::encode($category->description) ?>
            </p>
            <?php endif; ?>
            <div class="mt-4">
                <a href="<?= Url::to(['/site/menu']) ?>" class="btn btn-outline-light btn-lg">
                    <i class="fas fa-arrow-left me-2"></i> Back to Categories
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Items Grid Section -->
<section class="items-grid-section py-5">
    <div class="container">
        <?php if (!empty($items)): ?>
        <div class="row g-4">
            <?php foreach ($items as $item): ?>
            <div class="col-12">
                <div class="item-card-horizontal animate-slide-up">
                    <div class="item-image-container">
                        <div class="item-image" 
                             style="background-image: url('<?= $item->getImageUrl() ?: 'https://via.placeholder.com/300x200?text=No+Image' ?>')"
                             onclick="openImageModal('<?= $item->getImageUrl() ?: 'https://via.placeholder.com/800x600?text=No+Image' ?>', '<?= Html::encode($item->name) ?>')">
                            <?php if ($item->is_available): ?>
                            <div class="item-badge available">
                                <i class="fas fa-check"></i> Available
                            </div>
                            <?php else: ?>
                            <div class="item-badge unavailable">
                                <i class="fas fa-clock"></i> Unavailable
                            </div>
                            <?php endif; ?>
                            
                            <!-- Fullscreen overlay -->
                            <div class="image-overlay">
                                <div class="overlay-content">
                                    <i class="fas fa-expand-arrows-alt fa-2x"></i>
                                    <p>Click to view full size</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="item-content">
                        <div class="item-header">
                            <h3 class="item-title"><?= Html::encode($item->name) ?></h3>
                            <div class="item-price">
                                $<?= number_format($item->base_price, 2) ?>
                            </div>
                        </div>
                        
                        <p class="item-description">
                            <?= Html::encode($item->description) ?>
                        </p>
                        
                        <!-- Features -->
                        <div class="item-features">
                            <?php if (!empty($item->variations)): ?>
                            <span class="feature-tag">
                                <i class="fas fa-list me-1"></i>
                                <?= count($item->variations) ?> variations
                            </span>
                            <?php endif; ?>
                            
                            <?php if (!empty($item->itemAddons)): ?>
                            <span class="feature-tag">
                                <i class="fas fa-plus me-1"></i>
                                <?= count($item->itemAddons) ?> addons
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="item-actions">
                            <a href="<?= Url::to(['/site/item-details', 'id' => $item->id]) ?>" 
                                class="btn btn-outline-primary btn-view-details"
                                <?= !$item->is_available ? 'disabled' : '' ?>>
                                    <i class="fas fa-eye me-2"></i>Customize & Order
                            </a>
                                                            
                            <!-- DIRECT WHATSAPP LINK - THIS WORKS -->
                            <a href="https://wa.me/<?= preg_replace('/\D/', '', $businessSettings->whatsapp_number) ?>?text=<?= urlencode("Hello! I would like to order 1x {$item->name}. Price: \${$item->base_price}. Thank you!") ?>" 
                                target="_blank" 
                                class="btn btn-success"
                                <?= !$item->is_available ? 'disabled' : '' ?>>
                                    <i class="fab fa-whatsapp me-2"></i>Quick Order
                            </a>
                            
                            <?php if (!$item->is_available): ?>
                            <span class="text-muted small ms-3">
                                <i class="fas fa-info-circle me-1"></i>Currently unavailable
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <!-- Empty State -->
        <div class="empty-state text-center py-5">
            <div class="empty-icon mb-4">
                <i class="fas fa-utensils fa-4x text-muted"></i>
            </div>
            <h3 class="text-muted mb-3">No Items Available</h3>
            <p class="text-muted mb-4">There are no items in this category at the moment.</p>
            <a href="<?= Url::to(['/site/menu']) ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-arrow-left me-2"></i> Back to Categories
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Full Screen Image Modal -->
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

<!-- Item Details Modal Container -->
<div class="modal fade item-modal" id="itemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Content will be loaded via AJAX -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View item details
    document.querySelectorAll('.btn-view-details').forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-id');
            loadItemModal(itemId);
        });
    });

    // Hover effects for item cards
    document.querySelectorAll('.item-card-horizontal').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 15px 40px rgba(0,0,0,0.15)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 5px 20px rgba(0,0,0,0.1)';
        });
    });
});

function loadItemModal(itemId) {
    console.log('Loading item modal for ID:', itemId);
    
    // Show loading state
    const modalContent = document.querySelector('#itemModal .modal-content');
    modalContent.innerHTML = `
        <div class="text-center p-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Loading item details...</p>
        </div>
    `;
    
    const modal = new bootstrap.Modal(document.getElementById('itemModal'));
    modal.show();
    
    fetch('<?= Yii::$app->urlManager->createUrl(['site/item']) ?>?id=' + itemId)
        .then(response => response.text())
        .then(html => {
            document.querySelector('#itemModal .modal-content').innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading item:', error);
            document.querySelector('#itemModal .modal-content').innerHTML = `
                <div class="text-center p-5">
                    <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                    <h4>Error Loading Item</h4>
                    <p>Could not load item details. Please try again.</p>
                    <button class="btn btn-primary" onclick="bootstrap.Modal.getInstance(document.getElementById('itemModal')).hide()">
                        Close
                    </button>
                </div>
            `;
        });
}

// Full Screen Image Modal Functions
function openImageModal(imageSrc, caption) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const captionText = document.getElementById('modalCaption');
    
    modal.style.display = 'block';
    modalImg.src = imageSrc;
    captionText.textContent = caption;
    
    // Add animation
    modal.classList.add('modal-open');
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.remove('modal-open');
    modal.style.display = 'none';
    
    // Restore body scroll
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside the image
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Keyboard support
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>

<style>
/* Full Screen Image Modal Styles */
.image-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.95);
    backdrop-filter: blur(10px);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.image-modal.modal-open {
    opacity: 1;
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
    transition: all 0.3s ease;
    background: rgba(0,0,0,0.5);
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.close-modal:hover {
    color: #ff6b6b;
    transform: scale(1.1);
    background: rgba(0,0,0,0.7);
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
    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    animation: zoomIn 0.5s ease;
}

@keyframes zoomIn {
    from {
        transform: scale(0.8);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

.image-caption {
    margin-top: 20px;
    text-align: center;
    color: white;
}

.image-caption h4 {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
    text-shadow: 0 2px 10px rgba(0,0,0,0.5);
}

.modal-actions {
    position: absolute;
    bottom: 30px;
    left: 0;
    right: 0;
    text-align: center;
}

/* Image Overlay Styles */
.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
    border-radius: 15px 0 0 15px;
}

.item-image-container:hover .image-overlay {
    opacity: 1;
}

.overlay-content {
    text-align: center;
    color: white;
    transform: translateY(20px);
    transition: all 0.3s ease;
}

.item-image-container:hover .overlay-content {
    transform: translateY(0);
}

.overlay-content i {
    margin-bottom: 10px;
    font-size: 2rem;
    color: #fff;
    text-shadow: 0 2px 10px rgba(0,0,0,0.5);
}

.overlay-content p {
    margin: 0;
    font-size: 0.9rem;
    font-weight: 500;
}

/* Item Image Clickable */
.item-image {
    cursor: zoom-in;
    transition: transform 0.3s ease;
}

.item-image:hover {
    transform: scale(1.02);
}

/* Responsive */
@media (max-width: 768px) {
    .close-modal {
        top: 10px;
        right: 15px;
        font-size: 30px;
        width: 40px;
        height: 40px;
    }
    
    #modalImage {
        max-width: 95%;
        max-height: 60vh;
    }
    
    .image-caption h4 {
        font-size: 1.2rem;
    }
    
    .modal-actions {
        bottom: 20px;
    }
}
</style>
<?php include 'footer.php'; ?>