<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ItemPrices $model */

$this->title = 'Update Item Prices: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Item Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="item-prices-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
