<?php
// PHP MAILER
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../PHPMailer/src/Exception.php';
require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';

require("global_timestamp.php");
if (isset($_GET['function'])) {
    if ($_GET['function'] == "submitEmail") {
        echo sendVerificationCode($_POST['user-email'], $_POST['empno'], $timestamp);
        exit;
    }

    if ($_GET['function'] == "submitOTP") {
        echo verifyOTP($_POST['otp'], $_POST['empno'], $timestamp);
        exit;
    }
}


function sendVerificationCode($email, $empno, $timestamp)
{
    try {
        if (emailAlreadyregistered($email, $timestamp)) {
            $response = array(
                'email_registered' => true,
                'email' => $email,
                'title' => 'Email OTP sending failed',
                'status' => 'error',
                'text' => 'Email address is already taken or OTP still exists'
            );
            return json_encode($response);
        }
        $HRconnect = mysqli_connect("localhost", "root", "", "hrms");
        $OTP = generateOTP();
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465; //465 587 25 2525
        $mail->SMTPSecure = 'ssl'; //tls
        $mail->SMTPAuth = true;
        $mail->Username = 'webdtrmarygracecafe@gmail.com';
        $mail->Password = 'apatxrmljvhofzkh';

        // app passworrd 2 factor
        // backup email
        // webdtrmarygracecafe@gmail.com
        // apatxrmljvhofzkh

        // main
        // marygracecafewebdtr@gmail.com
        // pixtcckypicmqrwy
        $mail->setFrom('no-reply@marygracecafe.com', 'Mary Grace Cafe HRMS - No Reply');
        $mail->addAddress($email);
        $mail->addReplyTo('no-reply@marygracecafe.com', 'Mary Grace Cafe HRMS - No Reply');
        $mail->Sender = 'no-reply@marygracecafe.com';
        $mail->addCustomHeader('Return-Path', 'no-reply@marygracecafe.com');

        $mail->IsHTML(true);

        $mail->Subject = "Email Verification Code";

        $mail->Body = '
                    <html>
                    <head>
                        <style>
                            table{width:100%;border-collapse:collapse;font-family:Arial,sans-serif;font-size:14px;color:#444}td{padding: 30px 50px 30px 50px;}p{margin: 0;}.header-row{background-color:#333;color:#fff;font-weight:700;text-align:center}.header-row td{border:none;padding:10px}.header-row td:first-child{border-radius:8px 0 0 0}.header-row td:last-child{border-radius:0 8px 0 0}.data-row td:first-child{font-weight:700}.header-row td{text-align:center}.upper-table{border-radius:20px 20px 0 0;padding:20px}.header{text-align:center}.bg-maroon{background:linear-gradient(281deg,rgba(89,15,26,1)0%,rgba(150,39,53,1)53%,rgba(89,15,26,1)100%)}.bg-secondary{background:#f1f0f0}.text-white{color:#fff}.text-maroon{color:#7e0000;padding:20px 0}.body-table{padding:10px 20px}.text-white{color:#fff}.text-maroon{color:#7e0000;padding:20px 0}.body-table{padding:10px 20px}a{background-color:#0072c6;color:#fff;text-decoration:none;padding:10px 20px;border-radius:5px;font-size:16px;margin-left:20px}a:hover{background-color:#000099}.space-between{word-spacing: 0.5em;}
                        </style>
                    </head>
                    <body>
                        <table>
                        <tr class="bg-maroon">
                            <td class="header upper-table">
                                <img src="cid:marygracelogo">
                                <h2 class="text-white">Mary Grace Cafe | HRMS</h2>
                            </td>
                        </tr>
                        <tr class="bg-secondary">
                            <td>
                                <h3>It is important to keep your One-Time Password (OTP) confidential and not share it with anyone to ensure the security of your account.</h3>
                                <p class="text-maroon" style="font-size: 45px; font-weight: bold; text-align: center">' . addSpacesToOTP($OTP) . '</p>
                                <p>When logging into the system, you will be prompted to enter the unique 6-digit pin that was sent to your email address for added security measures. Please ensure that you have received the email with the pin and that you enter the correct digits to gain access to your account. It is important that you do not share this pin with anyone else to maintain the confidentiality and integrity of your account.</p>
                                <br>
                                <hr>
                                <br>
                                <p class="header">&copy; 2023 Mary Grace Cafe HRMS. All Rights Reserved.</p>
                            </td>
                        </tr>
                        </table>
                    </body>
                </html>    
                ';

        $image = __DIR__ . "/../images/logoo.png";
        $mail->addEmbeddedImage($image, "marygracelogo");
        $mail->send();
        $response = array(
            'email_registered' => false,
            'email' => $email,
            'status' => 'success',
            'text' => 'A link has been sent to your provided email',
            'title' => 'Email Sent!'
        );

        $empno = mysqli_real_escape_string($HRconnect, $empno);
        $is_existing = "SELECT * FROM `hrms`.`user_email` WHERE empno = $empno";
        $query_existing = $HRconnect->query($is_existing);
        $expiration = createExpirationOTP($timestamp);

        if ($query_existing->num_rows > 0) {
            $update_is_otp = "UPDATE `hrms`.`user_email` SET `email` = ?, `email_otp` = ?, `created_otp_at` = ?, `expiration_otp_at` = ? WHERE (`empno` = ?);";
            $stmt = $HRconnect->prepare($update_is_otp);
            $stmt->bind_param("ssssi", $email, $OTP, $timestamp, $expiration, $empno);
            $stmt->execute();
        } else {
            $insert_new_email = "INSERT INTO `hrms`.`user_email` (`empno`, `email`, `email_otp`, `created_otp_at`, `expiration_otp_at`) VALUES (?, ?, ?, ?, ?);";
            $stmt = $HRconnect->prepare($insert_new_email);
            $stmt->bind_param("issss", $empno, $email, $OTP, $timestamp, $expiration);
            $stmt->execute();
        }

    } catch (Exception $e) {
        // If email sending failed 
        $response = array(
            'email_registered' => false,
            'email' => $email,
            'title' => 'Error',
            'status' => 'error',
            'text' => 'Failed to send email: ' . $mail->ErrorInfo
        );
    }
    return json_encode($response);
}

function verifyOTP($otp, $empno, $timestamp)
{
    try {
        $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

        $sql = "SELECT email_otp FROM `hrms`.`user_email` WHERE empno = ?";
        $stmt = $HRconnect->prepare($sql);
        $stmt->bind_param("i", $empno);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $email_otp = $row['email_otp'];

        if ($email_otp == $otp) {
            $update_is_otp = "UPDATE `hrms`.`user_email` SET `is_email_verified` = ? WHERE (`empno` = ?);";
            $stmt = $HRconnect->prepare($update_is_otp);
            $stmt->bind_param("ii", $is_email_verified, $empno);
            $is_email_verified = 1;
            $stmt->execute();

            $response = array(
                'otp' => $otp,
                'status' => 'success',
                'text' => 'Your email verification process has been completed successfully',
                'title' => 'Email Verified!'
            );
        } else {
            $response = array(
                'otp' => $otp,
                'title' => 'Invalid OTP',
                'status' => 'error',
                'text' => 'The entered OTP is invalid'
            );
        }
    } catch (Exception $e) {
        $response = array(
            'otp' => $otp,
            'title' => 'Invalid OTP',
            'status' => 'error',
            'text' => 'The entered OTP is invalid'
        );
    }

    return json_encode($response);
}

function generateOTP()
{
    $digits = "0123456789";
    $otp = "";
    for ($i = 0; $i < 6; $i++) {
        $otp .= $digits[rand(0, 9)];
    }
    return $otp;
}

function addSpacesToOTP($otp)
{
    $digits = str_split($otp);
    $spacedOTP = implode(" ", $digits);

    return $spacedOTP;
}

function emailAlreadyregistered($email, $timestamp)
{
    $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

    $email = mysqli_real_escape_string($HRconnect, $email);
    $select_email_existing = "SELECT email, is_email_verified, created_otp_at, expiration_otp_at FROM `hrms`.`user_email` WHERE email = '" . $email . "'";
    $query_email_existing = $HRconnect->query($select_email_existing);
    $row_email_existing = $query_email_existing->fetch_array();

    $status = false;
    if ($query_email_existing->num_rows > 0) {
        if ($row_email_existing['is_email_verified'] != 0) {
            $status = true;
        }
        // else if($row_email_existing['is_email_verified'] == 0 && (strtotime($timestamp) < strtotime($row_email_existing['expiration_otp_at']))){
        //     $status = true;
        // }
    }
    return $status;
}

function createExpirationOTP($timestamp)
{
    $date_created = new DateTime($timestamp);
    $interval_time = new DateInterval('PT05M'); // Token Expiration Interval
    $expiration_time = $date_created->add($interval_time);
    $expiration_time_string = $expiration_time->format('Y-m-d H:i:s');

    return $expiration_time_string;
}
?>