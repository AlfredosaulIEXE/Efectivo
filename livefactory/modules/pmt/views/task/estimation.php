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
$this->title = Yii::t ( 'app', 'Estimation' );
$this->params ['breadcrumbs'] [] = $this->title;



?>
<link rel="stylesheet" type="text/css" href="./jsgantt/jsgantt.css" />
<script language="javascript" src="./jsgantt/jsgantt.js"></script>


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
                                        <th>Project</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php
									$sql_project="select * from tbl_project where project_status_id not in ('3','4') order by project_name desc";
									$connection = \Yii::$app->db;
									$command_project=$connection->createCommand($sql_project);
									$datareader_project = $command_project->query();
									while (($project = $datareader_project->read()) !== false)
									{
										$project_name = $project['project_name'];
										$project_id = $project['id'];
										
														// tasks
										$sql="select t.*,u.first_name from tbl_task t
													join tbl_user u
													where t.user_assigned_id = u.id and t.project_id = '$project_id' order by expected_start_datetime asc";
										
//										echo $sql;

										$command=$connection->createCommand($sql);
										$datareader = $command->query();
										$count = 1;
										date_default_timezone_set(Yii::$app->params['TIME_ZONE']);
										$str = '';
										while (($task = $datareader->read()) !== false)
										{
											$task_name = $task['task_id']." - ".substr($task['task_name'], 0, 25);
											$task_url = $_SESSION['base_url'].Yii::$app->request->baseUrl."/index.php?r=pmt/task/task-view&id=".$task[id];
											$task_user = $task['first_name'];
											if($task['expected_start_datetime'])
												$start = date("n/j/Y", $task['expected_start_datetime']);
											else
												$start = date('n/j/Y', time());
											if($task['expected_end_datetime'])
											$end = date("n/j/Y", $task['expected_end_datetime']);
											else
												$end = date('n/j/Y', time());
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
											<th class="col-xs-3"><?php echo $project_name; ?></th>
											<td colspan="4">
											<div style="position:relative" class="gantt" id="GanttChartDIV<?php echo $project_id; ?>"></div>

											<script>

											  var g = new JSGantt.GanttChart('g',document.getElementById('GanttChartDIV<?php echo $project_id; ?>'), 'day');
											  g.setShowRes(1); // Show/Hide Responsible (0/1)
											  g.setShowDur(0); // Show/Hide Duration (0/1)
											  g.setShowComp(1); // Show/Hide % Complete(0/1)
											  g.setCaptionType('Resource');  // Set to Show Caption
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
           
           





