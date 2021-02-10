<?php

namespace livefactory\modules\product\controllers;

use Yii;
use livefactory\models\search\CommonModel as SessionVerification;
use livefactory\models\NoteModel;
use livefactory\models\FileModel;
use livefactory\models\HistoryModel;

use livefactory\models\File;
use livefactory\models\Note;
use livefactory\models\History;
use livefactory\models\SendEmail;
use livefactory\models\User as UserDetail;

use livefactory\models\Product;
use livefactory\models\search\Product as ProductSearch;
use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
	public $entity_type='product';
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
    	if(empty(Yii::$app->user->identity->id)){
          $this->redirect(array('/site/login'));
		}
	}
	public function getUserEmail($id){
		$userModel = UserDetail::findOne($id);	
		return $userModel->email;
	}
	public function getUserFullName($id){
		$user = UserDetail::findOne($id);
		return $user->first_name." ".$user->last_name;	
	}
	public function getLoggedUserFullName(){
		$user = UserDetail::findOne(Yii::$app->user->identity->id);
		return $user->first_name." ".$user->last_name;	
	}
	public function getLoggedUserDetail(){
		$user = UserDetail::find()->where('id='.Yii::$app->user->identity->id)->asArray()->one();
		return $user;	
	}
    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
		if(!Yii::$app->user->can('Product.Index')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}
        $searchModel = new ProductSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
		if(!empty($_REQUEST['multiple_del'])){
			if(!Yii::$app->user->can('Product.Delete')){
			throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
		}

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
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		if(!Yii::$app->user->can('Product.View')){
			throw new NotFoundHttpException('You dont have permissions to view this page.');
		}

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->id]);
        } else {
        return $this->render('view', ['model' => $model]);
}
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		if(!Yii::$app->user->can('Product.Create')){
			throw new NotFoundHttpException('You dont have permissions to view this page.');
		}
        $model = new Product;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//Add History For Project
			HistoryModel::historyInsert($this->entity_type,$model->id,'Product Created - (<a href="index.php?r=product/product/product-view&id='.$model->id.'">'.$model->product_name.'</a>)');
			//return $this->redirect(['product-view', 'id' => $model->id]);
			return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
	public function actionAddAttachment(){
			//$attachType = array('doc','xls','pdf','images','audio','vedio','zip');
			if(!empty($_FILES['attach'])){
				$file=FileModel::bulkFileInsert($_REQUEST['entity_id'],$this->entity_type);
            return $this->redirect(['product-view', 'id' => $_REQUEST['entity_id']]);
			} else {
            return $this->render('add-attachment');
        }
		
	}
    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		if(!Yii::$app->user->can('Product.Update')){
			throw new NotFoundHttpException('You dont have permissions to view this page.');
		}
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
	
	public function actionProductView($id)
    {
		if(!Yii::$app->user->can('Product.View')){
			throw new NotFoundHttpException('You dont have permissions to view this page.');
		}

		$emailObj = new SendEmail;
        $model = $this->findModel($id);
		$attachModelR='';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//Add History
			HistoryModel::historyInsert($this->entity_type,$model->id,'Update Product - (<a href="index.php?r=product/product/product-view&id='.$model->id.'">'.$model->product_name.'</a>)');
            return $this->redirect(['index']);
        } else {
			// Send Attachment File to Task Assigned User
			if(!empty($_REQUEST['send_attachment_file'])){
				//Send an Email	
				SendEmail::sendLiveEmail($_REQUEST['uemail'],$_REQUEST['email_body'], $_REQUEST['cc'], $_REQUEST['subject']);
				
					return $this->redirect(['product-view', 'id' => $_REQUEST['id']]);
			}
			// Delete Project Attachment
			if(!empty($_REQUEST['attachment_del_id'])){
					$Attachmodel = File::findOne($_REQUEST['attachment_del_id'])->delete();
			//Add History
			HistoryModel::historyInsert($this->entity_type,$model->id,'Deleted Attachment from Product  - (<a href="index.php?r=product/product/product-view&id='.$model->id.'">'.$model->product_name.'</a>)');
					return $this->redirect(['product-view', 'id' => $_REQUEST['id']]);
			}
			// Add Attachment for Project
			if(!empty($_REQUEST['add_attach'])){
				$aid=FileModel::fileInsert($_REQUEST['entity_id'],$this->entity_type);
				//Add History
			HistoryModel::historyInsert($this->entity_type,$model->id,'Added Attachment into Product - (<a href="index.php?r=product/product/product-view&id='.$model->id.'">'.$model->product_name.'</a>)');
					return $this->redirect(['product-view', 'id' => $_REQUEST['id']]);
			}
			// Product Attachment get
			if(!empty($_REQUEST['attach_update'])){
				$attachModelR=File::findOne($_REQUEST['attach_update']);
			}
			// Task Attachment Update
			if(!empty($_REQUEST['edit_attach'])){
				$file=FileModel::fileEdit();
					if($_FILES['attach']['name']){
						$aid=$_REQUEST['att_id'];
						$link="<a href='".str_replace('web/index.php','',$_SESSION['base_url'])."attachments/".$aid.strrchr($_FILES['attach']['name'], ".")."'>".$_FILES['attach']['name']."</a>";
			//Add History
			HistoryModel::historyInsert($this->entity_type,$model->id,'Updated Attachment in Product - (<a href="index.php?r=product/product/product-view&id='.$model->id.'">'.$model->product_name.'</a>)');
					}
					return $this->redirect(['product-view', 'id' => $_REQUEST['id']]);
			}
            return $this->render('product-view', [
                'model' => $model,
				'attachModel'=>$attachModelR,
            ]);
        }
	}
		/**

     * Deletes an existing Customer model.

     * If deletion is successful, the browser will be redirected to the 'index' page.

     * @param integer $id

     * @return mixed

     */

    public function actionDelete($id)
    {
		if(!Yii::$app->user->can('Product.Delete')){
			throw new NotFoundHttpException('You dont have permissions to view this page.');
		}

        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }



    /**

     * Finds the Customer model based on its primary key value.

     * If the model is not found, a 404 HTTP exception will be thrown.

     * @param integer $id

     * @return Customer the loaded model

     * @throws NotFoundHttpException if the model cannot be found

     */

    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

