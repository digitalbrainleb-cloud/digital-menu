<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "variations".
 *
 * @property int $id
 * @property string $type
 * @property string $value
 * @property string|null $updated_at
 * @property string|null $created_at
 *
 * @property VariationItem[] $variationItems
 */
class Variation extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'variations';
    }

    public function rules()
    {
        return [
            [['type', 'value'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['type'], 'string', 'max' => 100],
            [['value'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'value' => 'Value',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getVariationItems()
    {
        return $this->hasMany(VariationItems::class, ['variation_id' => 'id']);
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

    public function hasItems()
    {
        return $this->getVariationItems()->count() > 0;
    }
}