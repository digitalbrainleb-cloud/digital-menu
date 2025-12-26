<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use common\models\Category;
use common\models\Item;
use common\models\BusinessSettings;
use common\models\Feedback;
use common\models\Variation;
use common\models\ItemAddons;

class SiteController extends Controller
{
    public function actionIndex()
    {
        $businessSettings = BusinessSettings::getSettings();
        
        return $this->render('index', [
            'businessSettings' => $businessSettings,
        ]);
    }

    public function actionMenu()
    {
        $categories = Category::find()
            ->where(['status' => Category::STATUS_ACTIVE])
            ->with(['activeItems' => function($query) {
                $query->with(['variations', 'itemAddons']);
            }])
            ->orderBy(['sort_order' => SORT_ASC])
            ->all();

        $businessSettings = BusinessSettings::getSettings();

        return $this->render('menu', [
            'categories' => $categories,
            'businessSettings' => $businessSettings,
        ]);
    }

    public function actionCategory($id)
    {
        $category = Category::find()
            ->where(['id' => $id, 'status' => Category::STATUS_ACTIVE])
            ->one();

        if (!$category) {
            throw new NotFoundHttpException('Category not found.');
        }

        $items = Item::find()
            ->where(['category_id' => $id, 'is_available' => Item::STATUS_ACTIVE])
            ->with(['variations', 'itemAddons'])
            ->orderBy(['sort_order' => SORT_ASC])
            ->all();

        $businessSettings = BusinessSettings::getSettings();

        return $this->render('category', [
            'category' => $category,
            'items' => $items,
            'businessSettings' => $businessSettings,
        ]);
    }

    public function actionItem($id)
    {
        $item = Item::find()
            ->where(['id' => $id, 'is_available' => Item::STATUS_ACTIVE])
            ->with(['variations', 'itemAddons', 'category'])
            ->one();

        if (!$item) {
            throw new NotFoundHttpException('Item not found.');
        }

        $variationTypes = Variation::find()
            ->select('type')
            ->distinct()
            ->all();

        return $this->renderAjax('_item_modal', [
            'item' => $item,
            'variationTypes' => $variationTypes,
        ]);
    }

    public function actionFeedback()
    {
        $model = new Feedback();
        $businessSettings = BusinessSettings::getSettings();

        if ($model->load(Yii::$app->request->post())) {
            // Only validate rating
            if (empty($model->rating)) {
                $model->addError('rating', 'Please provide a rating');
            } else {
                // Save the feedback
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Thank you for your feedback! We appreciate your input.');
                    return $this->redirect(['/site/index']); // Redirect to homepage
                }
            }
        }

        return $this->render('feedback', [
            'model' => $model,
            'businessSettings' => $businessSettings,
        ]);
    }

    public function actionItemDetails($id)
    {
        $item = Item::find()
            ->where(['id' => $id, 'is_available' => Item::STATUS_ACTIVE])
            ->one();

        if (!$item) {
            throw new NotFoundHttpException('Item not found.');
        }

        // Get variations WITH their prices from item_prices table
        $variationsWithPrices = (new \yii\db\Query())
            ->select([
                'v.id',
                'v.type', 
                'v.value',
                'vi.id as variation_item_id',
                'COALESCE(ip.price, 0) as price'  // Get price from item_prices or default to 0
            ])
            ->from('{{%variations}} v')
            ->innerJoin('{{%variation_items}} vi', 'v.id = vi.variation_id')
            ->leftJoin('{{%item_prices}} ip', 'ip.variation_item_id = vi.id AND ip.item_id = :itemId', [':itemId' => $item->id])
            ->where(['vi.item_id' => $item->id])
            ->all();

        // Convert to objects with price
        $item->variations = [];
        foreach ($variationsWithPrices as $data) {
            $variation = (object)[
                'id' => $data['id'],
                'type' => $data['type'],
                'value' => $data['value'],
                'price' => $data['price'],
                'variation_item_id' => $data['variation_item_id']
            ];
            $item->variations[] = $variation;
        }

        $businessSettings = BusinessSettings::getSettings();

        return $this->render('item_details', [
            'item' => $item,
            'businessSettings' => $businessSettings,
        ]);
    }
}