<?php 
session_start();
if(!isset($_SESSION["manager"])){
	header("location:admin_login.php");
	exit();
}
$mangID = preg_replace('#[^0-9]#i','',$_SESSION["id"]);
$mang = preg_replace('#[^A-Za-z0-9]#i','',$_SESSION["manager"]);
$password = preg_replace('#[^A-Za-z0-9]#i','',$_SESSION["password"]);
include("../storescriptes/connect_to_mysql.php");
$sql = mysqli_query($connection,"SELECT * FROM admin WHERE id='$mangID' AND username='$mang' AND password='$password' LIMIT 1");
$existCount = mysqli_num_rows($sql);
if($existCount == 0) 
{
	echo'Error';
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Store Admin Page</title>
<link rel="stylesheet" href="../style/style.css" type="text/css" media="screen" />

</head>

<body>
<div align="center" id="mainWrapper">
  <?php include_once("../template_header.php");?>
  <div id="pageContent"><br />
    <div align="left" style="margin-left:24px;">
      <h2>Hi manager, what would you like to do today?</h2>
      <p><a href="inventory_list.php">Manage Inventory</a><br />
      <a href="#">Manage anything </a></p>
    </div>
    <br />
  <br />
  <br />
  </div> 
  <?php include_once("../template_footer.php"); ?>
</div>
</body>
</html>