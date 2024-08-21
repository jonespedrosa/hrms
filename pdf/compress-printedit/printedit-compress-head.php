<tr>
    <th colspan="100%" class="text-muted">
        <div class="d-flex align-items-center justify-content-between"> 
            <p class="m-0">Employee # : <b class="text-danger"><?php echo $empid; ?></b></p>
            <div>
                <button type="button" class="btn-sm btn-primary" id="compress-sched-btn" value="<?php echo $empid?>">Manage Schedule</button>
            </div>
        </div>
            <div class="row d-flex align-items-end">
                <div class="col-5">
                    <p class="text-uppercase"> 
                        Name: <b><?php echo $name; ?></b><br/>
                        Branch/Dept: <b><?php echo $branch; ?></b>  
                    </p>
                </div>
            <div class="col-3"></div>
            <div class="col-4">
                <?php 
                    include("../../Function/compress_schedule_func.php");
                    include("../compress-sched/compress_schedule_modals.php");
                ?>			
                <!-- ajax call the compress sched -->
                <button type="button" class="btn-sm d-none" id="compress-sched-btn" value="<?php echo $empid;?>"></button>
                <input type="text" class="d-none cutoff-sched" value = "<?php echo $cutfrom; ?>">
                <input type="text" class="d-none cutoff-sched" value = "<?php echo $cutto; ?>">
                <input type="text" class="d-none" id="sched-info-id" value = "<?php echo $_SESSION["emp_sched"]["id"]; ?>">
                <p class="text-uppercase"> 	
                    <p class="font-weight-bold text-secondary text-uppercase float-right">Compressed Schedule</p>
                </p>
            </div> 
        </div>					
    </th>
</tr> 
<tr class="text-uppercase">
    <th rowspan="2" colspan="2"><center><b>Cut-off Date</b></center></th>
    <th rowspan="2" width = "23%"><center><b>Schedule</b></center></th>  
    <th rowspan="2" width = "10%"><center><b>Break</b></center></th>   
    <th colspan="4"><center><b></b></center></th>
    <th rowspan="2" colspan="2"><center><b>Remarks</b></center></th>
    <th rowspan="2"><center><b>Action</b></center></th> 
</tr> 
<tr class="text-uppercase">											
    <th><center><b>Time in</b></center></th>
    <th><center><b>Break Out</b></center></th>
    <th><center><b>Break In</b></center></th>
    <th><center><b>Time Out</b></center></th>	
</tr>