<?php


use yii\helpers\Html;

use yii\helpers\ArrayHelper;

use kartik\widgets\ActiveForm;

use kartik\builder\Form;
use livefactory\models\search\CommonModel;
use livefactory\models\Country;
use livefactory\models\State;
use livefactory\models\City;
use kartik\widgets\DepDrop;
use livefactory\models\User;
use kartik\datecontrol\DateControl;

use livefactory\models\Loan;
use livefactory\models\LeadType;
use livefactory\models\LeadStatus;
use livefactory\models\LeadSource;
use dosamigos\ckeditor\CKEditor;

use livefactory\models\Currency;
use livefactory\models\Office;


/**
 *
 * @var yii\web\View $this
 * @var common\models\Lead $model
 * @var common\models\Address $addrModel
 * @var yii\widgets\ActiveForm $form
 */
?>
<script>
    $(function() {
        $('#lead-mobile, #lead-phone, #lead-email').on('blur', function () {
            $.ajax({
                url: 'http://crmoperacionesfinancieras.com/livecrm/web/index.php?r=sales/lead/checking',
                data: {
                    mobile: $('#lead-mobile').val(),
                    phone: $('#lead-phone').val(),
                    email: $('#lead-email').val(),
                    position_data: $('#position_data').val()
                },
                dataType: 'json' ,
                success:
                    function (res) {
                    if (res['success'] === 1) {
                        console.log(res);
                            if (res['mobile_data'] === 1){
                                $('#myAlert span').html('<span>Este número se encuentra en :<br>' + res[res['input']]['c_control'] + '</span><br>');
                                $('#myAlert').fadeIn('slow');
                                $('#position_data').val('1');
                            }
                            if (res['phone_data'] === 1){
                                $('#myAlert span').html('<span>Este teléfono fijo se encuentra en :<br>' + res[res['input']]['c_control'] + '</span><br>');
                                $('#myAlert').fadeIn('slow');
                                $('#position_data').val('2');

                            }
                            if (res['email_data'] === 1){
                                $('#myAlert span').html('<span>Este email se encuentra en :<br>' + res[res['input']]['c_control'] + '</span><br>');
                                $('#myAlert').fadeIn('slow');
                                $('#position_data').val('3');

                            }
                        $('#duplicated').val('1');
                    } else {
                        $('#duplicated').val('0');
                    }
                }
            })
        });

        $('#myAlert .close').click(function(e) {
            $("#myAlert span").remove();
            $('#myAlert' ).append("<span></span>");
        });
    });

