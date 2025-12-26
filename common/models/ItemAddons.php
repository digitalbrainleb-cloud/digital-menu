<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "item_addons".
 *
 * @property int $id
 * @property int|null $item_id
 * @property string $name
 * @property float|null $price
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class ItemAddons extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item_addons';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_id', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['price'], 'default', 'value' => 0.00],
            [['item_id'], 'integer'],
            [['name'], 'required'],
            [['price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'price' => 'Price',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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

}
