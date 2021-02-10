<?php

use livefactory\models\search\CommonModel;
use livefactory\models\search\Lead;
use yii\helpers\Html;
use livefactory\models\FileModel;
use yii\helpers\ArrayHelper;

use kartik\widgets\ActiveForm;

use kartik\builder\Form;

use livefactory\models\Loan;
use livefactory\models\Country;
use livefactory\models\State;
use livefactory\models\City;
use livefactory\models\User;
use kartik\widgets\DepDrop;

use kartik\datecontrol\DateControl;

use livefactory\models\LeadType;
use livefactory\models\LeadStatus;
use livefactory\models\LeadSource;
use livefactory\models\search\Estimate as EstimateSearch;

use livefactory\models\Currency;
use livefactory\models\Payment;

/**
 * @var yii\web\View $this
 * @var common\models\Project $model
 */

if (isset($_REQUEST['err_msg'])) {
    ?>
    <script>
        alert("<?=$_REQUEST['err_msg']?>");
    </script>
    <?php
}

$this->title = Yii::t('app', 'Update Lead') . ' : ' . $model->lead_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Leads'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->lead_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
if (isset($_GET['msg']))
    $msgBox = $_GET['msg'];
?>

    <link href="../../../../../vendor/bower/smartwizard/dist/css/smart_wizard.css" rel="stylesheet" type="text/css" />
    <link href="../../../../../vendor/bower/smartwizard/dist/css/smart_wizard_theme_dots.css" rel="stylesheet" type="text/css" />
    <link href="../../../../../vendor/bower/smartwizard/dist/css/smart_wizard_theme_circles.css" rel="stylesheet" type="text/css" />
    <link href="../../../../../vendor/bower/smartwizard/dist/css/smart_wizard_theme_arrows.css" rel="stylesheet" type="text/css" />
    <script src="../../vendor/bower/jquery/dist/jquery.js"></script>
    <script src="../../vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="../include/bootstrap-datetimepicker.js"></script>


    <style>
        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }
        .cke_contents {
            max-height: 250px
        }

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
    </style>

    <script type="text/javascript">
        function Add_Error(obj, msg) {
            $(obj).parents('.form-group').addClass('has-error');
            $(obj).parents('.form-group').append('<div style="color:#D16E6C; clear:both" class="error"><i class="icon-remove-sign"></i> ' + msg + '</div>');
            return true;
        }

        function Remove_Error(obj) {
            $(obj).parents('.form-group').removeClass('has-error');
            $(obj).parents('.form-group').children('.error').remove();
            return false;
        }

        function Add_ErrorTag(obj, msg) {
            obj.css({'border': '1px solid #D16E6C'});

            obj.after('<div style="color:#D16E6C; clear:both" class="error"><i class="icon-remove-sign"></i> ' + msg + '</div>');
            return true;
        }

        function Remove_ErrorTag(obj) {
            obj.removeAttr('style').next('.error').remove();
            return false;
        }

        function loadState() {
            $('.js-state-input').load('<?= isset($baseUrl) ? $baseUrl : ''; ?>?r=liveobjects/address/ajax-load-states&country_id=' + escape('<?=$addressModel->country_id?>') + '&state_id=' + escape('<?=$addressModel->state_id?>'));

        }

        function loadCity() {
            $('.js-city-input').load('<?= isset($baseUrl) ? $baseUrl : ''; ?>?r=liveobjects/address/ajax-load-cities&state_id=<?=$addressModel->state_id?>&city_id=<?=$addressModel->city_id?>')
        }

        $(document).ready(function () {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
                //save the latest tab; use cookies if you like 'em better:
                localStorage.setItem('lastTab_leadview', $(this).attr('href'));
            });

            //go to the latest tab, if it exists:
            var lastTab_leadview = localStorage.getItem('lastTab_leadview');
            if ($('a[href="' + lastTab_leadview + '"]').length > 0) {
                $('a[href="' + lastTab_leadview + '"]').tab('show');
            }
            else {
                // Set the first tab if cookie do not exist
                $('a[data-toggle="tab"]:first').tab('show');
            }
            if ('<?=!empty($_REQUEST['attach_update']) ? $_REQUEST['attach_update'] : ''?>' != '') {
                $('.popup').modal('show');

            }
            if ('<?=!empty($_GET['note_id']) ? $_GET['note_id'] : ''?>' != '') {
                $('.note_edit').modal('show');
            }
            if ('<?=!empty($_GET['contact_edit']) ? $_GET['contact_edit'] : ''?>' != '') {
                $('.contactae').modal('show');
            }
            if ('<?=!empty($_GET['address_edit']) ? $_GET['address_edit'] : ''?>' != '') {
                $('.addressae').modal('show');

                $('#sub_state_id').load('index.php?r=liveobjects/address/ajax-load-states&country_id=' + escape('<?=$sub_address_model->country_id?>') + '&state_id=' + escape('<?=$sub_address_model->state_id?>'));
                $('#sub_city_id').load('index.php?r=liveobjects/address/ajax-load-cities&state_id=<?=$sub_address_model->state_id?>&city_id=<?=$sub_address_model->city_id?>')
            }
            if ('<?=!empty($_GET['appointment_edit']) ? $_GET['appointment_edit'] : ''?>' != '') {
                $('.appointmentae').modal('show');
            }
            if ('<?=!empty($_GET['payment_edit']) ? $_GET['payment_edit'] : ''?>' != '') {
                $('.paymentae').modal('show');
            }
            $('#country_id').change(function () {
                $.post('index.php?r=liveobjects/address/ajax-load-states&country_id=' + $(this).val(), function (result) {
                    $('.js-state-input').html(result);
                    $('.js-city-input').html('<option value=""> --Select--</option>');
                })
            })
            $('.js-state-input').change(function (e) {
                $.post('index.php?r=liveobjects/address/ajax-load-cities&state_id=' + $(this).val(), function (result) {
                    $(e.target).closest('.row').find('.js-city-input').html(result);
                })
            })
            $('#sub_country_id').change(function () {
                $.post('index.php?r=liveobjects/address/ajax-load-states&country_id=' + $(this).val(), function (result) {
                    $('#sub_state_id').html(result);
                    $('#sub_city_id').html('<option value=""> --Select--</option>');
                })
            })
            $('#sub_state_id').change(function () {
                $.post('index.php?r=liveobjects/address/ajax-load-cities&state_id=' + $(this).val(), function (result) {
                    $('#sub_city_id').html(result);
                })
            })
            //Auto Load
            //loadState();
            //loadCity();

            $('.add_address').click(function (event) {
                var error = '';
                $('#addressform [data-validation="required"]').each(function (index, element) {
                    //alert($(this).attr('id'));
                    Remove_Error($(this));
                    if ($(this).val() == '') {
                        error += Add_Error($(this), '<?=Yii::t('app', 'This Field is Required!')?>');
                    } else {
                        Remove_Error($(this));
                    }
                    if (error != '') {
                        event.preventDefault();
                    } else {
                        return true;
                    }
                });
            });

            $('.add_contact').click(function (event) {
                var error = '';
                $('#contactform [data-validation="required"]').each(function (index, element) {
                    //alert($(this).attr('id'));
                    Remove_Error($(this));
                    if ($(this).val() == '') {
                        error += Add_Error($(this), '<?=Yii::t('app', 'This Field is Required!')?>');
                    } else {
                        if ($(this).is("[email-validation]")) {
                            var e = $(this).val();
                            var atpos = e.indexOf("@");
                            var dotpos = e.lastIndexOf(".");
                            if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= e.length) {
                                error += Add_Error($(this), 'Email Address Not Valid!');
                            } else {
                                Remove_Error($(this));
                            }
                        } else {
                            Remove_Error($(this));
                        }
                    }
                    if (error != '') {
                        event.preventDefault();
                    } else {
                        return true;
                    }
                });
            });
            $('[data-valid-num="required"]').keypress(function (key) {
                if ((key.charCode > 7 && key.charCode < 45) || key.charCode > 57) return false;
            });

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('.upload').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }


            $(".inp").change(function () {
                readURL(this);
                ajaxFileUpload(this);
                //$('#w0').submit();
            });
            $('.upload').click(function () {
                $('.inp').click();
            })

            function ajaxFileUpload(upload_field) {
// Checking file type
                /*var re_text = /\.jpg|\.gif|\.jpeg/i;
                var filename = upload_field.value;
                    if (filename.search(re_text) == -1) {
                        alert("File should be either jpg or gif or jpeg");
                        upload_field.form.reset();
                        return false;
                    }*/
                document.getElementById('picture_preview').innerHTML = '<div><img src="http://i.hizliresim.com/xAmY7B.gif" width="100%" border="0" /></div>';
                upload_field.form.action = 'index.php?r=sales/lead/view&id=<?=$_GET['id']?>';
                upload_field.form.target = 'upload_iframe';
                upload_field.form.submit();
                upload_field.form.action = '';
                upload_field.form.target = '';
                setTimeout(function () {
                    document.getElementById('picture_preview').innerHTML = '';
                }, 2500)
                return true;
            }

            $('.lead_submit').click(function (event) {

                $('#cke_2_contents').parent().parent().removeAttr('style').next('.error').remove();

                sageLength = CKEDITOR.instances['notes'].getData().replace(/<[^>]*>/gi, '').length;

                if (sageLength != 0) {
                    alert('Please fill Note');
                    $('.add-notes-modal').modal('show');

                    Add_ErrorTag($('#cke_2_contents').parent().parent(), '<?=Yii::t('app', 'This Field is Required!')?>');

                    event.preventDefault();

                }

            })
            if ('<?= isset($msgBox) ? $msgBox : ''?>' != '') {
                setTimeout(function () {
                    document.location.href = "index.php?r=sales/lead/view&id=<?=$_GET['id']?>";
                }, 2000);
            }
        });


    </script>
