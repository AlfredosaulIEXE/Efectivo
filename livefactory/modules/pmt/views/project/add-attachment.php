<?php



////////////Notes//////////////////////////////////////



/*



1."page" parameter use for redirect to detail page



2."id_field" parameter use for detail page id parameter get name



3. "field" parameter use for  fetch title



4. "table" parameter use for get table name



*/



$this->title =  Yii::t ('app','Project Attachments');



?>



<script>



function addAttach(){



	$('#first-col').after('<div class="col-sm-4"><div class="form-group"><div class="controls"><input class="form-control" name="filetitle[]" placeholder="File Title"><input type="file" class="form-control" name="attach[]"> </div></div> </div>	');



}



function validation(){



	if($('#att').val()	==''){



		$('#att').parent().parent().addClass('has-error');



		$('.help-block').text('<?=Yii::t ('app','This Field Required!')?>');



		



	}else{



		$('#att').parent().parent().removeClass('has-error');



		$('.help-block').text('');



		document.form1.submit();



	}



}



</script>



<?php



$entity_id='';



$entity_id=!empty($_REQUEST['entity_id'])?$_REQUEST['entity_id']:'';







?>



<div class="ibox float-e-margins">



        <div class="ibox-title">



            <h5> <?=$this->title ?></h5>







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



             <div class="row">



             	<div class="col-sm-6">



             	<?=Yii::t ('app','Note Attachment Type')?>: <i><?=Yii::t ('app','doc/xls/pdf/images/audio/video/zip')?></i>                 



                </div>



                <div class="col-sm-6" align="right">



                	<input type="button" class="btn btn-success btn-sm" onClick="addAttach()" value="<?=Yii::t ('app','Add More Files')?>">



                    		



                </div>



             </div>



                



            <form method="post" action="<?=$_SERVER['REQUEST_URI']?>"  enctype="multipart/form-data" name="form1" id="form1">



            <?php Yii::$app->request->enableCsrfValidation = true; ?>



    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">



            <input type="hidden" name="entity_id" value="<?=$entity_id?>">



            <div class="row">



                <div class="col-sm-4" id="first-col">



                    <div class="form-group">



                        <div class="controls">



                            <input type="text" class="form-control" name="filetitle[]" placeholder="File Title">



                            <input type="file" class="form-control" name="attach[]" id="att">



                            <span class="help-block"></span>



                        </div>	



                    </div>



                </div>



		 </div>











           <div class="row">



           		 <div class="col-sm-12">				 



           		<button class="btn btn-primary btn-sm" value="Submit" type="submit">



					<i class="fa fa-upload"></i> <?=Yii::t ('app','Upload')?>



				</button>



				<a href="index.php?r=pmt/project/project-view&id=<?=$entity_id?>" class="btn btn-warning btn-sm"> <?=Yii::t ('app','Skip')?></a>



                </div>



           </div>



         </form>



         </div>



    </div>