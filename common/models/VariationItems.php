<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "variation_items".
 *
 * @property int $id
 * @property int|null $item_id
 * @property int|null $variation_id
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Item $item
 * @property Variation $variation  // Add this relation
 */
class VariationItems extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'variation_items';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_id', 'variation_id', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['item_id', 'variation_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['item_id', 'variation_id'], 'unique', 'targetAttribute' => ['item_id', 'variation_id']],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::class, 'targetAttribute' => ['item_id' => 'id']],
            [['variation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Variation::class, 'targetAttribute' => ['variation_id' => 'id']],
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
            'variation_id' => 'Variation ID',
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
     * Gets query for [[Variation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVariation()
    {
        return $this->hasOne(Variation::class, ['id' => 'variation_id']);
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