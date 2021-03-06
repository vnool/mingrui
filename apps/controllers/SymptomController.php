<?php

namespace apps\controllers;

use Yii;
use apps\models\GenelistSymptom;
use apps\models\GenelistSymptomSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SymptomController implements the CRUD actions for GenelistSymptom model.
 */
class SymptomController extends Controller
{
    public $enableCsrfValidation = false;
     /**
     * @inheritdoc
     */
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

    /**
     * Lists all GenelistSymptom models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GenelistSymptomSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSearch() { 
    	 $keyword = Yii::$app->request->post('keyword');
    	 $keyword = str_replace('　', ' ', $keyword);
    	 $list = explode(' ', $keyword );
    	 $keyword  = end($list);
    	$query = GenelistSymptom::find()->where(['like', 'symptom',$keyword])->all();
    	$list = [];
        foreach ($query as   $info) {
        	 $list[] = ["label"=>$info->symptom, 'value'=>$info->symptom, 'id'=>$info->id];
        }

        $this->layout = false;
        return json_encode($list);

    }

    /**
     * Displays a single GenelistSymptom model.
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
     * Creates a new GenelistSymptom model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GenelistSymptom();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id'           => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing GenelistSymptom model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id'           => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing GenelistSymptom model.
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
     * Finds the GenelistSymptom model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return GenelistSymptom the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GenelistSymptom::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
