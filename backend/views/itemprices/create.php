<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ItemPrices $model */

$this->title = 'Create Item Prices';
$this->params['breadcrumbs'][] = ['label' => 'Item Prices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-prices-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
