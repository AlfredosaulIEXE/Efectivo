<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use livefactory\models\EstimateStatus;
use livefactory\models\Lead;
use livefactory\models\LeadStatus;

/**
 * @var yii\web\View $this
 * @var livefactory\models\Estimate $model
 */

$this->title = Yii::t('app','Status : ').$model->estimateStatus->label;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', ucfirst($model->entity_type))];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Estimates'), 'url' => ['index','entity_type'=>$model->entity_type]];
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Update'), 'url' => ['update','id'=>$model->id,'entity_type'=>$model->entity_type ]];
$this->params['breadcrumbs'][] = $model->estimation_code;
?>
<?php  if(!empty($_GET['added'])){?>
		<div class="alert alert-success"><?=Yii::t('app', 'Estimate Sent For Approval To '.ucfirst($model->entity_type))?></div>
		<script>
				setTimeout(function(){
					window.location.href='index.php?r=estimate/estimate/view&id=<?=$model->id?>&entity_type=<?=$model->entity_type?>';
				},4000);
			</script>	
<?php	} ?>
<script src="../../vendor/bower/jquery/dist/jquery.js"></script>
<div id="temp"></div>
<script>
$(document).ready(function(e) {
	$('#temp').load("index.php?r=estimate/estimate/view-pdf&id=<?=$model->id?>&entity_type=<?=$model->entity_type?>");
});
</script>

