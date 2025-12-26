<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<!-- Menu Header -->
<section class="hero-section" style="padding: 120px 0 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="hero-content text-center">
            <h1 class="display-4 fw-bold text-white mb-3 animate-fade-in">Our Menu</h1>
            <p class="lead text-white animate-fade-in" style="animation-delay: 0.2s">
                Discover our delicious offerings
            </p>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section py-5">
    <div class="container">
        <div class="row g-4 animate-stagger">
            <?php foreach ($categories as $category): ?>
            <div class="col-lg-4 col-md-6">
                <div class="category-card" onclick="location.href='<?= Url::to(['/site/category', 'id' => $category->id]) ?>'">
                    <div class="category-image" style="background-image: url('<?= $category->getImageUrl() ?: 'https://via.placeholder.com/400x200?text=Category' ?>')">
                        <div class="category-overlay">
                            <h5 class="mb-0"><?= Html::encode($category->name) ?></h5>
                            <small><?= count($category->activeItems) ?> items</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-0"><?= Html::encode($category->description) ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if (empty($categories)): ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-utensils fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No categories available</h4>
                <p class="text-muted">Please check back later</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php include 'footer.php'; ?>