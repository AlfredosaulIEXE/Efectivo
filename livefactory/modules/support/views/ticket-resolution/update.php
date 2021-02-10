<?php

use yii\helpers\Html;
use livefactory\models\search\Ticket as TicketSearch;

/**
 * @var yii\web\View $this
 * @var livefactory\models\TicketResolution $model
 */

$this->title = Yii::t('app', 'Update {modelClass}', [
    'modelClass' => $model->resolution_number,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ticket Resolutions'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script src="../../vendor/ckeditor/ckeditor/ckeditor.js"></script>
<div class="ticket-resolution-update">
	<form method="post" action=""  enctype="multipart/form-data">
	<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>"/>
   <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?= $this->title?></h5>
                        <div class="ibox-tools">
						    <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">

						<!--	<?= $this->render('_form', [
        						'model' => $model,
   							]) ?>-->

                    
                    <div class="tabbable">
                        <ul class="nav nav-tabs">
                        <li class="active"><a href="#desc" role="tab" data-toggle="tab"><?= Yii::t('app', 'Resolution subject & description')?></a></li>
                        <!--<li><a href="#attachments" role="tab" data-toggle="tab"><?= Yii::t('app', 'Attachments')?></a></li>
                        <li><a href="#notes" role="tab" data-toggle="tab"><?= Yii::t('app', 'Notes')?></a></li>-->
                        <li><a href="#linked" role="tab" data-toggle="tab"><?= Yii::t('app', 'Linked Tickets')?></a></li>                        
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane  active" id="desc"> 
                            <br/>
                            <?php
                            
                            echo '<div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label" for="lname">'.Yii::t('app', 'Subject').':</label>
                                        <div class="controls">
                                            <input type="text" name="TicketResolution[subject]" value="'.$model->subject.'" class="form-control">                                            
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label" for="lname">'.Yii::t('app', 'Resolution').':</label>
                                        <div class="controls">
                                            <textarea class="form-control input-sm ckeditor" name="TicketResolution[resolution]" rows="4" style="width:100%">'.htmlspecialchars($model->resolution).'</textarea>                                            
                                        </div>
                                        </div>
                                    </div>
                                </div>';

                            ?>

                        </div>
						</form>
						
                       <!-- <div class="tab-pane" id="attachments"> 
                        <br/>
                        </div>
    
                        <div class="tab-pane fade" id="notes"> 

                        <br/>  
                        </div>-->
                        <div class="tab-pane fade" id="linked"> 
                        <br/>   
						 <?php
                                            
							 $ticketSearch = new TicketSearch();
							 $dataProvider = $ticketSearch->searchLinkedWithResolution($model->id);
							 echo Yii::$app->controller->renderPartial("../../../support/views/ticket/linked-tickets",[ 
										 'dataProvider'=>$dataProvider,
										 'searchModel'=>$ticketSearch,
										
                            ] );
                        ?> 
                        </div>
    </div>
    </div>

    <?php

    echo Html::submitButton ( $model->isNewRecord ? Yii::t('app', 'Create' ): Yii::t('app', 'Update'), [ 
                                'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary btn-sm  update_ticket' 
                    ] );?> 
					<!--<a href="javascript:void(0)" class="btn btn-success btn-sm" onClick="$('.add-notes-modal').modal('show');"><i class="glyphicon glyphicon-comment"></i> <?= Yii::t('app', 'New Note')?></a>

                    <a href="javascript:void(0)" class="btn btn-success btn-sm" onClick="$('.savepopup').modal('show');"><i class="glyphicon glyphicon-save"></i> <?= Yii::t('app', 'New Attachment')?></a>-->
                    <?php
                    //ActiveForm::end ();
                    //echo "</form>";

    ?>

        </div>
    </div>
</div>
<?php

    //$entity_user=$model->user_assigned_id;

    //$email=TicketController::getUserEmail($model->user_assigned_id);

    //include_once(__DIR__ .'/../../../liveobjects/views/file/attachment-module/attachmentae.php');

    //include_once(__DIR__ .'/../../../liveobjects/views/note/notes-module/noteae.php');
    //$entity_type='ticket';//// This Variable is Impotant 
    //include_once(__DIR__ .'/../../../liveobjects/views/timesheet/timesheetae.php');
    //include_once(__DIR__ .'/../../../liveobjects/views/timesheet/timenote.php');
    //include_once(__DIR__ .'/../../../liveobjects/views/resolution/resolution-module/ticket_resolution.php');

?>
