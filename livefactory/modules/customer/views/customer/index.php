<?php



use yii\helpers\Html;

use kartik\grid\GridView;

use yii\widgets\Pjax;

use livefactory\models\CustomerType;
use livefactory\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


/**

 *

 * @var yii\web\View $this

 * @var yii\data\ActiveDataProvider $dataProvider

 * @var common\models\search\Customer $searchModel

 */

$this->title = Yii::t ( 'app', 'Customers' );

$this->params ['breadcrumbs'] [] = $this->title;
$pageView = !empty($_GET['view'])?$_GET['view']:Yii::$app->params['DEFAULT_SEARCH_PAGE_VIEW'];
///count($dataProviderBox);
function getPhone($customer_id){
	$sql ="select * from tbl_contact where entity_type='customer' and entity_id=$customer_id and is_primary='1'"; 
	$connection = \Yii::$app->db;
	  $command=$connection->createCommand($sql);
	  $dataReader=$command->queryOne();
	  return $dataReader['phone'];
}
?>

<link rel="stylesheet" href="../include/jPages.css">

<div class="row">

<div class="ibox float-e-margins">

      <div class="ibox-title">

                        <h5><?= Html::encode($this->title) ?> </h5>

                        <div class="ibox-tools">

                        	<button class="btn btn-xs btn-info box_btn  <?=$pageView  =='List View'?'':'hide' ?>"><?php echo Yii::t ( 'app', 'Switch to Tiles View' ); ?></button>

                            <button class="btn btn-xs btn-info list_btn <?=$pageView  =='List View'?'hide':'' ?>"><?php echo Yii::t ( 'app', 'Switch to List View' ); ?></button>

						    <a class="collapse-link">

                                <i class="fa fa-chevron-up"></i>

                            </a>

                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">

                                <i class="fa fa-wrench"></i>

                            </a>

                            <ul class="dropdown-menu dropdown-user">

                                <li><a href="javascript:void(0)"  class="box_btn <?=$pageView  =='List View'?'':'hide' ?>"><?php echo Yii::t ( 'app', 'Switch to Tiles View' ); ?></a>

                                </li>

                                <li><a href="javascript:void(0)" class="list_btn  <?=$pageView  =='List View'?'hide':'' ?>"><?php echo Yii::t ( 'app', 'Switch to List View' ); ?></a>

                                </li>

                            </ul>

                            <a class="close-link">

                                <i class="fa fa-times"></i>

                            </a>

                        </div>

                    </div>

        <div class="ibox-content">

            <div class="customer-index">

            	<div class="box row <?=$pageView  =='List View'?'hide':'' ?>" >

                	<div class="col-sm-12">

                        <div class="row" id="customer_div">

                        <?php foreach($dataProviderBox as $row){?>

                            <div class="col-lg-4">

                                <div class="contact-box">

                                    <a href="index.php?r=customer/customer/customer-view&id=<?=$row['id']?>">

                                    <div class="col-sm-4">

                                        <div class="text-center">

                                        	<?php

											$path='../customers/'.$row['id'].'.png';

											if(file_exists($path)){?>

												<img  class="img-circle m-t-xs img-responsive"  src="../customers/<?=$row['id']?>.png">								

											<?php }else{?>

												<img   class="img-circle m-t-xs img-responsive" src="../customers/nophoto.jpg">

											<?php }

										?>

                                            <div class="m-t-xs font-bold"><?=$row['type']?></div>

                                            <div class="form-group">
	
                                                <a href="index.php?r=customer/customer/customer-view&id=<?=$row['id']?>" style="color:#fff;" class="btn btn-xs  btn-success"><span class="glyphicon glyphicon-eye-open"></span></a>
                                                <form action="index.php?r=customer/customer/delete&id=<?=$row['id']?>" method="post" name="cus<?=$row['id']?>" style="display:inline">
                                                <?php Yii::$app->request->enableCsrfValidation = true; ?>

    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
        <button type="submit" onClick="return confirm('<?=Yii::t ('app','Are you Sure!')?>')" style="color:#fff;" class="btn btn-xs  btn-danger"><span class="glyphicon glyphicon-trash"></span></button>
    </form>

                                                

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-sm-8">

                                        <h3><strong><?=$row['customer_name']?></strong></h3>

                                        <p><i class="fa fa-map-marker"></i> <?=$row['first_name']?> <?=$row['last_name']?></p>

                                        <address>

                                            <?=!empty($row['address_1'])?$row['address_1'].",":''?> <?=$row['city']?><br>

                                            <?=!empty($row['state'])?$row['state'].",":''?> <?=$row['country']?><br>

                                            <abbr title="Phone">P:</abbr> <?=getPhone($row['id'])?>

                                        </address>

                                    </div>

                                    <div class="clearfix"></div>

                                        </a>

                                </div>

                            </div>

                            

                        <?php } ?>

                            

                        </div>

                        <div class="row">

                            <div class="holder"></div>

                        </div>

                     </div>

                 </div>

                <div class="gridlist <?=$pageView =='List View'?'':'hide' ?>">

               	<!-- <form action=""  method="post" name="frm"> -->

            <?php Yii::$app->request->enableCsrfValidation = true; ?>

            

            <?php

                        

        Pjax::begin ();

                        echo GridView::widget ( [ 

                                'dataProvider' => $dataProvider,

                                'filterModel' => $searchModel,'responsive' => true,'responsiveWrap' => false,
'pjax' => true,
								
								'pjax' => true,

                                'columns' => [ 

                                        ['class' => '\kartik\grid\CheckboxColumn'],

                                        ['class' => 'yii\grid\SerialColumn'],

                                        

                                        // 'id',
										[ 

										'attribute' => 'id',

										'label' => Yii::t('app', 'Image'),

										'format' => 'raw',

										'width' => '50px',

										'value' => function ($model, $key, $index, $widget)

										{

												$customer='<div class="project-people">';

														$path='../customers/'.$model->id.'.png';

														if(file_exists($path)){

															$image='<img  src="../customers/'.$model->id.'.png">';								

														 }else{ 

															$image='<img src="../users/nophoto.jpg">';

														 }

														$customer.=' <a href="index.php?r=customer/customer/customer-view&id='.$model->id.'">'.$image.'</a>';	

												$customer.='</div>';

												return $customer;

										} 

								],

                                        [ 

                                                'attribute' => 'customer_name',

                                                'width' => '200px',

                                                'format' => 'raw',

                                                'value' => function ($model, $key, $index, $widget)

                                                {

                                                    return '<a href="index.php?r=customer/customer/customer-view&id=' . $model->id . '">' . $model->customer_name . '</a>';

                                                } 

                                        ],

                                        [ 

                                                'attribute' => 'customer_type_id',

                                               // 'label' => 'Type',

                                                'filterType' => GridView::FILTER_SELECT2,

                                                'format' => 'raw',

                                                'width' => '100px',

                                                'filter' => ArrayHelper::map ( CustomerType::find ()->where("active=1")->orderBy ( 'sort_order' )->asArray ()->all (), 'id', 'label' ),

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

                                                    // var_dump($model->user);

                                                    // var_dump($model->customerType);

                                                    if (isset ( $model->customerType ) && ! empty ( $model->customerType->label ))

                                                        return $model->customerType->label;

                                                } 

                                        ],

                                        'first_name',

                                        'last_name',

                                        'email:email',

                                        

                                        // 'phone',

                                        'mobile',
										[
										'attribute' => 'customer_owner_id',
										'label' => Yii::t('app', 'Owner'),
										'filterType' => GridView::FILTER_SELECT2,
										'format' => 'raw',
										
										// 'width' => '100px',
										'filter' => ArrayHelper::map(User::find()->orderBy('first_name')
											->where("active=1")
											->asArray()
											->all(), 'id', function ($user, $defaultValue)
										{
											$username = $user['username'] ? $user['username'] : $user['email'];
											return $user['first_name'] . ' ' . $user['last_name'] . ' (' . $username . ')';
										}),
										
										'filterWidgetOptions' => [
											
											'options' => [
												
												'placeholder' => Yii::t('app', 'All...')
											]
											,
											
											'pluginOptions' => [
												
												'allowClear' => true
											]
											
										]
										,
										'value' => function ($model, $key, $index, $widget)
										
										{
											if (isset($model->user) && ! empty($model->user->first_name)) {
												$username = $model->user->username ? $model->user->username : $model->user->email;
												if (Yii::$app->params['USER_IMAGE'] == 'Yes') {
													$users = '<div class="project-people">';
													$path = '../users/' . $model->user->id . '.png';
													if (file_exists($path)) {
														$image = '<img  class="img-circle"  src="' . $path . '">';
													} else {
														$image = '<img   class="img-circle" src="../users/nophoto.jpg">';
													}
													$users .= ' <a  href="javascript:void(0)" onClick="showPopup(\'' . $model->user->id . '\')"  data-toggle="hover" data-placement="top" data-content="' . $model->user->first_name . ' ' . $model->user->last_name . ' (' . $username . ')">' . $image . '</a>';
													
													$users .= '</div>';
													return $users;
												} else {
													$users = ' <a  href="javascript:void(0)" onClick="showPopup(\'' . $model->user->id . '\')" class="btn btn-primary btn-xs" style="margin-bottom:5px">' . $model->user->first_name . ' ' . $model->user->last_name . ' (' . $username . ')</a>';
													return $users;
												}
											}
										}
									],
                                        

                                        // 'fax',

                                        // 'address_id',

                                        // 'created_at',

                                        // 'updated_at',

                                        

                                        [ 

                                                'class' => '\kartik\grid\ActionColumn',

                                                'template' => '{update} {delete}',
												'contentOptions' => ['style' => 'width:50px;'],
                                                'buttons' => [ 

                                                        'update' => function ($url, $model)

                                                        {

                                                           return Html::a ( '<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl ( [ 
															'customer/customer/customer-view',
															'id' => $model->id 
													] ), [ 
															'title' => Yii::t ( 'app', 'Edit' ) 
													] );

                                                        } ,
														'delete' => function($url,$model){
															$view = isset($_GET['view']) && $_GET['view'] != ''?'&view='.$_GET['view']:'';
															return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['customer/customer/delete','id' => $model->id.$view]), [
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

                                        'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Yii::t ( 'app', 'Customers' ).' </h3>',

                                        'type' => 'info',

                                        'before' => '<form action=""  method="post" name="frm">
										<input type="hidden" name="_csrf" value="'.$csrf.'">

            <input type="hidden" name="multiple_del" value="true">'.Html::a ( '<i class="glyphicon glyphicon-plus"></i> '.Yii::t ( 'app', 'Add' ), [ 

                                                'create' 

                                        ], [ 

                                                'class' => 'btn btn-success  btn-sm' 

                                        ] ).' <a href="javascript:void(0)" onClick="all_del()" class="btn btn-danger  btn-sm"><i class="glyphicon glyphicon-trash"></i> '.Yii::t ( 'app', 'Delete Selected' ).'</a>',

                                        'after' => '</form>'.Html::a ( '<i class="glyphicon glyphicon-repeat"></i> '.Yii::t ( 'app', 'Reset List' ), [ 

                                                'index' 

                                        ], [ 

                                                'class' => 'btn btn-info  btn-sm' 

                                        ] ),

                                        'showFooter' => false 

                                ] 

                        ] );

                        Pjax::end ();

                        ?>

       <!-- </form> -->

       			</div>

          </div>

		</div>

	</div>

</div>



<!--

	<div class="page-header">

		<h1><?= Html::encode($this->title) ?></h1>

	</div>

	-->



    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



  

        <?php 

/*

				       * echo Html::a(Yii::t('app', 'Create {modelClass}', [

				       * 'modelClass' => 'Customer',

				       * ]), ['create'], ['class' => 'btn btn-success'])

				       */

								?>

   



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
			//alert(getCookie("startPage"));
			
			
			$('.box_btn').click(function(){
				document.location.href='index.php?r=customer/customer/index&view=Tile View';
				/*$('.box').show();
				$(this).addClass('hide');
				$('.list_btn').removeClass('hide');
				$('.gridlist').hide();*/

			})

			$('.list_btn').click(function(){
				document.location.href='index.php?r=customer/customer/index&view=List View';
				/*$('.gridlist').show();
				$(this).addClass('hide');
				$('.box_btn').removeClass('hide');	
				$('.box').hide();*/

			})

			

			// $('.tabbable').appendTo('#w0');

			//console.log($('a[data-toggle="tab"]:first').tab('show'))

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