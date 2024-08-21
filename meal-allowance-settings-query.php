<?php
session_start();
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $food_cost = $_POST['food_cost'];

    // Validate inputs
    if (is_numeric($id) && is_numeric($food_cost)) {
        // Prepare and execute the update query
        $query = "UPDATE `meal_allowance_setting` SET `food_cost` = ? WHERE `id` = ?";
        $stmt = mysqli_prepare($HRconnect, $query);
        mysqli_stmt_bind_param($stmt, 'di', $food_cost, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo 'success';
        } else {
            error_log("MySQL error: " . mysqli_error($HRconnect));
            echo 'error';
        }
        
        mysqli_stmt_close($stmt);
    } else {
        error_log("Invalid input: id = $id, food_cost = $food_cost");
        echo 'invalid';
    }
} else {
    error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    echo 'error';
}

mysqli_close($HRconnect);
