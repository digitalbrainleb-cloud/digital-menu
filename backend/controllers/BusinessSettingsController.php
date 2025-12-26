<?php

namespace backend\controllers;

use Yii;
use common\models\BusinessSettings;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

class BusinessSettingsController extends Controller
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
        // Get the first business settings record or create new one
        $model = BusinessSettings::find()->one();
        if (!$model) {
            $model = new BusinessSettings();
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->logoFile = \yii\web\UploadedFile::getInstance($model, 'logoFile');
            
            // Validate the model
            if ($model->validate()) {
                // Handle file upload if a file was selected
                if ($model->logoFile) {
                    if ($model->upload()) {
                        // File uploaded successfully, now save the model (without logoFile validation)
                        if ($model->save(false)) {
                            Yii::$app->session->setFlash('success', 'Business settings updated successfully with new logo.');
                            return $this->refresh();
                        }
                    } else {
                        Yii::$app->session->setFlash('error', 'Failed to upload logo.');
                    }
                } else {
                    // No file to upload, just save the model
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', 'Business settings updated successfully.');
                        return $this->refresh();
                    }
                }
            }
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }
}