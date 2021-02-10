<!--<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link active" href="index.php?r=sales/lead/appointments">Tabla de citas</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="index.php?r=sales/lead/appointments-calendar">Calendario de citas</a>
    </li>

</ul>-->


<?php


use livefactory\models\UnitGenerate;
use yii\helpers\Html;

use kartik\grid\GridView;

use yii\widgets\Pjax;

use livefactory\models\LeadType;
use livefactory\models\Office;
use livefactory\models\LeadSource;
use livefactory\models\LeadStatus;
use livefactory\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


/**

 *

 * @var yii\web\View $this

 * @var yii\data\ActiveDataProvider $dataProvider

 * @var common\models\search\Lead $searchModel

 */

$this->title = Yii::t ( 'app', 'Appointments' );

$this->params ['breadcrumbs'] [] = $this->title;
$pageView = 'List View';
function getPhone($lead_id){
    $sql ="select * from tbl_contact where entity_type='lead' and entity_id=$lead_id and is_primary='1'";
    $connection = \Yii::$app->db;
    $command=$connection->createCommand($sql);
    $dataReader=$command->queryOne();
    return $dataReader['phone'];
}



function statusLabel($status)
{
    $label = $status->label;
    $status = $status->status;
    if (in_array(strtolower($status), array(
        'converted',
        'business',
        'completed',
        'low'
    ))) {
        $label = "<span class=\"label label-primary\">" . $label . "</span>";
    } else
        if (in_array(strtolower($status), array(
            'acquired',
            'in process',
            'medium',
            'p2',
            'p3'
        ))) {
            $label = "<span class=\"label label-success\">" . $label . "</span>";
        } else
            if (in_array(strtolower($status), array(
                'individual',
                'lowest',
                'recycled',
                'opportunity'
            ))) {
                $label = "<span class=\"label label-info\">" . $label . "</span>";
            } else
                if (in_array(strtolower($status), array(
                    'lost',
                    'needs action',
                    'highest',
                    'dead'
                ))) {
                    $label = "<span class=\"label label-danger\">" . $label . "</span>";
                } else
                    if (in_array(strtolower($status), array(
                        'student',
                        'on hold',
                        'high',
                        'new'
                    ))) {
                        $label = "<span class=\"label label-warning\">" . $label . "</span>";
                    } else {
                        $label = "<span class=\"label label-default\">" . $label . "</span>";
                    }
    return $label;
}

$by_office = Yii::$app->user->can('Office.NoLimit') == true;
$filter = Yii::$app->request->getQueryParam('Lead');

?>

<?php require 'filterappointments.php'; ?>

