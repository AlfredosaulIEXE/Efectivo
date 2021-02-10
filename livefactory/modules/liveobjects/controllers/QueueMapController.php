<?php

namespace livefactory\modules\liveobjects\controllers;

use Yii;
use livefactory\models\QueueMap;
use livefactory\models\search\QueueMap as QueueMapSearch;
use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use livefactory\models\TicketCategory;
use livefactory\models\Queue;
use livefactory\models\search\CommonModel as SessionVerification;
/**
 * QueueMapController implements the CRUD actions for QueueMap model.
 */
class QueueMapController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
	public function init(){

		SessionVerification::checkSessionDestroy();

    	if(!isset(Yii::$app->user->identity->id)){

          $this->redirect(array('/site/login'));

		}

	}
    /**
     * Lists all QueueMap models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QueueMapSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single QueueMap model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->id]);
        } else {
        return $this->render('view', ['model' => $model]);
}
    }

    /**
     * Creates a new QueueMap model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new QueueMap;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing QueueMap model.
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
     * Deletes an existing QueueMap model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
	public function actionAjaxGetCategory1($id,$cat_id)
    {
        $categories = TicketCategory::find()->where("active=1 and department_id=$id and parent_id=0")->orderBy("sort_order")->all();
		$options='<option value="">--'.Yii::t('app','Select Category').'--</option>';
		foreach($categories as $row){
			$selected = $row->id == $cat_id?'selected':'';
			$options.='<option value="'.$row->id.'" '.$selected.' >'.$row->label.'</option>';
		}
		echo  $options;
    }
	public function actionAjaxGetCategory2($id,$cat_id)
    {
        $categories = TicketCategory::find()->where("active=1  and parent_id=$id")->orderBy("sort_order")->all();
		$options='<option value="">--'.Yii::t('app','Select Sub Category').'--</option>';
		foreach($categories as $row){
			$selected = $row->id == $cat_id?'selected':'';
			$options.='<option value="'.$row->id.'" '.$selected.' >'.$row->label.'</option>';
		}
		echo  $options;
    }
	public function actionAjaxGetQueue($id,$que_id)
    {
        $queues = Queue::find()->where("department_id=$id")->all();
		$options='<option value="">--'.Yii::t('app','Select Queue').'--</option>';
		foreach($queues as $row){
			$selected = $row->id == $que_id?'selected':'';
			$options.='<option value="'.$row->id.'" '.$selected.' >'.$row->queue_title.'</option>';
		}
		echo  $options;
    }

    /**
     * Finds the QueueMap model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return QueueMap the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = QueueMap::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
