<option value=""> --Select--</option>

<?php

	

	foreach($states as $row){?>

	<option value="<?=$row['id']?>" <?=$state_id==$row['id']?'selected':''?>><?=$row['state']?></option>	

<?php	}

?>