<div class="row">



    <?php Yii::$app->request->enableCsrfValidation = true; ?>

    <?php
    $payed = [
        1 => "Yes",
        2 => "No"
    ];

    Pjax::begin (['id'=> 'data_appointments']);
    try {
        echo GridView::widget([

            'dataProvider' => $dataProvider,

            'filterModel' => $searchModel, 'responsive' => true, 'responsiveWrap' => false,

            'pjax' => true,

            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                ['class' => '\kartik\grid\CheckboxColumn'],

                [
                    'attribute' => $by_office ? 'office_id' : 'c_control',
                    'label' => 'Folio',
                    'width' => '5%',
                    'format' => 'raw',
                    /*'filterType' => $by_office ? GridView::FILTER_SELECT2 : null,
                    'filter' => $by_office ? ArrayHelper::map(Office::find()->where("active=1")->orderBy('code')->asArray()->all(), 'id', 'description') : null,
                    'filterWidgetOptions' => [
                        'options' => [
                            'placeholder' => 'Todas las oficinas...'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ]
                    ],
                    'filterInputOptions' => [
                            'selected' => 2
                    ],*/
                    'value' => function ($model, $key, $index, $widget) {
                        return $model->lead->c_control;
                    }
                ],

                [
                    'attribute' => 'lead_name',
                    'label' => 'Nombre',
                    'width' => '20%',
                    'format' => 'raw',
                    'value' => function ($model, $key, $index, $widget) {
                        return '<a href="index.php?r=sales/lead/view&id=' . $model->entity_id . '">' . $model->lead->lead_name . '</a>';
                    }
                ],
                [
                        'label' => 'Teléfono',
                        'format' => 'raw',
                        'width' => '5%',
                        'value' => function ($model, $key, $index, $widget) {
                                return $model->lead->mobile;
                        }
                ]
                ,
                [
                    'attribute' => 'lead_status_id',
                    'label'=>'Estado de lead',
                    /*'filterType' => GridView::FILTER_SELECT2,*/
                    'format' => 'raw',
                    'width' => '5%',
                    /*'filter' => ArrayHelper::map(LeadStatus::find()->where("active=1")->orderBy('sort_order')->asArray()->all(), 'id', 'label'),
                    'filterWidgetOptions' => [
                        'options' => [
                            'placeholder' => Yii::t('app', 'All...')
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ]
                    ],*/

                    'value' => function ($model, $key, $index, $widget) {
                        if (isset ($model->lead->leadStatus) && !empty ($model->lead->leadStatus->label))
                            return statusLabel($model->lead->leadStatus);

                    }
                ],
                [
                    'attribute' => 'lead_source_id',
                     'label' => 'Medio',
                    'filterType' => GridView::FILTER_SELECT2,
                    'format' => 'raw',
                    'width' => '10%',
                    'filter' => ArrayHelper::map(LeadSource::find()->where("active=1")->orderBy('sort_order')->asArray()->all(), 'id', 'label'),
                    'filterWidgetOptions' => [
                        'options' => [
                            'placeholder' => Yii::t('app', 'All...')
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ]
                    ],

                    'value' => function ($model, $key, $index, $widget) {
                        // var_dump($model->user);
//                        var_dump($model->lead->leadSource);
                        if (isset ($model->lead->leadSource) && !empty ($model->lead->leadSource->label))
                            return $model->lead->leadSource->label;

                    }

                ],
                [
                    'attribute' => 'appointment_status',
                    'label' => 'Estado de Cita',
                    //'filterType' => GridView::FILTER_SELECT2,
                    'format' => 'raw',
                    'width' => '10%',
                    //'filter' => ArrayHelper::map(LeadStatus::find()->where("active=1")->orderBy('sort_order')->asArray()->all(), 'id', 'label'),
                    /*'filterWidgetOptions' => [
                        'options' => [
                            'placeholder' => Yii::t('app', 'All...')
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ]
                    ],*/

                    'value' => /*function ($model, $key, $index, $widget) use ($promises) {
                        //$appointments = \livefactory\models\Appointment::find()->where('entity_id = ' . $model->id )->orderBy('date ASC')->one();
                        $status = '';
                        $current = strtotime(date('Y-m-d'));
                        $time = strtotime($promises[$model->id]['date']);
                        $class = $current > $time && $promises[$model->id]['status'] == '-1' ? 'default':($promises[$model->id]['status']=='0' ? 'primary': ($promises[$model->id]['status']=='1' ? 'success':'danger' )) ;
                        $label = $current > $time && $promises[$model->id]['status'] == '-1' ?  'Vencida': ($promises[$model->id]['status']=='0' ? 'Vigente': ($promises[$model->id]['status']=='1' ? 'Concretada' : 'No concretada' ));
                        $status .= '<span class="label label-'.$class.'">'.$label.'</span>';
                        $status .= '<br><small>' .date('d/m/Y',strtotime($promises[$model->id]['date'])) . '</small><br>';

                        return $status;
                    }*/

                        function ($model, $key, $index, $widget) {
                            $status = '';
                            $current = time();
                            $time = strtotime($model->date);
                            $class = $current > $time && $model['status'] == '-1' ? 'default':($model->status == '0' ? 'primary': ($model->status == '1' ? 'success':'danger' )) ;
                            $label = $current > $time && $model['status'] == '-1' ?  'Vencida': ($model->status == '0' ? 'Vigente': ($model->status == '1' ? 'Concretada' : 'No concretada' ));
                            $status .= '<span class="label label-'.$class.'">'.$label.'</span>';

                            return $status;
                        }
                ],
                [
                    'attribute' => 'appointment_date',
                    'label' => 'Fecha & Hora',
                    'width' => '15%',
                    'value' => function ($model, $key, $index, $widget) {
                        return date('d/m/Y', strtotime($model->date)) . ' ' . $model->time;
                    }
                ],
                [
                    'attribute' => 'loan_amount',
                    'label' => 'Monto (colocado)',
                    'width' => '10%',
                    'value' => function ($model, $key, $index, $widget) {
                        return '$' . number_format($model->lead->loan_amount, 2);
                    }
                ],
                [
                    'attribute' => 'loan_commission',
                    'label' => 'Venta',
                    'width' => '10%',
                    'value' => function ($model, $key, $index, $widget) {
                        return '$' . number_format($model->lead->loan_commission, 2);
                    }
                ],
                [
                    'attribute' => 'payments',
                    'label' => 'Cobrado',
                    'width' => '10%',
                    'value' => function ($model, $key, $index, $widget) {
//                        $payments = \livefactory\models\Payment::find()->where('entity_id = ' . $model->lead->id)->asArray()->all();
                        $appointments = \livefactory\models\Appointment::find()->where('entity_id = ' . $model->lead->id)->orderBy('date ASC')->asArray()->all();
                        $appointments_total = count($appointments);
                        $totala = 0;
                        $total = 0;
                            foreach ($appointments as $appointment)
                            {

                                if ($appointment['id'] == $model->id)
                                {
                                    $appointmentview = $totala;
                                }
                                $totala++;
                            }
                            $appointmentid = $appointments[$appointmentview];

                                if ($appointments_total == 1){
                                    $payments = \livefactory\models\Payment::find()->where('entity_id = ' . $model->lead->id . ' and date >= "' . $appointmentid['date'] . '"')->orderBy('date ASC')->asArray()->all();
                                }
                                else
                                {
                                    if ($appointments_total == $appointmentview){
                                        $payments = \livefactory\models\Payment::find()->where('entity_id = ' . $model->lead->id . ' and date >= "' . $appointmentid['date'] . '"')->orderBy('date ASC')->asArray()->all();
                                    }
                                    else{
                                        if ($appointments[$appointmentview + 1] != null){
                                            $appointmentidlast = $appointments[$appointmentview + 1] ;
//                                            var_dump($appointmentidlast);
                                            $payments = \livefactory\models\Payment::find()->where('entity_id = ' . $model->lead->id . ' and date >= "' . $appointmentid['date'] . '" and date <= "' . $appointmentidlast['date'] . '"')->orderBy('date ASC')->asArray()->all();

                                        }
                                        else{
                                            $payments = \livefactory\models\Payment::find()->where('entity_id = ' . $model->lead->id . ' and date >= "' . $appointmentid['date'] . '"')->orderBy('date ASC')->asArray()->all();
                                        }
                                    }
//
                                }
//                            var_dump($totala, $appointmentview);
//                            var_dump($appointments[$appointmentview]);
                        if (count($payments)) {


                            foreach ($payments as $payment) {
                                $total += $payment['amount'];
                            }
                        }



                        return '$' . number_format($total, 2);
                    }
                ],
                [
                    'attribute' => 'appointment_amount',
                    'label' => 'Promesa',
                    //'filterType' => GridView::FILTER_SELECT2,
                    'format' => 'raw',
                    'width' => '10%',
                    //'filter' => ArrayHelper::map(LeadStatus::find()->where("active=1")->orderBy('sort_order')->asArray()->all(), 'id', 'label'),
                    /*'filterWidgetOptions' => [
                        'options' => [
                            'placeholder' => Yii::t('app', 'All...')
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ]
                    ],*/

                    'value' => function ($model, $key, $index, $widget) use ($promises) {
                        //$appointments = \livefactory\models\Appointment::find()->where('entity_id = ' . $model->id )->orderBy('date ASC')->one();

                        return "$".number_format($model->amount,2);
                    }
                ],
                /*[
                    'attribute' => 'lead_source_id',
                    // 'label' => 'Source',
                    'filterType' => GridView::FILTER_SELECT2,
                    'format' => 'raw',
                    'width' => '10%',
                    'filter' => ArrayHelper::map(LeadSource::find()->where("active=1")->orderBy('sort_order')->asArray()->all(), 'id', 'label'),
                    'filterWidgetOptions' => [
                        'options' => [
                            'placeholder' => Yii::t('app', 'All...')
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ]
                    ],

                    'value' => function ($model, $key, $index, $widget) {
                        // var_dump($model->user);
                        // var_dump($model->leadStatus);
                        if (isset ($model->lead->leadSource) && !empty ($model->lead->leadSource->label))
                            return $model->lead->leadSource->label;
                    }
                ],*/
                [
                    'attribute' => 'user_id',
                    'label' => 'Capturista',
                    'filterType' => GridView::FILTER_SELECT2,
                    'format' => 'raw',

                    'width' => '10%',
                    'filter' => ArrayHelper::map(User::find()->where("id IN (SELECT user_id FROM tbl_lead WHERE user_id IS NOT NULL GROUP BY user_id) and active=1".$office_user_filter)->asArray()->all(),'id',function($user){
                        return $user['alias'];
                    }),

                    'filterWidgetOptions' => [
                        'options' => [
                            'placeholder' => Yii::t('app', 'All...')
                        ],

                        'pluginOptions' => [
                            'allowClear' => true
                        ]
                    ],
                    'value' => function ($model, $key, $index, $widget) {
                        if (isset($model->lead->agent)) {
                            return ' <a  href="javascript:void(0)" onClick="showPopup(\'' . $model->lead->agent->id . '\')" style="margin-bottom:5px">' . $model->lead->agent->alias . ' </a>';
                        }
                    }
                ],
                //[
                 // 'attribute' => 'user_id',
                  //  'label' => 'Generador de Cita',
                   // 'filterType' => GridView::FILTER_SELECT2,
                    //'format' => 'raw',
                    //'width' => '10%',
                    //'filter' =>  ArrayHelper::map(User::find()->where("id IN (SELECT user_id FROM tbl_appointment WHERE user_id IS NOT NULL GROUP BY user_id) and active=1".$office_user_filter)->asArray()->all(),'id',function($user){
                    //    return $user['alias'];
                    //}),
                    //'filterWidgetOptions' => [
                     //   'options' => [
                   //         'placeholder' => Yii::t('app', 'All...')
                   //     ],

                  //      'pluginOptions' => [
                  //          'allowClear' => true
                 //       ]
                //    ],
               //     'value' => function ($model, $key, $index, $widget) {
               //         if (isset($model->lead->agent)) {
                //            return ' <a  href="javascript:void(0)" onClick="showPopup(\'' . $model->user->id . '\')" style="margin-bottom:5px">' . $model->user->alias . ' </a>';
                //        }
                 //   }
              //  ],
                [
                    'attribute' => 'user_id',
                    'label' => 'Nombre Generador de Cita',
                    'value' => function ($model, $key, $index, $widget) {
                        if (isset($model->lead->agent)) {
                            return  $model->user->first_name . " " . $model->user->last_name . " " . $model->user->middle_name;
                        }
                    }
                ],
                [
                    'attribute' => 'user_id',
                    'label' => 'Usuario de Generador de Cita',
                    'value' => function ($model, $key, $index, $widget) {
                        if (isset($model->lead->agent)) {
                            return  $model->user->username ;
                        }
                    }
                ],
                [
                    'label' => 'Unidad Generadora',
                    'format' => 'raw',
                    'width' => '5%',
                    'value' => function ($model, $key, $index, $widget) {
                        $ownerGenerate = User::findOne($model->user_id);
                        $unitGenerate = UnitGenerate::findOne($ownerGenerate->unit_generate);
                        if ($ownerGenerate->unit_generate == 0)
                            return '';
                        else
                            return $unitGenerate->name ;
                    }
                ],
                [
                  'attribute' => 'added_at',
                    'label' => 'Fecha de Generación de Cita',
                    'value' => function ($model , $key, $index, $widget){
                        return date('d/m/Y H:i ',$model->added_at);
                    }
                ],
                [
                  'attribute' => 'updated_at',
                  'label' => 'Fecha de Actualización de Cita',
                  'value' => function ($model, $key, $index, $widget){
                        $date = '';
                        if ($model->updated_at != null)
                        {
                            $date = date('d/m/Y H:i', $model->updated_at);
                        }

                        return $date;
                  }
                ],
                [
                    'attribute' => 'appointment_type',
                    'label' => 'Tipo de cita',
                    'value' => function ($model, $key, $index, $widget){
                        $type_appointment = [
                            0 => 'En Oficina' ,
                            1 => 'En llamada'
                        ];

                        return $type_appointment[$model->type];
                    }
                ],
                [
                    'attribute' => 'lead_owner_id',
                    'label' => Yii::t('app', 'Owner'),
                    'filterType' => GridView::FILTER_SELECT2,
                    'format' => 'raw',

                    'width' => '10%',
                    'filter' => ArrayHelper::map(User::find()->orderBy('first_name')
                    ->where("active=1" . (Yii::$app->user->can('Office.NoLimit') ? '' : " and office_id = " . Yii::$app->user->identity->office_id))
                    ->asArray()
                    ->all(), 'id', function ($user, $defaultValue) {
                    $username = $user['username'] ? $user['username'] : $user['email'];
                    return $user['alias'] ;
                }),

                    'filterWidgetOptions' => [
                        'options' => [
                            'placeholder' => Yii::t('app', 'All...')
                        ],

                        'pluginOptions' => [
                            'allowClear' => true
                        ]
                    ],
                    'value' => function ($model, $key, $index, $widget) {
                        if (isset($model->lead->user) && !empty($model->lead->user->first_name)) {
                            $username = $model->lead->user->username ? $model->lead->user->username : $model->lead->user->email;
                            return ' <a  href="javascript:void(0)" onClick="showPopup(\'' . $model->lead->user->id . '\')" style="margin-bottom:5px">' . $model->lead->user->alias . ' </a>';
                        }
                    }
                ],

                [
                    'class' => '\kartik\grid\ActionColumn',

                    'template' => '{update} {delete}',
                    'contentOptions' => ['style' => 'width:50px;'],
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl([
                                'sales/lead/view',
                                'id' => $model->id
                            ]), [
                                'title' => Yii::t('app', 'Edit')
                            ]);
                        },
                        'delete' => function ($url, $model) {
                            $view = isset($_GET['view']) && $_GET['view'] != '' ? '&view=' . $_GET['view'] : '';
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['sales/lead/delete', 'id' => $model->id . $view]), [
                                'title' => Yii::t('app', 'Delete'),
                                'data' => [
                                    'method' => 'post',
                                    'confirm' => Yii::t('app', 'Are you sure?')],
                            ]);
                        }
                    ]
                ]


            ],

            'responsive' => true,
            'hover' => true,
            'condensed' => true,
            'floatHeader' => false,

            'panel' => [

                'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> ' . Yii::t('app', 'Leads') . ' </h3>',

                'type' => 'info',

                /*'before' => '<form action=""  method="post" name="frm">
                <input type="hidden" name="_csrf" value="'.$csrf.'">
<input type="hidden" name="multiple_del" value="true">'.Html::a ( '<i class="glyphicon glyphicon-plus"></i> '.Yii::t ( 'app', 'Add' ), [
                        'create'
                ], [
                        'class' => 'btn btn-success  btn-sm'
                ] ).' <a href="javascript:void(0)" onClick="all_del()" class="btn btn-danger  btn-sm"><i class="glyphicon glyphicon-trash"></i> '.Yii::t ( 'app', 'Delete Selected' ).'</a>',*/

                'before' => '<form action=""  method="post" name="frm">
                                <input type="hidden" name="_csrf" value="' . $csrf . '">
   <input type="hidden" name="multiple_del" value="true">' . Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'Add Lead'), [
                        'create'
                    ], [
                        'class' => 'btn btn-success  btn-sm'
                    ]) . ' <a href="javascript:void(0)" onClick="all_del()" class="btn btn-danger hide btn-sm"><i class="glyphicon glyphicon-trash"></i> ' . Yii::t('app', 'Delete Selected') . '</a>',
                'after' => '</form>' . Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'Reset List'), [
                        'appointments'
                    ], [
                        'class' => 'btn btn-info  btn-sm'
                    ]),

                'showFooter' => false
            ]
        ]);
    } catch (Exception $e) {
    }

    Pjax::end ();
    ?>