<?php
if (!empty($msgBox)) {
    ?>
    <div class="alert alert-success"><?= $msgBox ?></div>
<?php }
?>
<?php
if (!empty($_GET['error'])) {
    ?>
    <!--<div class="alert alert-danger"><?= $_GET['error'] ?></div>-->
<?php }
?>
    <iframe name="upload_iframe" id="upload_iframe" style="display:none;"></iframe>

<?php
if ($model->lead_status_id == LeadStatus::_CONVERTED)
    $dFlag = true;
else
    $dFlag = false;

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

$leadDocuments = [
    'home',
    'entry',
    'id',
    'curp',
    'birth',
    'signed',
];
$leadCustomerdocuments=[
        'evidence',
    'cancel',
    'refund',
    'profeco',
    'fiscalia'
];

$leadLoans = array();
foreach (ArrayHelper::map(Loan::find()->where("active=1")->orderBy('id')->asArray()->all(), 'id', 'description') as $key => $ld) {
    $leadLoans[$key] = $ld;
}

// Only user of office
$office_sql = '';
if ( ! Yii::$app->user->can('Office.NoLimit')) {
    $office_sql = ' and office_id = ' . Yii::$app->user->identity->office_id;
}
//Customer Director
$customermanager = [];
foreach (User::find()->join('LEFT JOIN', 'auth_assignment', 'auth_assignment.user_id = tbl_user.id')
             ->where(" auth_assignment.item_name = 'Customer.Director' ")
             ->andWhere('tbl_user.active = 1')
             ->asArray()->all() as $key => $item) {
    $customermanager[$item['id']] = $item['alias'] . ' ('. $item['username'] .')';
}
// Managers
if (isset($model->service_owner_id))
    $managers = [$model->service_owner_id];
else
{
    $managers = [];
}

foreach (User::find()->join('LEFT JOIN', 'auth_assignment', 'auth_assignment.user_id = tbl_user.id')
             ->where("auth_assignment.item_name = 'Customer.Service' OR auth_assignment.item_name = 'Customer.Service2' ")
             ->andWhere('office_id = ' . $model->office_id)
             ->andWhere('tbl_user.active = 1')
             ->asArray()->all() as $key => $item) {
    $managers[$item['id']] = $item['alias'] . ' ('. $item['username'] .')';
}
$manager = $managers + $customermanager;
//var_dump($manager);
$insurances =[];
foreach (User::find()->join('LEFT JOIN', 'auth_assignment', 'auth_assignment.user_id = tbl_user.id')
             ->where("auth_assignment.item_name = 'Insurance.Customer' OR auth_assignment.item_name = 'Insurance'")
             ->andWhere('office_id = ' . $model->office_id)
             ->asArray()->all() as $key => $item) {
    $insurances[$item['id']] = $item['alias'] . ' ('. $item['username'] .')';
}


// Managers
$commercial = [$model->lead_owner_id,173,228];
foreach (User::find()->join('LEFT JOIN', 'auth_assignment', 'auth_assignment.user_id = tbl_user.id')
             ->where("auth_assignment.item_name = 'Commercial.Manager' and tbl_user.active = 1 ")
             ->asArray()->all() as $key => $item) {
    $commercial[] = $item['id'];
}

$owner_sql = ' OR IN ('.$model->lead_owner_id.')';

$form = ActiveForm::begin([

    'type' => ActiveForm::TYPE_VERTICAL,
    'options' => array('enctype' => 'multipart/form-data')

]);
//check in payments where sum is > = 3000
$payment = Payment::find()->where(['entity_id' => $model->id] )->all();
$total = 0;

