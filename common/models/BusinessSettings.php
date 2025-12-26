<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "business_settings".
 *
 * @property int $id
 * @property string|null $business_name
 * @property string|null $logo
 * @property string|null $currency
 * @property string|null $whatsapp_number
 * @property string|null $facebook_url
 * @property string|null $instagram_url
 * @property string|null $twitter_url
 * @property string|null $description
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class BusinessSettings extends \yii\db\ActiveRecord
{

    public $logoFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_name', 'logo', 'whatsapp_number', 'facebook_url', 'instagram_url', 'twitter_url', 'description', 'address', 'phone', 'email', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['currency'], 'default', 'value' => 'USD'],
            [['description', 'address'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['business_name', 'logo', 'facebook_url', 'instagram_url', 'twitter_url', 'email'], 'string', 'max' => 255],
            [['currency'], 'string', 'max' => 10],
            [['whatsapp_number', 'phone'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'business_name' => 'Business Name',
            'logo' => 'Logo',
            'currency' => 'Currency',
            'whatsapp_number' => 'Whatsapp Number',
            'facebook_url' => 'Facebook Url',
            'instagram_url' => 'Instagram Url',
            'twitter_url' => 'Twitter Url',
            'description' => 'Description',
            'address' => 'Address',
            'phone' => 'Phone',
            'email' => 'Email',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function upload()
    {
        if ($this->logoFile) {
            // Generate unique filename
            $filename = Yii::$app->security->generateRandomString() . '.' . $this->logoFile->extension;
            $uploadPath = Yii::getAlias('@frontend/web/uploads/logo/');
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0775, true);
            }
            
            // Save the file
            if ($this->logoFile->saveAs($uploadPath . $filename)) {
                // Delete old logo if exists
                if ($this->logo && file_exists($uploadPath . $this->logo)) {
                    unlink($uploadPath . $this->logo);
                }
                
                $this->logo = $filename;
                return true;
            }
        }
        return false;
    }

    public static function getSettings()
    {
        $settings = self::findOne(1);
        if (!$settings) {
            // Create default settings if none exist
            $settings = new BusinessSettings();
            $settings->id = 1;
            $settings->business_name = 'My Restaurant';
            $settings->currency = 'USD';
            $settings->description = 'Welcome to our restaurant!';
            $settings->save(false); // Save without validation
        }
        return $settings;
    }

    public function getLogoUrl()
    {
        if ($this->logo) {
            return Yii::getAlias('@web/uploads/logo/') . $this->logo;
        }
        return 'https://via.placeholder.com/120x120?text=Logo';
    }

}
