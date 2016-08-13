<?php 
//viewing list

include("storescriptes/connect_to_mysql.php");

$dynamicList = "";
$sql = mysqli_query($connection,"SELECT * FROM products ORDER BY date_added DESC LIMIT 6");
$productCount = mysqli_num_rows($sql);
if($productCount > 0){
	while($row = mysqli_fetch_array($sql)){
		
		$id = $row ["id"];
		$product_name = $row ["product_name"];
		$price = $row["price"];
		$date_added = strftime("%b %d, %Y", strtotime($row["date_added"]));
		$dynamicList .= '<table width="100%" border="0" cellspacing="0" cellpadding="6">
        <tr>
          <td width="23%" valign="top"><a href="product.php?id='.$id.'"><img style="border:#000 2px solid" src="inventory_images/'.$id.'.jpg" width="110" height="150" alt="'.$product_name.'" /></a></td>
          <td width="77%" valign="top">'.$product_name.'<br />
            $'.$price.'<br />
            <a href="product.php?id='.$id.'">View Product</a></td>
        </tr>
      </table>';
	}
}else{
	$dynamicList="EMPTY STORE";
}
mysqli_close($connection);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Store Home Page</title>
<link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />

</head>

<body>
<div align="center" id="mainWrapper">
  <?php include_once("template_header.php");?>
  <div id="pageContent">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="31%" valign="top"><h3><strong>What is this site?</strong></h3>
      <p>this store to sall games <br />
        like (&quot;BF4, COD-AW, ... etc)
      </p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
    <td width="37%" valign="top"><p>Newest items in the Store</p>
      <p><?php echo $dynamicList; ?><br />
        </p>
<!--      <table width="100%" border="0" cellspacing="0" cellpadding="6">
        <tr>
          <td width="23%" valign="top"><a href="product.php?"><img style="border:#000 2px solid" src="inventory_images/4.jpg" width="110" height="150" alt="$dynamicTitle" /></a></td>
          <td width="77%" valign="top">Product Title<br />
            Product Price<br />
            <a href="product.php?">View Product</a></td>
        </tr>
      </table>  -->
      <p><br />
      </p></td>
    <td width="32%" valign="top"><h3>merchants</h3>
      <p>Ahmed hossam x'D </p></td>
  </tr>
</table>

  </div>
  <?php include_once("template_footer.php"); ?>
</div>
</body>
</html>