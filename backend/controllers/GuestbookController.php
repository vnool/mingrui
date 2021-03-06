<?php

namespace backend\controllers;

use backend\models\Geneareas;
use backend\models\MingruiComments;
use backend\models\RestReport;
use backend\models\RestReportSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use backend\models\MingruiScore;

/**
 * RestReportController implements the CRUD actions for RestReport model.
 */
class GuestbookController extends Controller
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

    public function actionSearch()
    {
        $searchModel = new RestReportSearch();
        $params      = Yii::$app->request->queryParams;
        //$params['RestReportSearch']['rest_report.status'] = 'finished';

        $query = RestReport::find();
        $query = $query
            ->where(['<>', 'ptype', 'yidai'])
            ->andWhere(['rest_report.status' => 'finished']);

        $dataProvider = $searchModel->search($params, $query);

        return $this->render('search', [
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Lists all RestReport models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RestClientSearch();
        $params      = Yii::$app->request->queryParams;
        //$params['RestReportSearch']['rest_report.status'] = 'finished';

        $query = RestClient::find();

        $dataProvider = $searchModel->search($params, $query);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RestReport model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $viewname = 'view';

        return $this->render($viewname, [
            'id'       => $id,
            'comments' => $this->getComments($id),
        ]);
    }

    public function actionMy()
    {
        $id = Yii::$app->user->Identity->role_tab_id;
        return $this->render('view', [
            'id'       => 'gb' . $id,
            'comments' => $this->getComments('gb' . $id),
        ]);
    }

/**
 * Displays a single RestReport model.
 * @param integer $id
 * @return mixed
 */
    public function actionSendComment($id)
    {
        $model = new MingruiComments();
        $model->load(Yii::$app->request->post());
        $model->uid = Yii::$app->user->id;

        if ($model->save()) {
        	MingruiScore::add('guestbook.create');
            return $this->redirect(['view', 'id' => $id]);
        } else {
            var_dump($model->errors);
        }
    }

    /**
     * Displays a single RestReport model.
     * @param integer $id
     * @return mixed
     */
    public function actionShowReport($id)
    {
        return $this->render('showreport', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RestReport model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RestReport();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RestReport model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
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
     * Deletes an existing RestReport model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function getComments($id)
    {
        $comments = MingruiComments::find()
            ->where(['report_id' => $id])
            ->joinWith(['creator'])
            ->all();
        /* foreach ($comments as $cmt ) {

        }*/

        return $comments;
    }

    public function actionAnalyze($id)
    {
        return $this->render('analyze', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionStats($id)
    {
        //1. find user's bad gene
        $userdata       = $this->findModel($id);
        $cnv_array      = json_decode($userdata->cnvsave, true);
        $user_cnv_gene  = '';
        $user_cnv_areas = [];
        foreach ($cnv_array as $key => $data) {
            $user_cnv_gene  = $data[2];
            $user_cnv_areas = $data[4];
        }

        //2. find all areas of this gene
        $final_areas = [];
        if (!empty($user_cnv_gene)) {
            $areas = Geneareas::find()->where(['geneareas.gene' => trim($user_cnv_gene)])->all();

            foreach ($areas as $area) {
                $final_areas[] = ['start' => $area->startcoord,
                    'end'                     => $area->endcoord,
                    'count'                   => $area->count,
                    'bad'                     => false,
                ];
            }

            foreach ($user_cnv_areas as $user_cnv_area) {
                $final_areas[$user_cnv_area - 1]['bad'] = true;
            }
        }

        return $this->render('stats', [
            'gene'  => $user_cnv_gene,
            'data'  => json_encode($final_areas),
            'model' => $this->findModel($id),
        ]);
    }

    //import gene areas data to DB
    public function actionImportgeneareas()
    {
        /* $handle=fopen("geneareas.csv","r"); */
        /* while($data=fgetcsv($handle,0,",")){ */
        /*      $gene = $data[0]; */
        /*      $count = $data[1]; */
        /*      $starts = explode(',', $data[2]); */
        /*      $ends = explode(',', $data[3]); */
        /*      for($i=0;$i<$count;$i++){ */
        /*           $area = new Geneareas; */
        /*           $area->gene = $gene; */
        /*           $area->startcoord = $starts[$i]; */
        /*           $area->endcoord = $ends[$i]; */
        /*           $area->save(); */
        /*      } */
        /* } */
        echo "OK";
    }

    //import gene types data to DB
    public function actionImportgenetypes()
    {
        /* $handle=fopen("genetypes.csv","r"); */
        /* while($data=fgetcsv($handle,0,",")){ */
        /*      $type = new Genetypes; */
        /*      $type->startCoord = $data[0]; */
        /*      $type->endCoord = $data[1]; */
        /*      $type->gene = $data[2]; */
        /*      $type->tag = $data[3]; */
        /*      $type->descr = $data[4]; */
        /*      $type->hgvs = $data[5]; */
        /*      $type->vartype = $data[6]; */
        /*      $type->save(); */
        /* } */
        echo "OK";
    }

    //calculate the report count of each gene area
    public function actionGenecal()
    {
        /* $types = Genetypes::find()->all(); */
        /* foreach($types as $type) { */
        /*      echo $type->gene; */
        /*      $areas = Geneareas::find()->where(['geneareas.gene' => trim($type->gene)])->all(); */
        /*      foreach($areas as $area){ */
        /*           if($type->startCoord >= $area->startcoord and $type->startCoord <= $area->endcoord){ */
        /*                $area->count = $area->count + 1; */
        /*                $area->save(); */
        /*           } */
        /*      } */
        /* } */
        echo "OK";
    }

    // public function
    /**
     * Finds the RestReport model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RestReport the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RestReport::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
