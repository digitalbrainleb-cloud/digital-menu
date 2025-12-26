<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\BusinessSettings $model */

$this->title = 'Business Settings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-settings-index">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h1 class="card-title mb-0"><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data']
            ]); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'business_name')->textInput(['maxlength' => true]) ?>
                    
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                    
                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                    
                    <?= $form->field($model, 'currency')->textInput(['maxlength' => true]) ?>
                    
                    <?= $form->field($model, 'whatsapp_number')->textInput(['maxlength' => true]) ?>
                </div>
                
                <div class="col-md-6">
                    <?= $form->field($model, 'logoFile')->fileInput() ?>
                    <?php if ($model->logo): ?>
                        <div class="mb-3">
                            <label class="form-label">Current Logo:</label>
                            <div>
            <img src="/digital-menu/frontend/web/uploads/logo/<?= $model->logo ?>" alt="Business Logo" style="max-width: 200px; max-height: 100px;">
        </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'facebook_url')->textInput(['maxlength' => true]) ?>
                    
                    <?= $form->field($model, 'instagram_url')->textInput(['maxlength' => true]) ?>
                    
                    <?= $form->field($model, 'twitter_url')->textInput(['maxlength' => true]) ?>
                </div>
                
                <div class="col-md-6">
                    <?= $form->field($model, 'address')->textarea(['rows' => 3]) ?>
                </div>
            </div>

            <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>

            <div class="form-group mt-4">
                <?= Html::submitButton('Save Settings', ['class' => 'btn btn-success btn-lg']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>