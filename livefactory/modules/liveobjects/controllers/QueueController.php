<?php

namespace livefactory\modules\liveobjects\controllers;

use Yii;
use livefactory\models\Queue;
use livefactory\models\User as UserModel;
use livefactory\models\QueueUsers;
use livefactory\models\History;
use livefactory\models\HistoryModel;
use livefactory\models\search\Queue as QueueSearch;
use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use livefactory\models\User as UserDetail;
/**
 * QueueController implements the CRUD actions for Queue model.
 */
class QueueController extends Controller
{
	public $entity_type='queue';
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

    /**
     * Lists all Queue models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QueueSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
		if(!empty($_REQUEST['multiple_del'])){
			$rows=$_REQUEST['selection'];
			for($i=0;$i<count($rows);$i++){
				$this->findModel($rows[$i])->delete();
			}
		}
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Queue model.
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
     * Creates a new Queue model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Queue;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

			if ($model->queue_supervisor_user_id)
			{
				$queueusers = new QueueUsers;
				$queueusers->queue_id = $model->id;
				$queueusers->user_id = $model->queue_supervisor_user_id;
				$queueusers->save();
			}
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Queue model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		//Get Users 
		$queueUserModel = UserDetail::find()->where("NOT EXISTS(Select *
FROM tbl_queue_users  WHERE queue_id =".$model->id." and user_id=tbl_user.id) and active=1")->asArray()->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if ($model->queue_supervisor_user_id && !QueueUsers::find()->where("id=".$model->queue_supervisor_user_id)->exists())
			{
				$queueusers = new QueueUsers;
				$queueusers->queue_id = $model->id;
				$queueusers->user_id = $model->queue_supervisor_user_id;
				$queueusers->save();
			}
            return $this->redirect(['index']);
        } else {
			// Add user for Queue
			if(!empty($_REQUEST['q_users'])){
				$q_users = $_REQUEST['q_users'];
				for($i=0;$i<count($q_users);$i++){
					$queueUserAdd = new QueueUsers();
					$queueUserAdd->queue_id=$_REQUEST['id'];
					$queueUserAdd->user_id=$q_users[$i];
					$queueUserAdd->save();
				}
				//Add History
			HistoryModel::historyInsert($this->entity_type,$model->id,'Add Users For Queue - (<a href="index.php?r=liveobjects/queue/update&id='.$model->id.'">'.$model->queue_title.'</a>)');
				return $this->redirect(['update', 'id' => $_REQUEST['id']]);	
			}
			// Delete Project User  
			if(!empty($_REQUEST['udel'])){
				$QueueUsers = QueueUsers::findOne($_REQUEST['udel']);
				if (!is_null($QueueUsers)) {
					$QueueUsers->delete();
				}
			//Add History
			HistoryModel::historyInsert($this->entity_type,$model->id,'Queue User Deleted from Queue - (<a href="index.php?r=liveobjects/queue/update&id='.$model->id.'">'.$model->queue_title.'</a>)');
					return $this->redirect(['update', 'id' => $_REQUEST['id']]);
			}
            return $this->render('update', [
                'model' => $model,
				'queueUserModel'=>$queueUserModel,
            ]);
        }
    }
	public function actionAjaxUserDetail($id){
		 if (($model = UserModel::findOne($id)) !== null) {
			 $path='../users/'.$model->id.'.png';

			if(file_exists($path)){

				$image='<img  src="../users/'.$model->id.'.png" class="img-responsive">';								

			 }else{ 

				$image='<img src="../users/nophoto.jpg" class="img-responsive">';

			 }
			 $data ='<div class="row">';
			 $data .='<div class="col-sm-9">
			 			<div class="row">
							<div class="col-sm-6">
								<b>'.Yii::t('app', 'First Name').'</b>
								'.$model->first_name.'
							</div>
							<div class="col-sm-6">
								<b>'.Yii::t('app', 'Last Name').'</b>
								'.$model->last_name.'
							</div>
						</div>
						<hr/>
						<div class="row">
							<div class="col-sm-6">
								<b>'.Yii::t('app', 'Username').'</b>
								'.$model->username.'
							</div>
							<div class="col-sm-6">
								<b>'.Yii::t('app', 'Email').'</b>
								'.$model->email.'
							</div>
						</div>
						<hr/>
						<div class="row">
							<div class="col-sm-12">
								<b>'.Yii::t('app', 'About').'</b><br/>
								'.$model->about.'
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						'.$image.'
					</div>
				</div>';
		 }else{
			$data ='<div class="alert alert-danger">'.Yii::t('app', 'No Data').'</div>'; 
		 }
		 return $data;
	}
    /**
     * Deletes an existing Queue model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Queue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Queue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Queue::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
