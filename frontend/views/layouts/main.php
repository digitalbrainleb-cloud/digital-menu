<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<!-- Floating Navigation -->
<nav class="floating-nav">
    <div class="nav-container">
        <a href="<?= Url::to(['/site/index']) ?>" class="nav-brand" data-bs-toggle="tooltip" title="Home">
            <i class="fas fa-home"></i>
        </a>
        <a href="<?= Url::to(['/site/menu']) ?>" class="nav-item" data-bs-toggle="tooltip" title="Menu">
            <i class="fas fa-utensils"></i>
        </a>
        <a href="#cart" class="nav-item cart-trigger" data-bs-toggle="tooltip" title="Cart">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-count">0</span>
        </a>
        <a href="<?= Url::to(['/site/feedback']) ?>" class="nav-item" data-bs-toggle="tooltip" title="Feedback">
            <i class="fas fa-comment"></i>
        </a>
    </div>
</nav>

<!-- Mobile Bottom Navigation -->
<nav class="mobile-nav">
    <a href="<?= Url::to(['/site/index']) ?>" class="mobile-nav-item">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </a>
    <a href="<?= Url::to(['/site/menu']) ?>" class="mobile-nav-item">
        <i class="fas fa-utensils"></i>
        <span>Menu</span>
    </a>
    <a href="#cart" class="mobile-nav-item cart-trigger">
        <i class="fas fa-shopping-cart"></i>
        <span>Cart</span>
        <span class="mobile-cart-count">0</span>
    </a>
    <a href="<?= Url::to(['/site/feedback']) ?>" class="mobile-nav-item">
        <i class="fas fa-comment"></i>
        <span>Feedback</span>
    </a>
</nav>

<main>
    <?= $content ?>
</main>

<!-- Shopping Cart Sidebar -->
<div class="cart-sidebar">
    <div class="cart-header">
        <h4>Your Order</h4>
        <button class="close-cart"><i class="fas fa-times"></i></button>
    </div>
    <div class="cart-items">
        <!-- Cart items will be populated here -->
    </div>
    <div class="cart-footer">
        <div class="cart-total">
            <strong>Total: $<span class="total-amount">0.00</span></strong>
        </div>
        <button class="btn btn-success btn-send-whatsapp w-100">
            <i class="fab fa-whatsapp"></i> Send via WhatsApp
        </button>
    </div>
</div>

<div class="cart-overlay"></div>

<?php $this->endBody() ?>

<script>
// Load business settings directly
window.businessSettings = {
    whatsapp_number: '<?= addslashes(\common\models\BusinessSettings::getSettings()->whatsapp_number) ?>',
    business_name: '<?= addslashes(\common\models\BusinessSettings::getSettings()->business_name) ?>',
    currency: '<?= addslashes(\common\models\BusinessSettings::getSettings()->currency) ?>'
};
console.log('Business settings loaded:', window.businessSettings);
</script>

</body>
</html>
<?php $this->endPage() ?>