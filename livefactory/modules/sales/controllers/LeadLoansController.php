<?php

namespace livefactory\modules\sales\controllers;

use Yii;
use livefactory\models\Loan;
use livefactory\models\search\Loan as LoanSearch;
use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LoanController implements the CRUD actions for Loan model.
 */
class LeadLoansController extends Controller
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
     * Lists all Loan models.
     * @return mixed
     */
    public function actionIndex()
    {
		// Make Default 
		\livefactory\models\DefaultValueModule::upsertDefault('lead_source');
		extract(Loan::find()->select("Max(sort_order) max_sort_order")->asArray()->one());

		//var_dump();

		if(!empty($_REQUEST['actionType'])){

			//$model = $this->findModel($_REQUEST['sort_order_update']);

			$sourceId = $_REQUEST['sort_order_update'];

			$sortValue = $_REQUEST['sort_order_update'.$sourceId];	

			//var_dump($sourceId." ".$sortValue);

			if(!empty($_REQUEST['sort_order_update']) && $_REQUEST['actionType'] !='Down'){

				if($sortValue !='1'){

					$minusValue = intval($sortValue)-1;

					$leadUpdate= Loan::find()->where(['sort_order' => $sortValue])->one();

					$leadUpdate1= Loan::find()->where(['sort_order' => $minusValue])->one();

					$leadUpdate->sort_order=$minusValue;

					$leadUpdate->update();

					

					$leadUpdate1->sort_order=$sortValue;

					$leadUpdate1->update();

				}

			}else if($_REQUEST['actionType'] == 'Down'){

				if($max_sort_order !=$sortValue){

					$plusValue = intval($sortValue)+1;

					$leadUpdate= Loan::find()->where(['sort_order' => $sortValue])->one();

					$leadUpdate1= Loan::find()->where(['sort_order' => $plusValue])->one();

					$leadUpdate->sort_order=$plusValue;

					$leadUpdate->update();

					$leadUpdate1->sort_order=$sortValue;

					$leadUpdate1->update();

				}

			}

		}

        $searchModel = new LoanSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Loan model.
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
     * Creates a new Loan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$model = new Loan;
		extract(Loan::find()->select("Max(sort_order) max_sort_order")->asArray()->one());
        
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
     * Updates an existing Loan model.
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
     * Deletes an existing Loan model.
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
     * Finds the Loan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Loan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Loan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
