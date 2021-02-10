<?php

namespace livefactory\modules\liveobjects\controllers;

use Yii;
use livefactory\models\Address;
use livefactory\models\search\Address as AddressSearch;
use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use livefactory\models\State;

use livefactory\models\City;
use livefactory\models\search\CommonModel as SessionVerification;
/**
 * AddressController implements the CRUD actions for Address model.
 */
class AddressController extends Controller
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
     * Lists all Address models.
     * @return mixed
     */
    public function actionIndex()
    {

		if(!Yii::$app->user->can('Setting.Pages')){

          $this->redirect(array('/site/index'));

		}
        $searchModel = new AddressSearch;
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
     * Displays a single Address model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

		if(!Yii::$app->user->can('Setting.Pages')){

          $this->redirect(array('/site/index'));

		}
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->id]);
        } else {
        return $this->render('view', ['model' => $model]);
}
    }

    /**
     * Creates a new Address model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

		if(!Yii::$app->user->can('Setting.Pages')){

          $this->redirect(array('/site/index'));

		}
        $model = new Address;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'added' => 'yes']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Address model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

		if(!Yii::$app->user->can('Setting.Pages')){

          $this->redirect(array('/site/index'));

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

    /**
     * Deletes an existing Address model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {

		if(!Yii::$app->user->can('Setting.Pages')){

          $this->redirect(array('/site/index'));

		}
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
	public function actionAjaxLoadStates(){

		$country_id=!empty($_REQUEST['country_id'])?$_REQUEST['country_id']:'0';

		$state_id=!empty($_REQUEST['state_id'])?$_REQUEST['state_id']:'0';

		$states = State::find()->orderBy('state')->where("country_id=$country_id  and active=1")->asArray()->all();

		 return $this->renderPartial('ajax-load-states', [

                'states' => $states,

				'state_id'=>$state_id,

            ]);

	}

	public function actionAjaxLoadCities(){

		$state_id=!empty($_REQUEST['state_id'])?$_REQUEST['state_id']:'0';

		$city_id=!empty($_REQUEST['city_id'])?$_REQUEST['city_id']:'0';

		$cities=City::find()->orderBy('city')->where("state_id=$state_id and active=1")->asArray()->all();

		 return $this->renderPartial('ajax-load-cities', [

                'cities' => $cities,

				'city_id'=>$city_id,

            ]);

	}
    /**
     * Finds the Address model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Address the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Address::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
