<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Variations $model */

$this->title = 'Create Variations';
$this->params['breadcrumbs'][] = ['label' => 'Variations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="variations-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
