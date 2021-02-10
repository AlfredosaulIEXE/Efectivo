<?php



use yii\helpers\Html;

use kartik\grid\GridView;

use yii\widgets\Pjax;

use livefactory\models\Country;

use livefactory\models\State;

use livefactory\models\City;


use livefactory\models\User;


use livefactory\models\User as UserDetail;

use yii\helpers\ArrayHelper;
function isConcreted($id){
    $sql="select * from  tbl_appointment  where id='$id' and status=1";
    $connection = \Yii::$app->db;
    $command=$connection->createCommand($sql);
    $appointment=$command->queryAll();
    return count($appointment);
}
?>

<?php
$_POST['at']=$dataProviderAppointment->count;
if (Yii::$app->request->getQueryParam('nodate'))
    echo '<div class="alert alert-warning"><h4>Opps!</h4> Antes de crear una nueva cita debes calificar las citas activas de este cliente.</div>';

Yii::$app->request->enableCsrfValidation = true;

$csrf=$this->renderDynamic('return Yii::$app->request->csrfToken;');

Pjax::begin(); echo GridView::widget([

    'dataProvider' => $dataProviderAppointment,
    //indicated customer
    'rowOptions'=>function($model)  {

        $user_id = (int) $model->user_id;

        if (empty($user_id))
            return '';
        //$sql="SELECT username, email FROM users";
        $sql = "select auth_assignment.item_name , auth_assignment.user_id from auth_item,auth_assignment where auth_item.type=2 and auth_assignment.item_name=auth_item.name and auth_assignment.user_id=$user_id";
        $connection = \Yii::$app->db;
        $command=$connection->createCommand($sql);
        $dataReader=$command->queryOne();

        //var_dump($dataReader['item_name'] , $model->user_id);exit;
        if($dataReader['item_name'] == "Admin" || $dataReader['item_name'] == 'Customer.Director' || $dataReader['item_name'] == 'Customer.Service') {

            return ['class' => 'warning'];
        }

    },

    'responsive' => true,'responsiveWrap' => false,

    //'filterModel' => $searchModelAttch,


    'columns' => [

        ['class' => 'yii\grid\SerialColumn'],



        //          'id',

        //'task_id',

        //'task_name',

        [

            'attribute' => 'description',
            'label'=>Yii::t('app', 'Description'),

            'width' => '35%',

            'format' => 'raw'

        ],

        [

            'attribute' => 'amount',
            'label'=> 'Monto',

            'width' => '25%',

            'format' => 'raw',

            'value' => function ($model) {
                return '$' . $model->amount;
            }

        ],

        [

            'attribute' => 'date',
            'label'=>Yii::t('app', 'Date'),

            'width' => '20%',

            'format' => 'raw'

        ],

        [

            'attribute' => 'time',
            'label'=>Yii::t('app', 'Time'),

            'width' => '10%',

            'format' => 'raw'

        ],
        [
                'attribute' =>'type',
            'label' => 'Tipo de Cita',
            'width' => '10%',
            'format' => 'raw',
            'value' => function($model){
                return $model->type == 0 ? 'En Oficina' : 'En llamada';
            }


        ],

        [

            'attribute' => 'status',
            'label'=>Yii::t('app', 'Status'),
            'width' => '20%',
            'format' => 'raw',
            'value'=>function($model){
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
                'attribute' => 'updated_at',
                'label' => 'Fecha de Actualización de la Cita',
                'width' => '20%',
                'format' => 'raw',
                'value' => function($model){
                    $date = '';
                    if ($model->updated_at != null)
                    {
                        $date = date('Y-m-d',$model->updated_at);
                    }

                    return $date;
                }
        ],

        [

            'class' => '\kartik\grid\ActionColumn',

            //'template'=>'{view}{update}{delete}',

            //'class'=>'CButtonColumn',

            // 'class' => ActionColumn::className(),

            'template'=>'{update}  {delete} {write} {primary}',

            'buttons' => [

                'width' => '10%',

                    'update' => function ($url, $model){
                            return"<form name='frm_appointment".$model->id."' action='".'index.php?r='.$_REQUEST['r'].'&id='.$_REQUEST['id']."&appointment_edit=".$model->id."' method='post' style='display:inline'><input type='hidden' value='$csrf' name='_csrf'>

									<a href='#' onClick='document.frm_appointment".$model->id.".submit()' title='".Yii::t('app', 'Edit')."' target='_parent'><span class='glyphicon glyphicon-pencil'></span></a></form>";

                    },

                'delete' => function ($url, $model) {



                },
                'write' => function ($url, $model) {



                },

                'delete' => function ($url, $model)
                {
                    return '<a href="index.php?r=sales/lead/appointmentdelete&id='.$_REQUEST['id'].'&appointment_del='.$model->id.'" onClick="return get_confirm();" title="'.Yii::t('app', 'Delete').'"><span class="glyphicon glyphicon-trash"></span></a>';
                },
                'write' => function ($url, $model)
                {
                    return '<a href="index.php?r=sales/lead/appointmentupdate'.'&id='.$_REQUEST['id'].'&appointment_edit='.$model->id.' &type_update='.$model->type.'" onClick="return get_confirm();" title="'.Yii::t('app', 'Actualiza tipo de cita').'"><span class="glyphicon glyphicon-retweet"></span></a>';
                },

            ],

        ],

    ],

    'responsive'=>true,

    'hover'=>true,

    'condensed'=>true,

    //'floatHeader'=>true,









    'panel' => [

        'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Yii::t('app', 'Appointments').'  </h3>',

        'type'=>'info',

        'before'=>'<a href="javascript:void(0)" class="btn btn-success btn-sm" onClick="$(\'.appointmentae\').modal(\'show\');"><i class="glyphicon glyphicon glyphicon-book"></i> '.Yii::t('app', 'New Appointment').'</a>',
        /*                                                                                                                                                'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),*/

        'showFooter'=>false

    ],

]); Pjax::end(); ?>

<script>
    function get_confirm(){
        return confirm("<?=Yii::t ('app','Are you Sure!')?>");
    }
</script>