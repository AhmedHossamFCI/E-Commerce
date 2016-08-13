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
//Delete
if(isset($_GET['deleteid'])){
	echo'Do You want to delete product with ID '.$_GET['deleteid'].'?<a href="inventory_list.php?yesdelete='.$_GET['deleteid'].'">YES</a>|<a href="inventory_list.php">NO</a>';
	exit();
}
if(isset($_GET['yesdelete'])){
	//remove item..
	$id_to_delete = $_GET['yesdelete'];
	$sql = mysqli_query($connection,"DELETE FROM products WHERE id = '$id_to_delete' LIMIT 1 ") or die(mysqli_error());
	$pictodelete = ("../inventory_images/$id_to_delete.jpg");
	if(file_exists($pictodelete)){
		unlink($pictodelete);
	}
	header("location: inventory_list.php");
	exit();
}
?>
<?php
//parsing and adding
if(isset($_POST['product_name']))
{
	$product_name = mysqli_real_escape_string($connection,$_POST['product_name']);
	$price = mysqli_real_escape_string($connection,$_POST['product_price']);
	$category = mysqli_real_escape_string($connection,$_POST['category']);
	$subcategory = mysqli_real_escape_string($connection,$_POST['subcategory']);
	$details = mysqli_real_escape_string($connection,$_POST['product_details']);
	$sql = mysqli_query($connection,"SELECT id FROM products WHERE product_name = '$product_name' LIMIT 1 ");
	$productMatch = mysqli_num_rows($sql);
	if($productMatch > 0){
		echo'Sorry this product "Product Name" is into inventory already <a href="inventory_list.php">clickhere</a>';
		exit();
	}
	$sql=mysqli_query($connection,"INSERT INTO products(product_name, price, details, category, subcategory, `date_added`) VALUES ('$product_name','$price','$details','$category','$subcategory',now())") or die(mysqli_error());
	$pid = mysqli_insert_id($connection);
	$newname = "$pid.jpg";
	move_uploaded_file($_FILES['fileField']['tmp_name'],"../inventory_images/$newname");
	header("location: inventory_list.php");
	exit();
}
?>
<?php 
//viewing list
$product_list = "";
$sql = mysqli_query($connection,"SELECT * FROM products ORDER BY date_added DESC");
$productCount = mysqli_num_rows($sql);
if($productCount > 0){
	while($row = mysqli_fetch_array($sql)){
		
		$id = $row ["id"];
		$product_name = $row ["product_name"];
		$price = $row["price"];
		$date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
		$product_list .= "Product ID: $id - <strong>$product_name</strong> - $$price - <em>Added $date_added</em> &nbsp; &nbsp; &nbsp; <a href='inventory_edit.php?pid=$id'>edit</a> &bull; <a href='inventory_list.php?deleteid=$id'>delete</a><br />";
	}
}else{
	$product_list="The product list empty";
}
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
      <?php echo $product_list; ?>
    </div>
    <hr />
    <a name="inventoryForm" id="inventoryForm"> </a>
    <h3>Add New Item</h3>
    <form action="inventory_list.php" enctype="multipart/form-data" name="AddItem" id="AddItem" method="post">
    <table width="794" border="0">
      <tr>
        <td width="20%" align="right">Product Name</td>
        <td width="80%"><label>
          <input type="text" name="product_name" id="product_name" size="42"/>
        </label></td>
      </tr>
      <tr>
        <td height="34" align="right">Product Price </td>
        <td>$ 
          <label>
            <input type="text" name="product_price" id="product_price" size="12px"/>
          </label></td>
      </tr>
      <tr>
        <td align="right">Category</td>
        <td><label>
          <select name="category" id="category">
          <option value="Games"> Games </option>
          </select>
        </label></td>
      </tr>
      <tr>
        <td align="right">Subcategory</td>
        <td><label>
          <select name="subcategory" id="subcategory">
          <option value=""></option>
          <option value="PC">PCGames</option>
          <option value="XBOX">XBOX Games</option>
          <option value="PS">PS Games</option>
          </select>
        </label></td>
      </tr>
      <tr>
        <td align="right">Product Details</td>
        <td><label>
          <textarea name="product_details" id="product_details" cols="45" rows="5"></textarea>
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
        <td><input type="submit" name="button" id="button" value="Add new item" /></td>
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