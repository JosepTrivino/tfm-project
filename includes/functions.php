<?php
	function validate_parameter($mysqli,$paramter)
	{
	    $paramter = stripslashes($paramter);
	    $paramter = mysqli_real_escape_string($mysqli,$paramter); 
	    return $paramter;
	}
?>