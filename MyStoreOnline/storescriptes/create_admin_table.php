<?php
require "connect_to_mysql.php";  

$sqlcommand = "CREATE TABLE admin ( 
				id int(11) NOT NULL auto_increment,
				username varchar(255) NOT NULL, 
				password varchar(255) NOT NULL, 
				last_log_date date NOT NULL, 
				PRIMARY KEY (id),
				UNIQUE KEY username(username)
				)";
if(mysqli_query($sqlcommand)){
	echo "Your admin table has been created successfully!";
}else{
	echo " ERROR " ;
}
?>