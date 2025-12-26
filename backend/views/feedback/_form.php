<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Feedback $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="feedback-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'customer_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'placeholder' => '+1 (555) 123-4567']) ?>
        </div>
        <div class="col-md-6">
            <!-- Star Rating Field -->
            <div class="form-group mb-2">  <!-- Reduced margin -->
                <label class="form-label fw-bold mb-1">Rating</label>  <!-- Added margin-bottom -->
                <?= $form->field($model, 'rating', [
                    'options' => ['class' => 'mb-0']  // Remove margin from field container
                ])->hiddenInput(['id' => 'rating-value'])->label(false) ?>  <!-- Remove the extra label -->
                <div class="star-rating mt-1">  <!-- Reduced top margin -->
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star" data-rating="<?= $i ?>" style="font-size: 30px; cursor: pointer; color: <?= $i <= ($model->rating ?: 0) ? '#ffc107' : '#e4e5e9' ?>;">
                            ★
                        </span>
                    <?php endfor; ?>
                </div>
                <div class="text-muted small mt-1">Click on the stars to rate (1-5)</div>
            </div>
        </div>
    </div>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->dropDownList(
        [
            1 => 'Active',
            0 => 'Inactive'
        ],
        ['prompt' => 'Select Status']
    ) ?>

    <div class="form-group mt-4">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<style>
.star-rating {
    display: flex;
    gap: 5px;
}
.star {
    transition: color 0.2s;
    cursor: pointer;
}
.star:hover,
.star.active {
    color: #ffc107 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating-value');
    
    // Convert NodeList to Array for left-to-right processing
    const starsArray = Array.from(stars);
    
    starsArray.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            ratingInput.value = rating;
            
            // Update star colors - light up stars from left to right
            starsArray.forEach((s, index) => {
                const starRating = parseInt(s.getAttribute('data-rating'));
                if (starRating <= rating) {
                    s.style.color = '#ffc107';
                    s.classList.add('active');
                } else {
                    s.style.color = '#e4e5e9';
                    s.classList.remove('active');
                }
            });
        });
        
        star.addEventListener('mouseover', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            
            // Light up stars from left to right on hover
            starsArray.forEach((s, index) => {
                const starRating = parseInt(s.getAttribute('data-rating'));
                if (starRating <= rating) {
                    s.style.color = '#ffc107';
                } else {
                    s.style.color = '#e4e5e9';
                }
            });
        });
    });
    
    // Reset stars when mouse leaves the rating area
    document.querySelector('.star-rating').addEventListener('mouseleave', function() {
        const currentRating = parseInt(ratingInput.value) || 0;
        starsArray.forEach(s => {
            const starRating = parseInt(s.getAttribute('data-rating'));
            if (starRating <= currentRating) {
                s.style.color = '#ffc107';
            } else {
                s.style.color = '#e4e5e9';
            }
        });
    });
    
    // Initialize stars based on current value
    const currentRating = parseInt(ratingInput.value) || 0;
    starsArray.forEach(s => {
        const starRating = parseInt(s.getAttribute('data-rating'));
        if (starRating <= currentRating) {
            s.style.color = '#ffc107';
        } else {
            s.style.color = '#e4e5e9';
        }
    });
});
</script>