<?php
//owner needs read write and execute on the directory.... 


function PHPQuickUploadForm()
{
	$msize = ini_get("upload_max_filesize");

	echo "<!DOCTYPE html>
		<html>
		<body>
		
		<form action='PHPQuickUpload.php' method='POST' enctype='multipart/form-data'>
		<input type='hidden' name='MAX_FILE_SIZE' value='1024'/> 
		  Select image to upload:
		  <input type='file' name='file' id='file'>
		  <input type='submit' value='Upload Image' name='submit_file'>
		</form>
		
		<p>Max is: $msize</p>
		
		</body>
		</html>";
}


function PHPQuickUploadProcess()
{
	$target_dir = getcwd()."/";
	$target_file = $target_dir . basename($_FILES["file"]["name"]);

	if (file_exists($target_file)) 
	{
		echo "File already exists.";
	}
	else
	{
		if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) 
		{
    		echo "File ". htmlspecialchars( basename( $_FILES["file"]["name"])). " uploaded.<br>";
    		echo "<a href='".basename( $_FILES['file']['name'])."'> Click Here </a>";
		}
		else 
		{
	    	echo "There was an error uploading the file.</br>";
	    	echo $target_file;
		}

	}
	
}


if(isset($_POST['submit_file']))
{
	PHPQuickUploadProcess();
}
else 
{
	PHPQuickUploadForm();
}



?>