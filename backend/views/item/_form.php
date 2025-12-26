<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Category;
use common\models\Variation;
use common\models\BusinessSettings;

/** @var yii\web\View $this */
/** @var common\models\Item $model */
/** @var yii\widgets\ActiveForm $form */

// Get currency from business settings
$businessSettings = BusinessSettings::find()->one();
$currency = $businessSettings ? $businessSettings->currency : 'USD';

// Get variation types
$variationTypes = Variation::find()
    ->select('type')
    ->distinct()
    ->indexBy('type')
    ->column();
?>

<div class="items-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data']
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'category_id')->dropDownList(
                ArrayHelper::map(Category::find()->all(), 'id', 'name'),
                ['prompt' => 'Select Category']
            ) ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>

            <?= $form->field($model, 'base_price')->textInput([
                'maxlength' => true,
                'placeholder' => '0.00'
            ]) ?>
            <small class="text-muted">Currency: <?= $currency ?></small>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'imageFile')->fileInput() ?>
            
            <?php if (!$model->isNewRecord && $model->image): ?>
                <div class="mb-3">
                    <label class="form-label">Current Image:</label>
                    <div>
                        <img src="<?= $model->getImageUrl() ?>" 
                             alt="<?= $model->name ?>" 
                             style="max-width: 200px; max-height: 150px; border-radius: 8px;">
                    </div>
                </div>
            <?php endif; ?>

            <?= $form->field($model, 'is_available')->dropDownList([
                $model::STATUS_ACTIVE => 'Active',
                $model::STATUS_INACTIVE => 'Inactive',
            ], ['prompt' => 'Select Status']) ?>

            <?= $form->field($model, 'sort_order')->textInput(['type' => 'number']) ?>
        </div>
    </div>

    <!-- Variations Section -->
    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Variations & Pricing</h5>
        </div>
        <div class="card-body">
            <div id="variations-container">
                <?php 
                // Use a separate counter for existing variations
                $existingVariationIndex = 0;
                if (!$model->isNewRecord && $model->itemPrices): ?>
                    <?php foreach ($model->itemPrices as $itemPrice): ?>
                        <?php if ($itemPrice->variationItems): ?>
                            <div class="variation-row row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Type</label>
                                    <select class="form-control variation-type" name="variations[<?= $existingVariationIndex ?>][type]">
                                        <option value="">Select Type</option>
                                        <?php foreach ($variationTypes as $type): ?>
                                            <option value="<?= $type ?>" <?= $itemPrice->variationItems->variation->type == $type ? 'selected' : '' ?>>
                                                <?= $type ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Value</label>
                                    <select class="form-control variation-value" name="variations[<?= $existingVariationIndex ?>][value]">
                                        <option value="">Select Value</option>
                                        <option value="<?= $itemPrice->variationItems->variation->value ?>" selected>
                                            <?= $itemPrice->variationItems->variation->value ?>
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Price (<?= $currency ?>)</label>
                                    <input type="number" step="0.01" class="form-control" 
                                        name="variations[<?= $existingVariationIndex ?>][price]" 
                                        value="<?= $itemPrice->price ?>">
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" class="btn btn-danger remove-variation">×</button>
                                </div>
                            </div>
                            <?php $existingVariationIndex++; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <button type="button" id="add-variation" class="btn btn-secondary btn-sm">+ Add Variation</button>
        </div>
    </div>

    <!-- Addons Section -->
    <div class="card mt-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">Addons</h5>
        </div>
        <div class="card-body">
            <div id="addons-container">
                <?php if (!$model->isNewRecord && $model->itemAddons): ?>
                    <?php foreach ($model->itemAddons as $index => $addon): ?>
                        <div class="addon-row row mb-3">
                            <div class="col-md-5">
                                <label class="form-label">Addon Name</label>
                                <input type="text" class="form-control" 
                                       name="addons[<?= $index ?>][name]" 
                                       value="<?= $addon->name ?>">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Price (<?= $currency ?>)</label>
                                <input type="number" step="0.01" class="form-control" 
                                       name="addons[<?= $index ?>][price]" 
                                       value="<?= $addon->price ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-danger remove-addon">×</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <button type="button" id="add-addon" class="btn btn-secondary btn-sm">+ Add Addon</button>
        </div>
    </div>

    <div class="form-group mt-4">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