<div class="invoice-view">
   <div class="row">

            <div class="col-lg-12">

                <div class="wrapper wrapper-content animated fadeInRight">
				
					<div class="ibox-title">

					<!--<h5><?= Html::encode($this->title) ?></h5>-->

					<div class="ibox-tools">

					<?php
					if(Yii::$app->user->identity->userType->type!="Customer")
					{
					?>
						<?php
						if ($model->estimate_status_id != EstimateStatus::_APPROVED)
						{
							echo Html::a('<i class="fa fa-envelope"></i> '.Yii::t('app', 'Send Estimate for Approval'), ['/estimate/estimate/update','id'=>$model->id, 'rqst'=>'sendapprove'], ['class'=>'btn btn-primary btn-xs']);
						}
					}
					?>

					<?php
							if($model->estimate_status_id == EstimateStatus::_APPROVED && (Yii::$app->user->identity->userType->type!="Customer" || Yii::$app->params['user_role'] =='admin') && ((in_array('sales',Yii::$app->params['modules']) && $model->entity_type=='lead' && Lead::findOne($model->customer_id)->lead_status_id == LeadStatus::_CONVERTED) || (in_array('invoice',Yii::$app->params['modules']) && $model->entity_type=='customer')))
							{
							?>
                            <a class="btn btn-xs btn-primary" href="index.php?r=estimate/estimate/generate-invoice&id=<?=$_GET['id']?>">

                                <i class="fa fa-file-text-o"></i> <?= Yii::t('app','Generate Invoice')?>
							<?php
							}
							?>

					
					<?= Html::a('<i class="fa fa-file-pdf-o"></i> '.Yii::t('app', 'Download Estimate'), ['/estimate/estimate/download','id'=>$model->id], ['class'=>'btn btn-primary btn-xs']) ?>
                            
					
					</div>
					</div>

                    <div class="ibox-content p-xl">

					<div class="row">
							<div class="col-sm-4">
								<?php
								if(Yii::$app->params['COMPANY_LOGO_ON_INVOICE'] == 'Yes')
								{
								?>
								<img width="140px" src="../logo/logo.png" class="img-responsive upload_logo">
								<?php
								}
								?>						
							</div>

							<div class="col-sm-8 text-right">
								<address style="margin-bottom:0px;">
									<strong class="text-navy"><?php echo Yii::t('app', 'Estimate Code'); ?>: </strong><?=$model->estimation_code?> <br>
									<?php
										date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
										$create_date=date('M d, Y', $model->date_issued);
									?>
									<strong><?php echo Yii::t('app', 'Estimate Date'); ?>:</strong> <?=$create_date?><br>
									<strong><?php echo Yii::t('app', 'Status'); ?>: <strong class="text-navy" <?=($model->estimate_status_id!==EstimateStatus::_APPROVED?"style='color: red;'":'')?>><?=$model->estimateStatus->label?></strong></strong><br>
								</address>
							</div>
						 </div>

						 <hr style="margin-bottom:10px;margin-top:10px;">



                            <div class="row">

                                <div class="col-sm-6">

                                    <h5><?php echo Yii::t('app', 'From'); ?>:</h5>

                                    <address>

                                        <strong> <?= Yii::$app->params['company']['company_name']?></strong><br>

                                        <?= Yii::$app->params['address']['address_1']?>  , <?= Yii::$app->params['address']['address_2']?><br>

                                        <?= Yii::$app->params['address']['city']?>, <?= Yii::$app->params['address']['state']?>, <?= Yii::$app->params['address']['country']?><br>

                                        <abbr title="Phone"><?php echo Yii::t('app', 'Phone'); ?>:</abbr> <?= Yii::$app->params['company']['phone']?>

                                    </address>

									<h5><?php echo Yii::t('app', 'Currency'); ?>: <?=$model->currency->currency?> (<?=$model->currency->alphabetic_code?>)</h5>

                                </div>



                                <div class="col-sm-6 text-right">

                                    
									
                                    <span><?php echo Yii::t('app', 'To'); ?>:</span>

                                    <address>
									<?php
										if(isset($custlead->customer_name))
										{
											$cusname=$custlead->customer_name;
										}
										else
										{
											$cusname=$custlead->lead_name;
										}

									?>

                                        <strong><?=$cusname?></strong><br>

                                        <?=$office_address->address_1?>, <?=$office_address->address_2?><br>

                                        <?=$office_address->country->country?>, <?=$office_address->state->state?>, <?=$office_address->city->city?><br>

                                        <abbr title="Phone"><?php echo Yii::t('app', 'Phone'); ?>: </abbr> <?=$custlead->phone?>

                                    </address>

                                    <p>
										<?php
											date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
											$date_issued=date('M d,Y', $model->date_issued);
										?>
                                       <!-- <span><strong>Date:</strong> <?=date('M d,Y',strtotime($model->date_issued))?></span><br/> -->
									  <!-- <span><strong>Date:</strong> <?=$date_issued?></span><br/>-->

                                       <!-- <span><strong>Due Date:</strong> March 24, 2014</span>-->

                                    </p>

                                </div>

                            </div>



                            <div class="table-responsive m-t">

                                <table class="table invoice-table">

                                    <thead>

                                    <tr>

                                        <th><?php echo Yii::t('app', 'Item List'); ?></th>

                                        <th><?php echo Yii::t('app', 'Quantity'); ?></th>

                                        <th><?php echo Yii::t('app', 'Unit Price'); ?></th>

                                        <th><?php echo Yii::t('app', 'Tax'); ?></th>

                                        <th><?php echo Yii::t('app', 'Total Price'); ?></th>

                                    </tr>

                                    </thead>

                                    <tbody>

                                    <?php 
										if(count($estimateDetails) > 0){
											foreach($estimateDetails as $detail){
									?>
                                    <tr>
                                        <td><?=$detail['description']?></td>
                                        <td><?=$detail['quantity']?></td>
                                        <td><?=$detail['rate']?></td>
                                        <td><?="<i style='font-size:10px'>".$detail['tax']['name']." (".$detail['tax']['tax_percentage'].") </i> ".$detail['tax_amount']?></td>	
                                        <td align="right"><?=$detail['total']?></td>
                                    </tr>
									<?php
											}
										}
									?>



                                    </tbody>

                                </table>

                            </div><!-- /table-responsive -->



                            <table class="table invoice-total">

                                <tbody>
								<tr>
									<td rowspan=5>
										<div class="col-sm-12">
											<?php
											if(Yii::$app->params['COMPANY_SEAL_ON_INVOICE'] == 'Yes')
											{
											?>
											
													<img width="140px" src="../logo/seal.png" class="img-responsive">
										
											<?php
											}
											?>
										</div>
									</td>
								</tr>
                                
                                <tr>

                                    <td><strong><?= Yii::t('app','Total Tax')?> : </strong></td>

                                    <td><?=$model->total_tax_amount?></td>

                                </tr>

								<tr>

                                    <td><strong><?php echo Yii::t('app', 'Sub Total'); ?> : </strong></td>

                                    <td><?=$model->sub_total?></td>

                                </tr>

								

								<tr>

                                    <td><strong><?php echo Yii::t('app', 'Discount'); ?> : </strong></td>

                                    <td><?=$model->discount_amount?></td>

                                </tr>

                                <tr>

                                    <td><strong><?php echo Yii::t('app', 'TOTAL'); ?> : </strong></td>

                                    <td><?=$model->currency->alphabetic_code?> <?=$model->grand_total?></td>

                                </tr>

                                </tbody>

                            </table>

                            <div class="text-right">

                                <!--<button class="btn btn-primary"><i class="fa fa-dollar"></i> Make A Payment</button>-->
								<?php
								if(Yii::$app->user->identity->userType->type!="Customer")
								{
								?>
									<a href="index.php?r=estimate/estimate/update&id=<?=$model->id?>&edit=t&entity_type=<?=$model->entity_type?>" class="btn btn-info btn-sm" onClick=""> <?= Yii::t('app','Edit')?></a>
									
									<?php
									/*if ($model->estimate_status_id != EstimateStatus::_APPROVED)
									{
									?>
										<a href="index.php?r=estimate/estimate/update&id=<?=$model->id?>&rqst=sendapprove" class="btn btn-success btn-sm" onClick=""> <?= Yii::t('app','Send Estimate for Approval')?></a>
									<?php
									}*/

								}

								if($model->estimate_status_id !=EstimateStatus::_APPROVED)
								{
								?>
									<a href="index.php?r=estimate/estimate/update&id=<?=$model->id?>&rqst=approve" class="btn btn-info btn-sm" onClick=""> <?= Yii::t('app','Approve Estimate')?></a>
								<?php
								}

								if($model->estimate_status_id !=EstimateStatus::_REJECTED)
								{
								?>
									<a href="index.php?r=estimate/estimate/update&id=<?=$model->id?>&rqst=reject" class="btn btn-danger btn-sm" onClick=""> <?= Yii::t('app','Reject Estimate')?></a>
								<?php
								}
								?>
                            </div>

                        </div>

                </div>

            </div>

        </div>
</div>
