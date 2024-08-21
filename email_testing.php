<?php
    $HRconnect = mysqli_connect("localhost", "root", "", "hrms");
    require "excelReader/excel_reader2.php";
    require "excelReader/SpreadsheetReader.php";
?>

<html>
    <head>
        <meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Mary Grace Foods Inc.</title>
		<link rel="icon" href="images/logoo.png">
		<!-- Custom fonts for this template -->
		<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
		<!-- Custom styles for this template -->
		<link href="css/sb-admin-2.min.css" rel="stylesheet">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<!-- SWAL -->
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<!-- jQuery -->
		<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
		<!-- Popper.js -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
		<!-- Bootstrap -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container mt-3">
            <form class="" action="" method="post" enctype="multipart/form-data">
                <input type="file" name="excel" required value="">
                <button type="submit" name="import">Import</button>
            </form>
            <hr>
            <table border = 1>
                <tr>
                    <td>#</td>
                    <td>Name</td>
                    <td>Age</td>
                    <td>Country</td>
                </tr>
              
                <tr>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                </tr>
            
            </table>
            <?php
            if(isset($_POST["import"])){
                echo $_POST["import"];
                $fileName = $_FILES["excel"]["name"];
                $fileExtension = explode('.', $fileName);
                $fileExtension = strtolower(end($fileExtension));
                $newFileName = date("Y.m.d") . " - " . date("h.i.sa") . "." . $fileExtension;

                $targetDirectory = "uploads/" . $newFileName;
                move_uploaded_file($_FILES['excel']['tmp_name'], $targetDirectory);

                error_reporting(0);
                ini_set('display_errors', 0);

                require 'excelReader/excel_reader2.php';
                require 'excelReader/SpreadsheetReader.php';

                $reader = new SpreadsheetReader($targetDirectory);
                // foreach($reader as $key => $row){
                //     $name = $row[0];
                //     $age = $row[1];
                //     $country = $row[2];
                //     mysqli_query($H, "INSERT INTO tb_data VALUES('', '$name', '$age', '$country')");
                // }

                echo
                "
                <script>
                alert('Succesfully Imported');
                document.location.href = '';
                </script>
                ";
            }
            ?>
        </div>
    </body>
</html> 