<?php

use common\models\Item;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\ItemSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="items-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Items', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'category_id',
                'label' => 'Category',
                'value' => function($model) {
                    return $model->category ? $model->category->name : 'No Category';
                },
                'filter' => \yii\helpers\ArrayHelper::map(\common\models\Category::find()->all(), 'id', 'name')
            ],
            'name',
            'description:ntext',
            // 'image', // Removed image column
            [
                'attribute' => 'base_price',
                'value' => function($model) {
                    return $model->currency . ' ' . number_format($model->base_price, 2);
                },
            ],
            [
                'attribute' => 'is_available',
                'label' => 'Status',
                'value' => function($model) {
                    return $model->is_available ? 'Active' : 'Inactive';
                },
                'filter' => [
                    1 => 'Active',
                    0 => 'Inactive'
                ],
            ],
            'sort_order',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Item $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

</div>