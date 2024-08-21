<?php
// Establish database connection
$HRconnect = mysqli_connect("localhost", "root", "", "hrms");

if (!$HRconnect) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input data
    $dateStartSched = $_POST['dateStartSched'];
    $dateEndSched = $_POST['dateEndSched'];
    $batchNumber = $_POST['batchNumber'];
    $schedule_id = $_POST['schedule_id'];
    $original_batch_number = $_POST['original_batch_number'];

    // Ensure session is started
    session_start();
    if (!isset($_SESSION['empno'])) {
        die("Session empno not set.");
    }
    $session_empno = $_SESSION['empno'];

    // Prepare and execute update query for lnd_training_schedule
    $querySchedule = "UPDATE lnd_training_schedule 
                      SET datefrom = ?, dateto = ?, batch_number = ?, updated_by_id = ?, updated_at = NOW()
                      WHERE id = ? AND batch_number = ?";
    
    $stmtSchedule = mysqli_prepare($HRconnect, $querySchedule);
    if ($stmtSchedule) {
        mysqli_stmt_bind_param($stmtSchedule, "ssisii", $dateStartSched, $dateEndSched, $batchNumber, $session_empno, $schedule_id, $original_batch_number);
        if (mysqli_stmt_execute($stmtSchedule)) {
            mysqli_stmt_close($stmtSchedule);

            // Fetch existing lnd_training_batch records for the given schedule_id
            $queryFetchBatch = "SELECT day, datefrom FROM lnd_training_batch WHERE id = ?";
            if ($stmtFetchBatch = mysqli_prepare($HRconnect, $queryFetchBatch)) {
                mysqli_stmt_bind_param($stmtFetchBatch, "i", $schedule_id);
                mysqli_stmt_execute($stmtFetchBatch);
                $result = mysqli_stmt_get_result($stmtFetchBatch);
                $existingBatch = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $existingBatch[$row['day']] = $row['datefrom'];
                }
                mysqli_stmt_close($stmtFetchBatch);
            } else {
                echo "Error preparing fetch statement: " . mysqli_error($HRconnect);
                exit();
            }

            // Prepare query for updating rows in lnd_training_batch
            $queryUpdateBatch = "UPDATE lnd_training_batch SET datefrom = ? WHERE id = ? AND day = ?";
            if ($stmtUpdateBatch = mysqli_prepare($HRconnect, $queryUpdateBatch)) {
                $start = new DateTime($dateStartSched);
                $end = new DateTime($dateEndSched);
                $end->modify('+1 day'); // Include the end date
                $interval = new DateInterval('P1D'); // 1 day interval
                $period = new DatePeriod($start, $interval, $end);

                $day = 1; // Initialize day counter
                foreach ($period as $date) {
                    $datefrom = $date->format('Y-m-d');
                    if (isset($existingBatch[$day])) {
                        // Update existing datefrom for the given day
                        mysqli_stmt_bind_param($stmtUpdateBatch, "sii", $datefrom, $schedule_id, $day);
                        mysqli_stmt_execute($stmtUpdateBatch);
                    } else {
                        // Insert new row if the day does not exist
                        $queryInsertBatch = "INSERT INTO lnd_training_batch (id, day, datefrom) VALUES (?, ?, ?)";
                        if ($stmtInsertBatch = mysqli_prepare($HRconnect, $queryInsertBatch)) {
                            mysqli_stmt_bind_param($stmtInsertBatch, "iis", $schedule_id, $day, $datefrom);
                            mysqli_stmt_execute($stmtInsertBatch);
                            mysqli_stmt_close($stmtInsertBatch);
                        } else {
                            echo "Error preparing insert statement: " . mysqli_error($HRconnect);
                        }
                    }
                    $day++;
                }
                mysqli_stmt_close($stmtUpdateBatch);

                echo "Schedule and batch updated successfully.";
            } else {
                echo "Error preparing update statement: " . mysqli_error($HRconnect);
            }
        } else {
            echo "Error updating schedule: " . mysqli_stmt_error($stmtSchedule);
        }
    } else {
        echo "Error preparing schedule statement: " . mysqli_error($HRconnect);
    }

    mysqli_close($HRconnect);
}













// WORK NOT DELETE DUPLICATE

