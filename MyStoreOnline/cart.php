<?php 
//connecting & error reporting & starting session
//Starting session
session_start();

//error reporting 
error_reporting(E_ALL);
ini_set('display_errors','1');

// Connect to database 
include("storescriptes/connect_to_mysql.php");
?>
<?php 
//1-add ITEM to cart
if(isset($_POST['pid'])){
	$pid = $_POST['pid'];
	$wasFound=false;
	$i = 0;
	if(!isset($_SESSION["cart_array"])||count($_SESSION["cart_array"])<1){
		$_SESSION["cart_array"] = array(0=> array("item_id"=>$pid,"quantity"=>1));
	}else{
		foreach($_SESSION["cart_array"]as$each_item){
			$i++;
			while(list($key,$value)=each($each_item)){
				if($key="item_id"&&$value==$pid){
					array_splice($_SESSION["cart_array"],$i-1,1,array(array("item_id"=>$pid,"quantity"=>$each_item['quantity']+1)));
					$wasFound=true;
				}
			}
		}
		if($wasFound==false){
			array_push($_SESSION["cart_array"],array("item_id"=>$pid,"quantity"=>1));
		}
	}
	header("location: cart.php");
	exit();
}
?>
<?php
//2-empty the shopping cart
if(isset($_GET['cmd'])&&$_GET['cmd']=="emptycart"){
	unset($_SESSION["cart_array"]);
}
?>
<?php
//3-change item quantity
if(isset($_POST['quantity_change'])&&$_POST['quantity_change']!=""){
	$quantity_change = $_POST['quantity_change'];
	$quantity = $_POST['quantity'];
	$quantity = preg_replace('#[^0-9]#i','',$quantity);
	if($quantity >=100){$quantity = 99;}
	if($quantity <=1){$quantity = 1;}
	$i=0;
	foreach($_SESSION["cart_array"]as$each_item){
		$i++;
		while(list($key,$value)=each($each_item)){
			if($key="item_id"&&$value==$quantity_change){
				array_splice($_SESSION["cart_array"],$i-1,1,array(array("item_id"=>$quantity_change,"quantity"=>$quantity)));
				}
			}
		}
}
?>
<?php 
//4- removing an item from cart
if(isset($_POST['index_to_remove'])&&$_POST['index_to_remove']!=""){
	$key_to_remove=$_POST['index_to_remove'];
	if(count($_SESSION["cart_array"])<=1){
		unset($_SESSION["cart_array"]);
	}else{
		unset($_SESSION["cart_array"]["$key_to_remove"]);
		sort($_SESSION["cart_array"]);
	}
	header("location: cart.php");
	exit();
}
?>
<?php 
//5-Rendar the cart
$cartOutput="";
$cartTotal="";
if(!isset($_SESSION["cart_array"])||count($_SESSION["cart_array"])<1){
	$cartOutput="<h2 align='center'>Your Shoppinh cart is empty</h2>";
}else{
	$i=0;
	foreach($_SESSION["cart_array"]as $each_item){
		$item_id = $each_item['item_id'];
		$sql = mysqli_query($connection,"SELECT * FROM products WHERE id = '$item_id' LIMIT 1");
		while($row = mysqli_fetch_array($sql)){
			$product_name = $row["product_name"];
			$price = $row["price"];
			$details = $row["details"];
		}
		$pricetotal = number_format($price * $each_item['quantity'],2);
		$cartTotal = number_format($pricetotal + $cartTotal,2);
		
		$cartOutput .='<tr>';
		$cartOutput .= '<td><a href="product.php?id='.$item_id.'">'.$product_name.'</a><br/> <img src="inventory_images/'.$item_id.'.jpg" alt="'.$product_name.'" width="40" height="52" border="1"/></td>';
		$cartOutput .= '<td>'.$details. '</td>';
		$cartOutput .= '<td>$'.$price. '</td>';
		$cartOutput .= '<td><form action="cart.php" method="post">
		<input name="quantity" type="text" value="'.$each_item['quantity']. '" size="1" maxlength="2" />
		<input name="changeBtn'.$item_id.'" type="submit" value="change" />
		<input name="quantity_change" type="hidden" value="'.$item_id.'"/>
		</form></td>';
		//$cartOutput .= '<td>'.$each_item['quantity']. '</td>';
		$cartOutput .= '<td>$'.$pricetotal. '</td>';
		$cartOutput .= '<td><form action="cart.php" method="post"><input name="deleteBtn'.$item_id.'" type="submit" value="X" /><input name="index_to_remove" type="hidden" value="'.$i.'" /></form></td>';
		$cartOutput .= '</tr>';
		$i++;
	}
	$cartTotal = "Cart Total: $".$pricetotal;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Your Cart</title>
<link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />
</head>

<body>
<div align="center" id="mainWrapper">
  <?php include_once("template_header.php");?>
  <div id="pageContent">
    <div style="margin:24px" align="left">
    <br/>
    <table width="100%" border="1" cellspacing="0" cellpadding="6">
      <tr>
        <th width="17%" bgcolor="#FFFFCC">Product</th>
        <th width="35%" bgcolor="#FFFFCC">Product Details</th>
        <th width="12%" bgcolor="#FFFFCC">Unit Price</th>
        <th width="13%" bgcolor="#FFFFCC">Quantity</th>
        <th width="12%" bgcolor="#FFFFCC">Total</th>
        <th width="11%" bgcolor="#FFFFCC">Remove</th>
      </tr>
      <?php echo $cartOutput; ?>
    <!--  <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr> -->
    </table>
    <div align="right"><?php echo $cartTotal; ?><br/> </div>
    <a href="cart.php?cmd=emptycart">Click Here to Empty your Shopping Cart</a>
    </div>
    <br/>  
  </div>
  <?php include_once("template_footer.php"); ?>
</div>
</body>
</html>