

<?php
  
  $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

    $sql = "SELECT imageId FROM attachment ORDER BY imageId DESC"; 
    $result = mysqli_query($HRconnect, $sql);
?>
<HTML>
<HEAD>
<TITLE>List BLOB Images</TITLE>
<link href="imageStyles.css" rel="stylesheet" type="text/css" />
</HEAD>
<BODY>
<?php
	while($row = mysqli_fetch_array($result)) {
	?>
		<br/>
	
<?php		
	}
    mysqli_close($HRconnect);
?>
</BODY>
</HTML>