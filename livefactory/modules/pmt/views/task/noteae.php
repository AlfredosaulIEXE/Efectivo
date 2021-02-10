<style>



	.modal-dialog{width:80% !important;}



</style>

<script>

$(document).ready(function(e) {

    $(document).on('click','a',function(){

		$('.notesAdd').click(function(event){

			$('#cke_2_contents').parent().parent().removeAttr('style').next('.error').remove();

			sageLength = CKEDITOR.instances['notes'].getData().replace(/<[^>]*>/gi, '').length;

			if(sageLength==0){

				Add_Error($('#cke_2_contents').parent().parent(),'<?=Yii::t ('app','This Field is Required!')?>');

			event.preventDefault();

			}

		})

		

	})

	$('.note-edit').click(function(event){

		$('#cke_3_contents').parent().parent().removeAttr('style').next('.error').remove();

		sageLength = CKEDITOR.instances['note_body'].getData().replace(/<[^>]*>/gi, '').length;

		if(sageLength==0){

			Add_Error($('#cke_3_contents').parent().parent(),'<?=Yii::t ('app','This Field is Required!')?>');

		event.preventDefault();

		}

	})

	$('.notesAdd').click(function(event){

			$('#cke_2_contents').parent().parent().removeAttr('style').next('.error').remove();

			sageLength = CKEDITOR.instances['notes'].getData().replace(/<[^>]*>/gi, '').length;

			if(sageLength==0){

				Add_Error($('#cke_2_contents').parent().parent(),'<?=Yii::t ('app','This Field is Required!')?>');

			event.preventDefault();

			}

		})

});

</script>

<div class="modal fade add-notes-modal">

			  <div class="modal-dialog">

				<div class="modal-content">

				  <div class="modal-header">

					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

					<h4 class="modal-title">Notes Add</h4>

				  </div>

					<div class="modal-body">



<form  class="form-horizontal" role="form" name="noteae" id="noteae" action="" method="post" >

            <?php Yii::$app->request->enableCsrfValidation = true; ?>

    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">

                 <input type="hidden" name="entity_id" value="<?=$_GET['id']?>">

                 <input type="hidden" name="entity_type" value="task">

                 <input type="hidden" name="add_note_model" value="true">

				  <!--<legend>Notes</legend> -->

       

						

						<?php if ($error != '') { ?>

									<div class="alert alert-danger alert-dismissable">

										<button type="button" class="close" data-dismiss="alert"><i class="fa fa-remove"></i></button>

										<?php echo $error; ?>

									</div>

							<?php } ?>



						

						<div class="form-group">

							<label class="col-sm-2 control-label" for="lead_source">Notes:<font color="#FF0000">*</font></label>

							<div class="col-sm-8">

							<textarea class="form-control ckeditor"  name="notes" id="notes" rows=6 ><?php echo isset($notes) ? $notes : ''; ?></textarea> <span class="help-block"></span>

							</div>

						</div>

						

			

						 <div class="form-group">

							<div class="col-sm-offset-2 col-sm-10">

								<button class="btn btn-primary notesAdd" type="submit" name="Submit" value="Save Notes"/><i class="fa fa-comment"></i> Save</button>

								<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>

							</div>

						</div>

                </form>

					</div>

				 

				</div><!-- /.modal-content -->

			  </div><!-- /.modal-dialog -->

			</div><!-- /.modal -->

                 

<div class="modal fade note_edit">

			  <div class="modal-dialog  modal-lg" >

				<div class="modal-content">

				  <div class="modal-header">

					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

					<h4 class="modal-title">Notes Update</h4>

				  </div>

					<div class="modal-body">

		      <form  class="form-horizontal" role="form"  action="" method="post" >

            <?php Yii::$app->request->enableCsrfValidation = true; ?>

    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">

                 <input type="hidden" name="entity_id" value="<?=$_GET['id']?>">

                 <input type="hidden" name="entity_type" value="task">

				<input type="hidden" name="edit_note_model" value="true">

						

						<div class="form-group">

							<label class="col-sm-2 control-label" for="lead_source">Notes:<font color="#FF0000">*</font></label>

							<div class="col-sm-8">

							<textarea class="form-control ckeditor"  name="notes" id="note_body" rows=8 ><?php echo $noteModel->notes; ?></textarea> <span class="help-block"></span>

							</div>

						</div>

						

			

						 <div class="form-group">

							<div class="col-sm-offset-2 col-sm-10">

								<button class="btn btn-primary note-edit" type="submit" name="noteedit" value="Save Notes"/><i class="fa fa-comment"></i> Update</button>

								<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i> Close</button>

                                

							</div>

						</div>

					</div>

				</div><!-- /.modal-content -->

				 </form>

			  </div><!-- /.modal-dialog -->

			</div><!-- /.modal -->



