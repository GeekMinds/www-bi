<?php
    ini_set("display_errors",0);
    $type = $_POST['mimetype']; 
    $xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'; 

    if ($type == 'xml') { 
        header('Content-type: text/xml'); 
?> 
<address attr1="value1" attr2="value2"> 
    <street attr="value">A &amp; B</street> 
    <city>Palmyra</city> 
</address> 
<?php 
    } 
    else if ($type == 'json') { 
        // wrap json in a textarea if the request did not come from xhr 
        //if (!$xhr) echo '<textarea>'; 
?>
<?php
        //if (!$xhr) echo '</textarea>'; 
    } 
    else if ($type == 'script') { 
        // wrap script in a textarea if the request did not come from xhr 
        //if (!$xhr) echo '<textarea>'; 
?> 

<?php
        //if (!$xhr) echo '</textarea>'; 
    } 
    else { 
        // return text var_dump for the html request 
        //echo "VAR DUMP ...:<p />"; 
        //var_dump($_POST); 
        foreach($_FILES as $file) { 
            $n = $file['name']; 
            $s = $file['size']; 
            if (!$n) continue; 
            //echo "File: $n ($s bytes)"; 
        } 
		
		echo $_FILES["myfile"]["name"];
		
		//echo "Upload: " . $_FILES["myfile"]["name"] . "<br>";
		//echo "Type: " . $_FILES["myfile"]["type"] . "<br>";
		//echo "Size: " . ($_FILES["myfile"]["size"] / 1024) . " kB<br>";
		//echo "Temp file: " . $_FILES["myfile"]["tmp_name"] . "<br>";
	
		if (file_exists("files/" . $_FILES["myfile"]["name"]))
		  {
		  //echo $_FILES["myfile"]["name"] . " already exists. ";
		  }
		else
		  {
		  move_uploaded_file($_FILES["myfile"]["tmp_name"],
		  "files/" . $_FILES["myfile"]["name"]);
		  //echo "Stored in: " . "courses/" . $_FILES["myfile"]["name"];
		  }
    } 
?>