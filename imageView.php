<?php
     $HRconnect = mysqli_connect("localhost", "root", "", "hrms");
     
    if(isset($_GET['image_id'])) {
        $sql = "SELECT imageType,imageData FROM attachment WHERE imageId=" . $_GET['image_id'];
		$result = mysqli_query($HRconnect, $sql) or die("<b>Error:</b> Problem on Retrieving Image BLOB<br/>" . mysqli_error($conn));
		$row = mysqli_fetch_array($result);
		header("Content-type: " . $row["imageType"]);
        echo $row["imageData"];
	}

	 if(isset($_GET['image_id1'])) {
        $sql = "SELECT imageType1,imageData1 FROM attachment WHERE imageId=" . $_GET['image_id1'];
		$result = mysqli_query($HRconnect, $sql) or die("<b>Error:</b> Problem on Retrieving Image BLOB<br/>" . mysqli_error($conn));
		$row = mysqli_fetch_array($result);
		header("Content-type: " . $row["imageType1"]);
        echo $row["imageData1"];
	}

	 if(isset($_GET['image_id2'])) {
        $sql = "SELECT imageType2,imageData2 FROM attachment WHERE imageId=" . $_GET['image_id2'];
		$result = mysqli_query($HRconnect, $sql) or die("<b>Error:</b> Problem on Retrieving Image BLOB<br/>" . mysqli_error($conn));
		$row = mysqli_fetch_array($result);
		header("Content-type: " . $row["imageType2"]);
        echo $row["imageData2"];
	}

	 if(isset($_GET['image_id3'])) {
        $sql = "SELECT imageType3,imageData3 FROM attachment WHERE imageId=" . $_GET['image_id3'];
		$result = mysqli_query($HRconnect, $sql) or die("<b>Error:</b> Problem on Retrieving Image BLOB<br/>" . mysqli_error($conn));
		$row = mysqli_fetch_array($result);
		header("Content-type: " . $row["imageType3"]);
        echo $row["imageData3"];
	}
	mysqli_close($HRconnect);
?>