<?php
// new version for Jan 2023 
// this file allows you to quickly add a list of tables and then links to a view of each tables data. 
// it uses GET variables to pass through which table. 
// minimal effort escaping so not safe for production. 

//example of one way to include this. 
/*
if(isset($_GET['page']) && $_GET['page']=='tableview')
{
	include "PHPQuickTableView.php";
	PHPQuickTableView($conn, "main.php?page=tableview");
}

*/

//$conn is the MySQL connection to use 
//$uri is the string to prepend hyperlinks. 
function PHPQuickTableView($conn,$uri)
{
	if(isset($_GET['table']))
	{
		echo "show tha table stuff. ";
		showtableview($conn,$uri,$_GET['table']);
	}
	else 
	{
		showtables($conn, $uri);// code...
	}
}

function showtables($conn, $uri)
{
	$sql = "SHOW TABLES";
	$result = mysqli_query($conn, $sql);
	while ($row=mysqli_fetch_array($result))
	{echo "<a href='$uri&table=".$row[0]."'>".$row[0]."</a><br>";}
}

function showtableview($conn,$uri,$table)
{
	//not safe but a minimal effort. 
	$table = mysqli_real_escape_string($conn,$table);
	$sql = "SELECT * from ".$table.";";
	$result = mysqli_query($conn, $sql);

	//lets get the feild names
	$fields = [];
	foreach(mysqli_fetch_fields($result) as $column){array_push($fields,$column->name);}

	//lets echo the table header. 
	echo "<table border=0><tr>";
	foreach ($fields as $feild) {echo "<th>".$feild."</th>";}	
	echo "</tr>";
	
	//and now fill it with the data. 
	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result)) 
		{
    		echo "<tr>";
    		foreach ($fields as $field)
        	{echo "<td>".$row[$field]."</td>";}
    		echo "</tr>";
	 	}
	} 
	else 
	{echo "<tr><td colspan=".sizeof($fields)." align='center'> No results found. </td></tr>";}
	echo "</table>";
}

?>