</div>


<script src="../../vendor/bower/jquery/dist/jquery.js"></script>

<script>
    function setCookie(cname,cvalue,exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires=" + d.toGMTString();
        document.cookie = cname+"="+cvalue+"; "+expires;
    }
    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
    var pageView = '<?= !empty($_GET['view'])?$_GET['view']: Yii::$app->params['DEFAULT_SEARCH_PAGE_VIEW']?>';
    var view = '<?=Yii::$app->params['DEFAULT_SEARCH_PAGE_VIEW'] =='List View'?'gridlist':'box' ?>';
    function all_del(){

        var r = confirm("<?=Yii::t ('app','Are you Sure!')?>");

        if (r == true) {

            document.frm.submit()

        } else {


        }

    }
    var start_page=1;
    $(document).ready(function(){
        $(document).on('click','.holder > a',	function(){
            setTimeout(function(){
                setCookie("startPage", $('a.jp-current').text(), 30);
            },1000);

        })
        start_page = getCookie("startPage") == "" ?1:getCookie("startPage");
        if(<?=count($dataProviderBox)?> <= 9){
            start_page = 1;
        }

        $('.box_btn').click(function(){
            document.location.href='index.php?r=sales/lead/index&view=Tile View';
        })

        $('.list_btn').click(function(){
            document.location.href='index.php?r=sales/lead/index&view=List View';
        })


        $('a[data-toggle="tab"]').on('shown.bs.tab', function () {

            //save the latest tab; use cookies if you like 'em better:

            //	localStorage.setItem('lastTab_leadview', $(this).attr('href'));

        });



        //go to the latest tab, if it exists:

        var lastTab_leadview = localStorage.getItem('lastTab_leadview');

        if ($('a[href="' + lastTab_leadview + '"]').length > 0) {

            $('a[href="' + lastTab_leadview + '"]').tab('show');

        }

        else

        {

            // Set the first tab if cookie do not exist

            $('a[data-toggle="tab"]:first').tab('show');

        }

        $('.contact-box').each(function() {

            animationHover(this, 'pulse');

        });

        var maxVal=0

        $('.contact-box').each(function(){

            if($(this).outerHeight()>maxVal){

                maxVal=	$(this).outerHeight();

            }

        })

        $('.contact-box').each(function(){

            $(this).css({'height':maxVal});

        })

        $("div.holder").jPages({

            containerID : "customer_div",

            perPage : 9,
            startPage : start_page,
            delay : 20

        });

        //	setTimeout(function(){
        /*if(view=='gridlist'){
            $('.gridlist').show();
            $('.box').hide();
        }else{
            $('.gridlist').hide();
            $('.box').show();
        }*/


        //},1000);

    });

</script>

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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="gridSystemModalLabel"><?=Yii::t('app', 'User Detail')?></h4>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>