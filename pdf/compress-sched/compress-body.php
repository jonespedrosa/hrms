<!-- LOOP THE TR FOR MULTIPLE ENTRIES OF TIME INPUTS -->
<?php
for ($counter = 0; $counter < count($employee_schedule); $counter++) {
    #region Hooks
    $schedfrom = $employee_schedule[$counter]["schedfrom"];
    $schedto = $employee_schedule[$counter]["schedto"];
    $M_timein = $employee_schedule[$counter]["M_timein"];
    $M_timeout = $employee_schedule[$counter]["M_timeout"];
    $A_timein = $employee_schedule[$counter]["A_timein"];
    $A_timeout = $employee_schedule[$counter]["A_timeout"];
    $timein_status = $employee_schedule[$counter]["m_in_status"];
    $timeout_status = $employee_schedule[$counter]["a_o_status"];
    $breakin_status = $employee_schedule[$counter]["a_in_status"];
    $breakout_status = $employee_schedule[$counter]["m_o_status"];
    $timein = ($employee_schedule[$counter]["M_timein"] == "") ? "" : date("Y-m-d H:i", strtotime($employee_schedule[$counter]["M_timein"]));

    // FOR BREAKS
    $M_timeout_result;
    $A_timein_result;
    if (stripos($employee_schedule[$counter]["M_timeout"], "No Break") !== false) {
        $M_timeout_result = "No Break";
    } else {
        $M_timeout_result = date("H:i", strtotime($employee_schedule[$counter]["M_timeout"]));
    }
    if (stripos($employee_schedule[$counter]["A_timein"], "No Break") !== false) {
        $A_timein_result = "No Break";
    } else {
        $A_timein_result = date("H:i", strtotime($employee_schedule[$counter]["A_timein"]));
    }

    $breakout = ($employee_schedule[$counter]["M_timeout"] == "") ? "" : $M_timeout_result;
    $breakin = ($employee_schedule[$counter]["A_timein"] == "") ? "" : $A_timein_result;

    $timeout = ($employee_schedule[$counter]["A_timeout"] == "") ? "" : date("Y-m-d H:i", strtotime($employee_schedule[$counter]["A_timeout"]));
    // broken sched time in
    $timein_broken = ($employee_schedule[$counter]["timein4"] == "") ? "" : date("Y-m-d H:i", strtotime($employee_schedule[$counter]["timein4"]));
    $timeout_broken = ($employee_schedule[$counter]["timeout4"] == "") ? "" : date("Y-m-d H:i", strtotime($employee_schedule[$counter]["timeout4"]));

    $break = $employee_schedule[$counter]["break"];
    $holiday_type = $employee_schedule[$counter]["type"];
    $holiday_prior_dates = array($employee_schedule[$counter]["prior1"], $employee_schedule[$counter]["prior2"], $employee_schedule[$counter]["prior3"]);
    $dateStart_nd = date("Y-m-d", strtotime($schedfrom)) . " " . $night_diff_start;
    $dateEnd_nd = date("Y-m-d", strtotime("+1 day", strtotime($schedfrom))) . " " . $night_diff_end;
    $broken_ot_hours = "";
    $ot_hours = "";
    $ot_type = $employee_schedule[$counter]["ottype"];
    $actual_work_hours = "";
    $checkPriorDate = false;
    $work_hours = 0;
    #endregion

    for ($i = 0; $i < count($holiday_prior_dates); $i++) {
        $checkPriorDate = checkPriorDates($holiday_prior_dates[$i], $empid, $HRconnect);
        if ($checkPriorDate) {
            break;
        }
    }

    if ($employee_schedule[$counter]["vlstatus"] == "approved") {
        $leave += round(floatval($employee_schedule[$counter]["vlhours"]), 2);
    }

    if ($employee_schedule[$counter]["work_hours"] == 10 || $employee_schedule[$counter]["work_hours"] == 8) {
        $boolwork_hours = intval($employee_schedule[$counter]["work_hours"]);
    } else if ($employee_schedule[$counter]["work_hours"] == "NWD") {
        $boolwork_hours = 8;
    } else if ($employee_schedule[$counter]["work_hours"] == "RD") {
        $boolwork_hours = 8;
    } else {
        $boolwork_hours = "";
    }

    $sched_range = strtotime("+" . $boolwork_hours + $break . " hours", strtotime($schedfrom));
    $bool_sched_range = $sched_range == strtotime($schedto);
    if (($timein != "" && $breakout != "" && $breakin != "" && $timeout != "") && $bool_sched_range) {
        #region Attendance ---------------------------------------------------------------- //
        $actual_work_hours = floor(((strtotime($A_timeout) - strtotime($M_timein)) / 3600) - $break);
        if ($employee_schedule[$counter]["work_hours"] == 10 || $employee_schedule[$counter]["work_hours"] == 8) {
            $work_hours = intval($employee_schedule[$counter]["work_hours"]);
            if ((strcasecmp("approved", $timein_status) == 0 && strcasecmp("approved", $breakin_status) == 0 && strcasecmp("approved", $breakout_status) == 0 && strcasecmp("approved", $timeout_status) == 0)) {
                $total_work_hours += $work_hours;
            }

        } else if ($employee_schedule[$counter]["work_hours"] == "NWD") {
            $work_hours = 8;
        } else if ($employee_schedule[$counter]["work_hours"] == "RD") {
            $work_hours = 8;
            if ((strcasecmp("approved", $timein_status) == 0 && strcasecmp("approved", $breakin_status) == 0 && strcasecmp("approved", $breakout_status) == 0 && strcasecmp("approved", $timeout_status) == 0)) {
                $total_work_hours += $work_hours;
            }
        } else {
            $work_hours = "";
        }

        if ($employee_schedule[$counter]["work_hours"] != "NWD") {
            $late += max(strtotime($M_timein) - strtotime($schedfrom), 0);
            if ($break > 0) {
                $late += max(strtotime($A_timein) - strtotime("+" . intval($break) . " hours", strtotime($M_timeout)), 0);
            }
            $undertime += max(strtotime($schedto) - strtotime($A_timeout), 0);
        }
        #endregion -------------------------------------------------------------------------- //

        #region Overtime Calculation
        // if($employee_schedule[$counter]["otstatus"] == "approved" && $employee_schedule[$counter]["ottype"] == 0 && $employee_schedule[$counter]["wdostatus"] == "approved"){
        //     $working_off += $employee_schedule[$counter]["othours"];
        //     $ot_hours = $employee_schedule[$counter]["othours"];
        //     if ($holiday_type == "1" && $employee_schedule[$counter]["ottype"] != 0) {
        //         $special_ndot = OTcalculator(strtotime($schedto), strtotime($dateStart_nd), strtotime($dateEnd_nd), $ot_hours, strtotime($schedfrom), $employee_schedule[$counter]["work_hours"], date("Y-m-d H:i", strtotime($employee_schedule[$counter]["A_timeout"])), $ot_type, $timein_broken)["nd_overtime"];
        //     }
        //     else if($holiday_type == "0" && $employee_schedule[$counter]["ottype"] != 0 && $checkPriorDate) {
        //         $legal_ndot += OTcalculator(strtotime($schedto), strtotime($dateStart_nd), strtotime($dateEnd_nd), $ot_hours, strtotime($schedfrom), $employee_schedule[$counter]["work_hours"], date("Y-m-d H:i", strtotime($employee_schedule[$counter]["A_timeout"])), $ot_type, $timein_broken)["nd_overtime"];
        //     }
        //     else{ 
        //         $ordinary_ndot += OTcalculator(strtotime($schedto), strtotime($dateStart_nd), strtotime($dateEnd_nd), $ot_hours, strtotime($schedfrom), $employee_schedule[$counter]["work_hours"], date("Y-m-d H:i", strtotime($employee_schedule[$counter]["A_timeout"])), $ot_type, $timein_broken)["nd_overtime"];   
        //     }
        // }else 
        if ($employee_schedule[$counter]["otstatus"] == "approved" && $employee_schedule[$counter]["ottype"] == 0) {
            $ot_hours = $employee_schedule[$counter]["othours"];
            if (($employee_schedule[$counter]["isNWD"] == 1 && $employee_schedule[$counter]["work_hours"] == "NWD") || ($employee_schedule[$counter]["isNWD"] == 0 && $employee_schedule[$counter]["work_hours"] != "NWD")) {
                if ($holiday_type == "1" && $employee_schedule[$counter]["ottype"] == 0) {
                    $special_ot = OTcalculator(strtotime($schedto), strtotime($dateStart_nd), strtotime($dateEnd_nd), $ot_hours, strtotime($schedfrom), $employee_schedule[$counter]["work_hours"], date("Y-m-d H:i", strtotime($employee_schedule[$counter]["A_timeout"])), $ot_type, $timein_broken)["normal_ot"];
                    $special_ndot = OTcalculator(strtotime($schedto), strtotime($dateStart_nd), strtotime($dateEnd_nd), $ot_hours, strtotime($schedfrom), $employee_schedule[$counter]["work_hours"], date("Y-m-d H:i", strtotime($employee_schedule[$counter]["A_timeout"])), $ot_type, $timein_broken)["nd_overtime"];
                } else if ($holiday_type == "0" && $employee_schedule[$counter]["ottype"] == 0 && $checkPriorDate) {
                    $legal_ot += OTcalculator(strtotime($schedto), strtotime($dateStart_nd), strtotime($dateEnd_nd), $ot_hours, strtotime($schedfrom), $employee_schedule[$counter]["work_hours"], date("Y-m-d H:i", strtotime($employee_schedule[$counter]["A_timeout"])), $ot_type, $timein_broken)["normal_ot"];
                    $legal_ndot += OTcalculator(strtotime($schedto), strtotime($dateStart_nd), strtotime($dateEnd_nd), $ot_hours, strtotime($schedfrom), $employee_schedule[$counter]["work_hours"], date("Y-m-d H:i", strtotime($employee_schedule[$counter]["A_timeout"])), $ot_type, $timein_broken)["nd_overtime"];
                } else {
                    $ordinary_ot += OTcalculator(strtotime($schedto), strtotime($dateStart_nd), strtotime($dateEnd_nd), $ot_hours, strtotime($schedfrom), $employee_schedule[$counter]["work_hours"], date("Y-m-d H:i", strtotime($employee_schedule[$counter]["A_timeout"])), $ot_type, $timein_broken)["normal_ot"];
                    $ordinary_ndot += OTcalculator(strtotime($schedto), strtotime($dateStart_nd), strtotime($dateEnd_nd), $ot_hours, strtotime($schedfrom), $employee_schedule[$counter]["work_hours"], date("Y-m-d H:i", strtotime($employee_schedule[$counter]["A_timeout"])), $ot_type, $timein_broken)["nd_overtime"];
                }
            }
        } else if ($employee_schedule[$counter]["otstatus"] == "pending" && $employee_schedule[$counter]["ottype"] == 0) {
            $ot_hours = ucfirst($employee_schedule[$counter]["otstatus"]);
        } else if ($employee_schedule[$counter]["otstatus"] == "pending2" && $employee_schedule[$counter]["ottype"] == 0) {
            $ot_hours = "Partially Approved";
        }

        // if($employee_schedule[$counter]["otstatus"] == "approved" && $employee_schedule[$counter]["ottype"] != 0 && $employee_schedule[$counter]["wdostatus"] == "approved"){

        //     $working_off += $employee_schedule[$counter]["othours"];
        //     $broken_ot_hours = $employee_schedule[$counter]["othours"];
        //     if ($holiday_type == "1" && $employee_schedule[$counter]["ottype"] != 0) {
        //         $special_ndot = OTcalculator(strtotime($schedto), strtotime($dateStart_nd), strtotime($dateEnd_nd), $broken_ot_hours, strtotime($schedfrom), $employee_schedule[$counter]["work_hours"], date("Y-m-d H:i", strtotime($employee_schedule[$counter]["A_timeout"])), $ot_type, $timein_broken)["nd_overtime"];  
        //     }
        //     else if($holiday_type == "0" && $employee_schedule[$counter]["ottype"] != 0 && $checkPriorDate) {
        //         $legal_ndot += OTcalculator(strtotime($schedto), strtotime($dateStart_nd), strtotime($dateEnd_nd), $broken_ot_hours, strtotime($schedfrom), $employee_schedule[$counter]["work_hours"], date("Y-m-d H:i", strtotime($employee_schedule[$counter]["A_timeout"])), $ot_type, $timein_broken)["nd_overtime"];
        //     }
        //     else{ 
        //         $ordinary_ndot += OTcalculator(strtotime($schedto), strtotime($dateStart_nd), strtotime($dateEnd_nd), $broken_ot_hours, strtotime($schedfrom), $employee_schedule[$counter]["work_hours"], date("Y-m-d H:i", strtotime($employee_schedule[$counter]["A_timeout"])), $ot_type, $timein_broken)["nd_overtime"];  
        //     }

        //     $undertime -= ($broken_ot_hours * 3600);
        //     if($undertime <= 0){
        //         $undertime = 0;
        //     }
        // }else 

        if ($employee_schedule[$counter]["otstatus"] == "approved" && $employee_schedule[$counter]["ottype"] != 0) {

            $broken_ot_hours = $employee_schedule[$counter]["othours"];
            if (($employee_schedule[$counter]["isNWD"] == 1 && $employee_schedule[$counter]["work_hours"] == "NWD") || ($employee_schedule[$counter]["isNWD"] == 0 && $employee_schedule[$counter]["work_hours"] != "NWD")) {
                if ($holiday_type == "1" && $employee_schedule[$counter]["ottype"] != 0) {
                    $special_ot = OTcalculator(strtotime($schedto), strtotime($dateStart_nd), strtotime($dateEnd_nd), $broken_ot_hours, strtotime($schedfrom), $employee_schedule[$counter]["work_hours"], date("Y-m-d H:i", strtotime($employee_schedule[$counter]["A_timeout"])), $ot_type, $timein_broken)["normal_ot"];
                    $special_ndot = OTcalculator(strtotime($schedto), strtotime($dateStart_nd), strtotime($dateEnd_nd), $broken_ot_hours, strtotime($schedfrom), $employee_schedule[$counter]["work_hours"], date("Y-m-d H:i", strtotime($employee_schedule[$counter]["A_timeout"])), $ot_type, $timein_broken)["nd_overtime"];
                } else if ($holiday_type == "0" && $employee_schedule[$counter]["ottype"] != 0 && $checkPriorDate) {
                    $legal_ot += OTcalculator(strtotime($schedto), strtotime($dateStart_nd), strtotime($dateEnd_nd), $broken_ot_hours, strtotime($schedfrom), $employee_schedule[$counter]["work_hours"], date("Y-m-d H:i", strtotime($employee_schedule[$counter]["A_timeout"])), $ot_type, $timein_broken)["normal_ot"];
                    $legal_ndot += OTcalculator(strtotime($schedto), strtotime($dateStart_nd), strtotime($dateEnd_nd), $broken_ot_hours, strtotime($schedfrom), $employee_schedule[$counter]["work_hours"], date("Y-m-d H:i", strtotime($employee_schedule[$counter]["A_timeout"])), $ot_type, $timein_broken)["nd_overtime"];
                } else {
                    $ordinary_ot += OTcalculator(strtotime($schedto), strtotime($dateStart_nd), strtotime($dateEnd_nd), $broken_ot_hours, strtotime($schedfrom), $employee_schedule[$counter]["work_hours"], date("Y-m-d H:i", strtotime($employee_schedule[$counter]["A_timeout"])), $ot_type, $timein_broken)["normal_ot"];
                    $ordinary_ndot += OTcalculator(strtotime($schedto), strtotime($dateStart_nd), strtotime($dateEnd_nd), $broken_ot_hours, strtotime($schedfrom), $employee_schedule[$counter]["work_hours"], date("Y-m-d H:i", strtotime($employee_schedule[$counter]["A_timeout"])), $ot_type, $timein_broken)["nd_overtime"];
                }
                // $undertime -= ($broken_ot_hours * 3600);

                if ($timein_broken != "" && $timeout_broken != "") {
                    $timein_b = strtotime($timein_broken);
                    $timeout_b = strtotime($timeout_broken);

                    $broken_minutes = max($timeout_b - $timein_b, 0);
                    $ut -= $broken_minutes;
                }

                if ($undertime <= 0) {
                    $undertime = 0;
                }
            }
        } else if ($employee_schedule[$counter]["otstatus"] == "pending" && $employee_schedule[$counter]["ottype"] != 0) {
            $broken_ot_hours = ucfirst($employee_schedule[$counter]["otstatus"]);
        } else if ($employee_schedule[$counter]["otstatus"] == "pending2" && $employee_schedule[$counter]["ottype"] != 0) {
            $broken_ot_hours = "Partially Approved";
        }
        #endregion ----------------------------------------------------------------------------//

        $datefromto = $employee_schedule[$counter]["datefromto"];
        #region Night Differential 
        if ($holiday_type == "1") {
            $special_hrs += nightDiffCalcu(strtotime($schedfrom), strtotime($dateStart_nd), strtotime($dateEnd_nd), strtotime($A_timeout), $work_hours, $break, $holiday_type)["normal_hrs"];
            $special_nd += nightDiffCalcu(strtotime($schedfrom), strtotime($dateStart_nd), strtotime($dateEnd_nd), strtotime($A_timeout), $work_hours, $break, $holiday_type)["night_diff"];
        } else if ($holiday_type == 0 && $checkPriorDate) {

            $legal_nd += nightDiffCalcu(strtotime($schedfrom), strtotime($dateStart_nd), strtotime($dateEnd_nd), strtotime($A_timeout), $work_hours, $break, $holiday_type)["night_diff"];
        } else {
            $ordinary_nd += nightDiffCalcu(strtotime($schedfrom), strtotime($dateStart_nd), strtotime($dateEnd_nd), strtotime($A_timeout), $work_hours, $break, $holiday_type)["night_diff"];
        }
        #endregion

        #region working off
        if ($employee_schedule[$counter]["wdostatus"] == "approved") {
            $working_off += $employee_schedule[$counter]["working_hours"];
        }
        #endregion
    }

    // incomplete time inputs 
    if (($holiday_type === 0 && ($timein != "" && $breakout != "" && $breakin != "" && $timeout != "")) || $checkPriorDate) {

        if ($employee_schedule[$counter]["work_hours"] == 10 || $employee_schedule[$counter]["work_hours"] == 8) {
            // echo "prior date " . $checkPriorDate;
            $work_hours = intval($employee_schedule[$counter]["work_hours"]);
            $legal_hrs += nightDiffCalcu(strtotime($schedfrom), strtotime($dateStart_nd), strtotime($dateEnd_nd), strtotime($A_timeout), $work_hours, $break, $holiday_type)["normal_hrs"];
        } else if ($employee_schedule[$counter]["work_hours"] == "NWD" || $employee_schedule[$counter]["work_hours"] == "RD") {
            $work_hours = 8;
            $legal_hrs += nightDiffCalcu(strtotime($schedfrom), strtotime($dateStart_nd), strtotime($dateEnd_nd), strtotime($A_timeout), $work_hours, $break, $holiday_type)["normal_hrs"];
        } else {
            $work_hours = "";
        }
    }
    ?>
    <tr>
        <td colspan=2><?php echo $employee_schedule[$counter]["datefromto"]; ?></td>
        <td colspan=2 class="schedule">
            <?php
            echo date("H:i", strtotime($schedfrom)) . " - " . date("H:i", strtotime($schedto));
            ?>
        </td>
        <td class="breaks">
            <?php echo $break ?>
        </td>
        <td class="timein">
            <?php
            if ($timein == "") {
                echo "";
            } else if (strcasecmp('pending', $timein_status) == 0) {
                echo "Pending";
            } else {
                echo date("H:i", strtotime($timein));
            }
            ?>
        </td>
        <td class="breakout">
            <?php
            if ($breakout == "") {
                echo "";
            } else if (strcasecmp('pending', $breakout_status) == 0) {
                echo "Pending";
            } else if (strcasecmp('No Break', $breakout) == 0) {
                echo "No Break";
            } else {
                echo date("H:i", strtotime($breakout));
            }
            ?>
        </td>
        <td class="breakin">
            <?php
            if ($breakin == "") {
                echo "";
            } else if (strcasecmp('pending', $breakin_status) == 0) {
                echo "Pending";
            } else if (strcasecmp('No Break', $breakin) == 0) {
                echo "No Break";
            } else {
                echo date("H:i", strtotime($breakin));
            }
            ?>
        </td>
        <td class="timeout">
            <?php
            if ($timeout == "") {
                echo "";
            } else if (strcasecmp('pending', $timeout_status) == 0) {
                echo "Pending";
            } else {
                echo date("H:i", strtotime($timeout));
            }
            ?>
        </td>
        <td class="work-hours">
            <?php echo ($timein != "" && $breakout != "" && $breakin != "" && $timeout != "") ? $work_hours : ""; ?>
        </td>
        <td
            class="<?php echo (($employee_schedule[$counter]["isNWD"] == 1 && $employee_schedule[$counter]["work_hours"] == "NWD") || ($employee_schedule[$counter]["isNWD"] == 0 && $employee_schedule[$counter]["work_hours"] != "NWD")) ? "text-primary" : "text-danger" ?> font-weight-bold">
            <?php echo $ot_hours; ?>
        </td>
        <td class="text-primary font-weight-bold">
            <?php echo $broken_ot_hours; ?>
        </td>
        <td>
            <?php echo ($employee_schedule[$counter]["timein4"] == "") ? "" : date("H:i", strtotime($employee_schedule[$counter]["timein4"])); ?>
        </td>
        <td>
            <?php echo ($employee_schedule[$counter]["timeout4"] == "") ? "" : date("H:i", strtotime($employee_schedule[$counter]["timeout4"])); ?>
        </td>
        <td colspan=3>
            <div class="d-flex align-tems-center flex-column">
                <?php
                echo $employee_schedule[$counter]["remarks"];
                echo checkDTRconcern($employee_schedule[$counter]["datefromto"], $empid, $HRconnect);
                echo checkOBP($employee_schedule[$counter]["datefromto"], $empid, $HRconnect);
                echo checkWDO($employee_schedule[$counter]["datefromto"], $empid, $HRconnect);
                echo checkCS($employee_schedule[$counter]["datefromto"], $empid, $HRconnect);
                echo checkVL($employee_schedule[$counter]["datefromto"], $empid, $HRconnect);
                ?>
            </div>
        </td>
    </tr>
    <?php
}
?>