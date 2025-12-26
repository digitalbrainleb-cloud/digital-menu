<?php

use common\models\Feedback;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\FeedbackSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Feedbacks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feedback-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Feedback', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

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
                    $stars .= '<span style="color: ' . $color . '; font-size: 18px;">★</span>';
                }
                return '<div style="display: flex; gap: 2px;">' . $stars . '</div>' . ' (' . $model->rating . '/5)';
            },
        ],
        'comment:ntext',
        [
            'attribute' => 'status',
            'value' => function($model) {
                return $model->getStatusLabel();
            },
            'filter' => [
                1 => 'Active',
                0 => 'Inactive'
            ],
        ],
        'created_at:datetime',

        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>


</div>
