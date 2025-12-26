<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "items".
 *
 * @property int $id
 * @property int|null $category_id
 * @property string $name
 * @property string|null $description
 * @property string|null $image
 * @property float|null $base_price
 * @property int $is_available
 * @property int $sort_order
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Category $category
 * @property ItemAddosn[] $itemAddons
 * @property VariationItems[] $variationItems
 * @property ItemPrices[] $itemPrices
 */
class Item extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public $imageFile;
    public $variations = []; // For handling multiple variations
    public $addons = []; // For handling multiple addons

    public static function tableName()
    {
        return 'items';
    }

    public function rules()
    {
        return [
            [['category_id', 'is_available', 'sort_order'], 'integer'],
            [['name', 'category_id'], 'required'],
            [['description'], 'string'],
            [['base_price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'image'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['variations', 'addons'], 'safe'],
            ['is_available', 'default', 'value' => self::STATUS_ACTIVE],
            ['sort_order', 'default', 'value' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category',
            'name' => 'Name',
            'description' => 'Description',
            'image' => 'Image',
            'imageFile' => 'Item Image',
            'base_price' => 'Base Price',
            'is_available' => 'Status',
            'sort_order' => 'Sort Order',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getItemAddons()
    {
        return $this->hasMany(ItemAddons::class, ['item_id' => 'id']);
    }

    public function getVariationItems()
    {
        return $this->hasMany(VariationItems::class, ['item_id' => 'id']);
    }

    public function getItemPrices()
    {
        return $this->hasMany(ItemPrices::class, ['item_id' => 'id']);
    }

    public function getVariations()
    {
        return $this->hasMany(Variation::class, ['id' => 'variation_id'])
            ->viaTable('variation_items', ['item_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = date('Y-m-d H:i:s');
            }
            $this->updated_at = date('Y-m-d H:i:s');
            return true;
        }
        return false;
    }

    public function upload()
    {
        if ($this->imageFile) {
            // Get category name for folder
            $category = $this->category;
            $categoryFolder = $category ? $this->generateFolderName($category->name) : 'uncategorized';
            
            $uploadPath = Yii::getAlias('@frontend/web/uploads/items/' . $categoryFolder . '/');
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }
            
            $filename = Yii::$app->security->generateRandomString() . '.' . $this->imageFile->extension;
            
            if ($this->imageFile->saveAs($uploadPath . $filename)) {
                // Delete old image if exists
                if ($this->image) {
                    $this->deleteOldImage();
                }
                
                $this->image = $categoryFolder . '/' . $filename;
                return true;
            }
        }
        return false;
    }

    private function generateFolderName($categoryName)
    {
        $folderName = preg_replace('/[^a-zA-Z0-9_\-\s]/', '', $categoryName);
        $folderName = str_replace(' ', '_', $folderName);
        $folderName = strtolower($folderName);
        
        if (empty($folderName)) {
            $folderName = 'uncategorized';
        }
        
        return $folderName;
    }

    private function deleteOldImage()
    {
        $oldImagePath = Yii::getAlias('@frontend/web/uploads/items/' . $this->image);
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }
    }

    public function getImageUrl()
    {
        if ($this->image) {
            return Yii::getAlias('/digital-menu/frontend/web/uploads/items/') . $this->image;
        }
        return null;
    }

    public function afterDelete()
    {
        parent::afterDelete();
        // Delete item image
        if ($this->image) {
            $imagePath = Yii::getAlias('@frontend/web/uploads/items/' . $this->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        // Delete related records
        ItemAddons::deleteAll(['item_id' => $this->id]);
        VariationItems::deleteAll(['item_id' => $this->id]);
        ItemPrices::deleteAll(['item_id' => $this->id]);
    }

    /**
     * Get business currency
     */
    public function getCurrency()
    {
        $businessSettings = BusinessSettings::find()->one();
        return $businessSettings ? $businessSettings->currency : 'USD';
    }

    /**
     * Get all variation types
     */
    public static function getVariationTypes()
    {
        return Variation::find()
            ->select('type')
            ->distinct()
            ->indexBy('type')
            ->column();
    }

    /**
     * Get variations by type
     */
    public static function getVariationsByType($type)
    {
        return Variation::find()
            ->where(['type' => $type])
            ->all();
    }
    
}