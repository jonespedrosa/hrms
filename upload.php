<?php

  $ORconnect = mysqli_connect("localhost", "root", "", "db");
  $HRconnect = mysqli_connect("localhost", "root", "", "hrms");


$target_dir = "/xampp/htdocs/hrms/SystemPictures/";
$target_file = $target_dir . basename($_FILES["fileToUpload1"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);



// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
 if($_FILES["fileToUpload1"]["tmp_name"] != ''){
    $check = getimagesize($_FILES["fileToUpload1"]["tmp_name"]);
}else{
    
}
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    }else{
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload1"]["size"] > 500000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "JPG" && $imageFileType != "png" && $imageFileType != "PNG" && $imageFileType != "jpeg" && $imageFileType != "JPEG") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {

    if (move_uploaded_file($_FILES["fileToUpload1"]["tmp_name"], $target_file)) {
     
    
 $image1 = $_FILES["fileToUpload1"]["name"];

    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}



$target_dir = "/xampp/htdocs/hrms/SystemPictures/";
$target_file = $target_dir . basename($_FILES["fileToUpload2"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);



// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
 if($_FILES["fileToUpload2"]["tmp_name"] != ''){
    $check = getimagesize($_FILES["fileToUpload2"]["tmp_name"]);
}else{
    
}
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}


// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload2"]["size"] > 500000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "JPG" && $imageFileType != "png" && $imageFileType != "PNG" && $imageFileType != "jpeg" && $imageFileType != "JPEG") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload2"]["tmp_name"], $target_file)) {
    
 $image2 = $_FILES["fileToUpload2"]["name"];

    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}



$target_dir = "/xampp/htdocs/hrms/SystemPictures/";
$target_file = $target_dir . basename($_FILES["fileToUpload3"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);



// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
 if($_FILES["fileToUpload3"]["tmp_name"] != ''){
    $check = getimagesize($_FILES["fileToUpload3"]["tmp_name"]);
}else{
    
}

    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}


// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload3"]["size"] > 500000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "JPG" && $imageFileType != "png" && $imageFileType != "PNG" && $imageFileType != "jpeg" && $imageFileType != "JPEG") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload3"]["tmp_name"], $target_file)) {
     
    
 $image3 = $_FILES["fileToUpload3"]["name"];

    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}


$target_dir = "/xampp/htdocs/hrms/SystemPictures/";
$target_file = $target_dir . basename($_FILES["fileToUpload4"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);



// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    if($_FILES["fileToUpload4"]["tmp_name"] != ''){
    $check = getimagesize($_FILES["fileToUpload4"]["tmp_name"]);
}else{

}
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}


// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload4"]["size"] > 500000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "JPG" && $imageFileType != "png" && $imageFileType != "PNG" && $imageFileType != "jpeg" && $imageFileType != "JPEG") {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload4"]["tmp_name"], $target_file)) {
     
    
 $image4 = $_FILES["fileToUpload4"]["name"];


    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}



if(isset($_POST["submit"])) {


        $image1 = $_FILES["fileToUpload1"]["name"];
        $image2 = $_FILES["fileToUpload2"]["name"];
        $image3 = $_FILES["fileToUpload3"]["name"];
        $image4 = $_FILES["fileToUpload4"]["name"];


        $empno = $_POST['empno'];
        $datefrom = $_POST['datefrom'];
        $reason = $_POST['reason'];
        $remarks = $_POST['remarks'];
        $type = $_POST['type'];
        $system = $_POST['system'];
        $bio = $_POST['bio'];

        
       $sql = "INSERT INTO attach(empno,atdatefrom,attype,atsystem,atbio,atreason,atremarks,image1,image2,image3,image4)
    VALUES('{$empno}','{$datefrom}','{$type}','{$system}','{$bio}','{$reason}','{$remarks}','{$image1}','{$image2}','{$image3}','{$image4}')";
        $current_id = mysqli_query($HRconnect, $sql) or die("<b>Error:</b> Problem on Image Insert<br/>" . mysqli_error($HRconnect));

          header("Location: discrepancy.php?discrepancy=discrepancy");

    }

?>