<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ItemAddons $model */

$this->title = 'Create Item Addons';
$this->params['breadcrumbs'][] = ['label' => 'Item Addons', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-addons-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
