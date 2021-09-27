<?php

function isEmpty($val){
if($val===0){
return true;
}else if (empty($val) && $val !== '0') {
    return true;
}else{
	return false;
}
}

?>
