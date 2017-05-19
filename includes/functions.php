<?php
	function validate_parameter($mysqli,$paramter)
	{
	    $paramter = stripslashes($paramter);
	    $paramter = mysqli_real_escape_string($mysqli,$paramter); 
	    return $paramter;
	}

	function search_user_email($mysqli, $email){
		$sql = "SELECT userId FROM users WHERE userEmail = '$email'";
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

	function insert_user($mysqli, $email, $password, $name, $lastname, $dateofbirth, $gender){
	    $result = search_user_email($mysqli, $email);
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

	function select_user_id($mysqli, $id){
		$sql = "SELECT * FROM users WHERE userId = '$id'";
		$result = mysqli_query($mysqli,$sql);
		return mysqli_fetch_assoc($result);
	}

?>