<?php

namespace livefactory\modules\pmt\controllers;

use Yii;
use livefactory\models\ProjectPriority;
use livefactory\models\search\ProjectPriority as ProjectPrioritySearch;
use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use livefactory\models\DefaultValue;
use yii\filters\VerbFilter;
use livefactory\models\search\CommonModel as SessionVerification;
//use livefactory\models\DefaultValueModule;
/**
 * ProjectPriorityController implements the CRUD actions for ProjectPriority model.
 */
class ProjectPriorityController extends Controller
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
     * Lists all ProjectPriority models.
     * @return mixed
     */
    public function actionIndex()
    {
		// Make Default 
		\livefactory\models\DefaultValueModule::upsertDefault('project_priority');
		extract(ProjectPriority::find()->select("Max(sort_order) max_sort_order")->asArray()->one());

		if(!empty($_REQUEST['sort_order_update']) && !empty($_REQUEST['actionType'])){

			$statusId = $_REQUEST['sort_order_update'];

			$sortValue = $_REQUEST['sort_order_update'.$statusId];	

			if($_REQUEST['actionType'] !='Down'){

				if($sortValue !='1'){

					$minusValue = intval($sortValue)-1;

					$projectUpdate= ProjectPriority::find()->where(['sort_order' => $sortValue])->one();

					$projectUpdate1= ProjectPriority::find()->where(['sort_order' => $minusValue])->one();

					$projectUpdate->sort_order=$minusValue;

					$projectUpdate->update();

					

					$projectUpdate1->sort_order=$sortValue;

					$projectUpdate1->update();

				}

			}else if($_REQUEST['actionType'] == 'Down'){

				if($max_sort_order !=$sortValue){

					$plusValue = intval($sortValue)+1;

					$projectUpdate= ProjectPriority::find()->where(['sort_order' => $sortValue])->one();

					$projectUpdate1= ProjectPriority::find()->where(['sort_order' => $plusValue])->one();

					$projectUpdate->sort_order=$plusValue;

					$projectUpdate->update();

					$projectUpdate1->sort_order=$sortValue;

					$projectUpdate1->update();

				}

			}

		}
        $searchModel = new ProjectPrioritySearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single ProjectPriority model.
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
     * Creates a new ProjectPriority model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ProjectPriority;
		extract(ProjectPriority::find()->select("Max(sort_order) max_sort_order")->asArray()->one());
        
		$model->sort_order=$max_sort_order+1;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'added' => 'yes']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ProjectPriority model.
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
     * Deletes an existing ProjectPriority model.
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
     * Finds the ProjectPriority model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProjectPriority the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProjectPriority::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
