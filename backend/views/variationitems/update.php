<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\VariationItems $model */

$this->title = 'Update Variation Items: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Variation Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="variation-items-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
