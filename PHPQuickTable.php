<?php

//generates a table using the following 
//PHPQuickTable($table,$cols,$query,$order,$limit)
//eg : PHPQuickTable("products","name,rate","drate>10","drate","100");
//uses $conn globally for mysql link... 

function PHPQuickTable($table,$cols,$query,$order,$limit)
{
	global $conn;
	//lets create the query and execute it. 
	if($cols==""){$cols="*";}
	$sql = "SELECT $cols FROM $table";
	if($query==""){}else{$sql .= " where $query";}
	if($order==""){}else{$sql .= " ORDER BY $order ASC";}
	if($limit==""){}else{$sql .= " LIMIT  $limit";}
	$result = mysqli_query($conn, $sql);
	
	//lets get the feild names
	$feilds = [];
	$i=0;
	while ($i < mysqli_num_fields($result))
		{
		 //$f = mysql_fetch_field($result, $i);
		 $f = mysqli_fetch_field_direct($result, $i);
		 $i = $i + 1;
		 $feilds[$i] = $f->name;
		 //echo $feilds[$i];
		}
		
	//lets echo the table header. 
	echo "<table border=1><tr>";
	foreach ($feilds as $feildname) 
		{
    	echo "<th>".$feildname."</th>";
		}	
	echo "</tr>";

	if (mysqli_num_rows($result) > 0) {
	  // output data of each row
	 	while($row = mysqli_fetch_assoc($result)) 
	 	{
        echo "<tr>";
        foreach ($feilds as $feild)
        {echo "<td>".$row[$feild]."</td>";}
        echo "</tr>";
	 	}
	  
	
	} 
	 else 
	 {
	 	echo "<tr><td colspan=".mysqli_num_fields($result)." align='center'> No results found. </td></tr>";
	 }
	echo "</table>";
	
}

?>
