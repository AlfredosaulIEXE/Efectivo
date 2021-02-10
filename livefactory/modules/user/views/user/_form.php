<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use livefactory\models\UserRole;
use livefactory\models\UserType;
use livefactory\models\Customer;
use livefactory\models\AuthItem;
use livefactory\models\Office;
use livefactory\models\Status;
use livefactory\models\Address;
use livefactory\models\Contact;
use livefactory\models\Country;
use livefactory\models\State;
use livefactory\models\City;
/**
 *
 * @var yii\web\View $this
 * @var common\models\User $model
 * @var yii\widgets\ActiveForm $form
 */
$address = Address::findOne(['entity_id' => $model->id, 'entity_type' => 'user']);
$address = $address ? $address : new Address();
$contact = Contact::findOne(['entity_id' => $model->id, 'entity_type' => 'user']);
$contact = $contact ? $contact : new Contact();
$unitGenerate = \livefactory\models\UnitGenerate::find()->where('active = 1')->all();


function getUserRole($id) {

    // No user
    if (empty($id)) {
        return null;
    }

    $connection = \Yii::$app->db;
    $sql="select auth_item.* from auth_item,auth_assignment where auth_item.type=2 and auth_assignment.user_id=".$id." and auth_assignment.item_name=auth_item.name";
    $command=$connection->createCommand($sql);
    $dataReader=$command->queryAll();

    if (count($dataReader) > 0) {
        return $dataReader[0];
    }

    return null;
}
?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script>
    $(document).ready(function () {
        $('.js-state-input').change(function (e) {
            $.post('index.php?r=liveobjects/address/ajax-load-cities&state_id=' + $(this).val(), function (result) {
                $(e.target).closest('.row').find('.js-city-input').html(result);
            });
        });
        $('.user-code').inputmask("numeric", {
            autoGroup: true,
            prefix: '',
            rightAlign: false,
            oncleared: function (e) { $(e.target).val(''); }
        });
    });
</script>

 <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5> <?php echo $this->title; if(isset($_GET['id']) && $_GET['id']){ ?> <span class="pull-right label <?=$model->active =='1'?'label-primary':'label-danger'?>"> <?=$model->active =='1'?'Active':'Inactive'?> </span><?php } ?></h5>
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