</script>
<div class="lead-form">

    <?php
    $dFlag = false;
    if (empty($_GET['id'])) {
        $model->lead_type_id = \livefactory\models\DefaultValueModule::getDefaultValueId('lead_type');
        if (!isset($model->lead_type_id))
            $model->lead_type_id = 1;
        $model->lead_status_id = LeadStatus::_NEW;
        $dFlag = true;
        $model->lead_source_id = \livefactory\models\DefaultValueModule::getDefaultValueId('lead_source');
        if (!isset($model->lead_source_id))
            $model->lead_source_id = 1;

        $model->lead_owner_id = User::find()->where("username='admin'")->one()->id;

        //	$model->first_name='NA';
        //	$model->last_name='NA';
        //	$model->email='dummy@dummy.com';
        //	$model->mobile='0';
        //	$model->do_not_call='Y';
    }

    $leadType = array();
    foreach (ArrayHelper::map(LeadType::find()->where("active=1")->orderBy('sort_order')->asArray()->all(), 'id', 'label') as $key => $ld) {
        $leadType[$key] = $ld;
    }

    $leadStatus = array();
    foreach (ArrayHelper::map(LeadStatus::find()->where("active=1 and id=1")->orderBy('sort_order')->asArray()->all(), 'id', 'label') as $key => $ld) {
        $leadStatus[$key] = $ld;
    }

    $leadSource = array();
    foreach (ArrayHelper::map(LeadSource::find()->where("active=1")->orderBy('sort_order')->asArray()->all(), 'id', 'label') as $key => $ld) {
        $leadSource[$key] = $ld;
    }

    $leadLoans = array();
    foreach (ArrayHelper::map(Loan::find()->where("active=1")->orderBy('id')->asArray()->all(), 'id', 'description') as $key => $ld) {
        $leadLoans[$key] = $ld;
    }

    $form = ActiveForm::begin([

        'type' => ActiveForm::TYPE_VERTICAL,
        'fieldConfig' => ['errorOptions' => ['encode' => false, 'class' => 'help-block']],  //this helps to show icons in validation messages

    ]);

    $leadCivilStatus = [
        1 => 'Soltero/a',
        2 => 'Comprometido/a',
        3 => 'Casado/a',
        4 => 'Divorciado/a',
        5 => 'Viudo/a'
    ];

    $leadCivilStatusRegime = [
        1 => 'Separación de bienes',
        2 => 'Sociedad Conyugal'
    ];

    $leadHomeStatus = [
        1 => 'Casa propia',
        2 => 'Casa rentada',
        3 => 'Familiares/Padres',
        4 => 'Pagandola'
    ];

    $leadBureauStatus = [
        1 => 'Bueno',
        2 => 'Regular',
        3 => 'Malo',
        4 => 'No ha tenido créditos'
    ];


    // Only user of office
    $office_sql = '';
    if ( ! Yii::$app->user->can('Office.NoLimit')) {
        $office_sql = ' and office_id = ' . Yii::$app->user->identity->office_id;
    }
    // Managers
    $commercial = [$model->lead_owner_id,173];
    foreach (User::find()->join('LEFT JOIN', 'auth_assignment', 'auth_assignment.user_id = tbl_user.id')
                 ->where("auth_assignment.item_name = 'Commercial.Manager'")
                 ->asArray()->all() as $key => $item) {
        $commercial[] = $item['id'];
    }
    ?>
    <?php
    if(Yii::$app->user->id == 173 )
    {
    $sql="SELECT id,code FROM `tbl_office`";
    $connection = \Yii::$app->db;
    $command=$connection->createCommand($sql);
    $officebot=$command->queryAll();
    }
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo Yii::t('app', 'Lead Details'); ?></h3>
        </div>
        <div class="panel-body">
            <?php

            try {

                echo Yii::$app->user->can('Lead.Owner') ?   ( Yii::$app->user->can('Lead.Office') ?  Form::widget([
                    'model' => $model,
                    'form' => $form,
                    'columns' => 3,
                    'attributes' => [
                        'first_name' => [
                            'type' => Form::INPUT_TEXT,
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter First Name') . '...',
                                'maxlength' => 255,
                                'required' => 'true',
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
                            'label' => Yii::t('app', 'Middle Name'),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter Middle Name') . '...',
                                'maxlength' => 255
                            ]
                        ],

                        'age' => [
                            'type' => Form::INPUT_TEXT,
                            'label' => Yii::t('app', 'Age'),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter Age') . '...',
                                'maxlength' => 3
                            ]
                        ],

                        'lead_source_id' => [
                            'label' => Yii::t('app', 'Lead Source'),
                            'type' => Form::INPUT_DROPDOWN_LIST,
                            'options' => [
                                'prompt' => '--' . Yii::t('app', 'Lead Source') . '--',
                            ],
                            'items' => $leadSource
                        ],
                        'office_id' => [
                            'label' => 'Oficina',
                            'type' => Form::INPUT_DROPDOWN_LIST,
                            'options' => [
                                'class' => 'js-lead-owner',
                                'prompt' => '-- Oficina  --',
                                'required' => 'true'

                            ],
                            'items' => ArrayHelper::map(Office::find()->where("active=1 and reports=1")->orderBy('description')->asArray()->all(), 'id', 'description')
                        ],


                    ]
                ]) : Form::widget([
                    'model' => $model,
                    'form' => $form,
                    'columns' => 3,
                    'attributes' => [
                        'first_name' => [
                            'type' => Form::INPUT_TEXT,
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter First Name') . '...',
                                'maxlength' => 255,
                                'required' => 'true',
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
                            'label' => Yii::t('app', 'Middle Name'),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter Middle Name') . '...',
                                'maxlength' => 255
                            ]
                        ],

                        'age' => [
                            'type' => Form::INPUT_TEXT,
                            'label' => Yii::t('app', 'Age'),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter Age') . '...',
                                'maxlength' => 3
                            ]
                        ],

                        'lead_source_id' => [
                            'label' => Yii::t('app', 'Lead Source'),
                            'type' => Form::INPUT_DROPDOWN_LIST,
                            'options' => [
                                'prompt' => '--' . Yii::t('app', 'Lead Source') . '--',
                            ],
                            'items' => $leadSource
                        ],
                        'lead_owner_id' => [
                            'label' => Yii::t ( 'app', 'Lead Owner' ),
                            'type' => Form::INPUT_DROPDOWN_LIST,
                            'options' => [
                                (Yii::$app->user->can('Lead.Owner') ? 'class' : 'disabled') => 'true',
                                'class' => 'js-lead-owner',
                                'prompt' => '--'.Yii::t ( 'app', 'Lead Owner' ).'--',
                                'required' => 'true'

                            ],
                            'items' => Yii::$app->user->id == 173 ?  ArrayHelper::map(User::find()->where("id = 173 ")->asArray()->all(),'id',function($user){
                                return $user['alias'].' ('.$user['username'].')';
                            })
                                :ArrayHelper::map(User::find()->where("id IN (select auth_assignment.user_id from auth_item,auth_assignment where (auth_item.type=2 and auth_assignment.item_name=auth_item.name and auth_assignment.user_id=tbl_user.id)  and (auth_item.name = 'Sales Person' OR auth_item.name = 'Sales Manager' OR auth_item.name = 'Commercial.Manager')) and tbl_user.active=1 and tbl_user.id not in (133 , 226 , 224 , 225 , 227) "." ".$office_sql)->asArray()->all(),'id',function($user){
                                    return $user['alias'].' ('.$user['username'].')';
                            })
                        ],


                    ]
                ])) : ( Yii::$app->user->can('Lead.Office') ? Form::widget([
                    'model' => $model,
                    'form' => $form,
                    'columns' => 3,
                    'attributes' => [
                        'first_name' => [
                            'type' => Form::INPUT_TEXT,
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter First Name') . '...',
                                'maxlength' => 255,
                                'required' => 'true',
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
                            'label' => Yii::t('app', 'Middle Name'),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter Middle Name') . '...',
                                'maxlength' => 255
                            ]
                        ],

                        'age' => [
                            'type' => Form::INPUT_TEXT,
                            'label' => Yii::t('app', 'Age'),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter Age') . '...',
                                'maxlength' => 3
                            ]
                        ],

                        'lead_source_id' => [
                            'label' => Yii::t('app', 'Lead Source'),
                            'type' => Form::INPUT_DROPDOWN_LIST,
                            'options' => [
                                'prompt' => '--' . Yii::t('app', 'Lead Source') . '--',
                            ],
                            'items' => $leadSource
                        ],
                        'office_id' => [
                            'label' => 'Oficina',
                            'type' => Form::INPUT_DROPDOWN_LIST,
                            'options' => [
                                'class' => 'js-lead-owner',
                                'prompt' => '-- Oficina  --',
                                'required' => 'true'

                            ],
                            'items' => ArrayHelper::map(Office::find()->where("active=1 and reports=1")->orderBy('description')->asArray()->all(), 'id', 'description')
                        ],


                    ]
                ]) : Form::widget([
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
                            'label' => Yii::t('app', 'Middle Name'),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter Middle Name') . '...',
                                'maxlength' => 255
                            ]
                        ],

                        'age' => [
                            'type' => Form::INPUT_TEXT,
                            'label' => Yii::t('app', 'Age'),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter Age') . '...',
                                'maxlength' => 3
                            ]
                        ],
                        'lead_source_id' => [
                            'label' => Yii::t('app', 'Lead Source'),
                            'type' => Form::INPUT_DROPDOWN_LIST,
                            'options' => [
                                'prompt' => '--' . Yii::t('app', 'Lead Source') . '--',
                            ],
                            'items' => $leadSource
                        ],

                    ]
                ]));
            } catch (Exception $e) {

            }

            ?>
            <h3 class="page-header"><?php echo Yii::t('app', 'Contact Data'); ?></h3>
            <div class="alert alert-warning" id="myAlert" style="display: none">
                <button type="button" class="close" >×</button>
                <span></span>

            </div>
            <?php
            try {
                echo Form::widget([
                    'model' => $model,
                    'form' => $form,
                    'columns' => 3,
                    'attributes' => [
                        'mobile' => [
                            'type' => Form::INPUT_TEXT,
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter Mobile') . '...',
                                'id' => 'lead-mobile'
                            ]
                        ],
                        'phone' => [
                            'type' => Form::INPUT_TEXT,
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter Phone') . '...',
                                'maxlength' => 255
                            ]
                        ],
                        'email' => [
                            'type' => Form::INPUT_TEXT,
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter Email') . '...',
                                'maxlength' => 255
                            ]
                        ]
                    ]
                ]);

                echo '<div class="row">
						<div class="col-sm-4">
							<div class="form-group required">
								<label class="control-label">' . Yii::t('app', 'Country') . '</label>'
                    // .Html::dropDownList('country_id',  \livefactory\models\DefaultValueModule::getDefaultValueId('country'),
                    . Html::dropDownList('country_id', 156,
                        ArrayHelper::map(Country::find()->orderBy('country')->where('active=1')->asArray()->all(), 'id', 'country'), ['prompt' => '--' . Yii::t('app', 'Select') . '--', 'class' => 'form-control', 'id' => 'country_id', 'data-validation' => 'required', 'disabled' => 'disabled']) . '
                            </div>
                        </div>
	 					<div class="col-sm-4">
						    <div class="form-group required">
								<label class="control-label">' . Yii::t('app', 'State') . '</label>'
                    . Html::dropDownList('state_id', 'state_id',
                        ArrayHelper::map(State::find()->where('country_id=156')->orderBy('state')->asArray()->all(), 'id', 'state'), ['prompt' => '--' . Yii::t('app', 'Select') . '--', 'class' => 'form-control', 'id' => 'state_id']) . '
                            </div>
                        </div>
								<input type="hidden" name="duplicated" id="duplicated" value="">
								<input type="hidden" name="position_data" id="position_data" value="" default>
                        <!--<div class="col-sm-4">
                            <div class="form-group required">
								<label class="control-label">' . Yii::t('app', 'City') . '</label>
						' . Html::dropDownList('city_id', 'city_id',
                        ArrayHelper::map(City::find()->where('id=0')->orderBy('city')->asArray()->all(), 'id', 'city'), ['prompt' => '--' . Yii::t('app', 'Select') . '--', 'class' => 'form-control', 'id' => 'city_id']) . '
						    </div>
                        </div>-->
                    </div>';

            } catch (Exception $e) {}
            ?>
            <h3 class="page-header"><?php echo Yii::t('app', 'Economic Data'); ?></h3>
            <?php
            try {
                echo Form::widget([
                    'model' => $model,
                    'form' => $form,
                    'columns' => 3,
                    'attributes' => [
                        'company_name' => [
                            'type' => Form::INPUT_TEXT,
                            'label' => Yii::t('app', 'Company Name'),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter Company Name') . '...',
                                'maxlength' => 255
                            ]
                        ],
                        'job' => [
                            'type' => Form::INPUT_TEXT,
                            'label' => Yii::t('app', 'Job'),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter Job') . '...',
                                'maxlength' => 255
                            ]
                        ],
                        'labor_old' => [
                            'type' => Form::INPUT_TEXT,
                            'label' => Yii::t('app', 'Labor Old'),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter Labor Old') . '...',
                                'maxlength' => 255
                            ]
                        ],
                        'monthly_income' => [
                            'type' => Form::INPUT_TEXT,
                            'label' => Yii::t('app', 'Income'),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter Income') . '...',
                                'class' => 'currency',
                                'maxlength' => 255
                            ]
                        ],
                        'monthly_income2' => [
                            'type' => Form::INPUT_TEXT,
                            'label' => Yii::t('app', 'Income2'),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter Income') . '...',
                                'class' => 'currency',
                                'maxlength' => 255
                            ]
                        ],
                        'monthly_expenses' => [
                            'type' => Form::INPUT_TEXT,
                            'label' => Yii::t('app', 'Expenses'),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter Expenses') . '...',
                                'class' => 'currency',
                                'maxlength' => 255
                            ]
                        ],
                        'economic_dep' => [
                            'type' => Form::INPUT_TEXT,
                            'label' => Yii::t('app', 'Economic Dependents'),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter Economic Dependents') . '...',
                                'maxlength' => 255
                            ]
                        ],
                        'home_status' => [
                            'label' => Yii::t('app', 'Home Status'),
                            'type' => Form::INPUT_DROPDOWN_LIST,
                            'options' => [
                                'prompt' => '--' . Yii::t('app', 'Home Status') . '--',
                            ],
                            'items' => $leadHomeStatus
                        ],
                        'bureau_status' => [
                            'label' => Yii::t('app', 'Bureau Status'),
                            'type' => Form::INPUT_DROPDOWN_LIST,
                            'options' => [
                                'prompt' => '--' . Yii::t('app', 'Bureau Status') . '--',
                            ],
                            'items' => $leadBureauStatus
                        ],
                        'bureau_status_desc' => [
                            'type' => Form::INPUT_TEXTAREA,
                            'label' => Yii::t('app', 'Bureau Status Desc'),
                            'columnOptions' => [
                                'colspan' => 3
                            ],
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter Bureau Status Desc') . '...',
                                'maxlength' => 255
                            ]
                        ]
                    ]
                ]);
            } catch (Exception $e) {}
            ?>
            <h3 class="page-header">Datos Estado Civil</h3>
            <?php
                try {

                    echo Form::widget([
                        'model' => $model,
                        'form' => $form,
                        'columns' => 3,
                            'attributes' => [
                                'civil_status' => [
                                    'label' => Yii::t('app', 'Civil Status'),
                                    'type' => Form::INPUT_DROPDOWN_LIST,
                                    'options' => [
                                        'prompt' => '--' . Yii::t('app', 'Civil Status') . '--',
                                    ],
                                    'items' => $leadCivilStatus
                                ],
                                'civil_status_regime' => [
                                    'label' => Yii::t('app', 'Civil Status Regime'),
                                    'type' => Form::INPUT_DROPDOWN_LIST,
                                    'options' => [
                                        'prompt' => '--' . Yii::t('app', 'Si es casado/a especificar bajo qué régimen') . '--',
                                    ],
                                    'items' => $leadCivilStatusRegime
                                ],
                                'spouse_job' => [
                                    'type' => Form::INPUT_TEXT,
                                    'label' => Yii::t('app', 'Spouse Job'),
                                    'options' => [
                                        'placeholder' => Yii::t('app', 'Enter Spouse Job') . '...',
                                        'maxlength' => 255
                                    ]
                                ],
                                'spouse_monthly_income' => [
                                    'type' => Form::INPUT_TEXT,
                                    'label' => Yii::t('app', 'Spouse Income'),
                                    'options' => [
                                        'placeholder' => Yii::t('app', 'Enter Spouse Income') . '...',
                                        'class' => 'currency',
                                        'maxlength' => 255
                                    ]
                                ]
                            ]
                    ]);

                } catch (Exception $e) {}
            ?>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo Yii::t('app', 'Credit Details'); ?></h3>
        </div>
        <div class="panel-body">
            <?php
            try {

                echo Form::widget([
                    'model' => $model,
                    'form' => $form,
                    'columns' => 3,
                    'attributes' => [
                        'product_id' => [
                            'label' => Yii::t('app', 'Loan Type'),
                            'type' => Form::INPUT_DROPDOWN_LIST,
                            'options' => [
                                'prompt' => '--' . Yii::t('app', 'Loan Type') . '--',
                            ],
                            'items' => $leadLoans
                        ],
                        'loan_term' => [
                            'type' => Form::INPUT_TEXT,
                            'label' => Yii::t('app', 'Loan Term'),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter Loan Term') . '...',
                                'maxlength' => 3
                            ]
                        ],
                        'loan_amount' => [
                            'type' => Form::INPUT_TEXT,
                            'label' => Yii::t('app', 'Loan Amount'),
                            'options' => [
                                'placeholder' => Yii::t('app', 'Enter Loan Amount') . '...',
                                'class' => 'currency',
                                'maxlength' => 13
                            ]
                        ]
                    ]
                ]);

            } catch (Exception $e) {}
            ?>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group required">
                        <label class="control-label">% Comisión</label>
                        <input type="text" id="lead-loan_interest" name="Lead[loan_interest]" value="7" class="form-control">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group required">
                        <label class="control-label">$ Comisión (monto a cobrar al cliente)</label>
                        <input type="text" id="lead-loan_commission" name="Lead[loan_commission]" class="form-control currency">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group ">
                        <label class="control-label">Qualify</label>
                        <select name="quality" class="form-control">
                            <option value="2">Pendiente Q</option>
                            <option value="0">NQ</option>
                            <option  value="1">Q</option>
                        </select>
                    </div>
                </div>
            </div>
            <?php if(Yii::$app->user->id == 173 )
            {?>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label"><?=Yii::t('app', 'Oficina')?></label>
                                <select name="office_id" class="form-control" required>
                                    <option><?='--' . Yii::t('app', 'Oficina') . '--'?></option>
                                    <?php
                                        foreach ($officebot as $item) {
                                            $officeid =$item['id'];
                                            $officetype=$item['code'];
                                            echo "<option  value='$officeid' selected>$officetype</option>";
                                        }
                                    ?>
                                </select>
                        </div>
                    </div>
            <?php
            }
            echo '</div></div>';
            echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [

                'class' => $model->isNewRecord ? 'btn btn-success btn-sm lead_submit' : 'btn btn-success btn-sm lead_submit',
                'onclick' => "return confirm('¿Has confirmado que este nuevo lead no esté previamente registrado?')"

            ]);

            ActiveForm::end();

            ?>

        </div>
