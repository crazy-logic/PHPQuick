<?php
// new version for Jan 2023 
// this file allows you to quickly add a analytics to a project. 
// include this before any headers or contect is returned 
// include "PHPQuickAnalytics.php";
// call analytics_record($conn); to record a view - $conn being the database. 
// call analytics_session($conn); for a view of the analytics. 
// call analytics_report($conn); for a day's worth of aanalytics - this is returned rather than echoed as it's designed to be used as a scheduled mail thing. 
// no escaping so not safe for production. 

//if the table doesn't exist it creates it. $conn must exist before this is included.
//once created this function could be removed. 



//specify which DB to use? 
//$conn;
mysqli_report(MYSQLI_REPORT_OFF);
$result = mysqli_query($conn, "SELECT 1 FROM `Analytics` LIMIT 1 ");
mysqli_report(MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT);
if(!$result && mysqli_errno($conn)==1146)
{
	//echo "no table and correct error no";
	//create table. 
	
	$sql = "CREATE TABLE Analytics (
	request_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	address VARCHAR(255) NOT NULL,
	uri VARCHAR(255) NOT NULL,
	referer VARCHAR(255),
	agent VARCHAR(255),
	session_id varchar(50),
	date DATETIME DEFAULT CURRENT_TIMESTAMP
	)";

	mysqli_query($conn, $sql);
}




if (!isset($_SESSION))
{
	session_start();
}





function analytics_record($conn)
{
	$address = $_SERVER['REMOTE_ADDR'];
	$uri = $_SERVER['REQUEST_URI'];
	if(isset($_SERVER['HTTP_REFERER'])){$referer = $_SERVER['HTTP_REFERER'];}else{$referer='';}
	$agent = $_SERVER['HTTP_USER_AGENT'];
	$session = session_id();
	
	$sql = "INSERT INTO Analytics (request_id, address, uri, referer, agent, date, session_id)
	VALUES ('', '$address', '$uri', '$referer', '$agent', NOW(), '$session')";
	mysqli_query($conn, $sql);
}

function analytics_sessions($conn)
{
	echo "<h2>Unique users</h2>";
	
	$sql = "SELECT CONCAT(address,'_',agent,'_',session_id) AS CC, session_id FROM `Analytics` GROUP BY `session_id`;";
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
	
	
	//SELECT [activity_dt], count(*) FROM table1 GROUP BY hour( activity_dt ) 
	
	echo "<h2>By hour</h2>";
	
	$sql = "SELECT count(*) AS views, hour(`date`) AS Hour FROM `Analytics` GROUP BY hour(`date`);";
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
	
	
	echo "<h2>By pages</h2>";
	
	$sql = "SELECT COUNT(uri) AS Views, uri AS Page FROM `Analytics` GROUP BY uri;";
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

function analytics_report($conn) //this returns rather than echos 
{
	$reply = "";
	//$date = "2023-01-25 00:00:00";
	//mysql datetime format 
	$date = date("Y-m-d H:i:s", (time()-(24*60*60)));
	
	
	
	$reply .= "<h2>Unique users report</h2>";
	
	$sql = "SELECT CONCAT(address,'_',agent,'_',session_id) AS CC, session_id FROM `Analytics` WHERE `date` > '$date'  GROUP BY `session_id`;";
	$result = mysqli_query($conn, $sql);

	//lets get the feild names
	$fields = [];
	foreach(mysqli_fetch_fields($result) as $column){array_push($fields,$column->name);}

	//lets echo the table header. 
	$reply .= "<table border=0><tr>";
	foreach ($fields as $feild) {$reply .= "<th>".$feild."</th>";}	
	$reply .= "</tr>";
	
	//and now fill it with the data. 
	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result)) 
		{
    		$reply .= "<tr>";
    		foreach ($fields as $field)
        	{$reply .= "<td>".$row[$field]."</td>";}
    		$reply .= "</tr>";
	 	}
	} 
	else 
	{$reply .= "<tr><td colspan=".sizeof($fields)." align='center'> No results found. </td></tr>";}
	$reply .= "</table>";
	
	
	//SELECT [activity_dt], count(*) FROM table1 GROUP BY hour( activity_dt ) 
	
	$reply .= "<h2>By hour</h2>";
	
	$sql = "SELECT count(*) AS views, hour(`date`) AS Hour FROM `Analytics` WHERE `date` > '$date' GROUP BY hour(`date`);";
	$result = mysqli_query($conn, $sql);

	//lets get the feild names
	$fields = [];
	foreach(mysqli_fetch_fields($result) as $column){array_push($fields,$column->name);}

	//lets echo the table header. 
	$reply .= "<table border=0><tr>";
	foreach ($fields as $feild) {$reply .= "<th>".$feild."</th>";}	
	$reply .= "</tr>";
	
	//and now fill it with the data. 
	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result)) 
		{
    		$reply .= "<tr>";
    		foreach ($fields as $field)
        	{$reply .= "<td>".$row[$field]."</td>";}
    		$reply .= "</tr>";
	 	}
	} 
	else 
	{$reply .= "<tr><td colspan=".sizeof($fields)." align='center'> No results found. </td></tr>";}
	$reply .= "</table>";
	
	
	$reply .= "<h2>By pages</h2>";
	
	$sql = "SELECT COUNT(uri) AS Views, uri AS Page FROM `Analytics` WHERE `date` > '$date' GROUP BY uri;";
	$result = mysqli_query($conn, $sql);

	//lets get the feild names
	$fields = [];
	foreach(mysqli_fetch_fields($result) as $column){array_push($fields,$column->name);}

	//lets echo the table header. 
	$reply .= "<table border=0><tr>";
	foreach ($fields as $feild) {$reply .= "<th>".$feild."</th>";}	
	$reply .= "</tr>";
	
	//and now fill it with the data. 
	if (mysqli_num_rows($result) > 0) 
	{
		while($row = mysqli_fetch_assoc($result)) 
		{
    		$reply .= "<tr>";
    		foreach ($fields as $field)
        	{$reply .= "<td>".$row[$field]."</td>";}
    		$reply .= "</tr>";
	 	}
	} 
	else 
	{$reply .= "<tr><td colspan=".sizeof($fields)." align='center'> No results found. </td></tr>";}
	$reply .= "</table>";
	
	return $reply;
}

?>
