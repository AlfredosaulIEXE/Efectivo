<?php

namespace livefactory\modules\liveobjects\controllers;

use Yii;
use livefactory\models\Office;
use livefactory\models\AddressModel;
use livefactory\models\ContactModel;
use livefactory\models\search\Office as OfficeSearch;
use livefactory\models\Timeclock;
use livefactory\models\AuthItem;
use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use livefactory\models\ImageUpload;


/**
 * OfficeController implements the CRUD actions for Office model.
 */
class OfficeController extends Controller
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
     * Lists all Office models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OfficeSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Office model.
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
     * Creates a new Office model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!Yii::$app->user->can('Office.Create')){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }
        $img=new ImageUpload();
        $model = new Office;

       if ($model->load(Yii::$app->request->post())) {
            $model->attributes = $_POST['Office'];
            $model->added_at = time();
           if ( $_POST['height_document'])
               $model->height_document =  $_POST['height_document'];
           else{
               $model->height_document = 140;
           }


           if ( $_POST['height_custom'])
               $model->height_custom =  $_POST['height_custom'];
           else{
               $model->height_custom = 90;
           }

		if($model->save()) {

            /* Begin changes to save address details and contact details with new lead creation */
            $_REQUEST = Yii::$app->request->post('address');
            AddressModel::addressInsert($model->id, 'office');

            $_REQUEST = Yii::$app->request->post('contact');
            ContactModel::contactInsert($model->id, 'office');
            $img->loadImage('../office/nophoto.jpg')->saveImage("../office/".$model->id.".png");
            $img->loadImage('../office/nophoto.jpg')->resize(30, 30)->saveImage("../office/office_".$model->id.".png");

            return $this->redirect(['index']);
        }
		else
			return $this->render('create', ['model' => $model,]);
            
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Office model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        //var_dump($_REQUEST,$_FILES);
        if(!Yii::$app->user->can('Office.Update')){
            throw new \yii\web\ForbiddenHttpException('You dont have permissions to view this page.');
        }

        $img=new ImageUpload();
        $model = $this->findModel($id);
        if(!empty($_GET['img_del'])){
            unlink('../office/'.$model->id.'.png');
            unlink('../office/office_'.$model->id.'.png');
            return $this->redirect(['update', 'id' => $model->id]);
        }
        if(!empty($_GET['active'])) {
            $status = $_GET['active']=='yes'?'1':'0';
            $model->active=$status;
            $model->save();
        }

        $model = $this->findModel($id);
        if(!empty($_FILES['office_image']['tmp_name'])){
            $img->loadImage($_FILES['office_image']['tmp_name'])->saveImage("../office/".$model->id.".png");
            //$img->loadImage($_FILES['office_image']['tmp_name'])->resize(30, 30)->saveImage("../office/office_".$model->id.".png");
        }

        if ($model->load(Yii::$app->request->post())) {

            $model->attributes = $_POST['Office'];
            $model->updated_at = time();
            if ( $_POST['height_document'])
                $model->height_document =  $_POST['height_document'];


            if ( $_POST['height_custom'])
                $model->height_custom =  $_POST['height_custom'];


            if($model->save()) {

                $contact_id = Yii::$app->request->post('contact_id');
                $_REQUEST = Yii::$app->request->post('contact');
                if ( ! empty($contact_id)) {
                    ContactModel::contactUpdate($contact_id);
                } else {
                    ContactModel::contactInsert($model->id, 'office');
                }

                $address_id = Yii::$app->request->post('address_id');
                $_REQUEST = Yii::$app->request->post('address');
                if ( ! empty($address_id)) {
                    AddressModel::addressUpdate($address_id);
                } else {
                    AddressModel::addressInsert($model->id, 'office');
                }

			    return $this->redirect(['index']);
            }

        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }

    }

    /**
     * Deletes an existing Office model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionTimeclock($id)
    {
        $office = $this->findModel($id);
        $role_id = Yii::$app->request->getQueryParam('role_id');

        // Get office times
        $office_times = Timeclock::find()
            ->where('office_id = ' . $id . ( ! empty($role_id) ? " AND role_id = '$role_id'" : ''))
            ->all();

        if (Yii::$app->request->getIsPost()) {
            $times = Yii::$app->request->post('times');

            foreach ($times as $week_day => $_times) {

                // Get current time
                $time = Timeclock::find()->where('office_id = ' . $id . ' AND week_day = ' . $week_day. ( ! empty($role_id) ? " AND role_id = '$role_id'" : ''))->one();

                $is_denied = isset($_times['denied']);
                $start_time = $is_denied ? ( ! is_null($time) ? $time->start_time : '09:00') : $_times['start_time'];
                $end_time = $is_denied ? ( ! is_null($time) ? $time->end_time : '19:00') : $_times['end_time'];

                // Not exist
                if ( is_null($time)) {
                    $time = new Timeclock();
                    $time->role_id = empty($role_id) ? null : $role_id;
                    $time->office_id = $id;
                    $time->week_day = $week_day;
                    $time->start_time = $start_time;
                    $time->end_time = $end_time;
                    $time->denied = (int) $is_denied;
                    $time->created_at = time();

                    $time->save();
                } else {

                    $time->start_time = $start_time;
                    $time->end_time = $end_time;
                    $time->denied = (int) $is_denied;

                    $time->save();
                }
            }

            return $this->redirect(['timeclock','id' => $id, 'role_id' => $role_id]);
        }

        $roles = AuthItem::find()->where("type = 2")->andWhere("name NOT IN ('Customer', 'Employee', 'Admin', 'Audit.Member', 'Director', 'Director.Assistant', 'Wall-e')")->asArray()->all();

        return $this->render('timeclock', [
            'office' => $office,
            'office_times' => $office_times,
            'roles' => $roles,
            'role_id' => $role_id
        ]);
    }

    /**
     * Finds the Office model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Office the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Office::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
