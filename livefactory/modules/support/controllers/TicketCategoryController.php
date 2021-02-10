<?php

namespace livefactory\modules\support\controllers;

use Yii;
use livefactory\models\TicketCategory;
use livefactory\models\searchTicketCategory;
use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TicketCategoryController implements the CRUD actions for TicketCategory model.
 */
class TicketCategoryController extends Controller
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

    /**
     * Lists all TicketCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
		
		if(!empty($_REQUEST['actionType'])){

			//$model = $this->findModel($_REQUEST['sort_order_update']);

			$statusId = $_REQUEST['sort_order_update'];

			$sortValue = $_REQUEST['sort_order_update'.$statusId];	

			//var_dump($statusId." ".$sortValue);
			extract(TicketCategory::find()->select("Max(sort_order) max_sort_order")->where(['parent_id' => '0'])->asArray()->one());
		
			if(!empty($_REQUEST['sort_order_update']) && $_REQUEST['actionType'] !='Down'){

				if($sortValue !='1'){

					$minusValue = intval($sortValue)-1;
					$ticketUpdate= TicketCategory::find()->where(['sort_order' => $sortValue])
					->andWhere(['parent_id' => 0])
					->one();

					$ticketUpdate1= TicketCategory::find()->where(['sort_order' => $minusValue])
					->andWhere(['parent_id' => 0])
					->one();

					$ticketUpdate->sort_order=$minusValue;

					$ticketUpdate->update();

					

					$ticketUpdate1->sort_order=$sortValue;

					$ticketUpdate1->update();

				}

			}else if($_REQUEST['actionType'] == 'Down'){

				if($max_sort_order !=$sortValue){

					$plusValue = intval($sortValue)+1;

					$ticketUpdate= TicketCategory::find()->where(['sort_order' => $sortValue])
					->andWhere(['parent_id' => 0])
					->one();

					$ticketUpdate1= TicketCategory::find()->where(['sort_order' => $plusValue])
					->andWhere(['parent_id' => 0])
					->one();

					$ticketUpdate->sort_order=$plusValue;

					$ticketUpdate->update();

					$ticketUpdate1->sort_order=$sortValue;

					$ticketUpdate1->update();

				}

			}

		}
		
		
        $searchModel = new searchTicketCategory;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
		
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
			
        ]);
    }

    /**
     * Displays a single TicketCategory model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['update', 'id' => $model->parent_id]);
        } else {
        return $this->render('view', ['model' => $model]);
}
    }

    /**
     * Creates a new TicketCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TicketCategory;
		$parent = new TicketCategory;
		 if(!empty($_GET['parent_id'])){
			$parent =  TicketCategory::findOne($_GET['parent_id']);
		 }else{
		
		extract(TicketCategory::find()->select("Max(sort_order) max_sort_order")->where(['parent_id'=> 0])->asArray()->one());
        $model->sort_order=$max_sort_order+1;
		
		}
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if(!empty($_GET['parent_id'])){
				return $this->redirect(['update', 'id' => $_GET['parent_id']]);
			}else{
				 return $this->redirect(['update', 'id' => $model->id]);
			}
        } else {
            return $this->render('create', [
                'model' => $model,
				'parent' => $parent,
            ]);
        }
    }

    /**
     * Updates an existing TicketCategory model.
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

	public function actionSubUpdate($id)
    {
        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Deletes an existing TicketCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
		//(new TicketCategory)->delete()->where('id='.$id.' or parent_id='.$id);

        return $this->redirect(['index']);
    }

    /**
     * Finds the TicketCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TicketCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TicketCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
