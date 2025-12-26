<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\VariationItems $model */

$this->title = 'Create Variation Items';
$this->params['breadcrumbs'][] = ['label' => 'Variation Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="variation-items-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
