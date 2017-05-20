<?php
	function validate_parameter($mysqli,$paramter)
	{
	    $paramter = stripslashes($paramter);
	    $paramter = mysqli_real_escape_string($mysqli,$paramter); 
	    return $paramter;
	}

	function count_total_opinions($mysqli, $object_id){
		$sql = "SELECT  COUNT(*) AS total_opinions FROM opinions WHERE objectId = $object_id";
	    $result = mysqli_query($mysqli,$sql);
	    $result = mysqli_fetch_assoc($result);
	    return $result["total_opinions"];
	}

	function count_mean_score($mysqli, $object_id){
		$sql = "SELECT COALESCE(sum(score), 0) AS total_score FROM opinions WHERE objectId = $object_id";
	    $result = mysqli_query($mysqli,$sql);
	    $result = mysqli_fetch_assoc($result);
	    return $result["total_score"];
	}

	function select_user_signed($mysqli,  $id, $object_id, $date_ini, $date_end){
    	$sql = "SELECT * FROM visits WHERE objectId = '$object_id' and userId = '$id' and (((visitStart between STR_TO_DATE('$date_ini', '%d/%m/%Y') and STR_TO_DATE('$date_end', '%d/%m/%Y') or visitEnd between STR_TO_DATE('$date_ini', '%d/%m/%Y') and STR_TO_DATE('$date_end', '%d/%m/%Y')) or (STR_TO_DATE('$date_ini', '%d/%m/%Y') between visitStart and VisitEnd) or (STR_TO_DATE('$date_end', '%d/%m/%Y') between visitStart and visitEnd)))";
		$result = mysqli_query($mysqli,$sql);
		return mysqli_num_rows($result);
	}

    function select_visits($mysqli, $object_id, $date_ini, $date_end){
    	$sql = "SELECT * FROM visits WHERE objectId = '$object_id' and (((visitStart between STR_TO_DATE('$date_ini', '%d/%m/%Y') and STR_TO_DATE('$date_end', '%d/%m/%Y')) or (visitEnd between STR_TO_DATE('$date_ini', '%d/%m/%Y') and STR_TO_DATE('$date_end', '%d/%m/%Y')) or (STR_TO_DATE('$date_ini', '%d/%m/%Y') between visitStart and VisitEnd) or (STR_TO_DATE('$date_end', '%d/%m/%Y') between visitStart and visitEnd)))";
    	return mysqli_query($mysqli,$sql);  
    }

	function select_user_email($mysqli, $email){
		$sql = "SELECT userId FROM users WHERE userEmail = '$email'";
	    return mysqli_query($mysqli,$sql);
	}

	function select_user_id($mysqli, $id){
		$sql = "SELECT * FROM users WHERE userId = '$id'";
		$result = mysqli_query($mysqli,$sql);
		if($result && mysqli_num_rows($result) > 0){
			return mysqli_fetch_assoc($result);
		} else {
			return 0;
		}
	}

	function select_opinions_object($mysqli, $object_id){
		$sql = "SELECT * FROM opinions WHERE objectId = '$object_id' ORDER BY opinionDate DESC";
		return mysqli_query($mysqli,$sql);
	}

	function select_opinions_limit($mysqli, $object_id){
		$sql = "SELECT * FROM opinions WHERE objectId = '$object_id' ORDER BY opinionDate DESC LIMIT 2";
		return mysqli_query($mysqli,$sql);
	}

	function select_object_id($mysqli, $id){
		$sql = "SELECT * FROM objects WHERE objectId = $id";
		return mysqli_query($mysqli,$sql);
	}

	function select_object_city($mysqli, $city, $type){
		$sql = "SELECT * FROM objects WHERE objectCity = '$city' and objectType = '$type'";
		return mysqli_query($mysqli,$sql);
	}

	function select_visit_id($mysqli, $id){
		$sql = "SELECT * FROM visits WHERE userId = '$id' ORDER BY visitStart DESC";
		return mysqli_query($mysqli,$sql);
	}

	function select_opinion_id($mysqli, $id){
  		$sql = "SELECT * FROM opinions WHERE userId = '$id' ORDER BY opinionDate DESC";
 		return mysqli_query($mysqli,$sql);
	}

	function select_messages_by_id($mysqli, $id){
		$sql = "SELECT * FROM messages WHERE userRxId = '$id' || userTxId = '$id' ORDER BY messageDate DESC";
		return mysqli_query($mysqli,$sql);
	}

	function login_select($mysqli, $email, $password){
	    $sql = "SELECT userId FROM users WHERE userEmail = '$email' and userPass = '".md5($password)."'";
	    $result = mysqli_query($mysqli,$sql);
	    $count =  mysqli_num_rows($result);
	    $result_fetch = mysqli_fetch_assoc($result);

	    if($count == 1) {
	      session_start();
	      $_SESSION['login_user'] = $email;
	      $_SESSION['login_id'] = $result_fetch["userId"];
	      header("location: search.php");
	    } else {
	        return "Incorrect email or password";
	    }
	}

	function insert_message($mysqli, $txId, $rxId, $title, $message){
        $sql = "INSERT INTO messages (userTxId,userRxId,messageTitle,messageText) VALUES ('$txId','$rxId','$title','$message')";
        $result = mysqli_query ($mysqli, $sql);
        if($result) {
            header("location: {$_SESSION['history']}");
        } else {
            return "Message couldn't be send. Try later.";
        }
 	}

 	function insert_opinion($mysqli, $id, $objectId, $rate, $opinion){
        $sql = "INSERT INTO opinions (userId,objectId,score,opinionText) VALUES ('$id','$objectId','$rate','$opinion')";
        $result = mysqli_query ($mysqli, $sql);
        if($result) {
            header("location: {$_SESSION['history']}");
        } else {
            return "Opinion couldn't be submitted. Try later.";
        }
 	}

 	function insert_visit($mysqli, $user_id_post, $object_id_post, $date_ini_post, $date_end_post){
    	$sql = "INSERT INTO visits (userId, objectId, visitStart, visitEnd) VALUES ('$user_id_post', '$object_id_post',STR_TO_DATE('$date_ini_post', '%d/%m/%Y'),STR_TO_DATE('$date_end_post', '%d/%m/%Y'))";
        return mysqli_query ($mysqli, $sql);
 	}

	function insert_user($mysqli, $email, $password, $name, $lastname, $dateofbirth, $gender){
	    $result = select_user_email($mysqli, $email);
	    $count = mysqli_num_rows($result);
	    $result_fetch = mysqli_fetch_assoc($result);
	    if($count == 0) { 
	      $sql = "INSERT INTO users (userEmail,userPass,userName,userLastName,userDBirth,userGender,userImage,userFriends) VALUES ('$email','".md5($password)."','$name','$lastname', STR_TO_DATE('$dateofbirth', '%d/%m/%Y'),'$gender','images/profile',',')";
	      $result = mysqli_query ($mysqli, $sql);
	      if($result) {
	        session_start();
	        $result = search_user_email($mysqli, $email);
	        $result_fetch = mysqli_fetch_assoc($result);
	        $_SESSION['login_user'] = $email;
	        $_SESSION['login_id'] = $result_fetch["userId"];
	        header("location: search.php");
	      } else{
	        return "Unexpected error"; 
	      }
	    } else {
	    return "User already exists in the database"; 
	    }
	}

	function update_user($mysqli, $id, $name, $lastname, $dateofbirth, $gender, $country, $city, $information){
	  $sql = "UPDATE users SET userName='$name', userLastName='$lastname', userDBirth=STR_TO_DATE('$dateofbirth', '%d/%m/%Y'), userGender='$gender', userCountry='$country', userCity='$city', userDescription='$information' WHERE userId='$id'";
	  return mysqli_query ($mysqli, $sql);
	}

	function update_user_friends($mysqli, $id, $userId, $option){
      if($option == "add"){
          $concat = $userId.",";
          $sql = "UPDATE users SET userFriends = CONCAT(userFriends, '$concat') WHERE userId = '$id'";
          return mysqli_query($mysqli,$sql);
      }
      else {
          $concat = ",".$userId.",";
          $sql = "UPDATE users SET userFriends = REPLACE(userFriends, '$concat', ',') WHERE userId = '$id'"; 
          return  mysqli_query($mysqli,$sql);
      }
	}

	function update_password($mysqli, $id, $password){
        $password = md5($password);
        $sql = "UPDATE users SET userPass='$password' WHERE userId='$id'";
        return mysqli_query($mysqli, $sql);
	}

	function update_profile_image($mysqli, $id, $image){
	    $target_dir = "upload_pictures/";
	    $target_file = $target_dir . basename($image["name"]);
	    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	    $check = getimagesize($image["tmp_name"]);
	    $error_upload = 1;
	    if($check !== false) {
	      $error_upload = 1;
	    } else {
	      $error_upload = 0;
	    }
	    if ($image["size"] > 500000) {
	      $error_upload = 0;
	    }
	    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
	      $error_upload = 0;
	    }
	    if ($error_upload == 0) {
	      return "Sorry, your image was not uploaded.";
	    } else {
	      if (move_uploaded_file($image["tmp_name"], $target_file)) {
	        $sql = "UPDATE users SET userImage='$target_file' WHERE userId='$id'";
	        $result = mysqli_query ($mysqli, $sql);
	        return "";
	      } else {
	        return "Sorry, there was an error uploading your file.";
	      }
	    }
	}

	function delete_opinion($mysqli, $id){
  		$sql = "DELETE FROM opinions WHERE opinionId = $opinionId";
 		return mysqli_query($mysqli,$sql);
	}

	function delete_visit($mysqli, $id){
  		$sql = "DELETE FROM visits WHERE visitId = $visitId";
 		return mysqli_query($mysqli,$sql);
	}

?>