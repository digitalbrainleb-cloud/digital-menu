<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ItemAddons $model */

$this->title = 'Update Item Addons: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Item Addons', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="item-addons-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
