<!-- Categories -->
<tr>
    <td colspan=4 class="font-weight-bold table-secondary">ATTENDANCE</td>
    <td colspan=3 class="font-weight-bold table-secondary">ORDINARY DAY</td>
    <td colspan=4 class="font-weight-bold table-secondary">SPECIAL HOLIDAY</td>
    <td colspan=4 class="font-weight-bold table-secondary">LEGAL HOLIDAY</td>
    <td rowspan=2 class="font-weight-bold table-secondary">WORKING OFF</td>
</tr>

<!-- Subcategories -->
<tr>
    <td class="table-secondary">WORKDAYS</td>
    <td class="table-secondary">LATE</td>
    <td class="table-secondary">UT</td>
    <td class="table-secondary">LEAVE</td>
    <td class="table-secondary">ND</td>
    <td class="table-secondary">OT</td>
    <td class="table-secondary">ND.OT</td>
    <td class="table-secondary">HRS</td>
    <td class="table-secondary">ND</td>
    <td class="table-secondary">OT</td>
    <td class="table-secondary">ND.OT</td>
    <td class="table-secondary">HRS</td>
    <td class="table-secondary">ND</td>
    <td class="table-secondary">OT</td>
    <td class="table-secondary">ND.OT</td>
</tr>

<!-- RESULTS AND TOTAL -->
<?php

$totalWorkDays = $total_work_hours / 8;
$lateHours = $late / 60;
$undertimeHours = $undertime / 60;
$leaveHours = $leave;
$ordinaryNd = $ordinary_nd;
$ordinaryOt = $ordinary_ot;
$ordinaryNdot = $ordinary_ndot;
$specialHrs = $special_hrs;
$specialNd = $special_nd;
$specialOt = $special_ot;
$specialNdot = $special_ndot;
$legalHrs = $legal_hrs;
$legalNd = $legal_nd;
$legalOt = $legal_ot;
$legalNdot = $legal_ndot;
$workingOff = $working_off;

if (checkBackTrack($empid, $datefrom, $dateto, $HRconnect)) {
    $generated = getGenerated($empid, $datefrom, $dateto, $HRconnect);
    $totalWorkDays = $generated['workdays'];
    $lateHours = $generated['late'];
    $undertimeHours = $generated['undertime'];
    $leaveHours = $generated['leave'];
    $ordinaryNd = $generated['ordinary_nd'];
    $ordinaryOt = $generated['ordinary_ot'];
    $ordinaryNdot = $generated['ordinary_ndot'];
    $specialHrs = $generated['special_hrs'];
    $specialNd = $generated['special_nd'];
    $specialOt = $generated['special_ot'];
    $specialNdot = $generated['special_ndot'];
    $legalHrs = $generated['legal_hrs'];
    $legalNd = $generated['legal_nd'];
    $legalOt = $generated['legal_ot'];
    $legalNdot = $generated['legal_ndot'];
    $workingOff = $generated['working_off'];
}
?>

<tr>
    <td id="workdays">
        <?php echo $totalWorkDays; ?>
    </td>
    <td id="late">
        <?php echo $lateHours; ?>
    </td>
    <td id="undertime">
        <?php echo $undertimeHours; ?>
    </td>
    <td id="leave">
        <?php echo $leaveHours; ?>
    </td>
    <td id="od-nd">
        <?php echo floor($ordinaryNd); ?>
    </td>
    <td id="od-ot">
        <?php echo $ordinaryOt; ?>
    </td>
    <td id="od-ndot">
        <?php echo $ordinaryNdot; ?>
    </td>
    <td id="sph-hrs">
        <?php echo floor($specialHrs); ?>
    </td>
    <td id="sph-nd">
        <?php echo floor($specialNd); ?>
    </td>
    <td id="sph-ot">
        <?php echo $specialOt; ?>
    </td>
    <td id="sph-ndot">
        <?php echo $specialNdot; ?>
    </td>
    <td id="lh-hrs">
        <?php echo floor($legalHrs); ?>
    </td>
    <td id="lh-nd">
        <?php echo floor($legalNd); ?>
    </td>
    <td id="lh-ot">
        <?php echo $legalOt; ?>
    </td>
    <td id="lh-ndot">
        <?php echo $legalNdot; ?>
    </td>
    <td id="workingoff">
        <?php echo $workingOff; ?>
    </td>
</tr>