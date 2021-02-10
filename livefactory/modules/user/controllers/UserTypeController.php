<?php



namespace livefactory\modules\user\controllers;



use Yii;

use livefactory\models\UserType;

use livefactory\models\search\UserType as UserTypeSearch;

use livefactory\controllers\Controller;

use yii\web\NotFoundHttpException;

use yii\filters\VerbFilter;

use livefactory\models\search\CommonModel as SessionVerification;

/**

 * UserTypeController implements the CRUD actions for UserType model.

 */

class UserTypeController extends Controller

{

	public function init(){

		SessionVerification::checkSessionDestroy();

    	if(!isset(Yii::$app->user->identity->id)){

          $this->redirect(array('/site/login'));

		}

		if(!Yii::$app->user->can('Setting.Pages')){

          $this->redirect(array('/site/index'));

		}

	}

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

     * Lists all UserType models.

     * @return mixed

     */

    public function actionIndex()

    {
		// Make Default 
		\livefactory\models\DefaultValueModule::upsertDefault('user_type');
		extract(UserType::find()->select("Max(sort_order) max_sort_order")->asArray()->one());

		//var_dump();

		if(!empty($_REQUEST['sort_order_update']) && !empty($_REQUEST['actionType'])){

			//$model = $this->findModel($_REQUEST['sort_order_update']);

			$statusId = $_REQUEST['sort_order_update'];

			$sortValue = $_REQUEST['sort_order_update'.$statusId];	

			//var_dump($statusId." ".$sortValue);

			if($_REQUEST['actionType'] !='Down'){

				if($sortValue !='1'){

					$minusValue = intval($sortValue)-1;

					$userUpdate= UserType::find()->where(['sort_order' => $sortValue])->one();

					$userUpdate1= UserType::find()->where(['sort_order' => $minusValue])->one();

					$userUpdate->sort_order=$minusValue;

					$userUpdate->update();

					

					$userUpdate1->sort_order=$sortValue;

					$userUpdate1->update();

				}

			}else if($_REQUEST['actionType'] == 'Down'){

				if($max_sort_order !=$sortValue){

					$plusValue = intval($sortValue)+1;

					$userUpdate= UserType::find()->where(['sort_order' => $sortValue])->one();

					$userUpdate1= UserType::find()->where(['sort_order' => $plusValue])->one();

					$userUpdate->sort_order=$plusValue;

					$userUpdate->update();

					$userUpdate1->sort_order=$sortValue;

					$userUpdate1->update();

				}

			}

		}
        $searchModel = new UserTypeSearch;

        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());



        return $this->render('index', [

            'dataProvider' => $dataProvider,

            'searchModel' => $searchModel,

        ]);

    }



    /**

     * Displays a single UserType model.

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

     * Creates a new UserType model.

     * If creation is successful, the browser will be redirected to the 'view' page.

     * @return mixed

     */

    public function actionCreate()

    {

        $model = new UserType;
		extract(UserType::find()->select("Max(sort_order) max_sort_order")->asArray()->one());
        
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

     * Updates an existing UserType model.

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

     * Deletes an existing UserType model.

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

     * Finds the UserType model based on its primary key value.

     * If the model is not found, a 404 HTTP exception will be thrown.

     * @param integer $id

     * @return UserType the loaded model

     * @throws NotFoundHttpException if the model cannot be found

     */

    protected function findModel($id)

    {

        if (($model = UserType::findOne($id)) !== null) {

            return $model;

        } else {

            throw new NotFoundHttpException('The requested page does not exist.');

        }

    }

}