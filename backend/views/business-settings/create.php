<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\BusinessSettings $model */

$this->title = 'Create Business Settings';
$this->params['breadcrumbs'][] = ['label' => 'Business Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-settings-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
