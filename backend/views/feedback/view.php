<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Feedback $model */

$this->title = $model->customer_name . "'s Feedback";
$this->params['breadcrumbs'][] = ['label' => 'Feedbacks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="feedback-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'customer_name',
            'email:email',
            'phone',
            [
                'attribute' => 'rating',
                'format' => 'raw',
                'value' => function($model) {
                    $stars = '';
                    for ($i = 1; $i <= 5; $i++) {
                        $color = $i <= $model->rating ? '#ffc107' : '#e4e5e9';
                        $stars .= '<span style="color: ' . $color . '; font-size: 20px;">★</span>';
                    }
                    return '<div style="display: flex; gap: 3px;">' . $stars . '</div>' . 
                           '<div class="mt-1"><small class="text-muted">Rating: ' . $model->rating . '/5</small></div>';
                },
            ],
            [
                'attribute' => 'comment',
                'format' => 'ntext',
                'contentOptions' => ['style' => 'max-width: 300px; word-wrap: break-word;'],
            ],
            [
                'attribute' => 'status',
                'value' => function($model) {
                    return $model->getStatusLabel();
                },
            ],
            'created_at:datetime',
        ],
    ]) ?>

</div>