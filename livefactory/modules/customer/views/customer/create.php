<?php



use yii\helpers\Html;



/**

 * @var yii\web\View $this

 * @var common\models\Customer $model

 */


$this->title = Yii::t('app', 'Add Customer', [

    'modelClass' => 'Customer',

]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;

?>

<script src="../../vendor/bower/jquery/dist/jquery.js"></script>

<script>

function Add_Error(obj,msg){

	 $(obj).parents('.form-group').addClass('has-error');

	 $(obj).parents('.form-group').append('<div style="color:#D16E6C; clear:both" class="error"><i class="icon-remove-sign"></i> '+msg+'</div>');

	 return true;

}

function Remove_Error(obj){

	$(obj).parents('.form-group').removeClass('has-error');

	$(obj).parents('.form-group').children('.error').remove();

	return false;

}

$(document).ready(function(e) {
	if('<?= \livefactory\models\DefaultValueModule::getDefaultValueId('customer_type')?>' != ''){
		$('#state_id').load('index.php?r=liveobjects/address/ajax-load-states&country_id=<?= \livefactory\models\DefaultValueModule::getDefaultValueId('country')?>');
	}
	$('#country_id').change(function(){

    $.post('index.php?r=liveobjects/address/ajax-load-states&country_id='+$(this).val(),function(result){

					$('#state_id').html(result);

					$('#city_id').html('<option value=""> --Select--</option>');

				})

	})

	$('#state_id').change(function(){
    $.post('index.php?r=liveobjects/address/ajax-load-cities&state_id='+$(this).val(),function(result){
					$('#city_id').html(result);

				})

	})

	$('#w0').submit(function(event){

		var error='';

		$('[data-validation="required"]').each(function(index, element) {

			//alert($(this).attr('id'));

			Remove_Error($(this));

			if($(this).val() == ''){

				error+=Add_Error($(this),'<?=Yii::t ('app','This Field is Required!')?>');

			}else{

					Remove_Error($(this));							

			}

			if(error !=''){

				event.preventDefault();

			}else{

				return true;

			}

		});

	});

});

</script>



                <div class="ibox float-e-margins">

                    <div class="ibox-title">

                        <h5><?= Html::encode($this->title) ?> <small class="m-l-sm"><?php echo Yii::t ( 'app', 'Enter Customer Details, Contact Details & Address Details' ); ?></small></h5>

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

										 <div class="customer-create">

							<?= $this->render('_form', [

								'model' => $model,

							]) ?>

						

						</div>

                    </div>

                </div>