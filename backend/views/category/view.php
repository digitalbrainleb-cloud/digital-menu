<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Category $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="category-view">

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
            'name',
            'description:ntext',
            [
                'attribute' => 'image',
                'format' => 'raw',
                'value' => function($model) {
                    $imageUrl = $model->getImageUrl();
                    $imagePath = $model->getImagePath();
                    
                    // Check if we have both URL and path, and file exists
                    if ($imageUrl && $imagePath && file_exists($imagePath)) {
                        return Html::img($imageUrl, [
                            'style' => 'max-width: 200px; max-height: 150px; border-radius: 8px; border: 1px solid #ddd;'
                        ]);
                    } else {
                        $debugInfo = [];
                        if (!$model->image) $debugInfo[] = 'No image in database';
                        if (!$imageUrl) $debugInfo[] = 'No image URL';
                        if (!$imagePath) $debugInfo[] = 'No image path';
                        if ($imagePath && !file_exists($imagePath)) $debugInfo[] = 'File not found at path';
                        
                        return 'No image available<br>'
                             . '<small class="text-muted">' . implode(', ', $debugInfo) . '</small>';
                    }
                },
            ],
            'sort_order',
            [
                'attribute' => 'status',
                'value' => function($model) {
                    return $model->status ? 'Active' : 'Inactive';
                },
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>