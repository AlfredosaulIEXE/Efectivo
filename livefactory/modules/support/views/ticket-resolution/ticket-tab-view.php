<?php

use yii\helpers\Html;
use livefactory\models\search\Ticket as TicketSearch;

/**
 * @var yii\web\View $this
 * @var livefactory\models\TicketResolution $model
 */

$this->title = Yii::t('app', 'View {modelClass}', [
    'modelClass' => $model->resolution_number,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ticket Resolutions'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'View');
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script src="../../vendor/ckeditor/ckeditor/ckeditor.js"></script>
<div class="ticket-resolution-update">
	<form method="post" action=""  enctype="multipart/form-data">
   <div class="ibox float-e-margins">
                    
                    <div class="ibox-content">

						<?php
                            echo '<div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label" for="lname">'.Yii::t('app', 'Subject').':</label>
                                        <div class="controls">
                                            <input type="text" readonly name="TicketResolution[subject]" value="'.$model->subject.'" class="form-control">                                            
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="control-label" for="lname">'.Yii::t('app', 'Resolution').':</label>
                                        <div class="controls">
                                            <textarea class="form-control input-sm ckeditor" name="TicketResolution[resolution]" rows="4" style="width:100%" readonly>'.htmlspecialchars($model->resolution).'</textarea>                                            
                                        </div>
                                        </div>
                                    </div>
                                </div>';

                            ?>
                    
                    
					</div>
    </div>
	</form>
</div>