<?php


use yii\db\Query;
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

$this->title = Yii::t ( 'app', 'Leads' );

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

$by_office = Yii::$app->user->can('Office.NoLimit')  == true  ;
$filter = Yii::$app->request->getQueryParam('Lead');
$lead = Yii::$app->request->getQueryParam('Lead');
$office_user_filter = ! $by_office ? 'AND office_id = ' . Yii::$app->user->identity->office_id : ($filter && $filter['office_id'] ? 'AND office_id = ' . $filter['office_id'] : '');
// Managers 133 , 226 , 224 , 225 , 227,
$commercial = [133 , 226 , 224 , 225 , 227,173, 228];
foreach (User::find()->join('LEFT JOIN', 'auth_assignment', 'auth_assignment.user_id = tbl_user.id')
             ->where("auth_assignment.item_name = 'Commercial.Manager'")
             ->asArray()->all() as $key => $item) {
    $commercial[] = (int) $item['id'];
}
// owners
$owner = [173,228];
foreach (User::find()->join('LEFT JOIN', 'auth_assignment', 'auth_assignment.user_id = tbl_user.id')
             ->where("auth_assignment.item_name = 'Commercial.Manager' and tbl_user.active = 1")
             ->asArray()->all() as $key => $item) {
    $owner[] = (int) $item['id'];
}

?>

<?php require 'filter.php'; ?>

