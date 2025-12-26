<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Category $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>

            <?= $form->field($model, 'sort_order')->textInput(['type' => 'number']) ?>

            <?= $form->field($model, 'status')->dropDownList([
                $model::STATUS_ACTIVE => 'Active',
                $model::STATUS_INACTIVE => 'Inactive',
            ], ['prompt' => 'Select Status']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'imageFile')->fileInput() ?>
            
            <?php if (!$model->isNewRecord && $model->image): ?>
                <div class="mb-3">
                    <label class="form-label">Current Image:</label>
                    <div>
                        <img src="/digital-menu/frontend/web/uploads/categories/<?= $model->image ?>" 
                             alt="<?= $model->name ?>" 
                             style="max-width: 200px; max-height: 150px; border-radius: 8px;">
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-group mt-4">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>