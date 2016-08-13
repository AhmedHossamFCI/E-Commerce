<?php 
session_start();
if(isset($_SESSION["manager"])){
	header("location:index.php");
	exit();
}
?>

<?php
if(isset($_POST["username"])&&isset($_POST["password"])){
	
	$mang = preg_replace('#[^A-Za-z0-9]#i','',$_POST["username"]);
	$password = preg_replace('#[^A-Za-z0-9]#i','',$_POST["password"]);
	include"../storescriptes/connect_to_mysql.php";
	$sql = mysqli_query($connection,"SELECT id FROM admin WHERE username='$mang' AND password='$password' LIMIT 1");
	$existCount = mysqli_num_rows($sql);
    if ($existCount == 1) {
	     while($row = mysqli_fetch_array($sql)){ 
             $id = $row["id"];
		 }
		 $_SESSION["id"] = $id;
		 $_SESSION["manager"] = $mang;
		 $_SESSION["password"] = $password;
		 header("location: index.php");
         exit();
    }
	else{
		echo'the inforamtion is incorrect, try again <a href="index.php"> Click here </a>';
		exit();
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin Login Page</title>
<link rel="stylesheet" href="../style/style.css" type="text/css" media="screen" />

</head>

<body>
<div align="center" id="mainWrapper">
  <?php include_once("../template_header.php");?>
  <div id="pageContent"> <br/>
    <div align="left" style="margin-left:24px">    
    <h2>Log in for admin Page</h2>
    <form id="loginform" method="post" action="admin_login.php">
    User Name: <br/>
    <input name="username" type="text" /> 
    <br/> <br/>
    Password: <br/>
    <input name="password" type="password" />
    <br/>
    <br/>
    <br/>
    <input id="button" type="submit" name="button" value="Log in" />
    </form>
    </div>
<br/>
<br/>
  </div>  
  <?php include_once("../template_footer.php"); ?>
</div>
</body>
</html>