<div class="row">
    <div class="col-sm-12">

            <?php Yii::$app->request->enableCsrfValidation = true;


                ?>

                <?php
                $payed = [
                    1 => "Yes",
                    2 => "No"
                ];

                Pjax::begin();

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
                                'filterType' => $by_office ? GridView::FILTER_SELECT2 :  null,
                                'filter' => $by_office ? ArrayHelper::map(Office::find()->where("active=1")->orderBy('code')->asArray()->all(), 'id', 'description') : null,
                                'filterWidgetOptions' => [
                                    'options' => [
                                        'placeholder' => 'Todas las oficinas...'
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ]
                                ],
                                'value' => function ($model, $key, $index, $widget) {
                                    return $model->c_control;
                                }
                            ],

                            [
                                'attribute' => 'lead_name',
                                'label' => 'Nombre',
                                'width' => '20%',
                                'format' => 'raw',
                                'value' => function ($model, $key, $index, $widget) {
                                    return '<a href="index.php?r=sales/lead/view&id=' . $model->id . '">' . $model->lead_name . '</a>';
                                }
                            ],

                            [
                                'attribute' => 'mobile',
                                'width' => '10%',
                            ],

                            /*[
                                    'attribute' => 'lead_type_id',
                                   // 'label' => 'Type',
                                    'filterType' => GridView::FILTER_SELECT2,
                                    'format' => 'raw',
                                    'width' => '10%',
                                    'filter' => ArrayHelper::map ( LeadType::find ()->where("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' ),
                                    'filterWidgetOptions' => [
                                            'options' => [
                                                    'placeholder' => Yii::t('app', 'All...')
                                            ],
                                            'pluginOptions' => [
                                                    'allowClear' => true
                                            ]
                                    ],

                                    'value' => function ($model, $key, $index, $widget)
                                    {
                                        if (isset ( $model->leadType ) && ! empty ( $model->leadType->label ))
                                            return $model->leadType->label;
                                    }
                            ],*/

                            [
                                'attribute' => 'lead_status_id',
                                'filterType' => GridView::FILTER_SELECT2,
                                'format' => 'raw',
                                'width' => '5%',
                                'filter' => ArrayHelper::map(LeadStatus::find()->where("active=1")->orderBy('sort_order')->asArray()->all(), 'id', 'label'),
                                'filterWidgetOptions' => [
                                    'options' => [
                                        'placeholder' => Yii::t('app', 'All...')
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ]
                                ],

                                'value' => function ($model, $key, $index, $widget) {
                                    if (isset ($model->leadStatus) && !empty ($model->leadStatus->label))
                                        return statusLabel($model->leadStatus);

                                }
                            ],

                            [
                                'attribute' => 'loan_amount',
                                'label' => 'Monto (colocado)',
                                'width' => '10%',
                                'value' => function ($model, $key, $index, $widget) {
                                    return '$' . number_format($model->loan_amount, 2);
                                }
                            ],

                            [
                                'attribute' => 'loan_commission',
                                'label' => 'Venta',
                                'width' => '10%',
                                'value' => function ($model, $key, $index, $widget) {
                                    return '$' . number_format($model->loan_commission, 2);
                                }
                            ],

                            [
                                'attribute' => 'payments',
                                'label' => 'Cobrado',
                                'width' => '10%',
                                'value' => function ($model, $key, $index, $widget) {
                                    $payments = \livefactory\models\Payment::find()->where('entity_id = ' . $model->id)->asArray()->all();
                                    $total = 0;
                                    if (count($payments)) {
                                        foreach ($payments as $payment) {
                                            $total += $payment['amount'];
                                        }
                                    }

                                    return '$' . number_format($total, 2);
                                }
                            ],

                            /*[
                                'attribute' => 'payed',
                                'filterType' => GridView::FILTER_SELECT2,
                                'format' => 'raw',
                                'width' => '10%',
                                'filter' => $payed,
                                'filterWidgetOptions' => [
                                    'options' => [
                                        'placeholder' => Yii::t('app', 'All...')
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ]
                                ],

                                'value' => function ($model, $key, $index, $widget) {
                                    if ($model->payed) {
                                        return '<span class="label label-primary">' . Yii::t('app', 'Yes') . '</span>';
                                    } else {
                                        return '<span class="label label-default">' . Yii::t('app', 'No') . '</span>';
                                    }

                                }
                            ],*/

                            [
                                'attribute'=>'lead_days',
                                'label'=>'Días de incubación',
                                'format' => 'raw',
                                'width'=>'10%',
                                'value'=>function($model,$key,$index,$widget){

                                    if ($model->lead_status_id != 4)
                                        return 'N/A';


                                    $day_added= new DateTime(date('Y-m-d',$model->added_at));
                                    $day_converted=new DateTime(date('Y-m-d',$model->converted_at));
                                    $dif=$day_added->diff($day_converted);
                                    //$label ='<span >'.$day_added->format('d/m/Y') . '</span><br>';
                                    $label = $dif->format('%a días');
                                    return $label;
                                }
                            ],
                            [
                                'label' => 'Dias de inactividad',
                                'value' => function($model, $key, $index, $widget){
                                    $query = (new Query())->select('added_at')->from('tbl_history');
                                    $query->where(' entity_id = ' . $model->id )->orderBy('id DESC')->limit(1);
                                    foreach ($query->all() as $key=>$da)
                                    {
                                        $history_date= $da;
                                    }
                                    $history_date = new DateTime(date('Y-m-d',$history_date['added_at']));
                                    $date_now = new DateTime(date('Y-m-d'));

                                    $dif = $history_date->diff($date_now);

                                    $label = $dif->format('%a días');

                                    return $label;
                                }
                            ],

                            [
                                'attribute' => 'user_id',
                                'label' => 'Capturista',
                                'filterType' => GridView::FILTER_SELECT2,
                                'format' => 'raw',

                                'width' => '10%',
                               /* '_filter' => ArrayHelper::map(User::find()->where("id IN (SELECT user_id FROM tbl_lead WHERE user_id IS NOT NULL GROUP BY user_id) and active=1 ".$office_user_filter )->union('select * from tbl_user where id=173')->union('select * from tbl_user WHERE id IN ('.implode($agents_capturists).')')->asArray()->all(),'id',function($user){
                                    return $user['alias'];
                                }),*/

                                'filter' => ArrayHelper::map(User::find()->where("id IN (" . implode(',', $commercial) .") OR id IN (select auth_assignment.user_id from auth_item,auth_assignment where (auth_item.type=2 and auth_assignment.item_name=auth_item.name and auth_assignment.user_id=tbl_user.id)  and (auth_item.name = 'Sales Person' OR auth_item.name = 'Sales Manager' OR auth_item.name = 'Commercial.Manager' OR auth_item.name = 'Capturist' OR auth_item.name = 'Capturist2')) and active=1 " . $office_user_filter)->asArray()->all(),'id',function($user){
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
                                    if (isset($model->agent)) {
                                        return ' <a  href="javascript:void(0)" onClick="showPopup(\'' . $model->agent->id . '\')" style="margin-bottom:5px">' . $model->agent->alias .'</a>';
                                    }
                                }
                            ],

                            [
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
                                     //var_dump($model->leadStatus);
                                    if (isset ($model->leadSource) && !empty ($model->leadSource->label))
                                        return $model->leadSource->label;
                                }
                            ],
                            [
                              'attribute' => 'product_id',
                                'label' => 'Tipo de Crédito',
                                'value' => function ($model, $key, $index, $widget){
                                    $loan =\livefactory\models\Loan::findOne($model->product_id);
                                    return $loan['description'];
                                }
                            ],

                            /*[
                            'attribute' => 'first_name',
                            'width' => '10%',
                            ],

                            [
                            'attribute' => 'last_name',
                            'width' => '10%',
                            ],*/

                            // 'phone',
                            [
                                'attribute' => 'added_at',
                                'label' => 'Fecha',
                                /*'filterType' => GridView::FILTER_DATE,
                                'filterWidgetOptions' => [
                                    'pluginOptions' => [
                                        'format' => 'yyyy-mm-dd ',
                                        'autoclose' => true,
                                        'todayHighlight' => true,
                                    ]
                                ],*/
                                'value' => function($model) {
                                    return date('d/m/Y h:i a', $model->added_at);
                                }
                            ],

                            [
                                'attribute' => 'lead_owner_id',
                                'label' => Yii::t('app', 'Owner'),
                                'filterType' => GridView::FILTER_SELECT2,
                                'format' => 'raw',

                                'width' => '10%',
                                /*'filter' => ArrayHelper::map(User::find()->orderBy('alias')
                                    ->where( ! $by_office ? 'office_id = ' . Yii::$app->user->identity->office_id : ($filter && $filter['office_id'] ? 'office_id = ' . $filter['office_id'] : '1'))->andWhere('active=1')->union('select * from tbl_user where id=173')
                                    ->asArray()
                                    ->all(), 'id', function ($user, $defaultValue) {
                                    $username = $user['username'] ? $user['username'] : $user['email'];
                                    return  $user['alias'];
                                }),*/
                                'filter' => ArrayHelper::map(User::find()->where("id IN (" . implode(',', $owner) .") OR id IN (select auth_assignment.user_id from auth_item,auth_assignment where (auth_item.type=2 and auth_assignment.item_name=auth_item.name and auth_assignment.user_id=tbl_user.id)  and (auth_item.name = 'Sales Person' OR auth_item.name = 'Sales Manager' OR auth_item.name = 'Commercial.Manager')) and active=1 " . $office_user_filter)->asArray()->all(),'id',function($user){
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
                                    if (isset($model->user) && !empty($model->user->first_name)) {
                                        $username = $model->user->alias ? $model->user->alias : $model->user->email;
                                        return ' <a  href="javascript:void(0)" onClick="showPopup(\'' . $model->user->id . '\')" style="margin-bottom:5px">' . $model->user->alias .  ' </a>';
                                    }
                                }
                            ],


                            'covid19' => [
                                'attribute' => 'covid19',
                                'label' => 'Flag'
                            ],

                            // 'created_at',

                            // 'updated_at',

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
   <input type="hidden" name="multiple_del" value="true">' . Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'Add'), [
                                    'create'
                                ], [
                                    'class' => 'btn btn-success  btn-sm'
                                ]) . ' <a href="javascript:void(0)" onClick="all_del()" class="btn btn-danger  btn-sm"><i class="glyphicon glyphicon-trash"></i> ' . Yii::t('app', 'Delete Selected') . '</a>',
                            'after' => '</form>' . Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'Reset List'), [
                                    'index'
                                ], [
                                    'class' => 'btn btn-info  btn-sm'
                                ]),

                            'showFooter' => false
                        ]
                    ]);
                } catch (Exception $e) {
                }

                Pjax::end();
            ?>
    </div>
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