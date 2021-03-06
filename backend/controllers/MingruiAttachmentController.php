<?php

namespace backend\controllers;

use backend\models\MingruiAttachment;
use backend\models\MingruiAttachmentSearch;
use backend\models\RestReport;
use backend\models\SaveImage;
use backend\widgets\Nodata;
use backend\models\MingruiScore;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * MingruiAttachmentController implements the CRUD actions for MingruiAttachment model.
 */
class MingruiAttachmentController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionMyattachment()
    {
        $sid = Yii::$app->user->Identity->role_tab_id;
        //var_dump(Yii::$app->user->Identity);
        //$mysample = RestSample::findOne($sid);
        
        $rp = RestReport::find()->where(['sample_id' => $sid])->one();
        if ($rp) {
            return $this->redirect(['index', 'reportid' => $rp->id]);
        } else {
            return Nodata::widget(['message' => '找不到你的报告']);
        }

    }

    /**
     * Lists all MingruiAttachment models.
     * @return mixed
     */
    public function actionIndex($reportid)
    {
        $searchModel = new MingruiAttachmentSearch();
        $params      = Yii::$app->request->queryParams;

        $query = MingruiAttachment::find();
        $query = $query
            ->where(['report_id' => $reportid])
            ->orderBy('id DESC');
        $dataProvider = $searchModel->search($params, $query);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MingruiAttachment model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MingruiAttachment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MingruiAttachment();
        //$imageup = UploadedFile::getInstances($model, 'image');

        if ($model->load(Yii::$app->request->post())) {
            $model->image      = 'tosave';
            $model->createtime = time();
            if (!$model->save()) {
                var_export($model->errors);exit;
            }
            SaveImage::save($model, 'image');

            MingruiScore::add('attachment.add');

            return $this->redirect(['index', 'reportid' => $model->report_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MingruiAttachment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MingruiAttachment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MingruiAttachment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MingruiAttachment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MingruiAttachment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
