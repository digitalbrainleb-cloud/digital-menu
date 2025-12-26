<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $image
 * @property int $sort_order
 * @property int $status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Item[] $items
 */
class Category extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public $imageFile; // Add this property

    public static function tableName()
    {
        return 'categories';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['sort_order', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'image'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['sort_order', 'default', 'value' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'image' => 'Image',
            'imageFile' => 'Category Image',
            'sort_order' => 'Sort Order',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getItems()
    {
        return $this->hasMany(Item::class, ['category_id' => 'id'])
            ->orderBy(['sort_order' => SORT_ASC]);
    }

    public function getActiveItems()
    {
        return $this->getItems()->andWhere(['is_available' => 1]);
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

    /**
     * Handle image file upload - Creates category-specific folder
     */
    public function upload()
    {
        if ($this->imageFile) {
            // Create safe folder name from category name
            $folderName = $this->generateFolderName($this->name);
            $uploadPath = Yii::getAlias('@frontend/web/uploads/categories/' . $folderName . '/');
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadPath)) {
                if (!mkdir($uploadPath, 0775, true)) {
                    Yii::error("Failed to create directory: " . $uploadPath, 'category');
                    return false;
                }
            }
            
            // Generate unique filename
            $filename = Yii::$app->security->generateRandomString() . '.' . $this->imageFile->extension;
            
            if ($this->imageFile->saveAs($uploadPath . $filename)) {
                // Delete old image if exists (for updates)
                if ($this->image) {
                    $this->deleteOldImage();
                }
                
                // Save the relative path including folder name to the model
                $this->image = $folderName . '/' . $filename;
                Yii::info("Image uploaded successfully: " . $this->image, 'category');
                return true;
            } else {
                Yii::error("Failed to save uploaded file to: " . $uploadPath . $filename, 'category');
            }
        }
        return false;
    }

    /**
     * Generate safe folder name from category name
     */
    private function generateFolderName($categoryName)
    {
        // Remove special characters and replace spaces with underscores
        $folderName = preg_replace('/[^a-zA-Z0-9_\-\s]/', '', $categoryName);
        $folderName = str_replace(' ', '_', $folderName);
        $folderName = strtolower($folderName);
        
        // If the name is empty after cleaning, use a default
        if (empty($folderName)) {
            $folderName = 'category_' . $this->id;
        }
        
        return $folderName;
    }

    /**
     * Delete old image when updating
     */
    private function deleteOldImage()
    {
        $oldImagePath = Yii::getAlias('@frontend/web/uploads/categories/' . $this->image);
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }
    }

    /**
     * Get the folder name for this category
     */
    public function getFolderName()
    {
        return $this->generateFolderName($this->name);
    }

    /**
     * Get the full path to the category folder
     */
    public function getFolderPath()
    {
        $folderName = $this->getFolderName();
        return Yii::getAlias('@frontend/web/uploads/categories/' . $folderName);
    }

    /**
     * Delete the category folder and all its contents
     */
    public function deleteFolder()
    {
        $folderPath = $this->getFolderPath();
        
        if (is_dir($folderPath)) {
            $this->deleteFolderRecursive($folderPath);
            Yii::info("Category folder deleted: " . $folderPath, 'category');
            return true;
        }
        
        return false;
    }

    /**
     * Recursively delete a folder and all its contents
     */
    private function deleteFolderRecursive($dir)
    {
        if (!is_dir($dir)) {
            return false;
        }
        
        $files = array_diff(scandir($dir), array('.', '..'));
        
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_dir($path)) {
                $this->deleteFolderRecursive($path);
            } else {
                unlink($path);
            }
        }
        
        return rmdir($dir);
    }

    /**
     * After delete event - delete the folder
     */
    public function afterDelete()
    {
        parent::afterDelete();
        $this->deleteFolder();
    }

    /**
     * Get the full URL for the image
     */
    public function getImageUrl()
    {
        if ($this->image) {
            return Yii::getAlias('/digital-menu/frontend/web/uploads/categories/') . $this->image;
        }
        return null;
    }

    /**
     * Get the full filesystem path for the image
     */
    public function getImagePath()
    {
        if ($this->image) {
            return Yii::getAlias('@frontend/web/uploads/categories/') . $this->image;
        }
        return null;
    }
}