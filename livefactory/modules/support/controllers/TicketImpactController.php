<?php

namespace livefactory\modules\support\controllers;

use Yii;
use livefactory\models\TicketImpact;
use livefactory\models\search\TicketImpact as TicketImpactSearch;
use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TicketImpactController implements the CRUD actions for TicketImpact model.
 */
class TicketImpactController extends Controller
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
     * Lists all TicketImpact models.
     * @return mixed
     */
    public function actionIndex()
    {
		// Make Default 
		\livefactory\models\DefaultValueModule::upsertDefault('ticket_impact');
		if(!empty($_REQUEST['actionType'])){

			//$model = $this->findModel($_REQUEST['sort_order_update']);

			$statusId = $_REQUEST['sort_order_update'];

			$sortValue = $_REQUEST['sort_order_update'.$statusId];	

			//var_dump($statusId." ".$sortValue);
			extract(TicketImpact::find()->select("Max(sort_order) max_sort_order")->asArray()->one());
			if(!empty($_REQUEST['sort_order_update']) && $_REQUEST['actionType'] !='Down'){

				if($sortValue !='1'){

					$minusValue = intval($sortValue)-1;

					$ticketUpdate= TicketImpact::find()->where(['sort_order' => $sortValue])->one();

					$ticketUpdate1= TicketImpact::find()->where(['sort_order' => $minusValue])->one();

					$ticketUpdate->sort_order=$minusValue;

					$ticketUpdate->update();

					

					$ticketUpdate1->sort_order=$sortValue;

					$ticketUpdate1->update();

				}

			}else if($_REQUEST['actionType'] == 'Down'){

				if($max_sort_order !=$sortValue){

					$plusValue = intval($sortValue)+1;

					$ticketUpdate= TicketImpact::find()->where(['sort_order' => $sortValue])->one();

					$ticketUpdate1= TicketImpact::find()->where(['sort_order' => $plusValue])->one();

					$ticketUpdate->sort_order=$plusValue;

					$ticketUpdate->update();

					$ticketUpdate1->sort_order=$sortValue;

					$ticketUpdate1->update();

				}

			}

		}
        $searchModel = new TicketImpactSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single TicketImpact model.
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
     * Creates a new TicketImpact model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TicketImpact;
		extract(TicketImpact::find()->select("Max(sort_order) max_sort_order")->asArray()->one());
        
		$model->sort_order=$max_sort_order+1;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TicketImpact model.
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
     * Deletes an existing TicketImpact model.
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
     * Finds the TicketImpact model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TicketImpact the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TicketImpact::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