// Get variations data for JavaScript
$variationsData = [];
if (!empty($variationTypes)) {
    foreach ($variationTypes as $type) {
        $variations = Variation::find()->where(['type' => $type])->all();
        $variationsData[$type] = ArrayHelper::map($variations, 'value', 'value');
    }
}
?>

<style>
.variation-row, .addon-row {
    border: 1px solid #ddd;
    padding: 15px;
    border-radius: 5px;
    background: #f9f9f9;
}
.remove-variation, .remove-addon {
    margin-top: 32px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const variationsData = <?= json_encode($variationsData) ?>;
    
    // Start counters from the number of existing variations/addons
    let variationCounter = <?= !$model->isNewRecord && $model->itemPrices ? count($model->itemPrices) : 0 ?>;
    let addonCounter = <?= !$model->isNewRecord && $model->itemAddons ? count($model->itemAddons) : 0 ?>;

    // Debug: Log what's being submitted
    document.querySelector('form').addEventListener('submit', function(e) {
        console.log('Submitting variations:', Array.from(this.elements).filter(el => el.name.includes('variations')));
        console.log('Submitting addons:', Array.from(this.elements).filter(el => el.name.includes('addons')));
    });

    // Add Variation
    document.getElementById('add-variation').addEventListener('click', function() {
        const container = document.getElementById('variations-container');
        const newRow = document.createElement('div');
        newRow.className = 'variation-row row mb-3';
        newRow.innerHTML = `
            <div class="col-md-4">
                <label class="form-label">Type</label>
                <select class="form-control variation-type" name="variations[${variationCounter}][type]">
                    <option value="">Select Type</option>
                    ${Object.keys(variationsData).map(type => 
                        `<option value="${type}">${type}</option>`
                    ).join('')}
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Value</label>
                <select class="form-control variation-value" name="variations[${variationCounter}][value]">
                    <option value="">Select Value</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Price (<?= $currency ?>)</label>
                <input type="number" step="0.01" class="form-control" 
                       name="variations[${variationCounter}][price]" value="">
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-danger remove-variation">×</button>
            </div>
        `;
        container.appendChild(newRow);
        variationCounter++;
    });

    // Add Addon
    document.getElementById('add-addon').addEventListener('click', function() {
        const container = document.getElementById('addons-container');
        const newRow = document.createElement('div');
        newRow.className = 'addon-row row mb-3';
        newRow.innerHTML = `
            <div class="col-md-5">
                <label class="form-label">Addon Name</label>
                <input type="text" class="form-control" 
                       name="addons[${addonCounter}][name]" value="">
            </div>
            <div class="col-md-5">
                <label class="form-label">Price (<?= $currency ?>)</label>
                <input type="number" step="0.01" class="form-control" 
                       name="addons[${addonCounter}][price]" value="">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-danger remove-addon">×</button>
            </div>
        `;
        container.appendChild(newRow);
        addonCounter++;
    });

    // Remove Variation/Addon
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-variation')) {
            e.target.closest('.variation-row').remove();
        }
        if (e.target.classList.contains('remove-addon')) {
            e.target.closest('.addon-row').remove();
        }
    });

    // Update values when type changes
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('variation-type')) {
            const type = e.target.value;
            const valueSelect = e.target.closest('.variation-row').querySelector('.variation-value');
            valueSelect.innerHTML = '<option value="">Select Value</option>';
            
            if (type && variationsData[type]) {
                Object.keys(variationsData[type]).forEach(value => {
                    const option = document.createElement('option');
                    option.value = value;
                    option.textContent = value;
                    valueSelect.appendChild(option);
                });
            }
        }
    });

    // Initialize existing variation values - FIXED VERSION
    document.querySelectorAll('.variation-row').forEach((row, index) => {
        const typeSelect = row.querySelector('.variation-type');
        const valueSelect = row.querySelector('.variation-value');
        
        // Get the currently selected value from the value select
        const currentValue = valueSelect.querySelector('option[selected]')?.value || '';
        
        if (typeSelect.value) {
            // Populate the value dropdown based on the selected type
            const type = typeSelect.value;
            valueSelect.innerHTML = '<option value="">Select Value</option>';
            
            if (type && variationsData[type]) {
                Object.keys(variationsData[type]).forEach(value => {
                    const option = document.createElement('option');
                    option.value = value;
                    option.textContent = value;
                    if (value === currentValue) {
                        option.selected = true;
                    }
                    valueSelect.appendChild(option);
                });
            }
        }
    });
});
</script>