// // Establish database connection
// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// if (!$HRconnect) {
//     die("Connection failed: " . mysqli_connect_error());
// }

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $dateStartSched = $_POST['dateStartSched'];
//     $dateEndSched = $_POST['dateEndSched'];
//     $batchNumber = $_POST['batchNumber'];
//     $schedule_id = $_POST['schedule_id'];
//     $original_batch_number = $_POST['original_batch_number'];

//     // Update lnd_training_schedule
//     $querySchedule = "UPDATE lnd_training_schedule 
//                       SET datefrom = ?, dateto = ?, batch_number = ? 
//                       WHERE id = ? AND batch_number = ?";
           
//     if ($stmtSchedule = mysqli_prepare($HRconnect, $querySchedule)) {
//         mysqli_stmt_bind_param($stmtSchedule, "sssii", $dateStartSched, $dateEndSched, $batchNumber, $schedule_id, $original_batch_number);
//         if (mysqli_stmt_execute($stmtSchedule)) {
//             mysqli_stmt_close($stmtSchedule);

//             // Get the maximum day value and corresponding datefrom for the given schedule_id
//             $queryMaxDay = "SELECT MAX(day) AS max_day, MAX(datefrom) AS max_datefrom FROM lnd_training_batch WHERE id = ?";
//             if ($stmtMaxDay = mysqli_prepare($HRconnect, $queryMaxDay)) {
//                 mysqli_stmt_bind_param($stmtMaxDay, "i", $schedule_id);
//                 mysqli_stmt_execute($stmtMaxDay);
//                 mysqli_stmt_bind_result($stmtMaxDay, $max_day, $max_datefrom);
//                 mysqli_stmt_fetch($stmtMaxDay);
//                 mysqli_stmt_close($stmtMaxDay);
//             } else {
//                 echo "Error preparing max day statement: " . mysqli_error($HRconnect);
//                 exit();
//             }

//             $max_day = $max_day ? $max_day : 0; // Default to 0 if no max day found

//             // Calculate number of days between the day after the last max_datefrom and dateEndSched
//             $start = new DateTime($max_datefrom);
//             $start->modify('+1 day'); // Start from the day after the last datefrom
//             $end = new DateTime($dateEndSched);
//             $end->modify('+1 day'); // Include the end date
//             $interval = new DateInterval('P1D'); // 1 day interval
//             $period = new DatePeriod($start, $interval, $end);

//             // Prepare query for updating or inserting rows in lnd_training_batch
//             $queryUpsertBatch = "INSERT INTO lnd_training_batch (id, day, datefrom) 
//                                  VALUES (?, ?, ?) 
//                                  ON DUPLICATE KEY UPDATE datefrom = VALUES(datefrom)";
            
//             if ($stmtUpsertBatch = mysqli_prepare($HRconnect, $queryUpsertBatch)) {
//                 foreach ($period as $date) {
//                     $max_day++; // Increment the day counter
//                     $datefrom = $date->format('Y-m-d');
//                     mysqli_stmt_bind_param($stmtUpsertBatch, "iis", $schedule_id, $max_day, $datefrom);
//                     mysqli_stmt_execute($stmtUpsertBatch);
//                 }
//                 mysqli_stmt_close($stmtUpsertBatch);
                
//                 echo "Schedule and batch updated successfully.";
//             } else {
//                 echo "Error preparing upsert statement: " . mysqli_error($HRconnect);
//             }
//         } else {
//             echo "Error updating schedule: " . mysqli_stmt_error($stmtSchedule);
//         }
//     } else {
//         echo "Error preparing schedule statement: " . mysqli_error($HRconnect);
//     }

//     mysqli_close($HRconnect);
// }






















// WORKING BUT THEY DELETED
// // Establish database connection
// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// if (!$HRconnect) {
//     die("Connection failed: " . mysqli_connect_error());
// }

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $dateStartSched = $_POST['dateStartSched'];
//     $dateEndSched = $_POST['dateEndSched'];
//     $batchNumber = $_POST['batchNumber'];
//     $schedule_id = $_POST['schedule_id'];
//     $original_batch_number = $_POST['original_batch_number'];

