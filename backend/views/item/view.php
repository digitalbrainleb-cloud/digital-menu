<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Item $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

// Get currency
$currency = $model->currency;
?>

<div class="items-view">

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
            [
                'attribute' => 'category_id',
                'label' => 'Category',
                'value' => function($model) {
                    return $model->category ? $model->category->name : 'No Category';
                },
            ],
            'name',
            'description:ntext',
            [
                'attribute' => 'image',
                'label' => 'Item Image',
                'format' => 'raw',
                'value' => function($model) {
                    if ($model->image) {
                        return Html::img($model->getImageUrl(), [
                            'style' => 'max-width: 200px; max-height: 150px; border-radius: 8px; border: 1px solid #ddd;'
                        ]);
                    }
                    return 'No image';
                },
            ],
            [
                'attribute' => 'base_price',
                'value' => function($model) use ($currency) {
                    return $currency . ' ' . number_format($model->base_price, 2);
                },
            ],
            [
                'attribute' => 'is_available',
                'label' => 'Status',
                'value' => function($model) {
                    return $model->is_available ? 'Active' : 'Inactive';
                },
            ],
            'sort_order',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <!-- Variations Section -->
    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">Variations & Pricing</h4>
        </div>
        <div class="card-body">
            <?php if ($model->itemPrices): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Type</th>
                                <th>Value</th>
                                <th>Price (<?= $currency ?>)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($model->itemPrices as $itemPrice): ?>
                                <?php if ($itemPrice->variationItems && $itemPrice->variationItems->variation): ?>
                                    <tr>
                                        <td><?= Html::encode($itemPrice->variationItems->variation->type) ?></td>
                                        <td><?= Html::encode($itemPrice->variationItems->variation->value) ?></td>
                                        <td><?= number_format($itemPrice->price, 2) ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No variations added for this item.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Addons Section -->
    <div class="card mt-4">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Addons</h4>
        </div>
        <div class="card-body">
            <?php if ($model->itemAddons): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Addon Name</th>
                                <th>Price (<?= $currency ?>)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($model->itemAddons as $addon): ?>
                                <tr>
                                    <td><?= Html::encode($addon->name) ?></td>
                                    <td><?= number_format($addon->price, 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No addons added for this item.</p>
            <?php endif; ?>
        </div>
    </div>

</div>