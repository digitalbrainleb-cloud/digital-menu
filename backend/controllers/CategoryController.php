<?php

namespace backend\controllers;

use Yii;
use common\models\Category;
use common\models\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

class CategoryController extends Controller
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
        $searchModel = new CategorySearch();
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
        $model = new Category();

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            
            // Validate the model first
            if ($model->validate()) {
                // Handle file upload BEFORE saving to database
                if ($model->imageFile && $model->upload()) {
                    // File uploaded successfully, the image field is now set
                    // Now save the model (including the image field)
                    if ($model->save(false)) { // false to skip validation as we already validated
                        Yii::$app->session->setFlash('success', 'Category created successfully with image.');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } else {
                    // No file to upload or upload failed, just save the model
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Category created successfully.');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }
            
            // If we get here, there was an error
            Yii::$app->session->setFlash('error', 'There was an error creating the category.');
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
            
            // Validate the model first
            if ($model->validate()) {
                // Handle file upload BEFORE saving to database
                if ($model->imageFile && $model->upload()) {
                    // File uploaded successfully, the image field is now updated
                    // Now save the model (including the updated image field)
                    if ($model->save(false)) {
                        Yii::$app->session->setFlash('success', 'Category updated successfully with new image.');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } else {
                    // No file to upload, just save the model
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Category updated successfully.');
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }
            
            // If we get here, there was an error
            Yii::$app->session->setFlash('error', 'There was an error updating the category.');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $categoryName = $model->name;
        
        // Check if category has items before attempting deletion
        $itemCount = $model->getItems()->count();
        if ($itemCount > 0) {
            Yii::$app->session->setFlash('error', 
                "Cannot delete category '{$categoryName}' because it has {$itemCount} item(s) assigned to it. " .
                "Please remove or reassign all items before deleting this category."
            );
            return $this->redirect(['index']);
        }
        
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', "Category '{$categoryName}' and its folder have been deleted successfully.");
        } else {
            Yii::$app->session->setFlash('error', 'There was an error deleting the category.');
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}