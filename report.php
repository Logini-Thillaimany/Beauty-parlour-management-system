<?php
if(!isset($_SESSION))
{
	session_start();
}
if(isset($_SESSION["login_usertype"]))
{//someone who login to the system
	$system_usertype=$_SESSION["login_usertype"];
	$system_user_id=$_SESSION["login_user_id"];
	$system_username=$_SESSION["login_username"];
}
else
{//guest or public
	$system_usertype="Guest";
}
date_default_timezone_set("Asia/Colombo");
include("connection.php");
?>
<script>
    //issuing booking details report
function active_enddate_booking_details()
{
    var startdate = document.getElementById("txtstartdate").value;
    document.getElementById("txtenddate").value="";
    document.getElementById("txtenddate").readOnly=true;
    document.getElementById("btnprint").disabled=true;
    document.getElementById("display_report_booking_details").innerHTML="";
    
    if(startdate!="")
    {
        document.getElementById("txtenddate").readOnly=false;
        document.getElementById("txtenddate").min=startdate;
    }
}
</script>
<script>
    //generate booking details report
    function generate_report_booking_details()
    {
        var startdate = document.getElementById("txtstartdate").value;
        var enddate = document.getElementById("txtenddate").value;
        
        document.getElementById("display_report_booking_details").innerHTML="";
        if(startdate<=enddate)
        {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    var responsevalue = xmlhttp.responseText.trim();
                    document.getElementById("display_report_booking_details").innerHTML = responsevalue;
                     document.getElementById("btnprint").disabled=false;
                    
                }
            };
            xmlhttp.open("GET", "reportajax.php?option=booking_details&ajax_startdate=" + startdate+"&ajax_enddate="+ enddate, true);
            xmlhttp.send();
        }
        else
        {
            document.getElementById("btnprint").disabled=true;
        }
        
    }
</script>
<script>
    //print booking details report
function print_booking_details()
{
     var startdate = document.getElementById("txtstartdate").value;
        var enddate = document.getElementById("txtenddate").value;
        
        var url="print.php?print=report.php&option=booking_details&print_startdate=" + startdate+"&print_enddate="+ enddate;
        window.open(url, '_blank');
}
</script>
<script>
    //issuing Purchase details report
function active_enddate_purchase_details()
{
    var startdate = document.getElementById("txtstartdate").value;
    document.getElementById("txtenddate").value="";
    document.getElementById("txtenddate").readOnly=true;
    document.getElementById("btnprint").disabled=true;
    document.getElementById("display_report_purchase_details").innerHTML="";
    
    if(startdate!="")
    {
        document.getElementById("txtenddate").readOnly=false;
        document.getElementById("txtenddate").min=startdate;
    }
}
</script>
<script>
    //generate purchase details report
    function generate_report_purchase_details()
    {
        var startdate = document.getElementById("txtstartdate").value;
        var enddate = document.getElementById("txtenddate").value;
        
        document.getElementById("display_report_purchase_details").innerHTML="";
        if(startdate<=enddate)
        {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    var responsevalue = xmlhttp.responseText.trim();
                    document.getElementById("display_report_purchase_details").innerHTML = responsevalue;
                     document.getElementById("btnprint").disabled=false;
                    
                }
            };
            xmlhttp.open("GET", "reportajax.php?option=purchase_details&ajax_startdate=" + startdate+"&ajax_enddate="+ enddate, true);
            xmlhttp.send();
        }
        else
        {
            document.getElementById("btnprint").disabled=true;
        }
        
    }
</script>
<script>
    //print purchase details report
function print_purchase_details()
{
     var startdate = document.getElementById("txtstartdate").value;
        var enddate = document.getElementById("txtenddate").value;
        
        var url="print.php?print=report.php&option=purchase_details&print_startdate=" + startdate+"&print_enddate="+ enddate;
        window.open(url, '_blank');
}
</script>
<script>
    //issuing staff leave details report
function active_enddate_staff_leave_details()
{
    var startdate = document.getElementById("txtstartdate").value;
    document.getElementById("txtenddate").value="";
    document.getElementById("txtenddate").readOnly=true;
    document.getElementById("btnprint").disabled=true;
    document.getElementById("display_report_staff_leave_details").innerHTML="";
    
    if(startdate!="")
    {
        document.getElementById("txtenddate").readOnly=false;
        document.getElementById("txtenddate").min=startdate;
    }
}
</script>
<script>
    //generate staff_leave details report
    function generate_report_staff_leave_details()
    {
        var startdate = document.getElementById("txtstartdate").value;
        var enddate = document.getElementById("txtenddate").value;
        
        document.getElementById("display_report_staff_leave_details").innerHTML="";
        if(startdate<=enddate)
        {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() 
            {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
                {
                    var responsevalue = xmlhttp.responseText.trim();
                    document.getElementById("display_report_staff_leave_details").innerHTML = responsevalue;
                     document.getElementById("btnprint").disabled=false;
                    
                }
            };
            xmlhttp.open("GET", "reportajax.php?option=staff_leave_details&ajax_startdate=" + startdate+"&ajax_enddate="+ enddate, true);
            xmlhttp.send();
        }
        else
        {
            document.getElementById("btnprint").disabled=true;
        }
        
    }
</script>
<script>
    //print staff_leave details report
function print_staff_leave_details()
{
     var startdate = document.getElementById("txtstartdate").value;
        var enddate = document.getElementById("txtenddate").value;
        
        var url="print.php?print=report.php&option=staff_leave_details&print_startdate=" + startdate+"&print_enddate="+ enddate;
        window.open(url, '_blank');
}
</script>

