<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<!-- Hero Section -->
<section class="hero-section">
    <!-- Success Message -->
    <?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="container">
        <div class="alert alert-success alert-dismissible fade show mt-4 animate-bounce-in" role="alert" style="margin-top: 20px !important;">
            <i class="fas fa-check-circle me-2"></i>
            <?= Yii::$app->session->getFlash('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="container">
        <div class="hero-content animate-fade-in">
            <?php if ($businessSettings->logo): ?>
                <img src="<?= $businessSettings->getLogoUrl() ?>" 
                     alt="<?= Html::encode($businessSettings->business_name) ?>" 
                     class="business-logo animate-bounce-in">
            <?php endif; ?>
            
            <h1 class="display-4 fw-bold mb-3 animate-slide-up">
                <?= Html::encode($businessSettings->business_name ?: 'Our Restaurant') ?>
            </h1>
            
            <p class="lead mb-4 animate-slide-up" style="animation-delay: 0.2s">
                <?= Html::encode($businessSettings->description ?: 'Welcome to our digital menu') ?>
            </p>

            <!-- Social Media Links -->
            <?php if ($businessSettings->facebook_url || $businessSettings->instagram_url || $businessSettings->twitter_url): ?>
            <div class="social-links animate-slide-up" style="animation-delay: 0.3s">
                <?php if ($businessSettings->facebook_url): ?>
                    <a href="<?= Html::encode($businessSettings->facebook_url) ?>" 
                       target="_blank" class="social-link" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                <?php endif; ?>
                
                <?php if ($businessSettings->instagram_url): ?>
                    <a href="<?= Html::encode($businessSettings->instagram_url) ?>" 
                       target="_blank" class="social-link" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                <?php endif; ?>
                
                <?php if ($businessSettings->twitter_url): ?>
                    <a href="<?= Html::encode($businessSettings->twitter_url) ?>" 
                       target="_blank" class="social-link" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Contact Links -->
            <div class="contact-links animate-slide-up" style="animation-delay: 0.4s">
                <?php if ($businessSettings->whatsapp_number): ?>
                    <a href="https://wa.me/<?= preg_replace('/\D/', '', $businessSettings->whatsapp_number) ?>" 
                       target="_blank" class="contact-link">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                <?php endif; ?>
                
                <?php if ($businessSettings->phone): ?>
                    <a href="tel:<?= Html::encode($businessSettings->phone) ?>" 
                       class="contact-link">
                        <i class="fas fa-phone"></i> Call Us
                    </a>
                <?php endif; ?>
                
                <?php if ($businessSettings->email): ?>
                    <a href="mailto:<?= Html::encode($businessSettings->email) ?>" 
                       class="contact-link">
                        <i class="fas fa-envelope"></i> Email
                    </a>
                <?php endif; ?>
            </div>

            <!-- Call to Action -->
            <div class="mt-5 animate-slide-up" style="animation-delay: 0.5s">
                <a href="<?= Url::to(['/site/menu']) ?>" 
   class="btn btn-light btn-lg px-5 py-3 rounded-pill fw-bold shadow">
    <i class="fas fa-utensils me-2"></i> View Our Menu
</a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5 bg-light">
    <div class="container">
        <div class="row g-4 animate-stagger">
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-bolt fa-3x text-primary"></i>
                    </div>
                    <h4>Quick Order</h4>
                    <p class="text-muted">Order directly via WhatsApp with just one click</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-mobile-alt fa-3x text-primary"></i>
                    </div>
                    <h4>Mobile Friendly</h4>
                    <p class="text-muted">Perfect experience on all devices</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="text-center p-4">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-star fa-3x text-primary"></i>
                    </div>
                    <h4>Best Quality</h4>
                    <p class="text-muted">Fresh ingredients and amazing taste</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.features-section {
    background: rgba(255,255,255,0.9) !important;
    backdrop-filter: blur(10px);
}

.feature-icon {
    width: 80px;
    height: 80px;
    background: var(--gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: white;
}
</style>
<?php include 'footer.php'; ?>