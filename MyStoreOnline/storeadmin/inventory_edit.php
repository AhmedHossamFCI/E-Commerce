<?php 
//SESSION
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

<?php
//parsing and adding
if(isset($_POST['product_name']))
{
	$pid = mysqli_real_escape_string($connection,$_POST['ThisID']);
	$product_name = mysqli_real_escape_string($connection,$_POST['product_name']);
	$price = mysqli_real_escape_string($connection,$_POST['product_price']);
	$category = mysqli_real_escape_string($connection,$_POST['category']);
	$subcategory = mysqli_real_escape_string($connection,$_POST['subcategory']);
	$details = mysqli_real_escape_string($connection,$_POST['product_details']);
	$sql = mysqli_query($connection,"UPDATE products SET product_name ='$product_name', price ='$price', details ='$details', category ='$category',subcategory ='$subcategory' WHERE id = '$pid'");
	if($_FILES['fileField']['tmp_name']!=""){
		$newname = "$pid.jpg";
		move_uploaded_file($_FILES['fileField']['tmp_name'],"../inventory_images/$newname");
	}
	header("location: inventory_list.php");
	exit();
}
?>
<?php 
if(isset($_GET['pid'])){
	$targetID = $_GET['pid'];
	$sql = mysqli_query($connection,"SELECT * FROM `products` WHERE id = '$targetID'");
	$productCount = mysqli_num_rows($sql);
	if($productCount > 0){
		while($row = mysqli_fetch_array($sql)){
			$product_name = $row ["product_name"];
			$price = $row["price"];
			$category = $row["category"];
			$subcategory = $row["subcategory"];
			$details = $row["details"];
			$date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
		}
}else{
	echo 'Sorry it does not exist';
	exit(); 
}
}
?>
<?php 
//viewing list

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Inventory List</title>
<link rel="stylesheet" href="../style/style.css" type="text/css" media="screen" />

</head>

<body>
<div align="center" id="mainWrapper">
  <?php include_once("../template_header.php");?>
  <div id="pageContent"><br />
    <div align="right" style="margin-right:32px"><a href="inventory_list.php#inventoryForm">+add new item</a></div>
<div align="left" style="margin-left:24px;">
      <h2>Inventory List</h2>
<?php /*?>      <?php echo $product_list; ?>
<?php */?>    
</div>
    <hr />
    <a name="inventoryForm" id="inventoryForm"> </a>
    <h3>Edit Item</h3>
    <form action="inventory_edit.php" enctype="multipart/form-data" name="AddItem" id="AddItem" method="post">
    <table width="794" border="0">
      <tr>
        <td width="20%" align="right">Product Name</td>
        <td width="80%"><label>
          <input type="text" name="product_name" id="product_name" size="42" value="<?php echo $product_name; ?>"/>
        </label></td>
      </tr>
      <tr>
        <td height="34" align="right">Product Price </td>
        <td>$ 
          <label>
            <input type="text" name="product_price" id="product_price" size="12px" value="<?php echo $price; ?>"/>
          </label></td>
      </tr>
      <tr>
        <td align="right">Category</td>
        <td><label>
          <select name="category" id="category" value="<?php echo $category; ?>">
          <option value="Games"> Games </option>
          </select>
        </label></td>
      </tr>
      <tr>
        <td align="right">Subcategory</td>
        <td><label>
          <select name="subcategory" id="subcategory">
          <option value="<?php echo $subcategory; ?>"><?php echo $subcategory; ?></option>
          <option value="PC">PCGames</option>
          <option value="XBOX">XBOX Games</option>
          <option value="PS">PS Games</option>
          </select>
        </label></td>
      </tr>
      <tr>
        <td align="right">Product Details</td>
        <td><label>
          <textarea name="product_details" id="product_details" cols="45" rows="5"><?php echo $details; ?></textarea>
        </label></td>
      </tr>
      <tr>
        <td align="right">Product image</td>
        <td><label>
          <input type="file" name="fileField" id="fileField" />
        </label></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <input name="ThisID" type="hidden" value="<?php echo $targetID; ?>" />
        <td><input type="submit" name="button" id="button" value="Edit Item" /></td>
      </tr>
      </table>
    </form>
<br />
      <br />
      <br />
    </h3>
  </div>
  <?php include_once("../template_footer.php"); ?>
</div>
</body>
</html>