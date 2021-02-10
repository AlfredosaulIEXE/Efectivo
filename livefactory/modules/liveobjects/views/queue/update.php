<?php

use yii\helpers\Html;
use livefactory\models\search\Queue;
/**
 * @var yii\web\View $this
 * @var livefactory\models\Queue $model
 */

$this->title = Yii::t('app', 'Queue') . ' - ' . $model->queue_title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Queues'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<?php //$this->registerJsFile(Yii::$app->request->baseUrl.'../../vendor/bower/bootstrap/dist/js/bootstrap.min.js', ['depends' => [yii\web\YiiAsset::className()]]);?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>



<style>	

.cke_contents{max-height:250px}

.slider .tooltip.top {

    margin-top: -36px;

    z-index: 100;

}

.close {

    color: #000000;

    float: right;

    font-size: 18px;

    font-weight: bold;

    line-height: 1;

    opacity: 0.2;

    text-shadow: 0 1px 0 #ffffff;

}
/*.nav-tabs > li > a{
	font-size:11px
}*/
</style>

<script type="text/javascript">

$(document).ready(function(){


    $('.users').appendTo('#w0');
})
</script>
<div class="queue-update">
<div class="ibox float-e-margins">

                    <div class="ibox-title">

                        <h5><?= $this->title?></h5>

                        <div class="ibox-tools">

						    <a class="collapse-link">

                                <i class="fa fa-chevron-up"></i>

                            </a>

							<!--

                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">

                                <i class="fa fa-wrench"></i>

                            </a>

							

                            <ul class="dropdown-menu dropdown-user">

                                <li><a href="#">Config option 1</a>

                                </li>

                                <li><a href="#">Config option 2</a>

                                </li>

                            </ul>

							-->

                            <a class="close-link">

                                <i class="fa fa-times"></i>

                            </a>

                        </div>

                    </div>

                    <div class="ibox-content">

							<?= $this->render('_form', [

								'model' => $model,

							]) ?>

                    </div>

                </div>

</div>
<div class="users">
 <?php

                                        

                        $searchModelUser = new Queue();
						// $_SESSION['queue_owner_user_id']=$model->queue_owner_user_id;
                        $dataProviderUser = $searchModelUser->searchQueueUser( Yii::$app->request->getQueryParams (), $model->id );

                        echo Yii::$app->controller->renderPartial("user_tab", [ 

                                'dataProviderUser' => $dataProviderUser,

                                'searchModelUser' => $searchModelUser 

                        ] );

                        
echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);

		echo ' <a href="javascript:void(0)" class="btn btn-success" onClick="$(\'.exist_users\').modal(\'show\');"><i class="glyphicon glyphicon-user"></i> '.Yii::t('app', 'Add User to Queue').'</a>';
		echo "</form>";
                        ?>
</div>
<?php
include_once('join_users.php');
?>
<script>
	function showPopup(id){
		//alert('index.php?r=liveobjects/queue/ajax-user-detail&id='+id);
		$.post('index.php?r=liveobjects/queue/ajax-user-detail&id='+id,function(r){
			$('.modal-body').html(r);
		}).done(function(){
			$('.bs-example-modal-lg').modal('show');
		})
	}
</script>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title" id="gridSystemModalLabel"><?=Yii::t('app', 'User Detail')?></h4>
    </div>
      <div class="modal-body">
      
      </div>
    </div>
  </div>
</div>
