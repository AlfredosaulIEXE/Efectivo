<?php


namespace livefactory\modules\liveobjects\controllers;

use livefactory\models\UnitGenerate;
use Yii;
use livefactory\controllers\Controller;
use livefactory\models\search\UnitGenerate as UnitSearch;
use yii\web\NotFoundHttpException;

class UnitController extends Controller
{
    public function actionIndex(){

        $searchModel = new UnitSearch();
        $dataProvider = $searchModel->search();


        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionCreate(){
        $model = new UnitGenerate;
        if ($model->load(Yii::$app->request->post())) {
            $model->active = 1;
            if ($model->save()){
                return $this->redirect(['index']);
            }
            else
                return $this->render('create', ['model' => $model,]);
        }else
            return $this->render('create', [
            'model' => $model,
        ]);

    }
    public function actionUpdate($id){
        $model = $this->findModel($id);
        if(!empty($_GET['active'])) {
            $status = $_GET['active']=='yes'?'1':'0';
            $model->active=$status;
            $model->save();
        }
        if ($model->load(Yii::$app->request->post())) {


            if($model->save()) {
                return $this->redirect(['index']);
            }

        } else
        return $this->render('update',
            [
                'model' => $model,
            ]);

    }
    public function actionView(){
        return $this->render('view');

    }
    public function actionDelete($id){
        $unit = $this->findModel($id);

        $unit->active = 0;
        $unit->save();
        return $this->redirect(['index']);

    }

    /**
     * @param integer $id
     * @return UnitGenerate the loaded model
     * @throws NotFoundHttpException
     */
    protected function findModel($id){
        if(($model = UnitGenerate::findOne($id)) !== null){
            return $model;
        } else{
            throw new NotFoundHttpException('The requested page does not exist');
        }
    }

}