//     // Update lnd_training_schedule
//     $querySchedule = "UPDATE lnd_training_schedule 
//                       SET datefrom = ?, dateto = ?, batch_number = ? 
//                       WHERE id = ? AND batch_number = ?";
           
//     if ($stmtSchedule = mysqli_prepare($HRconnect, $querySchedule)) {
//         mysqli_stmt_bind_param($stmtSchedule, "sssii", $dateStartSched, $dateEndSched, $batchNumber, $schedule_id, $original_batch_number);
//         if (mysqli_stmt_execute($stmtSchedule)) {
//             mysqli_stmt_close($stmtSchedule);
            
//             // Delete existing lnd_training_batch entries for this schedule_id
//             $queryDeleteBatch = "DELETE FROM lnd_training_batch WHERE id = ?";
//             if ($stmtDeleteBatch = mysqli_prepare($HRconnect, $queryDeleteBatch)) {
//                 mysqli_stmt_bind_param($stmtDeleteBatch, "i", $schedule_id);
//                 mysqli_stmt_execute($stmtDeleteBatch);
//                 mysqli_stmt_close($stmtDeleteBatch);
//             } else {
//                 echo "Error preparing delete statement: " . mysqli_error($HRconnect);
//                 exit();
//             }

//             // Calculate number of days between dateStartSched and dateEndSched
//             $start = new DateTime($dateStartSched);
//             $end = new DateTime($dateEndSched);
//             $end->modify('+1 day'); // Include the end date
//             $interval = new DateInterval('P1D'); // 1 day interval
//             $period = new DatePeriod($start, $interval, $end);

//             // Generate and execute SQL to insert or replace rows in lnd_training_batch
//             $queryInsertBatch = "INSERT INTO lnd_training_batch (id, day, datefrom) 
//                                  VALUES (?, ?, ?) 
//                                  ON DUPLICATE KEY UPDATE day = VALUES(day), datefrom = VALUES(datefrom)";
            
//             if ($stmtInsertBatch = mysqli_prepare($HRconnect, $queryInsertBatch)) {
//                 $day = 1; // Initialize day counter
//                 foreach ($period as $date) {
//                     $datefrom = $date->format('Y-m-d');
//                     mysqli_stmt_bind_param($stmtInsertBatch, "iis", $schedule_id, $day, $datefrom);
//                     mysqli_stmt_execute($stmtInsertBatch);
//                     $day++; // Increment day counter
//                 }
//                 mysqli_stmt_close($stmtInsertBatch);
                
//                 echo "Schedule and batch updated successfully.";
//             } else {
//                 echo "Error preparing insert statement: " . mysqli_error($HRconnect);
//             }
//         } else {
//             echo "Error updating schedule: " . mysqli_stmt_error($stmtSchedule);
//         }
//     } else {
//         echo "Error preparing schedule statement: " . mysqli_error($HRconnect);
//     }

//     mysqli_close($HRconnect);
// }











// OLD 

// // Establish database connection
// $HRconnect = mysqli_connect("localhost", "root", "", "hrms");

// if (!$HRconnect) {
//     die("Connection failed: " . mysqli_connect_error());
// }

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $dateStartSched = $_POST['dateStartSched'];
//     $dateEndSched = $_POST['dateEndSched'];
//     $batchNumber = $_POST['batchNumber']; // Updated to match the JavaScript field name
//     $schedule_id = $_POST['schedule_id'];
//     $original_batch_number = $_POST['original_batch_number']; // Get original batch number from URL or form

//     $query = "UPDATE lnd_training_schedule 
//               SET datefrom = ?, dateto = ?, batch_number = ? 
//               WHERE id = ? AND batch_number = ?";
              
//     if ($stmt = mysqli_prepare($HRconnect, $query)) {
//         mysqli_stmt_bind_param($stmt, "sssii", $dateStartSched, $dateEndSched, $batchNumber, $schedule_id, $original_batch_number);
//         if (mysqli_stmt_execute($stmt)) {
//             echo "Schedule updated successfully.";
//         } else {
//             echo "Error updating schedule: " . mysqli_stmt_error($stmt);
//         }
//         mysqli_stmt_close($stmt);
//     } else {
//         echo "Error preparing statement: " . mysqli_error($HRconnect);
//     }

//     mysqli_close($HRconnect);
// }
