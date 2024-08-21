<?php
     $HRconnect = mysqli_connect("localhost", "root", "", "hrms");
     
    if(isset($_GET['image_id'])) {
        $sql = "SELECT imageType,imageData FROM attachment WHERE imageId=" . $_GET['image_id'];
		$result = mysqli_query($HRconnect, $sql) or die("<b>Error:</b> Problem on Retrieving Image BLOB<br/>" . mysqli_error($conn));
		$row = mysqli_fetch_array($result);
		header("Content-type: " . $row["imageType"]);
        echo $row["imageData"];
	}
	mysqli_close($HRconnect);
?>