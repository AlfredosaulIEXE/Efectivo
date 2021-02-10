<?php



use livefactory\models\search\CommonModel;

use yii\helpers\Html;

use yii\helpers\ArrayHelper;



use kartik\widgets\ActiveForm;



use kartik\builder\Form;



?>

<div class="modal fade bs-example-modal-lg contactae">

<form method="post" id="contactform" action=""  enctype="multipart/form-data">

<?php Yii::$app->request->enableCsrfValidation = true; ?>

    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">

   	<input type="hidden" name="contactae" value="true">

    <input type="hidden" name="contact_id" value="<?=$contact->id?>">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

        <h4 class="modal-title"><?=Yii::t('app', 'Add Contact')?></h4>

      </div>

      <div class="modal-body">

      	 <div class="row">

                            <div class="col-sm-6">

                                <div class="form-group">

                                    <label class="control-label"><?=Yii::t('app', 'First Name')?></label>

                                    <input type="text" name="first_name" data-validation="required" class="form-control" value="<?=$contact->first_name?>">

                                </div>

                            </div>

                            <div class="col-sm-6">

                                <div class="form-group">

                                    <label class="control-label"><?=Yii::t('app', 'Last Name')?></label>

                                    <input type="text" name="last_name" data-validation="required" class="form-control"  value="<?=$contact->last_name?>">

                                </div>

                            </div>

                        </div>

         <div class="row">

         	<div class="col-sm-3">

            	<div class="form-group">

                    <label class="control-label"><?=Yii::t('app', 'Email')?></label>

                    <input type="text" name="email" data-validation="required" email-validation="required" class="form-control"  value="<?=$contact->email?>">

                </div>

            </div>

            <div class="col-sm-3">

            	<div class="form-group">

                    <label class="control-label"><?=Yii::t('app', 'Mobile')?></label>

                    <input type="text" name="mobile" data-validation="required" class="form-control" data-valid-num="required"  value="<?=$contact->mobile?>">

                </div>

            </div>

            <div class="col-sm-3">

            	<div class="form-group">

                    <label class="control-label"><?=Yii::t('app', 'Phone')?></label>

                    <input type="text" name="phone"  class="form-control"  value="<?=$contact->phone?>">

                </div>

            </div>

            <div class="col-sm-3">

            	<div class="form-group">

                    <label class="control-label"><?=Yii::t('app', 'Fax')?></label>

                    <input type="text" name="fax"  class="form-control"  value="<?=$contact->fax?>">

                </div>

            </div>

         </div>      		

      </div>

      <div class="modal-footer">

      	<button type="submit" class="btn btn-primary add_contact">

        	<i class="glyphicon glyphicon-phone"></i> <?=Yii::t('app', 'Save')?></button>

        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i> <?=Yii::t('app', 'Close')?></button>

      </div>

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</form>

</div><!-- /.modal -->



