<?php

$ag_url_desc = $_POST["ag_url_desc"];
if(file_exists($ag_url_desc)){
	if(unlink($ag_url_desc)){
	    echo "removed";
	};
}else{
    echo "noDesc";
}

?>
