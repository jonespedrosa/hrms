<?php
// PHP MAILER
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../PHPMailer/src/Exception.php';
require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';

if (isset($_GET['function'])) {
    if ($_GET['function'] === "forgotPassword") {

        echo SendEmail($_POST['email_resetpass'], "HRMS || Reset Password");
        exit;
    }
}

function SendEmail($email, $subject)
{
    // Initialize PHPMailer object
    $mail = new PHPMailer(true);

    try {
        include("global_timestamp.php");
        $HRconnect = mysqli_connect("localhost", "root", "", "hrms");
        $sql_registeredemail = "SELECT ui.name, ui.empno, er.email_token, er.email, er.is_expired, er.created_at FROM user_info ui
                                    LEFT JOIN user_email ue ON ui.empno = ue.empno
                                    LEFT JOIN email_reset er ON er.email = ue.email 
                                    WHERE ue.email = '$email' AND ue.is_email_verified = 1 ORDER BY er.created_at DESC LIMIT 1";
        $query_registeredemail = $HRconnect->query($sql_registeredemail);
        if ($query_registeredemail->num_rows > 0) {
            isExpired($email, $timestamp, $HRconnect);

            // re-query to get updated list
            $query_registeredemail = $HRconnect->query($sql_registeredemail);
            $row_registeredemail = $query_registeredemail->fetch_array();

            if (empty($row_registeredemail['email_token'])) {
                $email_token = generateToken(32, $row_registeredemail['empno']);
                $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/hrms/index.php?ResetPassword&token=" . $email_token;
                $name_parts = explode(", ", $row_registeredemail['name']);
                $first_name = $name_parts[1];
                $last_name = $name_parts[0];
                $full_name = $first_name . ' ' . $last_name;

                // Insert Token
                $add_token = "UPDATE `hrms`.`email_reset` SET `email` = '$email', `email_token` = '$email_token',`created_at` = '" . $timestamp . "' WHERE email = '$email'
                    ORDER BY created_at DESC LIMIT 1;";
                $query_add_token = $HRconnect->query($add_token);

                // app passworrd 2 factor
                // backup email
                // webdtrmarygracecafe@gmail.com
                // eszzzqckscdsepvm

                // main
                // marygracecafewebdtr@gmail.com
                // pixtcckypicmqrwy
                // mail setup

                // forgot pass email sender
                // marygrace.forgotpass@gmail.com
                // gkbmhirziwihfsrj
                $mail->isSMTP();
                $mail->SMTPDebug = 0;
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;

                // Port
                // 465
                // 587
                $mail->SMTPSecure = 'ssl';
                $mail->SMTPAuth = true;
                $mail->Username = 'marygrace.forgotpass@gmail.com';
                $mail->Password = 'gkbmhirziwihfsrj';

                $mail->setFrom('no-reply@marygracecafe.com', 'Mary Grace Cafe HRMS - No Reply');
                $mail->addAddress($email);
                $mail->addReplyTo('no-reply@marygracecafe.com', 'Mary Grace Cafe HRMS - No Reply');
                $mail->Sender = 'no-reply@marygracecafe.com';
                $mail->addCustomHeader('Return-Path', 'no-reply@marygracecafe.com');

                $mail->IsHTML(true);

                $mail->Subject = $subject;
                $image = __DIR__ . "/../images/logoo.png";

                $mail->Body = '
                    <html>
                    <head>
                        <style>
                        table { width: 100%; border-collapse: collapse}
                        .upper-table { border-radius: 20px 20px 0 0; padding: 20px }
                        .header { text-align: center }
                        .bg-maroon { background: linear-gradient(281deg, rgba(89,15,26,1) 0%, rgba(150,39,53,1) 53%, rgba(89,15,26,1) 100%);  }
                        .bg-secondary { background: #F1F0F0; }
                        .text-white { color: white }
                        .text-maroon { color: #7E0000; padding: 20px 20px }
                        .body-table { padding: 10px 20px}
                        p { margin-left: 20px }
                        a { background-color: #0072c6; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-size: 16px; margin-left: 20px}
                        a:hover{
                            background-color: #000099;
                        }
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
                            <td class="body-table">
                                <h2 class="text-maroon">Dear ' . $full_name . ',</h2>
                                <p style="margin-bottom: 30px">We received a request to reset your password for your HRMS account. To reset your password, please click on the link below</p>
                                <a href="' . $resetLink . '" style="background-color: #0072c6; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-size: 16px;">Reset Password</a>
                                <p style="margin-top: 30px">If you did not request to reset your password, please ignore this email.</p>
                                <p>Best regards,</p>
                                <p>The IT Development Team</p>

                                <hr>
                                <br>
                                <p class="header">&copy; 2023 Mary Grace Cafe HRMS. All Rights Reserved.</p>
                            </td>
                        </tr>
                        </table>
                    </body>
                    </html>
                    ';
                $mail->addEmbeddedImage($image, "marygracelogo");
                $mail->send();

                $response = array(
                    'email' => $email,
                    'status' => 'success',
                    'text' => 'A link has been sent to your provided email',
                    'title' => 'Email Sent!'
                );

            } else {
                $response = array(
                    'email' => $email,
                    'title' => 'Failed to send email',
                    'status' => 'error',
                    'text' => 'The token request to reset your password still exist'
                );
            }

        } else {
            $response = array(
                'email' => $email,
                'title' => 'Failed to send email',
                'status' => 'error',
                'text' => 'The provided email is not valid or inactive: ' . $email
            );
        }
    } catch (Exception $e) {
        // If email sending failed
        $response = array(
            'email' => $email,
            'title' => 'Error',
            'status' => 'error',
            'text' => 'Failed to send email: ' . $mail->ErrorInfo
        );
    }

    return json_encode($response);
}

function generateToken($length, $empid)
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $chars_length = strlen($chars);
    $token = $empid . '?';
    for ($i = 0; $i < $length; $i++) {
        $token .= $chars[rand(0, $chars_length - 1)];
    }
    return $token;
}

function isExpired($email, $timestamp, $HRconnect)
{
    $isExisting = "SELECT * FROM `hrms`.`email_reset` WHERE email = '$email' AND is_expired = 0 
                        ORDER BY created_at DESC LIMIT 1";
    $query_isExisting = $HRconnect->query($isExisting);

    if ($query_isExisting->num_rows <= 0) {
        $expiration_time_string = createExpirationToken($timestamp, $email, $HRconnect);
    } else {
        $row_isExisting = $query_isExisting->fetch_array();
        $date_created = new DateTime($row_isExisting['created_at']);
        $interval_time = new DateInterval('PT05M'); // Token Expiration Interval
        $expiration_time = $date_created->add($interval_time);
        $expiration_time_string = $expiration_time->format('Y-m-d H:i:s');
    }

    if (strtotime($timestamp) > strtotime($expiration_time_string)) {
        $update_is_expired = "UPDATE `hrms`.`email_reset` SET `is_expired` = '1' WHERE (`email` = '$email')";
        $query_expired_token = $HRconnect->query($update_is_expired);
        createExpirationToken($timestamp, $email, $HRconnect);
    } else {
        $response = array(
            'email' => $email,
            'title' => 'Failed to send email',
            'status' => 'error',
            'text' => 'The token request to reset your password still exist'
        );

        return json_encode($response);
        exit;
    }
}

function createExpirationToken($timestamp, $email, $HRconnect)
{
    $date_created = new DateTime($timestamp);
    $interval_time = new DateInterval('PT05M'); // Token Expiration Interval
    $expiration_time = $date_created->add($interval_time);
    $expiration_time_string = $expiration_time->format('Y-m-d H:i:s');

    $insert_is_expired = "INSERT INTO `hrms`.`email_reset` (`email`,`expiration_at`) VALUES('$email','" . $expiration_time_string . "')";
    $query_expired_token = $HRconnect->query($insert_is_expired);

    return $expiration_time_string;
}

function sendSuccessEmail($name, $email, $subject)
{
    if ($email != "" || $email != null) {
        $name_parts = explode(", ", $name);
        $first_name = $name_parts[1];
        $last_name = $name_parts[0];
        $full_name = $first_name . ' ' . $last_name;

        // get browser
        // $userAgent = $_SERVER['HTTP_USER_AGENT'];
        // $browser = get_browser($userAgent, true);

        // //static ip address
        // // $ip = "103.1.119.225"; 
        // //Get IP Address of User in PHP
        // $ip = $_SERVER['REMOTE_ADDR']; 

        // //call api
        // $url = file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip);
        // //decode json data
        // $getInfo = json_decode($url); 

        // app passworrd 2 factor
        // backup email
        // webdtrmarygracecafe@gmail.com@gmail.com
        // apatxrmljvhofzkh

        // main
        // marygracecafewebdtr@gmail.com
        // pixtcckypicmqrwy
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPAuth = true;
        $mail->Username = 'webdtrmarygracecafe@gmail.com';
        $mail->Password = 'apatxrmljvhofzkh';

        $mail->setFrom('no-reply@marygracecafe.com', 'Mary Grace Cafe HRMS - No Reply');
        $mail->addAddress($email);
        $mail->addReplyTo('no-reply@marygracecafe.com', 'Mary Grace Cafe HRMS - No Reply');
        $mail->Sender = 'no-reply@marygracecafe.com';
        $mail->addCustomHeader('Return-Path', 'no-reply@marygracecafe.com');

        $mail->IsHTML(true);

        $mail->Subject = $subject;

        $mail->Body = '
                    <html>
                    <head>
                        <style>
                        table{width:100%;border-collapse:collapse;font-family:Arial,sans-serif;font-size:14px;color:#444444}th,td{border:1px solid #cccccc;padding:8px}th{background-color:#f2f2f2;text-align:left}p{margin:0}.header-row{background-color:#333333;color:#ffffff;font-weight:bold;text-align:center}.header-row td{border:none;padding:10px}.header-row td:first-child{border-radius:8px 0 0 0}.header-row td:last-child{border-radius:0 8px 0 0}.data-row td:first-child{font-weight:bold}.header-row td{text-align:center}.upper-table{border-radius:20px 20px 0 0;padding:20px}.header{text-align:center}.bg-maroon{background:linear-gradient(281deg,rgba(89,15,26,1) 0%,rgba(150,39,53,1) 53%,rgba(89,15,26,1) 100%)}.bg-secondary{background:#F1F0F0}.text-white{color:#fff}.text-maroon{color:#7E0000;padding:20px 0px}.body-table{padding:10px 20px}a{background-color:#0072c6;color:#fff;text-decoration:none;padding:10px 20px;border-radius:5px;font-size:16px;margin-left:20px}a:hover{background-color:#000099}
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
                            <td class="body-table" style="padding-left: 50px; padding-right: 50px; padding-bottom: 30px;">
                                <h2 class="text-maroon">Dear ' . $full_name . ',</h2>
                                <p style="margin-bottom: 30px">Your password has been updated successfully. Thank you for taking steps to improve your account\'s security. If you have any concerns or questions, please feel free to contact our support team for help.</p>
                                <p style="margin-top: 30px">Best regards,</p>
                                <p>The IT Development Team</p>
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
        // if(isMobileDevice()){
        //     $mobile = __DIR__."/../images/mobile_device.png";
        //     $mail->addEmbeddedImage($mobile, "mobile_device");
        // }else{
        //     $desktop = __DIR__."/../images/desktop_device.png";
        //     $mail->addEmbeddedImage($desktop, "desktop_device");
        // }
        $mail->addEmbeddedImage($image, "marygracelogo");
        $mail->send();
    }
}

function isMobileDevice()
{
    return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $_SERVER['HTTP_USER_AGENT']);
}
?>