<?php

namespace livefactory\modules\liveobjects\controllers;

use Yii;
use livefactory\models\AppLicense;
use livefactory\models\search\AppLicense as AppLicenseSearch;
use livefactory\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/**
 * AppLicenseController implements the CRUD actions for AppLicense model.
 */
class AppLicenseController extends Controller
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
     * Lists all AppLicense models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AppLicenseSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

	public function actionGetLicense() {

		$module_name = '';

		if(in_array('pmt', Yii::$app->params['modules']))
			$module_name = $module_name.'pmt,';
		
		if(in_array('support', Yii::$app->params['modules']))
			$module_name = $module_name.'support,';

		if(in_array('sales', Yii::$app->params['modules']))
			$module_name = $module_name.'sales,';

		if(in_array('invoice', Yii::$app->params['modules']))
			$module_name = $module_name.'invoice,';

		if($module_name != '')
			$module_name = substr($module_name, 0, -1);
	?>
		<body class="gray-bg">
		<div class=" loginscreen  animated fadeInDown">
		<?php
		if(!empty($_GET['msg'])){	
		?>
			<div class="alert alert-danger"><?=$_GET['msg']?></div>
		<?php } ?>
        <div>
            <div>
                <h1 class="logo-name"><?= Html::encode("Live") ?></h1>
            </div>
            <h3>Welcome to LiveCRM</h3>
            <p>Please fill out the following fields to obtain license:</p>
			<p><b>You can obtain Invoice Number, Order Number and Order Date from the invoice available in statement section of your Codecanyon account</b></p>
			
			<form name="form" method="post" action="validate.php">
				<input type="hidden" name="action" value="get">
				<input type="hidden" name="module_name" value="<?=$module_name?>">
				<table>
					<tr>
						<td align="right">Codecanyon Invoice Number: </td>
						<td><input name="invoice_number" type="text" size="50" maxlength="50"></td>
					</tr>
					<tr>
						<td align="right">Codecanyon Order Number: </td>
						<td><input name="order_number" type="text" size="50" maxlength="50"></td>
					</tr>
					<tr>
						<td align="right">Codecanyon Order Date (YYYY-MM-DD): </td>
						<td><input name="order_date" type="text" size="50" maxlength="50"></td>
					</tr>
					<tr>
						<td align="right">Customer First Name: </td>
						<td><input name="first_name" type="text" size="50" maxlength="100"></td>
					</tr>
					<tr>
						<td align="right">Customer Last Name: </td>
						<td><input name="last_name" type="text" size="50" maxlength="100"></td>
					</tr>
					<tr>
						<td align="right">Customer Email: </td>
						<td><input name="email" type="text" size="50" maxlength="100"></td>
					</tr>
					<tr>
						<td align="right">Customer Phone: </td>
						<td><input name="phone" type="text" size="50" maxlength="50"></td>
					</tr>
					<tr>
						<td colspan="3" align="right"><input type="submit" name="button" value="Validate"></td>
					</tr>
				</table>
			</form>
            
            <p class="m-t"> <small>LiveCRM framework based on Bootstrap 3 &copy; 2015</small> </p>
        </div>
	</div>
	</body>
	<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
	<link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<script src="js/bootstrap.min.js"></script>
	<script>
	<?php
	}
    /**
     * Displays a single AppLicense model.
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
     * Creates a new AppLicense model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AppLicense;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AppLicense model.
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
     * Deletes an existing AppLicense model.
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
     * Finds the AppLicense model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AppLicense the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AppLicense::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
