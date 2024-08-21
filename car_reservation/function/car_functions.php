<?php
    session_start();
    $HRconnect = mysqli_connect("localhost", "root", "", "hrms");
    if(isset($_GET['action'])){
        if($_GET['action'] == "login"){
            echo verifyUser($_GET["empno"], $_GET["password"], $HRconnect);
            exit;
        }
    }

    function verifyUser($empno, $password, $HRconnect){
        $sql = "SELECT empno FROM `hrms`.`user_info` WHERE empno = ? AND secpass = ?";
        $stmt = $HRconnect->prepare($sql);
        $stmt->bind_param("ss", $empno, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if($result->num_rows > 0){
            $response = array(
                'title' => 'Successful login',
                'status' => 'success'
            );
            $_SESSION['car_reservation_user'] = $empno;
        }else{
            $response = array(
                'title' => 'The employee id or password is incorrect',
                'status' => 'error'
            );
        }
        sleep(1);
        return json_encode($response);
    }
?>