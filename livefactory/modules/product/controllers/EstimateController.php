<?php

namespace livefactory\modules\product\controllers;

use Yii;
use livefactory\models\Estimate;
use livefactory\models\search\Estimate as EstimateSearch;
use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use livefactory\models\Tax;
use livefactory\models\Product;
use livefactory\models\EstimateDetails;
/**
 * EstimateController implements the CRUD actions for Estimate model.
 */
class EstimateController extends Controller
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
     * Lists all Estimate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EstimateSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Estimate model.
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
     * Creates a new Estimate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Estimate;
		$taxList = Tax::find()->all();
		$products = Product::find()->asArray()->all();
		$jSonList="[";
		$coma='';
		foreach($products as $pro){
			$jSonList .= $coma.'{"id":"'.$pro['id'].'","value":"'.$pro['product_name'].'"}';
			$coma=',';	
		}
		$jSonList.=']';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//$detail = $_REQUEST['detail_id'];
			$product_id = $_REQUEST['product_id'];
			$description = $_REQUEST['description'];
			$rate = $_REQUEST['rate'];
			$tax_id = $_REQUEST['tax_id'];
			$tax_amount = $_REQUEST['tax_amount'];
			$total = $_REQUEST['total'];
			$quantity = $_REQUEST['quantity'];
			if(count($description) > 0){
				for($i=0;$i<count($description);$i++){
					if(is_null(Product::find()->where("product_name='$description'")->one())){
						$pObj = new Product();
						$pObj->product_name=$description;
						$pObj->product_description=$description;
						$pObj->product_category_id=0;
						$pObj->product_price=$rate[$i];
						$pObj->active=1;
						$pObj->added_at=time();
						$pObj->save();
					}
					$obj = new EstimateDetails();
					$obj->product_id = $product_id[$i]?$product_id[$i]:$pObj->id;
					$obj->product_description = $description[$i];
					$obj->rate = $rate[$i];
					$obj->description = $description[$i];
					$obj->tax_id = intval($tax_id[$i]);
					$obj->tax_amount = $tax_amount[$i];
					$obj->total = $total[$i];
					$obj->estimate_id = $model->id;
					$obj->quantity=$quantity[$i];
					$obj->added_at=time();
					$obj->save();
				}
			}
			
			
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
				'taxList'=>$taxList,
				'jSonList'=>$jSonList
            ]);
        }
    }

    /**
     * Updates an existing Estimate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$taxList = Tax::find()->all();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
				'taxList'=>$taxList
            ]);
        }
    }

    /**
     * Deletes an existing Estimate model.
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
     * Finds the Estimate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Estimate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Estimate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
