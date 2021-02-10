<?php
use livefactory\models\User;

?>
<div class="modal fade exist_users">

  <div class="modal-dialog">

    <div class="modal-content">

   	  <div class="modal-header">

        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

        <h4 class="modal-title"><?=Yii::t('app', 'Add Users to Queue')?></h4>

      </div>

      <div class="modal-body" style="height:350px; overflow:auto">

      	<form action="" method="post">

        <?php Yii::$app->request->enableCsrfValidation = true; ?>

    <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">

      	<table class="table table-bordered table-striped">

        	<thead>

        	<tr>

            	<th>&nbsp;</th>

                <th><?=Yii::t('app', 'First Name')?></th>

                <th><?=Yii::t('app', 'Last Name')?></th>

                <th><?=Yii::t('app', 'Username')?></th>

				<th><?=Yii::t('app', 'User Type')?></th>
            </tr>

            </thead>

      	<?php

			if(count($queueUserModel) >0){

			foreach($queueUserModel as $urow){

			?>

            	<tr>

                	<td width="10"><input type="checkbox" name="q_users[]" value="<?=$urow['id']?>"></td> 

                	<td><?=$urow['first_name']?></td>

                    <td><?=$urow['last_name']?></td>

                    <td><?=$urow['username']?></td>
					
					<td><?=User::findOne($urow['id'])->userType->label?></td>

                </tr>

            <?php } 

			}else{

			?>

            <tr><td colspan="4"><?=Yii::t('app', 'no result')?></td></tr>

            

            <?php } ?>

        </table>

        <div class="form-group">

        	<input type="submit" value="<?=Yii::t('app', 'Add Selected Users to Queue')?>" class="btn btn-success btn-sm">

        </div>

        </form>

      </div>

   </div>

</div>

</div>