<?php



use yii\helpers\Html;

use kartik\grid\GridView;

use yii\widgets\Pjax;

use livefactory\models\User;

use yii\helpers\ArrayHelper;

?>

    <?php 
	date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
//var_dump($dataProviderNotes);
	?>
<!--	<div class="panel panel-info">
    	<div class="panel-heading">
        	<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> <?= Yii::t('app','Notes')?></h3>
        </div>
        <div class="panel-body" style="padding:0">-->


				<?php
					if(count($dataProviderNotes)>0){?>
                    
                   <!-- <div class="chat-discussion"> -->
				<?php
				foreach($dataProviderNotes as $data){?>
                    <?php $sql = "select auth_assignment.item_name , auth_assignment.user_id from auth_item,auth_assignment where auth_item.type=2 and auth_assignment.item_name=auth_item.name and auth_assignment.user_id=$data->user_id";
                    $connection = \Yii::$app->db;
                    $command=$connection->createCommand($sql);
                    $dataReader=$command->queryOne();

                    //var_dump($dataReader['item_name'] , $model->user_id);exit;
                    //indicated customer
	                    if($dataReader['item_name'] == "Admin" || $dataReader['item_name'] == 'Customer.Director' || $dataReader['item_name'] == 'Customer.Service' || $dataReader['item_name'] == 'Insurance.Customer' || $dataReader['item_name'] == 'Insurance.Director') {
	                        $classcustom='#fcf8e3';
                       }
                       else
                           $classcustom='#fff';
	                    ?>
                	<div class="chat-message" style="background: <?= $classcustom?>">

                        <img class="message-avatar" src="../users/<?=$data->user->id?>.png" alt="" onerror="this.onerror=null;this.src='../users/nophoto.jpg'">

                        <div class="message">

                            <a class="message-author" href="#"> <?=$data->user->first_name?> <?=$data->user->last_name?> </a>

                            <span class="message-date"> <?=date('jS \of F Y H:i:s',$data->added_at)?>
                                <?php if ($data->user_id == Yii::$app->user->id) { ?>
                                <a href="javascript:void(0)" onClick="callJs('<?=$data->id?>')"  title="Edit" ><span class="glyphicon glyphicon-pencil"></span></a>
							 <a href="index.php?r=<?=$_REQUEST['r']?>&id=<?=$_REQUEST['id']?>&note_del_id=<?=$data->id?>" onClick="return confirm('Are you Sure!')" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>;
							    <?php } ?>
                            </span>
                            <span class="message-content">
                            <?=$data->notes?><br/>
                            

                            </span>

                        </div>

                    </div>
				<?php
					if(isset($i))
					$i++;
				}  
				?>
          <!--       </div> -->
				<?php	}?>
                
 <!--       </div>
    </div> -->
    <script>
	function callJs(id){
		//alert(id);
		document.location.href='<?='index.php?r='.$_REQUEST['r'].'&id='.$_REQUEST['id'].'&note_id='?>'+id;	
	}
	function formSubmit(id){

		var r = confirm("<?=Yii::t ('app','Are you Sure!')?>");

		if (r == true) {

			$('#'+id).submit()

		} else {

			

		}	

	}
	</script>