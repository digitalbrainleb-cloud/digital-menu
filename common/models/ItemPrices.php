<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "item_prices".
 *
 * @property int $id
 * @property int|null $item_id
 * @property int|null $variation_item_id
 * @property float $price
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Item $item
 * @property VariationItems $variationItems  // Changed to plural
 */
class ItemPrices extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_prices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_id', 'variation_item_id', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['item_id', 'variation_item_id'], 'integer'],
            [['price'], 'required'],
            [['price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::class, 'targetAttribute' => ['item_id' => 'id']],
            [['variation_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => VariationItems::class, 'targetAttribute' => ['variation_item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_id' => 'Item ID',
            'variation_item_id' => 'Variation Item ID',
            'price' => 'Price',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Item]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::class, ['id' => 'item_id']);
    }

    /**
     * Gets query for [[VariationItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVariationItems()  // Changed to plural to match your model name
    {
        return $this->hasOne(VariationItems::class, ['id' => 'variation_item_id']);
    }

    /**
     * {@inheritdoc}
     */
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
}