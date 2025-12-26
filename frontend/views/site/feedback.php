<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\ActiveForm;
?>
<!-- Feedback Header -->
<section class="hero-section" style="padding: 120px 0 60px; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);">
    <div class="container">
        <div class="hero-content text-center">
            <h1 class="display-4 fw-bold text-white mb-3 animate-fade-in">Your Feedback</h1>
            <p class="lead text-white animate-fade-in" style="animation-delay: 0.2s">
                We value your opinion and would love to hear from you
            </p>
        </div>
    </div>
</section>

<!-- Feedback Form Section -->
<section class="feedback-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card border-0 shadow-lg animate-slide-up">
                    <div class="card-body p-5">
                        <div class="text-center mb-5">
                            <div class="feedback-icon mb-3">
                                <i class="fas fa-comment-dots fa-3x text-primary"></i>
                            </div>
                            <h3 class="fw-bold">Share Your Experience</h3>
                            <p class="text-muted">Tell us how we can improve your dining experience</p>
                        </div>

                        <?php $form = ActiveForm::begin([
                            'id' => 'feedback-form',
                            'fieldConfig' => [
                                'options' => ['class' => 'mb-4'],
                                'inputOptions' => ['class' => 'form-control form-control-lg'],
                                'labelOptions' => ['class' => 'form-label fw-bold']
                            ]
                        ]); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'customer_name')->textInput([
                                    'placeholder' => 'Enter your name (optional)'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'email')->textInput([
                                    'placeholder' => 'your@email.com (optional)'
                                ]) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'phone')->textInput([
                                    'placeholder' => 'Phone (optional)'
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <!-- Star Rating Field -->
                                <div class="form-group">
                                    <label class="form-label fw-bold">Rating *</label>
                                    <div class="star-rating">
                                        <input type="radio" id="star5" name="Feedback[rating]" value="5">
                                        <label for="star5" class="star-label" title="Excellent">
                                            <i class="fas fa-star"></i>
                                        </label>
                                        <input type="radio" id="star4" name="Feedback[rating]" value="4">
                                        <label for="star4" class="star-label" title="Very Good">
                                            <i class="fas fa-star"></i>
                                        </label>
                                        <input type="radio" id="star3" name="Feedback[rating]" value="3">
                                        <label for="star3" class="star-label" title="Good">
                                            <i class="fas fa-star"></i>
                                        </label>
                                        <input type="radio" id="star2" name="Feedback[rating]" value="2">
                                        <label for="star2" class="star-label" title="Fair">
                                            <i class="fas fa-star"></i>
                                        </label>
                                        <input type="radio" id="star1" name="Feedback[rating]" value="1">
                                        <label for="star1" class="star-label" title="Poor">
                                            <i class="fas fa-star"></i>
                                        </label>
                                    </div>
                                    <div class="rating-text text-center mt-2">
                                        <small class="text-muted">Click to rate your experience</small>
                                    </div>
                                    <?php if ($model->hasErrors('rating')): ?>
                                        <div class="text-danger small mt-1">
                                            <?= $model->getFirstError('rating') ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <?= $form->field($model, 'comment')->textarea([
                            'rows' => 5,
                            'placeholder' => 'Tell us about your experience... What did you like? What can we improve? (optional)'
                        ]) ?>

                        <div class="d-grid">
                            <?= Html::submitButton('Submit Feedback', [
                                'class' => 'btn btn-primary btn-lg py-3 fw-bold',
                                'name' => 'feedback-button'
                            ]) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                        <?php if (Yii::$app->session->hasFlash('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show mt-4 animate-bounce-in" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= Yii::$app->session->getFlash('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="row mt-5 animate-stagger">
                    <div class="col-md-4">
                        <div class="text-center p-4">
                            <div class="contact-icon mb-3">
                                <i class="fas fa-phone fa-2x text-primary"></i>
                            </div>
                            <h5>Call Us</h5>
                            <?php if ($businessSettings->phone): ?>
                                <a href="tel:<?= Html::encode($businessSettings->phone) ?>" class="text-decoration-none">
                                    <?= Html::encode($businessSettings->phone) ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Not provided</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-4">
                            <div class="contact-icon mb-3">
                                <i class="fas fa-envelope fa-2x text-primary"></i>
                            </div>
                            <h5>Email Us</h5>
                            <?php if ($businessSettings->email): ?>
                                <a href="mailto:<?= Html::encode($businessSettings->email) ?>" class="text-decoration-none">
                                    <?= Html::encode($businessSettings->email) ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Not provided</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-4">
                            <div class="contact-icon mb-3">
                                <i class="fab fa-whatsapp fa-2x text-primary"></i>
                            </div>
                            <h5>WhatsApp</h5>
                            <?php if ($businessSettings->whatsapp_number): ?>
                                <a href="https://wa.me/<?= preg_replace('/\D/', '', $businessSettings->whatsapp_number) ?>" 
                                   target="_blank" class="text-decoration-none">
                                    Message Us
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Not provided</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.feedback-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.feedback-icon {
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

.contact-icon {
    width: 60px;
    height: 60px;
    background: rgba(255,107,107,0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    color: var(--primary-color);
}

.form-control, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    transition: var(--transition);
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(255,107,107,0.25);
}

.alert {
    border-radius: 15px;
    border: none;
}

/* Star Rating Styles */
.star-rating {
    display: flex;
    justify-content: center;
    gap: 5px;
    direction: rtl; /* Reverse order for CSS sibling selector magic */
}

.star-rating input[type="radio"] {
    display: none;
}

.star-label {
    font-size: 3rem;
    color: #ddd;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.star-label:hover,
.star-label:hover ~ .star-label {
    color: #ffc107;
    transform: scale(1.1);
}

.star-rating input[type="radio"]:checked ~ .star-label {
    color: #ffc107;
}

.star-rating input[type="radio"]:checked + .star-label {
    color: #ffc107;
    animation: starPulse 0.6s ease;
}

.star-label .fas {
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
}

.rating-text {
    min-height: 20px;
}

@keyframes starPulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.3);
    }
    100% {
        transform: scale(1.1);
    }
}

/* Responsive stars */
@media (max-width: 768px) {
    .star-label {
        font-size: 2.5rem;
    }
}

@media (max-width: 576px) {
    .star-label {
        font-size: 2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Star rating functionality
    const starInputs = document.querySelectorAll('.star-rating input[type="radio"]');
    const starLabels = document.querySelectorAll('.star-label');
    const ratingText = document.querySelector('.rating-text small');
    
    const ratingMessages = {
        1: 'Poor - We apologize for the bad experience',
        2: 'Fair - We\'ll work to improve',
        3: 'Good - Thank you for your feedback',
        4: 'Very Good - We\'re glad you enjoyed!',
        5: 'Excellent - Thank you for the perfect rating!'
    };

    starInputs.forEach(input => {
        input.addEventListener('change', function() {
            const rating = this.value;
            
            // Update all stars
            starLabels.forEach((label, index) => {
                const starValue = 5 - index; // Because of direction: rtl
                if (starValue <= rating) {
                    label.style.color = '#ffc107';
                    label.style.transform = 'scale(1.1)';
                } else {
                    label.style.color = '#ddd';
                    label.style.transform = 'scale(1)';
                }
            });
            
            // Update rating text
            if (ratingText && ratingMessages[rating]) {
                ratingText.textContent = ratingMessages[rating];
                ratingText.className = 'text-success fw-bold';
            }
            
            // Add celebration for 5 stars
            if (rating == 5) {
                celebrateRating();
            }
        });
    });

    // Hover effects for stars
    starLabels.forEach(label => {
        label.addEventListener('mouseenter', function() {
            const rating = this.htmlFor.replace('star', '');
            
            starLabels.forEach((star, index) => {
                const starValue = 5 - index;
                if (starValue <= rating) {
                    star.style.color = '#ffc107';
                    star.style.transform = 'scale(1.2)';
                }
            });
        });
        
        label.addEventListener('mouseleave', function() {
            const checkedInput = document.querySelector('.star-rating input[type="radio"]:checked');
            
            if (checkedInput) {
                const rating = checkedInput.value;
                starLabels.forEach((star, index) => {
                    const starValue = 5 - index;
                    if (starValue <= rating) {
                        star.style.color = '#ffc107';
                        star.style.transform = 'scale(1.1)';
                    } else {
                        star.style.color = '#ddd';
                        star.style.transform = 'scale(1)';
                    }
                });
            } else {
                starLabels.forEach(star => {
                    star.style.color = '#ddd';
                    star.style.transform = 'scale(1)';
                });
                if (ratingText) {
                    ratingText.textContent = 'Click to rate your experience';
                    ratingText.className = 'text-muted';
                }
            }
        });
    });

    // Celebration effect for 5 stars
    function celebrateRating() {
        const stars = document.querySelectorAll('.star-label');
        stars.forEach((star, index) => {
            setTimeout(() => {
                star.style.animation = 'starPulse 0.6s ease';
                setTimeout(() => {
                    star.style.animation = '';
                }, 600);
            }, index * 200);
        });
    }

    // Form validation highlight
    const form = document.getElementById('feedback-form');
    if (form) {
        form.addEventListener('submit', function() {
            const ratingSelected = document.querySelector('.star-rating input[type="radio"]:checked');
            if (!ratingSelected) {
                const starContainer = document.querySelector('.star-rating');
                starContainer.style.animation = 'shake 0.5s ease';
                setTimeout(() => {
                    starContainer.style.animation = '';
                }, 500);
            }
        });
    }

    // Add shake animation for validation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    `;
    document.head.appendChild(style);

    // Add animation to form elements on focus
    const formInputs = document.querySelectorAll('.form-control');
    formInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('animate-pulse-slow');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('animate-pulse-slow');
        });
    });
});
</script>
<?php include 'footer.php'; ?>