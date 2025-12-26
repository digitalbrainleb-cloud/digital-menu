<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\BusinessSettingsSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="business-settings-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'business_name') ?>

    <?= $form->field($model, 'logo') ?>

    <?= $form->field($model, 'currency') ?>

    <?= $form->field($model, 'whatsapp_number') ?>

    <?php // echo $form->field($model, 'facebook_url') ?>

    <?php // echo $form->field($model, 'instagram_url') ?>

    <?php // echo $form->field($model, 'twitter_url') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
