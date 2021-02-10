<option value=""> --Select--</option>

<?php

	foreach($cities as $row){?>

	<option value="<?=$row['id']?>" <?=$city_id==$row['id']?'selected':''?>><?=$row['city']?></option>	

<?php	}

?>