<?php
if(isset($_GET["print"]))
{
    if($_GET["option"]=="booking_details")
    {
        echo '<body onLoad="generate_report_booking_details()">';
    }
    else if($_GET["option"]=="purchase_details")
    {
        echo '<body onLoad="generate_report_purchase_details()">';
    }
    else if($_GET["option"]=="staff_leave_details")
    {
        echo '<body onLoad="generate_report_staff_leave_details()">';
    }
}


if(isset($_GET["option"]))
{
    if($_GET["option"]=="booking_details")
    {
       ?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Booking Report </div>
					</div>
					<div class="card-body">
						<div class="row">
							<!-- one row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtpackageid">Start date</label>
											<?php
                                             if(isset($_GET["print"]))
                                             {
                                            ?>
                                            <input type="date" class="form-control" name="txtstartdate" id="txtstartdate" readonly  value="<?php echo $_GET["print_startdate"]; ?>"  required />
                                            <?php 
                                             }
                                             else 
                                             {
                                            ?>
                                            <input type="date" class="form-control" name="txtstartdate" id="txtstartdate" onChange="active_enddate_booking_details()" required />
                                            <?php
                                            }
                                            ?>
                                        </div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtsubcategory">End Date</label>
											<?php
                                             if(isset($_GET["print"]))
                                             {
                                            ?>
                                            <input type="date" class="form-control" name="txtenddate" id="txtenddate" readonly  value="<?php echo $_GET["print_enddate"]; ?>"  required />
                                            <?php
                                             }
                                             else
                                             {
                                            ?>
                                            <input type="date" class="form-control" name="txtenddate" id="txtenddate" onChange="generate_report_booking_details()" readonly required />
                                            <?php
                                             }
                                            ?>
                                            
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								
                                <?php 
                                if(isset($_GET["page"]))
                                {
                                ?>
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<input type="button" class="btn btn-primary" name="btnprint" id="btnprint" onClick="print_booking_details()"  value="Print" disabled/>
											
											</center>
										</div>
									</div>
								</div>
								<!-- button end -->
                                 <?php
                                }
                                 ?>
						</div>
					</div>
				</div>
			</div>
		</div>
        <div id="display_report_booking_details"></div>
		<?php 
    }
    else if($_GET["option"]=="purchase_details")
    {
       ?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Purchase Report </div>
					</div>
					<div class="card-body">
						<div class="row">
							<!-- one row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="startdate">Start date</label>
											<?php
                                             if(isset($_GET["print"]))
                                             {
                                            ?>
                                            <input type="date" class="form-control" name="txtstartdate" id="txtstartdate" readonly  value="<?php echo $_GET["print_startdate"]; ?>"  required />
                                            <?php 
                                             }
                                             else 
                                             {
                                            ?>
                                            <input type="date" class="form-control" name="txtstartdate" id="txtstartdate" onChange="active_enddate_purchase_details()" required />
                                            <?php
                                            }
                                            ?>
                                        </div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="enddate">End Date</label>
											<?php
                                             if(isset($_GET["print"]))
                                             {
                                            ?>
                                            <input type="date" class="form-control" name="txtenddate" id="txtenddate" readonly  value="<?php echo $_GET["print_enddate"]; ?>"  required />
                                            <?php
                                             }
                                             else
                                             {
                                            ?>
                                            <input type="date" class="form-control" name="txtenddate" id="txtenddate" onChange="generate_report_purchase_details()" readonly required />
                                            <?php
                                             }
                                            ?>
                                            
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								
                                <?php 
                                if(isset($_GET["page"]))
                                {
                                ?>
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<input type="button" class="btn btn-primary" name="btnprint" id="btnprint" onClick="print_purchase_details()"  value="Print" disabled/>
											
											</center>
										</div>
									</div>
								</div>
								<!-- button end -->
                                 <?php
                                }
                                 ?>
						</div>
					</div>
				</div>
			</div>
		</div>
        <div id="display_report_purchase_details"></div>
		<?php 
    }
    else if($_GET["option"]=="staff_leave_details")
    {
       ?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<div class="card-title"> Staff Leave Report </div>
					</div>
					<div class="card-body">
						<div class="row">
							<!-- one row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txtpackageid">Start date</label>
											<?php
                                             if(isset($_GET["print"]))
                                             {
                                            ?>
                                            <input type="date" class="form-control" name="txtstartdate" id="txtstartdate" readonly  value="<?php echo $_GET["print_startdate"]; ?>"  required />
                                            <?php 
                                             }
                                             else 
                                             {
                                            ?>
                                            <input type="date" class="form-control" name="txtstartdate" id="txtstartdate" onChange="active_enddate_staff_leave_details()" required />
                                            <?php
                                            }
                                            ?>
                                        </div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtsubcategory">End Date</label>
											<?php
                                             if(isset($_GET["print"]))
                                             {
                                            ?>
                                            <input type="date" class="form-control" name="txtenddate" id="txtenddate" readonly  value="<?php echo $_GET["print_enddate"]; ?>"  required />
                                            <?php
                                             }
                                             else
                                             {
                                            ?>
                                            <input type="date" class="form-control" name="txtenddate" id="txtenddate" onChange="generate_report_staff_leave_details()" readonly required />
                                            <?php
                                             }
                                            ?>
                                            
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								
                                <?php 
                                if(isset($_GET["page"]))
                                {
                                ?>
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<input type="button" class="btn btn-primary" name="btnprint" id="btnprint" onClick="print_staff_leave_details()"  value="Print" disabled/>
											
											</center>
										</div>
									</div>
								</div>
								<!-- button end -->
                                 <?php
                                }
                                 ?>
						</div>
					</div>
				</div>
			</div>
		</div>
        <div id="display_report_staff_leave_details"></div>
		<?php 
    }
}
?>