<div class="user-form">

    <?php
				
				$form = ActiveForm::begin ( [ 
						'type' => ActiveForm::TYPE_VERTICAL ,
						'options'=>array('enctype' => 'multipart/form-data')
				] );?>
    <input type="hidden" name="address_id" value="<?=$address->id; ?>">
    <input type="hidden" name="contact_id" value="<?=$contact->id; ?>">
               <div class="row">
               		<div class="<?php echo  isset($_GET['id'])?'col-sm-9':'col-sm-12'?>">
                    <?php
                    try {
                        echo Form::widget([

                            'model' => $model,
                            'form' => $form,
                            'columns' => 3,
                            'attributes' => [

                                'first_name' => [
                                    'type' => Form::INPUT_TEXT,
                                    'options' => [
                                        'placeholder' => Yii::t('app', 'Enter First Name') . '...',
                                        'maxlength' => 255
                                    ]
                                ],

                                'last_name' => [
                                    'type' => Form::INPUT_TEXT,
                                    'options' => [
                                        'placeholder' => Yii::t('app', 'Enter Last Name') . '...',
                                        'maxlength' => 255
                                    ]
                                ],

                                'middle_name' => [
                                    'type' => Form::INPUT_TEXT,
                                    'options' => [
                                        'placeholder' => Yii::t('app', 'Enter Middle Name') . '...',
                                        'maxlength' => 255
                                    ]
                                ],

                                'alias' => [
                                    'type' => Form::INPUT_TEXT,
                                    'options' => [
                                        'placeholder' => Yii::t('app', 'Enter Alias') . '...',
                                        'maxlength' => 255
                                    ]
                                ],

                                /*'username' => [
                                    'type' => Form::INPUT_TEXT,
                                    'options' => [
                                        'placeholder' => Yii::t('app', 'Enter Username Or Code') . '...',
                                        'class' => 'user-code',
                                        'maxlength' => 10
                                    ]
                                ],*/

                                // 'auth_key'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Auth Key...', 'maxlength'=>32]],

                                // 'password_hash'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Password Hash...', 'maxlength'=>255]],

                                'email' => [
                                    'type' => Form::INPUT_TEXT,
                                    'options' => [
                                        'placeholder' => Yii::t('app', 'Enter Email') . '...',
                                        'maxlength' => 255
                                    ]
                                ],
                                /*'password_hash'=> [
                                    'type'=> Form::INPUT_PASSWORD,
                                    'options'=> [
                                        'placeholder'=> Yii::t('app', 'Enter Password') . '...',
                                        'maxlength'=>255,
                                        'data-validation'=>'required']
                                ]*/
                                /*'entity_id' => [
                                    'type' => Form::INPUT_DROPDOWN_LIST,
                                    'options' => [
                                        'placeholder' => 'Enter User Role...',
                                        'prompt' => '--' . Yii::t('app', 'Select') . '--'
                                    ],
                                    //'items'=>ArrayHelper::map(Customer::find()->asArray()->where("email NOT IN(select email from tbl_user)")->all(), 'id', 'customer_name'),
                                    'items' => ArrayHelper::map(Customer::find()->asArray()->all(), 'id', 'customer_name'),
                                ]
                                ,*/

                                // 'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Created At...']],

                                // 'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Updated At...']],

                                // 'role'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Role...']],

                                /*
                                'active' => [
                                        'type' => Form::INPUT_DROPDOWN_LIST,
                                        'options' => [
                                                'placeholder' => 'Enter active...'
                                        ],
                                        'items'=>ArrayHelper::map(active::find()->orderBy('label')->asArray()->all(), 'id', 'label')
                                ]
                                */
                            ]
                        ]);
                    } catch (Exception $e) {
                    }
                ?>
                        <div class="row">

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label"><?=Yii::t('app', 'Mobile')?></label>
                                    <input type="text" name="contact[mobile]" class="form-control" value="<?=$contact->mobile?>" placeholder="<?=Yii::t('app', 'Enter Mobile')?>">
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label">Teléfono de Referencia </label>
                                    <input type="text" name="contact[phone]" class="form-control" value="<?=$contact->phone?>" placeholder="<?=Yii::t('app', 'Enter Phone')?>">
                                </div>
                            </div>


                        </div>
                        <h3 class="page-header" style="margin-top: 1em"><?php echo Yii::t('app', 'Personal Address'); ?></h3>
                        <div class="row">

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label"><?=Yii::t('app', 'Address 1')?></label>
                                    <input type="text" name="address[address_1]" data-validation="required" class="form-control" value="<?=$address->address_1?>">
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label"><?=Yii::t('app', 'Num Ext')?></label>
                                    <input type="text" name="address[num_ext]" class="form-control" value="<?=$address->num_ext?>">
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label"><?=Yii::t('app', 'Num Int')?></label>
                                    <input type="text" name="address[num_int]" class="form-control" value="<?=$address->num_int?>">
                                </div>
                            </div>

                        </div>

                        <div class="row">


                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label"><?=Yii::t('app', 'Address Block')?></label>
                                    <input type="text" name="address[block]" class="form-control" value="<?=$address->block?>">
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label"><?=Yii::t('app', 'Zipcode')?>:</label>
                                    <input type="text" name="address[zipcode]" data-validation="required" class="form-control"  value="<?=$address->zipcode?>" placeholder="<?=Yii::t('app', 'Enter Zipcode')?>">
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label"><?=Yii::t('app', 'Delegation')?>:</label>
                                    <input type="text" name="address[delegation]" data-validation="required" class="form-control"  value="<?=$address->delegation?>">
                                </div>
                            </div>
                        </div>
                        <?php

                        echo '<div class="row">

						<div class="col-sm-4">

							<div class="form-group required">

								<label class="control-label">'.Yii::t('app', 'Country').'</label>

						'.Html::dropDownList('address[country_id]',($address->country_id ? $address->country_id : 156),

                                ArrayHelper::map(Country::find()->orderBy('country')->asArray()->all(), 'id', 'country'), ['prompt' => '--Seleccionar--','class'=>'form-control','data-validation'=>'required', 'disabled' => 'disabled' ]  ).'</div></div>

	 					<div class="col-sm-4">

						<div class="form-group required">

								<label class="control-label">'.Yii::t('app', 'State').'</label>

						'.Html::dropDownList('address[state_id]',$address->state_id,

                                ArrayHelper::map(State::find()->where('country_id=' . ($address->country_id ? $address->country_id : 156))->orderBy('state')->asArray()->all(), 'id', 'state'), ['prompt' => '--Seleccionar--','class'=>'form-control js-state-input']  ).'</div></div>

	 				<div class="col-sm-4">

						<div class="form-group required">

								<label class="control-label">'.Yii::t('app', 'City').'</label>

						'.Html::dropDownList('address[city_id]',$address->city_id,

                                ArrayHelper::map(City::find()->where('state_id=' . ($address->state_id ? $address->state_id : 0))->orderBy('city')->asArray()->all(), 'id', 'city'), ['prompt' => '--Seleccionar--','class'=>'form-control js-city-input']  ).'</div></div></div>';

                        ?>
                        <h3 class="page-header" style="margin-top: 1em"><?php echo Yii::t('app', 'Extra Info'); ?></h3>
                        <div class="row">
                            <div class="col-sm-4">
                                <label class="control-label"><?= Yii::t('app', 'Employee Type')?></label>
                                <?php
                                echo Html::dropDownList('auth_item', getUserRole($model->id),
                                    ArrayHelper::map(AuthItem::find()->where("type = 2")->andWhere(['not in', 'name', [ 'Admin', 'Employee', 'Customer' ]])->asArray()->all(), 'name', 'description'), ['prompt' => '--Seleccionar--','class'=>'form-control','data-validation'=>'required', 'required' => 'true' ]);

                                ?>
                            </div>
                            <div class="col-sm-4">
                                <label class="control-label"><?= Yii::t('app', 'Employee Office')?></label>
                                <?php
                                echo Html::dropDownList('User[office_id]', $model->office_id,
                                    ArrayHelper::map(Office::find()->where('active = 1')->asArray()->all(), 'id', 'description'), ['prompt' => '--Seleccionar--','class'=>'form-control','data-validation'=>'required', 'required' => 'true' , 'id' => 'useroffice', ($model->id == 0 ? 'data-fake' : 'disabled') => 'true']);
                                ?>
                            </div>
                            <?php
                            if ($model->id != 0) {
                                $office = $model->office;
                            }
                            ?>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label"><?=Yii::t('app', 'Username Code')?>:</label>
                                    <div class="input-group">

                                        <input id="user-username" type="text" name="User[username]" data-validation="required" class="form-control"  value="<?=($model->username ? $model->username : '')?>" placeholder="<?=Yii::t('app', 'Enter Username Or Code')?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Unidad Generadora</label>
                                    <select id="unitGenerate" name="unit_generate" class="form-control" >
                                        <option>Seleccionar Unidad</option>
                                        <?php foreach ($unitGenerate as $unit){ ?>
                                        <option value="<?=(int) $unit->id?>" <?php if($unit->id == $model->unit_generate): ?> selected <?php endif;?>> <?= $unit->name ?></option>
                                        <?php }  ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
				<?php
                echo '</div>';
				if(isset($_GET['id'])){?>
                <div class="col-sm-3">
                	<div id="picture_preview"></div>
                            <label><?php echo Yii::t('app', 'Photo'); ?> 
                            <?php if(file_exists('../users/'.$model->id.'.png')){?>
                            <a href="index.php?r=user/user/update&id=<?=$model->id?>&edit=t&img_del=yes" class="btn btn-danger btn-xs" onClick="return confirm('Are you Sure!')"><?php echo Yii::t('app', 'Delete'); ?></a>
                            <?php } ?>
                            </label><br/>
                            <?php
                                if(file_exists('../users/'.$model->id.'.png')){?>
                                    <img src="../users/<?=$model->id?>.png" height="170" class="upload  img-responsive">								
                                <?php }else{?>
                                    <img src="../users/nophoto.jpg" height="170" class="upload  img-responsive">
                                <?php }
                            ?>
                            <input type="file" name="user_image" class="inp">
                            	<br/><br/>
                 </div>
				<?php }
				echo '</div>';
				if(empty($_GET['id'])){
				// 'password_reset_token'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Password Reset Token...', 'maxlength'=>255]],
				
				
				echo Html::submitButton ( $model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [ 
						'class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm' 
				] );
				
				} 
				
				ActiveForm::end ();
				?>

</div>

</div>
</div>
