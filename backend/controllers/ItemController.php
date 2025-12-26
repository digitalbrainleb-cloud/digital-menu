<?php

namespace backend\controllers;

use Yii;
use common\models\Item;
use common\models\ItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\models\Variation;
use common\models\VariationItems;
use common\models\ItemPrices;
use common\models\ItemAddons;

class ItemController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Item();

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            
            if ($model->validate()) {
                // Handle file upload
                if ($model->imageFile && $model->upload()) {
                    // File uploaded successfully
                }
                
                if ($model->save(false)) {
                    // Save variations
                    $this->saveVariations($model, Yii::$app->request->post('variations', []));
                    
                    // Save addons
                    $this->saveAddons($model, Yii::$app->request->post('addons', []));
                    
                    Yii::$app->session->setFlash('success', 'Item created successfully.');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            
            if ($model->validate()) {
                // Handle file upload
                if ($model->imageFile && $model->upload()) {
                    // File uploaded successfully
                }
                
                if ($model->save(false)) {
                    // Get variations and addons from POST data
                    $variations = Yii::$app->request->post('variations', []);
                    $addons = Yii::$app->request->post('addons', []);
                    
                    // Always process variations - empty array means remove all
                    VariationItems::deleteAll(['item_id' => $model->id]);
                    ItemPrices::deleteAll(['item_id' => $model->id]);
                    if (!empty($variations)) {
                        $this->saveVariations($model, $variations);
                    }
                    
                    // Always process addons - empty array means remove all
                    ItemAddons::deleteAll(['item_id' => $model->id]);
                    if (!empty($addons)) {
                        $this->saveAddons($model, $addons);
                    }
                    
                    Yii::$app->session->setFlash('success', 'Item updated successfully.');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Item deleted successfully.');
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Item::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Save variations for an item
     */
    private function saveVariations($item, $variations)
    {
        foreach ($variations as $variationData) {
            if (!empty($variationData['type']) && !empty($variationData['value']) && isset($variationData['price'])) {
                // Find or create variation
                $variation = Variation::find()
                    ->where(['type' => $variationData['type'], 'value' => $variationData['value']])
                    ->one();
                
                if (!$variation) {
                    $variation = new Variation();
                    $variation->type = $variationData['type'];
                    $variation->value = $variationData['value'];
                    $variation->save();
                }
                
                // Create variation item
                $variationItems = new VariationItems();
                $variationItems->item_id = $item->id;
                $variationItems->variation_id = $variation->id;
                $variationItems->save();
                
                // Create item price
                $itemPrices = new ItemPrices();
                $itemPrices->item_id = $item->id;
                $itemPrices->variation_item_id = $variationItems->id;
                $itemPrices->price = $variationData['price'];
                $itemPrices->save();
            }
        }
    }

    /**
     * Save addons for an item
     */
    private function saveAddons($item, $addons)
    {
        foreach ($addons as $addonData) {
            if (!empty($addonData['name']) && isset($addonData['price'])) {
                $addon = new ItemAddons();
                $addon->item_id = $item->id;
                $addon->name = $addonData['name'];
                $addon->price = $addonData['price'];
                $addon->save();
            }
        }
    }

    /**
     * Get variations by type (AJAX)
     */
    public function actionGetVariations($type)
    {
        $variations = Variation::find()
            ->where(['type' => $type])
            ->all();
        
        $result = [];
        foreach ($variations as $variation) {
            $result[] = [
                'id' => $variation->id,
                'value' => $variation->value,
            ];
        }
        
        return json_encode($result);
    }
}