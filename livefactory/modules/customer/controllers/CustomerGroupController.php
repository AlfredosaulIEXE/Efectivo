<?php

namespace livefactory\modules\customer\controllers;

use Yii;
use livefactory\models\CustomerGroup;
use livefactory\models\search\CustomerGroup as CustomerGroupSearch;
use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use livefactory\models\search\CommonModel as SessionVerification;
/**
 * CustomerGroupController implements the CRUD actions for CustomerGroup model.
 */
class CustomerGroupController extends Controller
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

		if(!Yii::$app->user->can('Setting.Pages')){

          $this->redirect(array('/site/index'));

		}

	}
    /**
     * Lists all CustomerGroup models.
     * @return mixed
     */
    public function actionIndex()
    {
		extract(CustomerGroup::find()->select("Max(sort_order) max_sort_order")->asArray()->one());

		//var_dump($_REQUEST['actionType']);

		if(!empty($_REQUEST['sort_order_update']) && !empty($_REQUEST['actionType'])){

			//$model = $this->findModel($_REQUEST['sort_order_update']);

			$statusId = $_REQUEST['sort_order_update'];

			$sortValue = $_REQUEST['sort_order_update'.$statusId];	

			//var_dump($statusId." ".$sortValue);

			if($_REQUEST['actionType'] !='Down'){

				if($sortValue !='1'){

					$minusValue = intval($sortValue)-1;

					$cusotomerUpdate= CustomerGroup::find()->where(['sort_order' => $sortValue])->one();

					$cusotomerUpdate1= CustomerGroup::find()->where(['sort_order' => $minusValue])->one();

					$cusotomerUpdate->sort_order=$minusValue;

					$cusotomerUpdate->update();

					

					$cusotomerUpdate1->sort_order=$sortValue;

					$cusotomerUpdate1->update();

				}

			}else if($_REQUEST['actionType'] == 'Down'){

				if($max_sort_order !=$sortValue){

					$plusValue = intval($sortValue)+1;

					$cusotomerUpdate= CustomerGroup::find()->where(['sort_order' => $sortValue])->one();

					$cusotomerUpdate1= CustomerGroup::find()->where(['sort_order' => $plusValue])->one();

					$cusotomerUpdate->sort_order=$plusValue;

					$cusotomerUpdate->update();

					$cusotomerUpdate1->sort_order=$sortValue;

					$cusotomerUpdate1->update();

				}

			}

		}
        $searchModel = new CustomerGroupSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single CustomerGroup model.
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
     * Creates a new CustomerGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CustomerGroup;
		extract(CustomerGroup::find()->select("Max(sort_order) max_sort_order")->asArray()->one());
        
		$model->sort_order=$max_sort_order+1;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CustomerGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CustomerGroup model.
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
     * Finds the CustomerGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CustomerGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CustomerGroup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