foreach ($payment as $pay){
    if ($pay->type != 5)
        $total += $pay->amount;
}
?>
    <style>
        .field-lead-lead_owner_id select,
        .field-lead-lead_owner_id .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #fff;
        }

        .field-lead-lead_owner_id select:hover,
        .field-lead-lead_owner_id .select2-container--default .select2-selection--single .select2-selection__rendered:hover {
            color: #676a6c;
        }
        .field-lead-lead_owner_id select:disabled,
        .field-lead-lead_owner_id .select2-container--default.select2-container--disabled .select2-selection--single .select2-selection__rendered {
            color: #eee;
        }

        .field-lead-lead_owner_id select:disabled:hover,
        .field-lead-lead_owner_id .select2-container--default.select2-container--disabled .select2-selection--single .select2-selection__rendered:hover {
            color: #676a6c;
        }
    </style>
    <div class="ibox clearfix" id="ibox1">
        <div class="ibox-title">
            <h3><?=LeadStatus::getStatusLabel($model)?> &nbsp;- <?= $model->c_control ?> - <?= $model->lead_name ?>
                <div class="pull-right" style=" display: flex; justify-content: space-between; align-items: center">
                    <div style="padding-right: 10px">
                        <button class="btn btn-info" id="myBtnwizard"><i class="fa fa-question"></i> <?php echo Yii::t('app', 'Wizard'); ?></button>
                    </div>
                    <?php
                    $progress = Lead::generateProgressLead($model->id);
                    $barClass = $progress <= 25 ? 'bg-danger' : ($progress <= 50 ? 'bg-warning' : ($progress <= 75 ? 'bg-info' : ''));
                    ?>
                    <div style="font-size: 14px">Perfil completado al:</div>
                    <div class="progress" style="display: inline-block ;width: 250px; margin-bottom: 0">
                        <div class="progress-bar progress-bar-striped <?=$barClass?>" role="progressbar"
                             style="width: <?= $progress ?>%"><?= $progress ?>%
                        </div>
                    </div>
                    <div>
                        <a href="#" onclick="history.back()" style="display: inline-block; margin-left: 10px; margin-top: 3px">
                            <span class="glyphicon glyphicon-remove"></span>
                        </a>
                    </div>

            </h3>
            <?php
            $qualify = [
                    0 => 'NQ',
                    1 => 'Q',
                    2 => 'Pendiente Q'
            ]; ?>
        <div class="ibox-content">
            <div class="sk-spinner sk-spinner-chasing-dots">
                <div class="sk-dot1"></div>
                <div class="sk-dot2"></div>
            </div>
            <div class="project-update">
                <div class="tabs-container">
                    <ul class="nav nav-tabs" id="viewlead">
                        <li class="active"><a id="generallead" href="#general" role="tab"
                                              data-toggle="tab" ><?= $model->lead_name ?></a></li>
                        <?php if ( ! Yii::$app->user->can('Role.Capturist' )):?>
                        <li><a id="documentslead" href="#documents" role="tab"
                               data-toggle="tab" ><?php echo Yii::t('app', 'Documents'); ?></a></li>
                        <?php if (LeadStatus::customerManage($model)):?>
                        <li><a id="documentscustomservicelead" href="#documentscustomservice" role="tab" data-toggle="tab" >Documentos atención a clientes</a> </li>
                        <?php endif; ?>
                        <li><a id="appointmentslead" href="#appointments" role="tab" data-toggle="tab" ><?php echo Yii::t('app', 'Appointments'); ?></a></li>
                        <li><a id="paymentslead" href="#payments" role="tab" data-toggle="tab" ><?php echo Yii::t('app', 'Payments'); ?></a></li>
                        <?php endif; ?>
                        <?php if(Yii::$app->user->can('Customer.Index')): ?>
                        <!--<li><a href="#paymentsinsurance" role="tab" data-toggle="tab">Pagos de seguro</a></li>-->
                        <?php endif; ?>
                        <li><a id="noteslead" href="#notes" role="tab" data-toggle="tab" ><?php echo Yii::t('app', 'Tracking'); ?></a></li>
                        <?php if ( ! Yii::$app->user->can('Role.Capturist')):?>
                        <li><a href="#activity" role="tab"
                               data-toggle="tab"><?php echo Yii::t('app', 'Binnacle'); ?></a></li>
                        <?php endif; ?>

                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="general">
                            <br>
                            <form method="post" id="form-lead-main" action="index.php?r=sales/lead/update&id=<?=$model->id?>"  enctype="multipart/form-data">
                                <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                                <input type="hidden" name="lead_id" value="<?php echo $model->id; ?>">
                                <?php

                                try {
                                    echo Form::widget([
                                        'model' => $model,
                                        'form' => $form,
                                        'columns' => 3,
                                        'attributes' => [
                                            'c_control' => [
                                                'type' => Form::INPUT_TEXT,
                                                'label' => Yii::t('app', 'Folio'),
                                                'options' => [
                                                    'readonly' => true,
                                                    'maxlength' => 18,
                                                ]
                                            ],
                                            'lead_source_id' => [
                                                'label' => Yii::t('app', 'Lead Source'),
                                                'type' => Form::INPUT_DROPDOWN_LIST,
                                                'options' => [
                                                    'prompt' => '--' . Yii::t('app', 'Lead Source') . '--',
                                                ],
                                                'items' =>  ArrayHelper::map(LeadSource::find()->where('active = 1 ')->orderBy('sort_order')->asArray()->all(), 'id', 'label')
                                            ],
                                            'lead_status_id' => [
                                                'label' => Yii::t('app', 'Lead Status'),
                                                'type' => Form::INPUT_DROPDOWN_LIST,
                                                'options' => [
                                                    'prompt' => '--' . Yii::t('app', 'Lead Status') . '--',
                                                    'readonly' =>  $dFlag,
                                                ],
                                                //array list
                                                'items'=> ArrayHelper::map(LeadStatus::find()->orderBy('sort_order')->where("id!=5 and id!=7")->asArray()->all(), 'id', 'label')
                                                //'items' =>  $model->lead_status_id == 7 ? (Yii::$app->user->can('Lead.Unfroze')) ?  ArrayHelper::map(LeadStatus::find()->orderBy('sort_order')->asArray()->all(), 'id', 'label') :ArrayHelper::map(LeadStatus::find()->where("id=7")->orderBy('sort_order')->asArray()->all(), 'id', 'label')    : ArrayHelper::map(LeadStatus::find()->orderBy('sort_order')->asArray()->all(), 'id', 'label')
                                            ],


                                            'lead_owner_id' => [
                                                'label' => Yii::t ( 'app', 'Lead Owner' ),
                                                'type' => Form::INPUT_DROPDOWN_LIST,
                                                'options' => [
                                                    (Yii::$app->user->can('Lead.Owner') ? 'class' : 'disabled') => 'true',
                                                    'class' => 'js-lead-owner',
                                                    'prompt' => '--'.Yii::t ( 'app', 'Lead Owner' ).'--',
                                                ],
                                                'items' => ArrayHelper::map(User::find()->where("id IN (" . implode(',', $commercial) .") OR id IN (select auth_assignment.user_id from auth_item,auth_assignment where (auth_item.type=2 and auth_assignment.item_name=auth_item.name and auth_assignment.user_id=tbl_user.id)  and (auth_item.name = 'Sales Person' OR auth_item.name = 'Sales Manager' OR auth_item.name = 'Commercial.Manager')) and tbl_user.active=1 and tbl_user.office_id=".$model->office_id." ".$office_sql)->asArray()->all(),'id',function($user){
                                                    return $user['alias'].' ('.$user['username'].')';
                                                })
                                            ],
                                            'added_at' => [
                                                'type' => Form::INPUT_TEXT,
                                                'label' => Yii::t('app', 'Added At'),
                                                'options' => [
                                                    'readonly' => true,
                                                    'value' => Yii::$app->formatter->asDate($model->added_at, 'php:d/m/Y h:i A')
                                                ]
                                            ],
                                            'c_contract' => [
                                                'type' => Form::INPUT_TEXT,
                                                'label' => 'Contrato/Cuenta',
                                                'options' => [
                                                    'readonly' => true,
                                                    'maxlength' => 18,
                                                ]
                                            ]
                                        ]
                                    ]);
                                } catch (Exception $e) {
                                    var_dump($e->getMessage());
                                }
                                ?>
                                <?php
                                echo Html::submitButton(Yii::t('app', 'Update'), [
                                    'class' => 'btn btn-primary btn-sm  lead_submit',
                                    'onclick' => 'return confirm(\'Estás a punto de actualizar la información. ¿Deseas continuar?\')'
                                ]);
                                ?>
                            </form>
                            <?php if (LeadStatus::customerManage($model)):?>
                            <div class="panel panel-info" style="margin-top: 2em">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Atención a Clientes</h3>
                                </div>
                                <div class="panel-body">
                                    <?php if (LeadStatus::isMigrated($model)): ?>
                                    <form method="post" action="index.php?r=sales/lead/customer&id=<?=$_REQUEST['id']?>">
                                        <?php Yii::$app->request->enableCsrfValidation = true; ?>
                                        <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                                    <?php
                                    if ($model->lead_status_id == LeadStatus::_CONVERTED/* && Yii::$app->user->can('Role.Customer.Service')*/) {
                                        try {
                                            echo Form::widget([
                                                'model' => $model,
                                                'form' => $form,
                                                'columns' => 3,
                                                'attributes' => [
                                                    'service_status_id' => [
                                                        'label' => 'Estado atención a clientes',
                                                        'type' => Form::INPUT_DROPDOWN_LIST,
                                                        'items' => LeadStatus::$_customer_service_status,
                                                        'options' => [
                                                            (LeadStatus::canChange($model) ? 'class' : 'disabled') => 'true'
                                                        ]
                                                    ],
                                                    'service_owner_id' => [
                                                        'label' => 'Propietario atención a clientes',
                                                        'type' => Form::INPUT_DROPDOWN_LIST,
                                                        'items' => $manager,
                                                        'options' => [
                                                            (Yii::$app->user->can('Lead.Migrate') ? 'class' : 'disabled') => 'true'
                                                        ]
                                                    ]
                                                ]
                                            ]);
                                        } catch (Exception $e) {
                                            var_dump($e->getMessage());
                                        }
                                    }
                                    ?>
                                        <?php
                                        echo Html::submitButton(Yii::t('app', 'Update'), [
                                            'class' => 'btn btn-success btn-sm',
                                            'onclick' => 'return confirm(\'Estás a punto de actualizar la información. ¿Deseas continuar?\')'
                                        ]);
                                        ?>
                                    </form>
                                    <?php else: ?>
                                    <?php if (Yii::$app->user->can('Role.Manager')): ?><div class="alert alert-warning">Antes de poder gestionar este lead debe ser migrado a "Atención a Clientes"</div><?php endif; ?>
                                        <?php if (LeadStatus::canMigrate($model)): ?>
                                            <?php if (Yii::$app->user->can('Lead.Migrate')): ?>
                                                <a href="javascript:void(0)" class="btn btn-success" onclick="$('#migrate-dialog').modal('show');">Migrar a Atención a Clientes</a>
                                            <?php else: ?>
                                                <!--<form method="post" action="index.php?r=sales/lead/migrate&id=<?=$_REQUEST['id']?>">
                                                    <?php Yii::$app->request->enableCsrfValidation = true; ?>
                                                    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                                                    <button type="submit" class="btn btn-success" onclick="return confirm('¿Deseas iniciar el proceso de migración a atención a clientes?')">Migrar a Atención a Clientes</button>
                                                </form>-->
                                            <div class="alert alert-warning">Favor de comunicarse con sistemas, y entregar la siguiente información: <strong>UserId: <?=Yii::$app->user->getId()?></strong></div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="alert alert-info">Lo sentimos no tienes permisos para poder migrar este lead.</div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
