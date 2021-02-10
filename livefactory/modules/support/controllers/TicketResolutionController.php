<?php

namespace livefactory\modules\support\controllers;

use Yii;
use livefactory\models\TicketResolution;
use livefactory\models\ResolutionReference;
use livefactory\models\Ticket;
use livefactory\models\search\TicketResolution as TicketResolutionSearch;
use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TicketResolutionController implements the CRUD actions for TicketResolution model.
 */
class TicketResolutionController extends Controller
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
     * Lists all TicketResolution models.
     * @return mixed
     */
    public function actionIndex()
    {
		if(!Yii::$app->user->can('Resolutions.Index')){
			throw new NotFoundHttpException('You dont have permissions to view this page.');
		}
        $searchModel = new TicketResolutionSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single TicketResolution model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
		if(!Yii::$app->user->can('Resolutions.View')){
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
     * Creates a new TicketResolution model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		if(!Yii::$app->user->can('Resolutions.Create')){
			throw new NotFoundHttpException('You dont have permissions to view this page.');
		}
        $model = new TicketResolution;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TicketResolution model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		if(!Yii::$app->user->can('Resolutions.Update')){
			throw new NotFoundHttpException('You dont have permissions to view this page.');
		}
        $model = $this->findModel($id);

		if(!empty($_REQUEST['multiple_del'])){
			$rows=$_REQUEST['selection'];
			for($i=0;$i<count($rows);$i++){
				ResolutionReference::find()->where('resolution_id='.$model->id.' and ticket_id='.$rows[$i])->one()->delete();
			}
		}

		if(!empty($_REQUEST['multiple_link'])){
			$rows=$_REQUEST['selection'];
			for($i=0;$i<count($rows);$i++){
				$resRef = new ResolutionReference;
				$resRef->resolution_id=$model->id;
				$resRef->ticket_id=$rows[$i];
				$resRef->save();
			}
		}

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$model->updated_at=time();
			$model->save();
            //return $this->redirect(['view', 'id' => $model->id]);
			return $this->render('update', [
                'model' => $model,
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TicketResolution model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		if(!Yii::$app->user->can('Resolutions.Delete')){
			throw new NotFoundHttpException('You dont have permissions to view this page.');
		}
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TicketResolution model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TicketResolution the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TicketResolution::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	
	public function actionTicketTabView($id)
    {
		$model = $this->findModel($id);
        return $this->render('ticket-tab-view', [
                'model' => $model,
            ]);
    }
}
