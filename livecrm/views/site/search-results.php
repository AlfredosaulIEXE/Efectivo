<?php
use livefactory\models\search\CommonModel;
$this->title = Yii::t('app', 'Search Results'); 
?>
<link rel="stylesheet" href="../include/jPages.css">
<div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            
                            <div class="search-form">
                                <form role="search"  method="post" action="index.php?r=site/search-results">
             <?php Yii::$app->request->enableCsrfValidation = true; ?>
              <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">
                                    <div class="input-group">
                                        <input type="text" value="<?=isset($_REQUEST['top_search'])?$_REQUEST['top_search']:''?>" placeholder=" <?=Yii::t('app', 'Search for something...')?>" name="top_search" class="form-control input-lg">
                                        <div class="input-group-btn">
                                            <button class="btn btn-lg btn-primary" type="submit">
                                                <?=Yii::t('app', 'Search')?>
                                            </button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
					
					
					 <?php if (in_array('customer',Yii::$app->params['modules']) || in_array('sales',Yii::$app->params['modules'])){ ?>
                    <!--  MODULE-Customer & Sales contact search result display -->
                            <div class="row hide">
                                <div class="col-lg-12">
                                    <div class="ibox float-e-margins">
                                        <div class="ibox-title">
                                            <h5><?=Yii::t('app',"Contact Results")?> : <?=$contactModel !=''?count($contactModel):''?></h5>
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
                                        	<div id="contacts">
                                      <?php  if($contactModel !=''){
												foreach($contactModel as $contact){
											if( in_array('customer',Yii::$app->params['modules']) && $contact['entity_type']=="customer" && Yii::$app->user->can('Customer.Update')){
														$connection = \Yii::$app->db;
														$id = $contact['entity_id'];
														$sql = "SELECT tbl_customer.id FROM `tbl_customer` INNER JOIN tbl_contact WHERE tbl_customer.id =$id";
														$command=$connection->createCommand($sql);
														$customer=$command->queryOne();
														?>
														
													<div class="search-result" style="border-bottom:1px dashed #e7eaec">
														<h3><a href="index.php?r=customer/customer/customer-view&id=<?=$customer['id']?>&contact_edit=<?=$contact['id']?>"><?=$contact['first_name']." ".$contact['last_name']?></a></h3>
														<a href="index.php?r=customer/customer/customer-view&id=<?=$customer['id']?>&contact_edit=<?=$contact['id']?>" class="search-link"><?=$contact['email']?></a>
														<p><strong><?=ucfirst($contact['entity_type'])?></strong></p>
														<p><i class="fa fa-mobile fa-lg" aria-hidden="true"></i><?=$contact['mobile']?></p>
													</div>
										<?php	}if(in_array('sales',Yii::$app->params['modules']) && $contact['entity_type']=="lead" && Yii::$app->user->can('Lead.view')){
												$connection = \Yii::$app->db;
												$id = $contact['entity_id'];
												$sql = "SELECT tbl_lead.id FROM `tbl_lead` INNER JOIN tbl_contact WHERE tbl_lead.id =$id";
												$command=$connection->createCommand($sql);
												$lead=$command->queryOne();
												?>
												<div class="search-result" style="border-bottom:1px dashed #e7eaec">
														<h3><a href="index.php?r=sales/lead/view&id=<?=$lead['id']?>&contact_edit=<?=$contact['id']?>"><?=$contact['first_name']." ".$contact['last_name']?></a></h3>
														<a href="index.php?r=sales/lead/view&id=<?=$lead['id']?>&contact_edit=<?=$contact['id']?>" class="search-link"><?=$contact['email']?></a>
														<p><strong><?=ucfirst($contact['entity_type'])?></strong></p>
														<p><i class="fa fa-mobile fa-lg" aria-hidden="true"></i>  <?=$contact['mobile']?></p>
													</div>
													
												<?php }} /* foreach($contactModel as $contact) ends here */
											}else{
												echo  Yii::t('app',"no result");	
											}
											?>
                                            </div>
                                            <div class="holder contact_holder"></div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
					
					
					
					 <?php }     if (in_array('pmt',Yii::$app->params['modules'])){ ?>
                    <!--  MODULE-PMT project search result display -->
					<?php  if (Yii::$app->user->can('Project.Update')){ ?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="ibox float-e-margins">
                                        <div class="ibox-title">
                                            <h5><?=Yii::t('app',"Project Results")?> : <?=$projectModel !=''?count($projectModel):''?></h5>
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
                                        	<div id="projects">
                                        	<?php
											if($projectModel !=''){
												foreach($projectModel as $project){?>
													<div class="search-result" style="border-bottom:1px dashed #e7eaec">
														<h3><a href="index.php?r=pmt/project/project-view&id=<?=$task['id']?>"><?=(strlen($project['project_name']) > 50) ? substr($project['project_name'],0,50).'...' :$project['project_name'];?></a></h3>
														<a href="index.php?r=pmt/project/project-view&id=<?=$project['id']?>" class="search-link"><?=date('F d,Y',$project['expected_start_datetime'])?></a>
                                                        <?php
															$desc = str_replace('&lt;p&gt;', '', $project['project_description']);
															$desc = str_replace('&lt;/p&gt;', '', $desc);
														?>
														<p><?=(strlen($desc) > 150) ? substr($desc,0,150).'...' :$desc;?></p>
													</div>
											<?php	}
											}else{
												echo  Yii::t('app',"no result");	
											}
											?>
                                            </div>
                                            <div class="holder project_holder"></div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
							<?php } ?>
							<!--  MODULE-PMT task search result display -->
							<?php  if (Yii::$app->user->can('Task.Update')){ ?>
							<div class="row">
                                <div class="col-lg-12">
                                    <div class="ibox float-e-margins">
                                        <div class="ibox-title">
                                            <h5><?=Yii::t('app', 'Task Results')?> : <?=$taskModel!=''?count($taskModel):''?></h5>
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
                                        	<div id="tasks">
                                        	<?php
											if($taskModel !=''){
												foreach($taskModel as $task){?>
													<div class="search-result" style="border-bottom:1px dashed #e7eaec">
														<h3><a href="index.php?r=pmt/task/task-view&id=<?=$task['id']?>"><?=(strlen($task['task_name']) > 50) ? substr($task['task_name'],0,50).'...' :$task['task_name'];?></a></h3>
														<a href="index.php?r=pmt/task/task-view&id=<?=$task['id']?>" class="search-link"><?=date('F d,Y',$task['expected_start_datetime'])?></a>
                                                        <?php
															$desc = str_replace('&lt;p&gt;', '', $task['task_description']);
															$desc = str_replace('&lt;/p&gt;', '', $desc);
														?>
														<p><?=(strlen($desc) > 150) ? substr($desc,0,150).'...' :$desc;?></p>
													</div>
											<?php	}
											}else{
												echo  Yii::t('app',"no result");	
											}
											?>
                                            </div>
                                            <div class="holder task_holder"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
							
							<!--  MODULE-PMT defect search result display -->
							<?php  if (Yii::$app->user->can('Defect.Update')){ ?>
							  <div class="row">
                                <div class="col-lg-12">
                                    <div class="ibox float-e-margins">
                                        <div class="ibox-title">
                                            <h5><?=Yii::t('app',"Defect Results")?> : <?=$defectModel !=''?count($defectModel):''?></h5>
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
                                        	<div id="defects">
                                        	<?php
											if($defectModel !=''){
												foreach($defectModel as $defect){?>
													<div class="search-result" style="border-bottom:1px dashed #e7eaec">
														<h3><a href="index.php?r=pmt/defect/defect-view&id=<?=$defect['id']?>"><?=(strlen($defect['defect_name']) > 50) ? substr($defect['defect_name'],0,50).'...' :$defect['defect_name'];?></a></h3>
														<a href="index.php?r=pmt/defect/defect-view&id=<?=$defect['id']?>" class="search-link"><?=date('F d,Y',$defect['expected_start_datetime'])?></a>
                                                        <?php
															$desc = str_replace('&lt;p&gt;', '', $defect['defect_description']);
															$desc = str_replace('&lt;/p&gt;', '', $desc);
														?>
														<p><?=(strlen($desc) > 150) ? substr($desc,0,150).'...' :$desc;?></p>
													</div>
											<?php	}
											}else{
												echo  Yii::t('app',"no result");	
											}
											?>
                                            </div>
                                            <div class="holder defect_holder"></div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
							<?php } /* permission check if end */
							} /* module check if end */
					
					if(in_array('support',yii::$app->params['modules']) && Yii::$app->user->can('Ticket.Update')){
					?>
							
							<!--  MODULE-SUPPORT ticket search result display -->
							  <div class="row">
                                <div class="col-lg-12">
                                    <div class="ibox float-e-margins">
                                        <div class="ibox-title">
                                            <h5><?=Yii::t('app',"Ticket Results")?> : <?=$ticketModel !=''?count($ticketModel):''?></h5>
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
                                        	<div id="tickets">
                                        	<?php
											if($ticketModel !=''){
												foreach($ticketModel as $ticket){?>
													<div class="search-result" style="border-bottom:1px dashed #e7eaec">
														<h3><a href="index.php?r=support/ticket/update&id=<?=$ticket['id']?>"><?=(strlen($ticket['ticket_title']) > 50) ? substr($ticket['ticket_title'],0,50).'...' :$ticket['ticket_title'];?></a></h3>
														<a href="index.php?r=support/ticket/update&id=<?=$ticket['id']?>" class="search-link"><?=date('F d,Y',$ticket['added_at'])?></a>
                                                        <?php
															$desc = $ticket['ticket_description'];
															$desc = str_replace('&lt;/p&gt;', '', $desc);
														?>
														<p><?=(strlen($desc) > 150) ? substr($desc,0,150).'...' :$desc;?></p>
													</div>
											<?php	}
											}else{
												echo  Yii::t('app',"no result");	
											}
											?>
                                            </div>
                                            <div class="holder ticket_holder"></div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
							
					<?php } 
					
					if(in_array('invoice',yii::$app->params['modules']) && Yii::$app->user->can('Invoice.View')){
					
					?>
							<!--  MODULE-INVOICE invoice search result display -->
							  <div class="row">
                                <div class="col-lg-12">
                                    <div class="ibox float-e-margins">
                                        <div class="ibox-title">
                                            <h5><?=Yii::t('app',"Invoice Results")?> : <?=$invoiceModel !=''?count($invoiceModel):''?></h5>
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
                                        	<div id="invoice">
                                        	<?php
											if($invoiceModel !=''){
												foreach($invoiceModel as $invoice){?>
													<div class="search-result" style="border-bottom:1px dashed #e7eaec">
														<h3><a href="index.php?r=invoice/invoice/view&id=<?=$invoice['id']?>"><?=(strlen($invoice['invoice_number']) > 50) ? substr($invoice['invoice_number'],0,50).'...' :$invoice['invoice_number'];?></a></h3>
														<a href="index.php?r=invoice/invoice/view&id=<?=$invoice['id']?>" class="search-link"><?=date('F d,Y',$invoice['date_created'])?></a>
                                                        <?php
															$desc = htmlspecialchars($invoice['notes']);
															//$desc = str_replace('&lt;/p&gt;', '', $desc);
														?>
														<p><?=(strlen($desc) > 150) ? substr($desc,0,150).'...' :$desc;?></p>
													</div>
											<?php	}
											}else{
												echo  Yii::t('app',"no result");	
											}
											?>
                                            </div>
                                            <div class="holder invoice_holder"></div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
							
							<?php }
					if(in_array('estimate',yii::$app->params['modules']) && (Yii::$app->user->can('Customer.Estimate.View')  || Yii::$app->user->can('Sales.Estimate.View'))){
					
					?>
							
							<!--  MODULE-ESTIMATE estimate search result display -->
							  <div class="row">
                                <div class="col-lg-12">
                                    <div class="ibox float-e-margins">
                                        <div class="ibox-title">
                                            <h5><?=Yii::t('app',"Estimate Results")?> : <?=$estimateModel !=''?count($estimateModel):''?></h5>
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
                                        	<div id="estimate">
                                        	<?php
											if($estimateModel !=''){
												foreach($estimateModel as $estimate){?>
													<div class="search-result" style="border-bottom:1px dashed #e7eaec">
														<h3><a href="index.php?r=estimate/estimate/view&id=<?=$estimate['id']?>"><?=(strlen($estimate['estimation_code']) > 50) ? substr($estimate['estimation_code'],0,50).'...' :$estimate['estimation_code'];?></a></h3>
														<a href="index.php?r=estimate/estimate/view&id=<?=$estimate['id']?>" class="search-link"><?=date('F d,Y',$estimate['added_at'])?></a>
                                                        <?php
															$desc = htmlspecialchars($estimate['notes']);
															//$desc = str_replace('&lt;/p&gt;', '', $desc);
														?>
														<p><?=(strlen($desc) > 150) ? substr($desc,0,150).'...' :$desc;?></p>
													</div>
											<?php	}
											}else{
												echo  Yii::t('app',"no result");	
											}
											?>
                                            </div>
                                            <div class="holder estimate_holder"></div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
							
					<?php } 
					
					if(in_array('sales',yii::$app->params['modules']) && Yii::$app->user->can('Lead.View')){
					
					?>
							<!-- MODULE- SALES   lead search result display -->
							  <div class="row">
                                <div class="col-lg-12">
                                    <div class="ibox float-e-margins">
                                        <div class="ibox-title">
                                            <h5><?=Yii::t('app',"Lead Results")?> : <?=$salesModel !=''?count($salesModel):''?></h5>
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
                                        	<div id="sales">
                                        	<?php
											if($salesModel !=''){
												foreach($salesModel as $lead){?>
													<div class="search-result" style="border-bottom:1px dashed #e7eaec">
														<h3><a href="index.php?r=sales/lead/view&id=<?=$lead['id']?>"><?= (strlen($lead['lead_name']) > 50) ? $lead['c_control']." ".substr($lead['lead_name'],0,50).'...' :$lead['c_control'] ." ".$lead['lead_name'];?></a></h3>
														<a href="index.php?r=sales/lead/view&id=<?=$lead['id']?>" class="search-link"><?=date('F d,Y',$lead['added_at'])?></a>
                                                        <?php
															$desc = htmlspecialchars($lead['lead_description']);
														?>
														<p><?=(strlen($desc) > 150) ? substr($desc,0,150).'...' :$desc;?></p>
													</div>
											<?php	}
											}else{
												echo  Yii::t('app',"no result");	
											}
											?>
                                            </div>
                                            <div class="holder sales_holder"></div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
							
							
					<?php } ?>  
							
							
                </div>
        </div>
</div>

<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<script>
	$(document).ready(function(e) {
	$("div.task_holder").jPages({
      containerID : "tasks",
      perPage : 7,
      delay : 20
    });
	$("div.project_holder").jPages({
      containerID : "projects",
      perPage : 7,
      delay : 20
    });
	$("div.contact_holder").jPages({
      containerID : "contacts",
      perPage : 7,
      delay : 20
    });
	$("div.defect_holder").jPages({
      containerID : "defects",
      perPage : 7,
      delay : 20
    });
		$("div.ticket_holder").jPages({
      containerID : "tickets",
      perPage : 7,
      delay : 20
    });
	$("div.invoice_holder").jPages({
      containerID : "invoice",
      perPage : 7,
      delay : 20
    });
	$("div.estimate_holder").jPages({
      containerID : "estimate",
      perPage : 7,
      delay : 20
    });
	$("div.sales_holder").jPages({
      containerID : "sales",
      perPage : 7,
      delay : 20
    });
        
    });
	</script>