<!--                            Insurance-->
                            <?php if (LeadStatus::caMigrateInsurance($model)):?>
                                <div class="panel panel-info" style="margin-top: 2em">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Seguros</h3>
                                    </div>
                                    <div class="panel-body">
                                        <?php if (LeadStatus::caMigrateInsuranceForm($model)): ?>
                                            <form method="post" action="index.php?r=sales/lead/customerinsurance&id=<?=$_REQUEST['id']?>">
                                                <?php Yii::$app->request->enableCsrfValidation = true; ?>
                                                <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                                                <?php
                                                    try {
                                                        echo Form::widget([
                                                            'model' => $model,
                                                            'form' => $form,
                                                            'columns' => 3,
                                                            'attributes' => [

                                                                'insurance_agent' => [
                                                                    'label' => 'Propietario Seguros',
                                                                    'type' => Form::INPUT_DROPDOWN_LIST,
                                                                    'items' => $insurances,

                                                                ]
                                                            ]
                                                        ]);
                                                    } catch (Exception $e) {
                                                        var_dump($e->getMessage());
                                                    }
                                                ?>
                                                <?php
                                                echo Html::submitButton(Yii::t('app', 'Update'), [
                                                    'class' => 'btn btn-success btn-sm',
                                                    'onclick' => 'return confirm(\'Estás a punto de actualizar la información. ¿Deseas continuar?\')'
                                                ]);
                                                ?>
                                            </form>
                                        <?php else: ?>
                                            <?php  if ((Yii::$app->user->can('Insurance.Director')) || (Yii::$app->user->can('Audit.Member')) || (Yii::$app->user->can('Admin'))): ?>
                                                    <a href="javascript:void(0)" class="btn btn-success" onclick="$('#migrate-dialog-insurance').modal('show');">Migrar a Seguros</a>
                                                <?php endif; ?>
                                        <?php endif; ?>


                                    </div>
                                </div>
                            <?php endif; ?>
                            <hr>
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <?php echo Yii::t('app', 'General Data'); ?>
                                </div>
                                <div class="panel-body">
                                    <div class="tabs-container">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#personal" role="tab"
                                                                  data-toggle="tab"><?php echo Yii::t('app', 'Personal Info'); ?></a></li>
                                            <?php if ( ! Yii::$app->user->can('Role.Capturist')):?>
                                            <li><a href="#contact_data" role="tab"
                                                   data-toggle="tab"><?php echo Yii::t('app', 'Contact Data'); ?></a></li>

                                            <li><a href="#economic" role="tab"
                                                   data-toggle="tab"><?php echo Yii::t('app', 'Economic Data'); ?></a></li>
                                            <li><a href="#spouse" role="tab"
                                                   data-toggle="tab"><?php echo Yii::t('app', 'Civil Status'); ?></a></li>
                                            <li><a href="#references" role="tab"
                                                   data-toggle="tab"><?php echo Yii::t('app', 'References'); ?></a></li>
                                            <?php endif; ?>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="personal">
                                                <div class="row">
                                                    <div style="background-color: #ebaf35;padding: 10px;margin-top: 35px;margin-bottom: 10px;margin-left: 20px;margin-right: 20px">
                                                        <h3>Por favor rectifica con el cliente su nombre, apellido paterno y apellido materno.</h3>
                                                    </div>
                                                </div>
                                                <br>
                                                <form method="post" id="form-lead-general" action=""  enctype="multipart/form-data">
                                                    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                                                    <input type="hidden" name="lead_id" value="<?php echo $model->id; ?>">
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
                                                                'middle_name' => [
                                                                    'type' => Form::INPUT_TEXT,
                                                                    'label' => Yii::t('app', 'Last Name'),
                                                                    'options' => [
                                                                        'placeholder' => Yii::t('app', 'Enter Middle Name') . '...',
                                                                        'maxlength' => 255
                                                                    ]
                                                                ],
                                                                'last_name' => [
                                                                    'type' => Form::INPUT_TEXT,
                                                                    'label' => Yii::t('app', 'Middle Name'),
                                                                    'options' => [
                                                                        'placeholder' => Yii::t('app', 'Enter Last Name') . '...',
                                                                        'maxlength' => 255
                                                                    ]
                                                                ],

                                                                'middle_name' => [
                                                                    'type' => Form::INPUT_TEXT,
                                                                    'label' => Yii::t('app', 'Last Name'),
                                                                    'options' => [
                                                                        'placeholder' => Yii::t('app', 'Enter Middle Name') . '...',
                                                                        'maxlength' => 255
                                                                    ]
                                                                ],

                                                                'last_name' => [
                                                                    'type' => Form::INPUT_TEXT,
                                                                    'label' => Yii::t('app', 'Middle Name'),
                                                                    'options' => [
                                                                        'placeholder' => Yii::t('app', 'Enter Last Name') . '...',
                                                                        'maxlength' => 255
                                                                    ]
                                                                ],


                                                                'birthdate' => [
                                                                    'type' => Form::INPUT_WIDGET,
                                                                    'label' => Yii::t('app', 'Birthdate'),
                                                                    'widgetClass' => \yii\widgets\MaskedInput::className(),
                                                                    'displayFormat' => 'php:d/m/Y',
                                                                    'autoWidget' => false,
                                                                    'options' => [
                                                                        'mask' => '99/99/9999',
                                                                    ]
                                                                ],
                                                                'place_of_birth' => [
                                                                    'type' => Form::INPUT_TEXT,
                                                                    'label' => Yii::t('app', 'Place of Birth'),
                                                                    'options' => [
                                                                        'placeholder' => Yii::t('app', 'Enter Place of Birth') . '...',
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
                                                                'rfc' => [
                                                                    'type' => Form::INPUT_TEXT,
                                                                    'label' => Yii::t('app', 'RFC'),
                                                                    'options' => [
                                                                        'placeholder' => Yii::t('app', 'Enter RFC') . '...',
                                                                        'maxlength' => 13
                                                                    ]
                                                                ],
                                                                'curp' => [
                                                                    'type' => Form::INPUT_TEXT,
                                                                    'label' => Yii::t('app', 'CURP'),
                                                                    'options' => [
                                                                        'placeholder' => Yii::t('app', 'Enter CURP') . '...',
                                                                        'maxlength' => 18
                                                                    ]
                                                                ]
                                                            ]
                                                        ]);
                                                    } catch (Exception $e) {
                                                        var_dump($e->getMessage());
                                                    }
                                                    ?>
                                                    <h3 class="page-header"
                                                        style="margin-top: 1em"><?php echo Yii::t('app', 'Credit Details'); ?></h3>
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
                                                                        'maxlength' => 20
                                                                    ],
                                                                ]
                                                            ]
                                                        ]);

                                                    } catch (Exception $e) {
                                                    }
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group required">
                                                                <label class="control-label">% Comisión</label>
                                                                <input type="text" id="lead-loan_interest" name="Lead[loan_interest]" value="<?php echo $model->getInterest(); ?>"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group required">
                                                                <label class="control-label">$ Comisión (monto a cobrar al cliente)</label>
                                                                <input type="text" id="lead-loan_commission" name="Lead[loan_commission]" class="form-control currency" value="<?php echo $model->loan_commission; ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group ">
                                                                <label class="control-label">Qualify</label>
                                                                <select name="quality" class="form-control">
                                                                    <?php foreach ($qualify as $key => $q) {?>
                                                                    <option value=<?= $key ?><?php if($key == $model->qualify): ?> selected<?php endif; ?>><?= $q ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    echo Html::submitButton(Yii::t('app', 'Update'), [
                                                        'class' => 'btn btn-primary btn-sm  lead_submit'
                                                    ]);
                                                    ?>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade" id="contact_data">
                                                <br>
                                                <form method="post" id="form-lead-contact" action=""  enctype="multipart/form-data">
                                                    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                                                    <input type="hidden" name="lead_id" value="<?php echo $model->id; ?>">
                                                    <?php
                                                    echo Yii::$app->controller->renderPartial("../../../liveobjects/views/address/address-model/custom", [
                                                        'lead' => $model,
                                                        'entity_type' => 'lead'
                                                    ]);
                                                    ?>
                                                    <?php
                                                    echo Html::submitButton(Yii::t('app', 'Update'), [
                                                        'class' => 'btn btn-primary btn-sm  lead_submit'
                                                    ]);
                                                    ?>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade" id="economic">
                                                <form method="post" id="form-lead-economic" action=""  enctype="multipart/form-data">
                                                    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                                                    <input type="hidden" name="lead_id" value="<?php echo $model->id; ?>">
                                                    <h3 class="page-header" style="margin-top: 1em"><?php echo Yii::t('app', 'Economic Only Data'); ?></h3>
                                                    <?php
                                                    try {
                                                        echo Form::widget([
                                                            'model' => $model,
                                                            'form' => $form,
                                                            'columns' => 3,
                                                            'attributes' => [
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
                                                    } catch (Exception $e) {
                                                    }
                                                    ?>
                                                    <h3 class="page-header" style="margin-top: 1em"><?php echo Yii::t('app', 'Job Data'); ?></h3>
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
                                                                ]
                                                            ]
                                                        ]);
                                                    } catch (Exception $e) {}
                                                    ?>
                                                    <?php
                                                    echo Yii::$app->controller->renderPartial("../../../liveobjects/views/address/address-model/custom", [
                                                        'lead' => $model,
                                                        'entity_type' => 'lead.job'
                                                    ]);
                                                    ?>
                                                    <?php
                                                    echo Html::submitButton(Yii::t('app', 'Update'), [
                                                        'class' => 'btn btn-primary btn-sm  lead_submit'
                                                    ]);
                                                    ?>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade" id="spouse">
                                                <br>
                                                <form method="post" id="form-lead-spouse" action=""  enctype="multipart/form-data">
                                                    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                                                    <input type="hidden" name="lead_id" value="<?php echo $model->id; ?>">
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
                                                                        'prompt' => '--' . Yii::t('app', 'Enter Civil Status Regime') . '--',
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

                                                    } catch (Exception $e) {
                                                    }
                                                    ?>
                                                    <h3 class="page-header" style="margin-top: 1em"><?php echo Yii::t('app', 'Spouse Info'); ?></h3>
                                                    <?php
                                                    echo Yii::$app->controller->renderPartial("../../../liveobjects/views/custom/spouse", [
                                                        'lead' => $model
                                                    ]);
                                                    ?>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade" id="references">
                                                <br>
                                                <div class="panel-group" id="references_c">
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h4 class="panel-title">
                                                                <a href="#reference1" data-toggle="collapse" data-parent="#references_c" style="display: block"><?php echo Yii::t('app', 'Reference 1'); ?></a>
                                                            </h4>
                                                        </div>
                                                        <div id="reference1" class="panel-collapse collapse in">
                                                            <div class="panel-body">
                                                                <form method="post" id="form-lead-reference" action=""  enctype="multipart/form-data" class="js-form-reference">
                                                                    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                                                                    <input type="hidden" name="lead_id" value="<?php echo $model->id; ?>">
                                                                    <input type="hidden" name="entity_type" value="lead.ref.1">
                                                                    <?php
                                                                    echo Yii::$app->controller->renderPartial("../../../liveobjects/views/custom/reference", [
                                                                        'lead' => $model,
                                                                        'entity_type' => 'lead.ref.1'
                                                                    ]);
                                                                    ?>
                                                                    <?php
                                                                    echo Yii::$app->controller->renderPartial("../../../liveobjects/views/address/address-model/custom", [
                                                                        'lead' => $model,
                                                                        'entity_type' => 'lead.ref.1'
                                                                    ]);
                                                                    ?>
                                                                    <?php
                                                                    echo Html::submitButton(Yii::t('app', 'Update'), [
                                                                        'class' => 'btn btn-primary btn-sm  lead_submit'
                                                                    ]);
                                                                    ?>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel panel-default">
                                                        <div class="panel-heading">
                                                            <h4 class="panel-title">
                                                                <a href="#reference2" data-toggle="collapse" data-parent="#references_c" style="display: block"><?php echo Yii::t('app', 'Reference 2'); ?></a>
                                                            </h4>
                                                        </div>
                                                        <div id="reference2" class="panel-collapse collapse">
                                                            <div class="panel-body">
                                                                <form method="post" id="form-lead-reference" action=""  enctype="multipart/form-data" class="js-form-reference">
                                                                    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                                                                    <input type="hidden" name="lead_id" value="<?php echo $model->id; ?>">
                                                                    <input type="hidden" name="entity_type" value="lead.ref.2">
                                                                    <?php
                                                                    echo Yii::$app->controller->renderPartial("../../../liveobjects/views/custom/reference", [
                                                                        'lead' => $model,
                                                                        'entity_type' => 'lead.ref.2'
                                                                    ]);
                                                                    ?>
                                                                    <?php
                                                                    echo Yii::$app->controller->renderPartial("../../../liveobjects/views/address/address-model/custom", [
                                                                        'lead' => $model,
                                                                        'entity_type' => 'lead.ref.2'
                                                                    ]);
                                                                    ?>
                                                                    <?php
                                                                    echo Html::submitButton(Yii::t('app', 'Update'), [
                                                                        'class' => 'btn btn-primary btn-sm  lead_submit'
                                                                    ]);
                                                                    ?>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <?php echo Yii::t('app', 'Prints'); ?>
                                </div>
                                <div class="panel-body">
                                    <?php if ($model->office_id == 11 || $model->office_id == 12 || $model->office_id == 17):?>
                                        <button class="btn btn-default js-print-btn" data-id="<?=$model->id?>" data-type="propiety_and_privacy-efectivida"><i class="fa fa-print"></i> <?php echo Yii::t('app', 'Aviso de Propiedad Intelectual y Privacidad'); ?></button>
                                    <?php endif; ?>
                                    <?php if ($model->office_id == 11 || $model->office_id == 2):?>
                                        <button class="btn btn-default js-print-btn" data-id="<?=$model->id?>" data-type="agreement"><i class="fa fa-print"></i> <?php echo Yii::t('app', 'Convenio'); ?></button>
                                    <?php endif; ?>
                                    <button class="btn btn-default" data-toggle="modal" data-target="#modal_cover"><i class="fa fa-print"></i> <?php echo Yii::t('app', 'Print Cover'); ?></button>
                                    <!--<button class="btn btn-default js-print-btn" data-id="<?=$model->id?>" data-type="cover"><i class="fa fa-print"></i> <?php echo Yii::t('app', 'Print Cover'); ?></button>-->
                                    <button class="btn btn-default" data-toggle="modal" data-target="#modal_contract"><i class="fa fa-print"></i> <?php echo Yii::t('app', 'Print Contract'); ?></button>
                                    <!--<button class="btn btn-default js-print-btn" data-id="<?=$model->id?>" data-type="contract"><i class="fa fa-print"></i> <?php echo Yii::t('app', 'Print Contract'); ?></button>-->
                                    <button class="btn btn-default js-print-btn" data-id="<?=$model->id?>" data-type="evaluation"><i class="fa fa-print"></i> <?php echo Yii::t('app', 'Print Evaluation'); ?></button>
                                    <button class="btn btn-default js-print-btn" data-id="<?=$model->id?>" data-type="check"><i class="fa fa-print"></i> <?php echo Yii::t('app', 'Print Check List'); ?></button>
                                    <button class="btn btn-default js-print-btn" data-id="<?=$model->id?>" data-type="privacy"><i class="fa fa-print"></i> <?php echo Yii::t('app', 'Print Privacy'); ?></button>
                                    <button class="btn btn-default js-print-btn" data-id="<?=$model->id?>" data-type="viability"><i class="fa fa-print"></i> <?php echo Yii::t('app', 'Viabilidad'); ?></button>
                                    <?php if (  Yii::$app->user->can('Moneyback')):?>
                                        <button class="btn btn-default" data-toggle="modal" data-target="#modal_print"><i class="fa fa-print"></i> <?php echo Yii::t('app', 'Carta Compromiso'); ?></button>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                        <!-- Modal button letter_commitment-->
                        <div id="modal_print" class="modal fade">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5>Confirmation</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-danger" style="margin: 0">
                                            Estás a punto de imprimir la carta compromiso, ¿Estás seguro?
                                        </div>
                                    </div>
                                    <div class="modal-footer" id="print-id">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        <button class="btn btn-danger js-print-btn" data-dismiss="modal" data-id="<?=$model->id?>" data-type="letter_commitment"><i class="fa fa-print"></i> <?php echo Yii::t('app', 'Imprimir Carta Compromiso'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal button cover-->
                        <div id="modal_cover" class="modal fade">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5>Confirmation</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-warning" style="margin: 0;font-size: 20px">
                                            Por favor rectifica con el cliente su nombre completo y que este aparezca correctamente en la impresión.
                                        </div>
                                    </div>
                                    <div class="modal-footer" id="print-id">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        <button class="btn btn-success js-print-btn" data-dismiss="modal" data-id="<?=$model->id?>" data-type="cover"><i class="fa fa-print"></i> <?php echo Yii::t('app', 'He Revisado los Datos'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal button contract-->
                        <div id="modal_contract" class="modal fade">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5>Confirmation</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-warning" style="margin: 0;font-size: 20px">
                                            Por favor rectifica con el cliente su nombre completo y que este aparezca correctamente en la impresión.
                                            <?php

                                            if ($model->lead_status_id == 4 && $total >= 3000){
                                                ?>

                                            <?php
                                            }
                                            else
                                            {
                                                ?>
                                                Este contrato no se puede imprimir ya que no contiene un pago mayor o igual a $3000 y/o no se encuentra en  venta.
                                            <?php
                                            } ?>
                                        </div>
                                    </div>
                                    <div class="modal-footer" id="print-id">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        <?php


                                        if ($model->lead_status_id == 4 || $total >= 3000){
                                            ?>
                                            <button class="btn btn-success js-print-btn" data-dismiss="modal" data-id="<?=$model->id?>" data-type="contract"><i class="fa fa-print"></i> <?php echo Yii::t('app', 'He Revisado los Datos'); ?></button>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            Este contrato no se puede imprimir

                                            <?php
                                        } ?>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal wizard modal from lead information -->
                        <div id="modal_wizard" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal_wizard" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <form method="post" action="index.php?r=sales/lead/update&id=<?=$model->id?>" id="myForm" role="form" enctype="multipart/form-data" data-toggle="validator" accept-charset="utf-8">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modal_wizard_label">Wizard</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                                    </div>
                                    <div class="modal-body">


                                                <!-- SmartWizard html -->
                                                <div id="smartwizard">
                                                    <ul>
                                                        <li><a href="#step-1">Datos Personales<br /><small>Registro de datos personales</small></a></li>
                                                        <li><a href="#step-2">Fuentes de Ingresos<br /><small>Registro de Ingresos</small></a></li>
                                                        <li><a href="#step-3">Referencias<br /><small>Referencias personales</small></a></li>
                                                        <li><a href="#step-4">Linea de Crédito<br /><small>Solicitud de crédito</small></a></li>
                                                    </ul>

                                                    <div>
                                                        <div id="step-1">
                                                            <div id="form-step-0" role="form" data-toggle="validator">
                                                            <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                                                            <input type="hidden" name="lead_id" value="<?php echo $model->id; ?>">
                                                            <input type="hidden" name="wizard_model" values="1">
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
                                                                                'maxlength' => 255,
                                                                                'required' => 'true',
                                                                            ]
                                                                        ],

                                                                        'last_name' => [
                                                                            'type' => Form::INPUT_TEXT,
                                                                            'options' => [
                                                                                'placeholder' => Yii::t('app', 'Enter Last Name') . '...',
                                                                                'maxlength' => 255,
                                                                                'required' => 'true',
                                                                            ]
                                                                        ],

                                                                        'middle_name' => [
                                                                            'type' => Form::INPUT_TEXT,
                                                                            'label' => Yii::t('app', 'Middle Name'),
                                                                            'options' => [
                                                                                'placeholder' => Yii::t('app', 'Enter Middle Name') . '...',
                                                                                'maxlength' => 255,
                                                                                'required' => 'true',
                                                                            ]
                                                                        ],
                                                                        'rfc' => [
                                                                            'type' => Form::INPUT_TEXT,
                                                                            'label' => Yii::t('app', 'RFC'),
                                                                            'options' => [
                                                                                'placeholder' => Yii::t('app', 'Enter RFC') . '...',
                                                                                'maxlength' => 13,
                                                                                'required' => 'true',
                                                                            ]
                                                                        ],
                                                                        'curp' => [
                                                                            'type' => Form::INPUT_TEXT,
                                                                            'label' => Yii::t('app', 'CURP'),
                                                                            'options' => [
                                                                                'placeholder' => Yii::t('app', 'Enter CURP') . '...',
                                                                                'maxlength' => 18,
                                                                                'required' => 'true',
                                                                            ]
                                                                        ],
                                                                        'mobile' => [
                                                                            'type' => Form::INPUT_TEXT,
                                                                            'label' => Yii::t('app', 'Teléfono Movil'),
                                                                            'options' => [
                                                                                'placeholder' => Yii::t('app', 'Enter') . '...',
                                                                                'maxlength' => 18,
                                                                                'required' => 'true',
                                                                            ]
                                                                        ],
                                                                        'phone' => [
                                                                            'type' => Form::INPUT_TEXT,
                                                                            'label' => Yii::t('app', 'Teléfono Fijo'),
                                                                            'options' => [
                                                                                'placeholder' => Yii::t('app', 'Enter') . '...',
                                                                                'maxlength' => 18,
                                                                                'required' => 'true',
                                                                            ]
                                                                        ],
                                                                    ]
                                                                ]);
                                                            } catch (Exception $e) {
                                                                var_dump($e->getMessage());
                                                            }
                                                            ?>
                                                            </div>
                                                        </div>
                                                        <div id="step-2">
                                                            <div id="form-step-1" role="form" data-toggle="validator">
                                                            <?php
                                                            try {
                                                                echo Form::widget([
                                                                    'model' => $model,
                                                                    'form' => $form,
                                                                    'columns' => 3,
                                                                    'attributes' => [
                                                                        'monthly_income' => [
                                                                            'type' => Form::INPUT_TEXT,
                                                                            'label' => Yii::t('app', 'Income'),
                                                                            'options' => [
                                                                                'placeholder' => Yii::t('app', 'Enter Income') . '...',
                                                                                'class' => 'currency',
                                                                                'maxlength' => 255,
                                                                                'required' => 'true',
                                                                            ]
                                                                        ],
                                                                        'monthly_income2' => [
                                                                            'type' => Form::INPUT_TEXT,
                                                                            'label' => Yii::t('app', 'Income2'),
                                                                            'options' => [
                                                                                'placeholder' => Yii::t('app', 'Enter Income') . '...',
                                                                                'class' => 'currency',
                                                                                'maxlength' => 255,
                                                                                'required' => 'true',
                                                                            ]
                                                                        ],
                                                                        'monthly_expenses' => [
                                                                            'type' => Form::INPUT_TEXT,
                                                                            'label' => Yii::t('app', 'Expenses'),
                                                                            'options' => [
                                                                                'placeholder' => Yii::t('app', 'Enter Expenses') . '...',
                                                                                'class' => 'currency',
                                                                                'maxlength' => 255,
                                                                                'required' => 'true',
                                                                            ]
                                                                        ],
                                                                        'economic_dep' => [
                                                                            'type' => Form::INPUT_TEXT,
                                                                            'label' => Yii::t('app', 'Economic Dependents'),
                                                                            'options' => [
                                                                                'placeholder' => Yii::t('app', 'Enter Economic Dependents') . '...',
                                                                                'maxlength' => 255,
                                                                                'required' => 'true',
                                                                            ]
                                                                        ],
                                                                        'home_status' => [
                                                                            'label' => Yii::t('app', 'Home Status'),
                                                                            'type' => Form::INPUT_DROPDOWN_LIST,
                                                                            'options' => [
                                                                                'prompt' => '--' . Yii::t('app', 'Home Status') . '--',
                                                                                'required' => 'true',
                                                                            ],
                                                                            'items' => $leadHomeStatus
                                                                        ],
                                                                        'bureau_status' => [
                                                                            'label' => Yii::t('app', 'Bureau Status'),
                                                                            'type' => Form::INPUT_DROPDOWN_LIST,
                                                                            'options' => [
                                                                                'prompt' => '--' . Yii::t('app', 'Bureau Status') . '--',
                                                                                'required' => 'true',
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
                                                                                'maxlength' => 255,
                                                                                'required' => 'true',
                                                                            ]
                                                                        ],
                                                                        'company_name' => [
                                                                            'type' => Form::INPUT_TEXT,
                                                                            'label' => Yii::t('app', 'Company Name'),
                                                                            'options' => [
                                                                                'placeholder' => Yii::t('app', 'Enter Company Name') . '...',
                                                                                'maxlength' => 255,
                                                                                'required' => 'true',
                                                                            ]
                                                                        ],
                                                                    ]
                                                                ]);
                                                            } catch (Exception $e) {
                                                            }
                                                            ?>
                                                            <h3 class="page-header" style="margin-top: 1em"><?php echo Yii::t('app', 'Complementario: Si cuenta con conyuge o pareja que ayude a incrementar su disponibilidad'); ?></h3>
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
                                                                                'required' => 'true',
                                                                            ],
                                                                            'items' => $leadCivilStatus
                                                                        ],
                                                                        'civil_status_regime' => [
                                                                            'label' => Yii::t('app', 'Civil Status Regime'),
                                                                            'type' => Form::INPUT_DROPDOWN_LIST,
                                                                            'options' => [
                                                                                'prompt' => '--' . Yii::t('app', 'Enter Civil Status Regime') . '--',
                                                                            ],
                                                                            'items' => $leadCivilStatusRegime
                                                                        ],
                                                                        'spouse_job' => [
                                                                            'type' => Form::INPUT_TEXT,
                                                                            'label' => Yii::t('app', 'Spouse Job'),
                                                                            'options' => [
                                                                                'placeholder' => Yii::t('app', 'Enter Spouse Job') . '...',
                                                                                'maxlength' => 255,
                                                                            ]
                                                                        ],
                                                                        'spouse_monthly_income' => [
                                                                            'type' => Form::INPUT_TEXT,
                                                                            'label' => Yii::t('app', 'Spouse Income'),
                                                                            'options' => [
                                                                                'placeholder' => Yii::t('app', 'Enter Spouse Income') . '...',
                                                                                'class' => 'currency',
                                                                                'maxlength' => 255,
                                                                            ]
                                                                        ]
                                                                    ]
                                                                ]);

                                                            } catch (Exception $e) {
                                                            }
                                                            ?>
                                                        </div>
                                                        </div>
                                                        <div id="step-3">
                                                            <div id="form-step-2" role="form" data-toggle="validator">
                                                            <h3 class="page-header" style="margin-top: 1em"><?php echo Yii::t('app', 'Referencia 1'); ?></h3>
                                                            <input type="hidden" name="entity_type" value="lead.ref.1">
                                                            <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">

                                                            <?php
                                                            echo Yii::$app->controller->renderPartial("../../../liveobjects/views/custom/custom_reference", [
                                                                'lead' => $model,
                                                                'entity_type' => 'lead.ref.1'
                                                            ]);
                                                            ?>
                                                            <h3 class="page-header" style="margin-top: 1em"><?php echo Yii::t('app', 'Referencia 2'); ?></h3>
                                                            <input type="hidden" name="entity_type_1" value="lead.ref.2">
                                                            <?php
                                                            echo Yii::$app->controller->renderPartial("../../../liveobjects/views/custom/custom_reference", [
                                                                'lead' => $model,
                                                                'entity_type' => 'lead.ref.2'
                                                            ]);
                                                            ?>
                                                        </div>
                                                        </div>
                                                        <div id="step-4" class="">
                                                            <div id="form-step-3" role="form" data-toggle="validator">
                                                        <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
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
                                                                        'maxlength' => 20
                                                                    ]
                                                                ]
                                                            ]
                                                        ]);

                                                    } catch (Exception $e) {
                                                    }
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group required">
                                                                <label class="control-label">% Comisión</label>
                                                                <input type="text" id="lead-loan_interest1" name="Lead[loan_interest]" value="<?php echo $model->getInterest(); ?> "
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group required">
                                                                <label class="control-label">$ Comisión (monto a cobrar al cliente)</label>
                                                                <input type="text" id="lead-loan_commission1" name="Lead[loan_commission]" class="form-control currency" value="<?php echo $model->loan_commission; ?>" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                                <div class="row">
                                                                    <div class="col-sm-4">
                                                                        <div class="form-group ">
                                                                            <label class="control-label">Qualify</label>
                                                                            <select name="quality" class="form-control">
                                                                                <?php foreach ($qualify as $key => $q) {?>
                                                                                    <option value=<?= $key ?><?php if($key == $model->qualify): ?> selected<?php endif; ?>><?= $q?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                </div></div>
                                                <br>


                                    </div></div>
                                    <div class="modal-footer">
                                        <div style="position: absolute">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                        </div>

                                        <?php
                                        echo Html::submitButton(Yii::t('app', 'Update'), [
                                            'class' => 'btn btn-primary btn-sm  lead_submit',
                                            'onclick' => 'return confirm(\'Estás a punto de actualizar la información. ¿Deseas continuar?\')'
                                        ]);
                                        ?>
                                    </div>
                                </div></form>
                            </div></div></div>
                        <div class="tab-pane fade" id="documents">
                            <br>
                            <div class="row">
                                <?php
                                $docIndex = 0;
                                foreach ($leadDocuments as $docType) {
                                    echo Yii::$app->controller->renderPartial("../../../liveobjects/views/custom/documents", [
                                        'lead' => $model,
                                        'type' => $docType,
                                        'index' => $docIndex
                                    ]);
                                    $docIndex++;
                                }
                                ?>
                            </div>
                            <button type="button" class="btn btn-primary btn-lg btn-block" onclick="$('.savepopup').modal('show');"><i class="fa fa-upload"></i> Cargar archivo</button>
                        </div>
                        <!--documentscustomerservice_custom-->
                        <div class="tab-pane fade" id="documentscustomservice">
                            <br>
                            <div class="row">
                                <?php
                                $docIndex = 0;
                                foreach ($leadCustomerdocuments as $docType) {
                                    echo Yii::$app->controller->renderPartial("../../../liveobjects/views/custom/documentscustomservice", [
                                        'lead' => $model,
                                        'type' => $docType,
                                        'index' => $docIndex
                                    ]);
                                    $docIndex++;
                                }
                                ?>
                            </div>
                            <button type="button" class="btn btn-primary btn-lg btn-block" onclick="$('.savepopup2').modal('show');"><i class="fa fa-upload"></i> Cargar archivo</button>
                        </div>
                        <div class="tab-pane fade" id="notes">
                            <br/>
                            <?php

                            $searchModelNotes = new CommonModel();
                            $dataProviderNotes = $searchModelNotes->searchNotes(Yii::$app->request->getQueryParams(), $model->id, 'lead');

                            echo Yii::$app->controller->renderPartial("../../../liveobjects/views/note/notes-module/notes", [
                                'dataProviderNotes' => $dataProviderNotes,
                                'searchModelNotes' => $searchModelNotes
                            ]);

                            ?>
                            <a href="javascript:void(0)" class="btn btn-success btn-sm"
                               onClick="$('.add-notes-modal').modal('show');"><i
                                        class="glyphicon glyphicon-comment"></i> <?= Yii::t('app', 'New Note') ?></a>
                        </div>
                        <div class="tab-pane fade" id="activity">
                            <br/>
                            <?php

                            $searchModelHistory = new CommonModel();
                            $dataProviderHistory = $searchModelHistory->searchHistory(Yii::$app->request->getQueryParams(), $model->id, 'lead');
                            echo Yii::$app->controller->renderPartial("../../../liveobjects/views/history/history-module/histories", [
                                'dataProviderHistory' => $dataProviderHistory,
                                'searchModelHistory' => $searchModelHistory
                            ]);

                            ?>
                        </div>
                        <div class="tab-pane fade" id="appointments">
                            <br/>
                            <?php

                            $searchAppointment = new CommonModel();
                            $dataProviderAppointment = $searchAppointment->searchAppointments(Yii::$app->request->getQueryParams(), $model->id, 'lead');


                            echo Yii::$app->controller->renderPartial("../../../liveobjects/views/appointment/appointment-model/appointments", [
                                'dataProviderAppointment' => $dataProviderAppointment
                            ]);

                            ?>
                        </div>
                        <div class="tab-pane fade" id="payments">
                            <br/>
                            <?php

                            $searchPayment = new CommonModel();
                            $dataProviderPayment = $searchPayment->searchPayment(Yii::$app->request->getQueryParams(), $model->id, 'lead');

                            echo Yii::$app->controller->renderPartial("../../../liveobjects/views/payment/payment-model/payments", [
                                'dataProviderPayment' => $dataProviderPayment
                            ]);

                            ?>
                        </div>
                        <div class="tab-pane fade" id="paymentsinsurance">
                            <br/>
                            <?php

                            $searchPayment1 = new CommonModel();
                            $dataProviderPayment1 = $searchPayment1->searchPaymentInsurance(Yii::$app->request->getQueryParams(), $model->id, 'lead');

                            echo Yii::$app->controller->renderPartial("../../../liveobjects/views/payment/payment-model/paymentsi", [
                                'dataProviderPayment1' => $dataProviderPayment1
                            ]);

                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php

            if ($model->lead_status_id != LeadStatus::_CONVERTED && Yii::$app->user->can('Lead.Update')) //if lead is converted then disable Update button
            {
            /*echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [

                'class' => $model->isNewRecord ? 'btn btn-success lead_submit' : 'btn btn-primary btn-sm  lead_submit'

            ]);*/
            ?>
        </div>

    </div>
    <?php
}

//echo "</form>";   <!-- this tag was making the tabs font larger & creating issue -->
?>

<?php
$email = $model->email;
include_once(__DIR__ . '/../../../liveobjects/views/file/attachment-module/attachmentae.php');
include_once(__DIR__ . '/../../../liveobjects/views/file/attachment-module/attachmentae2.php');
include_once(__DIR__ . '/../../../liveobjects/views/note/notes-module/noteae.php');
include_once(__DIR__ . '/../../../liveobjects/views/appointment/appointment-model/appointmentae.php');
include_once(__DIR__ . '/../../../liveobjects/views/payment/payment-model/paymentae.php');
include_once(__DIR__ . '/../../../liveobjects/views/payment/payment-model/paymentab.php');
include_once(__DIR__ . '/../../../liveobjects/views/customer/migrate-dialog.php');
include_once(__DIR__ . '/../../../liveobjects/views/customer/migrate-dialog-insurance.php');

?>