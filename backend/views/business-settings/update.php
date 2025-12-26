<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\BusinessSettings $model */

$this->title = 'Update Business Settings: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Business Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="business-settings-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
