<script>

	$(document).ready(function(e) {

        $('#defect-actual_start_datetime').val('0000-00-00 00:00:00');

		$('#defect-actual_end_datetime').val('0000-00-00 00:00:00');

		$('.project_defect').click(function(event){

			$('#cke_5_contents').parent().parent().removeAttr('style').next('.error').remove();

			sageLength = CKEDITOR.instances['defect_description'].getData().replace(/<[^>]*>/gi, '').length;

			if(sageLength==0){

				Add_ErrorTag($('#cke_5_contents').parent().parent(),'<?=Yii::t ('app','This Field is Required!')?>');

			event.preventDefault();

			}

		})

    });

</script>

<div class="modal fade defectae">

  <div class="modal-dialog  modal-lg">

    <div class="modal-content">

    	 <div class="modal-header">

        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

        <h4 class="modal-title">New Defect</h4>

      </div>

      <div class="modal-body">

			   <?= $this->render('_form_defect', [

					'defectModel' => $defectModel,

				]) ?>

		</div>

   </div>

 </div>

</div>

   

