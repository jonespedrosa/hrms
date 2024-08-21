<tr>
    <th colspan="100%" class="text-muted text-uppercase">
        <div class="row">
            <div class="col-5">
                Employee # : <b class="text-danger" id="employee-id">
                    <?php echo $empid; ?>
                </b>
                <br>
                <p>
                    <?php echo ($isPWD == "PWD") ? "PINCODE USER" : "" ?>
                </p>
            </div>
            <div class="col-3">

            </div>
            <div class="col-4">
                <p class="">Compressed Schedule</p>
            </div>
        </div>

        <div class="row">
            <div class="col-5 text-uppercase">

            </div>
            <center>
                <a href="../../viewsched.php?current=current">
                    <img src="../../images/logoo.png" width="90" height="90">
                </a>
            </center>
        </div>

        <div class="row">
            <div class="col-5">
                <p class="text-uppercase">
                    Branch: <b>
                        <?php echo $branch ?>
                    </b> <br>
                    Name: <b>
                        <?php echo $name ?>
                    </b>
                </p>
            </div>

            <div class="col-3">

            </div>

            <div class="col-4">
                <p class="text-uppercase">
                    Cut off : <b id="cutoff-sched">
                        <?php echo $datefrom . " - " . $dateto ?>
                    </b>
                    <br>
                    Position: <b>
                        <?php echo $position ?>
                    </b>
                </p>
            </div>
        </div>
    </th>
</tr>
<tr class="text-uppercase">
    <th rowspan="2" colspan="2">
        <center><b>Date</b></center>
    </th>
    <th rowspan="2" colspan="2">
        <center><b>Schedule</b></center>
    </th>
    <th rowspan="2">
        <center><b>Break</b></center>
    </th>
    <th colspan="4">
        <center><b></b></center>
    </th>
    <th rowspan="2">
        <center><b>Work Hours</b></center>
    </th>
    <th rowspan="2">
        <center><b>OT Hours</b></center>
    </th>
    <th rowspan="2">
        <center><b>Broken <br>Sched OT</b></center>
    </th>
    <th colspan="2">
        <center><b>Gen Meet/Gen Clean</b></center>
    </th>
    <th rowspan="2" colspan="3">
        <center><b>Remarks</b></center>
    </th>
</tr>

<tr class="text-uppercase">
    <th>
        <center><b>Time in</b></center>
    </th>
    <th>
        <center><b>Break Out</b></center>
    </th>
    <th>
        <center><b>Break in</b></center>
    </th>
    <th>
        <center><b>Time Out</b></center>
    </th>
    <th>
        <center><b>Time in</b></center>
    </th>
    <th>
        <center><b>Time Out</b></center>
    </th>
</tr>