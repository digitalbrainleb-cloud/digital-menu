<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "feedback".
 *
 * @property int $id
 * @property string $customer_name
 * @property string|null $email
 * @property string|null $phone
 * @property int $rating
 * @property string|null $comment
 * @property int $status
 * @property string|null $created_at
 */
class Feedback extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'feedback';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rating'], 'required', 'message' => 'Please provide a rating'],
            [['rating', 'status'], 'integer'],
            [['rating'], 'integer', 'min' => 1, 'max' => 5],
            [['comment'], 'string'],
            [['created_at'], 'safe'],
            [['customer_name', 'email', 'phone'], 'string', 'max' => 255],
            ['email', 'email', 'skipOnEmpty' => true], // Skip validation if empty
            ['phone', 'match', 'pattern' => '/^[\d\s\-\+\(\)]*$/', 'message' => 'Phone number can only contain digits, spaces, and basic phone characters.', 'skipOnEmpty' => true],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            // Remove required rules for other fields
            [['customer_name', 'email', 'phone', 'comment'], 'default', 'value' => null],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_name' => 'Customer Name',
            'email' => 'Email',
            'phone' => 'Phone Number',
            'rating' => 'Rating',
            'comment' => 'Comment',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
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
            return true;
        }
        return false;
    }

    /**
     * Get status options
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
        ];
    }

    /**
     * Get status label
     */
    public function getStatusLabel()
    {
        $options = self::getStatusOptions();
        return isset($options[$this->status]) ? $options[$this->status] : 'Unknown';
    }
}