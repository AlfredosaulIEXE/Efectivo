<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use livefactory\models\User;
use livefactory\models\TaskPriority;
use livefactory\models\TaskStatus;
use livefactory\models\Project;
use yii\helpers\ArrayHelper;

/**
 *
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\models\search\Task $searchModel
 */
$this->title = Yii::t ( 'app', 'Allocation' );
$this->params ['breadcrumbs'] [] = $this->title;



?>
<link rel="stylesheet" type="text/css" href="./jsgantt/jsgantt.css" />
<script language="javascript" src="./jsgantt/jsganttallocation.js"></script>


 <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <!--<div class="ibox-title">
                        <h5>Grid options</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>-->
                    <div class="ibox-content">

	                          <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Team</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php
									$sql_queue="select * from tbl_queue where active = 1 order by queue_title desc";
									$connection = \Yii::$app->db;
									$command_queue=$connection->createCommand($sql_queue);
									$datareader_queue = $command_queue->query();
									while (($queue = $datareader_queue->read()) !== false)
									{
										$queue_name = $queue['queue_title'];
										$queue_id = $queue['id'];
										
														// tasks
										$sql="select t.*,u.first_name,p.project_id,p.project_name from tbl_task t, tbl_user u, tbl_project p
													where t.user_assigned_id = u.id and
													t.project_id = p.id 
													and u.id in (select distinct(user_id) from tbl_queue_users where queue_id = $queue_id) order by u.first_name, t.expected_start_datetime asc";
										
									//	echo $sql;
										$command=$connection->createCommand($sql);
										$datareader = $command->query();
										$count = 1;
										date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
										$str = '';
										while (($task = $datareader->read()) !== false)
										{
											$task_name = $task['task_id']." - ".$task['task_name']."&nbsp;&nbsp;&nbsp;".$task['project_id']." - ".$task['project_name'];
											$task_url = $_SESSION['base_url'].Yii::$app->request->baseUrl."/index.php?r=pmt/task/task-view&id=".$task[id];
											$task_user = $task['first_name'];

											if($task['expected_start_datetime'])
												$start = date("n/j/Y", $task['expected_start_datetime']);
											else
												$start = '""';
											if($task['expected_end_datetime'])
											$end = date("n/j/Y", $task['expected_end_datetime']);
											else
												$end = '""';

											if($task['task_progress'])
											$task_progress = $task['task_progress'];
											else
												$task_progress = 0;

											$str .= "g.AddTaskItem(new JSGantt.TaskItem($count,   '".$task_name."', '".$start."', '".$end."', 'ADFF2F', '".$task_url."', 0, '".$task_user."', '".$task_progress."', 0, 0, 0));\n";
											$datareader->next();
											$count++;
										}
											
											?>
											
											<?php
											if($count > 1)
											{
											?>
											<tr>
											<th class="col-xs-3"><?php echo $queue_name; ?></th>
											<td colspan="4">
											<div style="position:relative" class="gantt" id="GanttChartDIV<?php echo $queue_id; ?>"></div>

											<script>

											  var g = new JSGantt.GanttChart('g',document.getElementById('GanttChartDIV<?php echo $queue_id; ?>'), 'day');
											  g.setShowRes(0); // Show/Hide Responsible (0/1)
											  g.setShowDur(0); // Show/Hide Duration (0/1)
											  g.setShowComp(1); // Show/Hide % Complete(0/1)
											  g.setCaptionType('Caption');  // Set to Show Caption
											//  g.setShowStartDate(0); // Show/Hide Start Date(0/1)
											//  g.setShowEndDate(0); // Show/Hide End Date(0/1)

											  if( g ) {
												<?php echo $str; ?>
												g.Draw();	
												g.DrawDependencies();
											  }
											  else
											  {
												alert("not defined");
											  }

											</script>

											</td>
											</tr>

											<?php
											}
											?>
									<?php

									}

									?>
                                    
                                    </tbody>
                                </table>
                            </div>
                           
                    </div>
                </div>
            </div>

            </div>
           
           





