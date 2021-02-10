<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;
use livefactory\models\EstimateStatus;

/**
 * @var yii\web\View $this
 * @var livefactory\models\Estimate $model
 */

$this->title = Yii::t('app','Status : ').$model->estimateStatus->label;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Estimates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="estimate-view">
   <div class="row">

            <div class="col-lg-12">

                <div class="wrapper wrapper-content animated fadeInRight">

                    <div class="ibox-content p-xl">
						<table width="100%">
							<tr>
								<td>
									<?php
									if(Yii::$app->params['COMPANY_LOGO_ON_INVOICE'] == 'Yes')
									{
									?>
									<img width="140px" src="../logo/logo.png" class="img-responsive upload_logo">
									<?php
									}
									?>						
								</td>

								<td align="right">
									<address style="font-size:12px; line-height:25px">
										<p class="text-right text-navy"><strong><?php echo Yii::t('app', 'Estimate Code'); ?>: </strong><?=$model->estimation_code?> </p>
										<?php
											date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
											$create_date=date('M d, Y', $model->date_issued);
										?>
										<p class="text-right"><strong><?php echo Yii::t('app', 'Estimate Date'); ?>:</strong> <?=$create_date?></p>
										<p class="text-right"><strong><?php echo Yii::t('app', 'Status'); ?>: <strong class="text-navy" <?=($model->estimate_status_id!==EstimateStatus::_APPROVED?"style='color: red;'":'')?>><?=$model->estimateStatus->label?></strong></strong></p>
									</address>
								</td>
							</tr>
						</table>
						<br><br>

						<table width="100%">
                        	<tr>
								

                            	<td valign="top">
                                	<div class="col-sm-6">

                                    <h5><?php echo Yii::t('app', 'From'); ?>:</h5>

                                    <address>

                                        <p style="margin-bottom:10px"><strong> <?= Yii::$app->params['company']['company_name']?></strong></p>

                                        <p style="line-height:25px"><?= Yii::$app->params['address']['address_1']?>  , <?= Yii::$app->params['address']['address_2']?></p>

                                        <p style="line-height:25px"><?= Yii::$app->params['address']['city']?>, <?= Yii::$app->params['address']['state']?>, <?= Yii::$app->params['address']['country']?></p>

                                        <p style="line-height:25px"><?= Yii::t('app', 'Phone'); ?>:</abbr> <?= Yii::$app->params['company']['phone']?></p>

                                    </address>
										 <br><br><br>
									<h5><?php echo Yii::t('app', 'Currency'); ?>: <?=$model->currency->currency?> (<?=$model->currency->alphabetic_code?>)</h5>

                                </div>
                                </td>
                                <td align="right" valign="top">
                                	<div class="col-sm-6 text-right">

                                    
                                    <span>To:</span>

                                    <address style="font-size:12px">

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

                                  

                                </div>
                                </td>
                            </tr>
                        </table>
<br/><br/>


                            <div class="table-responsive m-t">

                                <table class="table estimate-table">

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
                                        <td style="font-size:12px"><?=$detail['description']?></td>
                                        <td style="font-size:12px"><?=$detail['quantity']?></td>
                                        <td style="font-size:12px"><?=$detail['rate']?></td>
                                        <td><?="<i style='font-size:10px'>".$detail['tax']['name']." (".$detail['tax']['tax_percentage'].") </i> ".$detail['tax_amount']?></td>	
                                        <td style="font-size:12px" align="right"><?=$detail['total']?></td>
                                    </tr>
									<?php
											}
										}
									?>



                                    </tbody>

                                </table>

                            </div><!-- /table-responsive -->



                            <table class="table estimate-total" align="right">

                                <tbody>
								

								<tr>
									<td rowspan=4 width="60%">
										<div class="col-sm-4">
											<?php
											if(Yii::$app->params['COMPANY_SEAL_ON_INVOICE'] == 'Yes')
											{
											?>
											<img width="100px" src="../logo/seal.png" class="img-responsive upload_seal">
											<?php
											}
											?>
										</div>
									</td>
								</tr>

                               
								<tr>

                                    <td style="font-size:12px"><strong><?= Yii::t('app','Total Tax')?> : </strong></td>

                                    <td style="font-size:12px" align="right"><?=$model->total_tax_amount?></td>

                                </tr>

                                <tr>

                                    
								<td style="font-size:12px"><strong><?php echo Yii::t('app', 'Sub Total'); ?> : </strong></td>

                                <td style="font-size:12px" align="right"><?=$model->sub_total?></td>

                                </tr>

                                <tr>

                                    <td style="font-size:12px"><strong><?php echo Yii::t('app', 'TOTAL'); ?> : </strong></td>

                                    <td style="font-size:12px" align="right"><?=$model->currency->alphabetic_code?> <?=$model->grand_total?></td>

                                </tr>

                                </tbody>

                            </table>

                        </div>

                </div>

            </div>

        </div>
</div>
