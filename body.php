
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
if($system_usertype=="Admin" || $system_usertype=="Clerk" || $system_usertype=="MakeupArtist" || $system_usertype=="SaloonService")
{//allow these users to access this page 

include("connection.php");

// Initialize income variables
$no_SaloonService_bookings=0;
$no_Makeup_bookings=0;

$SaloonService_income=0;
$Makeup_income=0;
$sale_income=0;
$Total_income=0;
if($system_usertype=="Admin" || $system_usertype=="Clerk")
{
  $sql_load_booking="SELECT booking_id,bookdate,customer_id,totalamount,servicedate,booktype,status FROM booking WHERE (status='Finish' OR status='Accept')  ORDER BY booking_id DESC";
}
else
{
  $sql_load_booking="SELECT booking_id,bookdate,customer_id,totalamount,servicedate,booktype,status FROM booking WHERE (status='Finish' OR status='Accept')  AND booking_id IN(SELECT DISTINCT booking_id FROM bookingallocatestaff WHERE staff_id='$system_user_id')  ORDER BY booking_id DESC";
}
$result_load_booking=mysqli_query($con,$sql_load_booking) or die("sql error in sql_load_booking ".mysqli_error($con));
while($row_load_booking=mysqli_fetch_assoc($result_load_booking))
{ 
  if($row_load_booking["booktype"]!='Sale')
  {
    if($row_load_booking["booktype"]=='CA02')
    {
      $no_SaloonService_bookings++;
      $SaloonService_income=$SaloonService_income + $row_load_booking["totalamount"];
     
    }
    else if($row_load_booking["booktype"]=='CA01')
    {
      $no_Makeup_bookings++;
      $Makeup_income=$Makeup_income + $row_load_booking["totalamount"];
     
    }
  }
  else
  {
    $sale_income=$sale_income + $row_load_booking["totalamount"];
  }
}

if($system_usertype=="Admin" || $system_usertype=="Clerk")
{
  $sql_load_booking="SELECT booking_id,bookdate,customer_id,totalamount,servicedate,booktype,status FROM booking WHERE status='Sale' ORDER BY booking_id DESC";
}
else
{
  $sql_load_booking="SELECT booking_id,bookdate,customer_id,totalamount,servicedate,booktype,status FROM booking WHERE  status='Sale' AND booking_id IN(SELECT DISTINCT booking_id FROM bookingallocatestaff WHERE staff_id='$system_user_id')  ORDER BY booking_id DESC";
}
$result_load_booking=mysqli_query($con,$sql_load_booking) or die("sql error in sql_load_booking ".mysqli_error($con));
while($row_load_booking=mysqli_fetch_assoc($result_load_booking))
{ 
  $sale_income=$sale_income + $row_load_booking["totalamount"];
  
}
$Total_income=$SaloonService_income + $Makeup_income + $sale_income;

$chart_data= [];
$chart_data[] = ['Type', 'Income'];
$chart_data[] = ['Saloon Service', $SaloonService_income];
$chart_data[]=['Makeup', $Makeup_income];
$chart_data[]=['Sales', $sale_income];

?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Type', 'Number of Bookings'],
          ['Saloon Service', <?php  echo $no_SaloonService_bookings; ?>],
          ['Makeup', <?php echo $no_Makeup_bookings; ?>]
        ]);
       


        var options = {
          title: 'Booking Details'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>

    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable(<?php echo json_encode($chart_data); ?>);

        var options = {
          chart: {
            title: 'Parlor Booking'
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>
<div class="row">
  <?php
  //get the total number of customers
  $sql_count_customer="SELECT customer_id FROM customer";
	$result_count_customer=mysqli_query($con,$sql_count_customer) or die("sql error in sql_count_customer ".mysqli_error($con));
	$customer_count=mysqli_num_rows($result_count_customer);	
  
 
  if($system_usertype=="Admin" || $system_usertype=="Clerk")
  {

    //get the total number of packages
    $sql_count_package="SELECT package_id FROM package WHERE package_id IN (SELECT package_id from packageprice WHERE enddate IS NULL)";
    $result_count_package=mysqli_query($con,$sql_count_package) or die("sql error in sql_count_package ".mysqli_error($con));
    $package_count=mysqli_num_rows($result_count_package);

    //total sales amount
    //get the current year
    $year= date("Y");
    //startdate and end date of the year
    $start_date = $year.'-01-01';
    $start_date=date("Y-m-d", strtotime($start_date));

    $end_date = $year.'-12-31';
    $end_date = date("Y-m-d", strtotime($end_date));

    $total_Income=0;
    $Booking_count=0;
    $sql_booking="SELECT booking_id,bookdate,customer_id,totalamount,servicedate,status FROM booking WHERE (status='Finish' OR status='Accept' OR status='Sale') AND (servicedate>='$start_date' AND servicedate<='$end_date')";
		$result_booking=mysqli_query($con,$sql_booking) or die("sql error in sql_booking ".mysqli_error($con));
    while($row_booking=mysqli_fetch_assoc($result_booking))
    {
      $total_Income= $total_Income + $row_booking["totalamount"];

      if($row_booking["status"]=='Finish' OR $row_booking["status"]=='Accept')
      {
       $Booking_count++;
      }
    }
   
  }
  else
  {
    //staff will view the no of Booking packages today for them 
    $Leave=0;
    $sql_leave="SELECT leave_id,staff_id,startdate,enddate,status FROM staffleave WHERE staff_id='$system_user_id' AND status='Approved' ORDER BY startdate DESC";
    $result_leave=mysqli_query($con,$sql_leave) or die("sql error in sql_leave ".mysqli_error($con));
		while($row_leave=mysqli_fetch_assoc($result_leave))
    {
      $Leave++;
    }
		$thisyear=date("Y");
    $month = date("m");
    $start_date = $thisyear."-".$month.'-01';
    $start_date=date("Y-m-d", strtotime($start_date));

    $end_date = $thisyear."-".$month;
    $end_date = date("Y-m-t", strtotime($end_date));
    
    $finished_bookings_packages=0;
    $Upcoming_bookings_packages=0;

    $sql_staff_allocate="SELECT booking_id,package_id,staff_id FROM bookingallocatestaff WHERE staff_id='$system_user_id'";
    $result_staff_allocate=mysqli_query($con,$sql_staff_allocate) or die("sql error in sql_staff_allocate ".mysqli_error($con));
    while($row_staff_allocate=mysqli_fetch_assoc($result_staff_allocate))
    {
      $sql_booking_staff="SELECT booking_id,bookdate,customer_id,totalamount,servicedate,status FROM booking WHERE (status='Finish' OR status='Accept') AND booking_id='$row_staff_allocate[booking_id]' AND (servicedate>='$start_date' AND servicedate<='$end_date')";
      $result_booking_staff=mysqli_query($con,$sql_booking_staff) or die("sql error in sql_booking_staff ".mysqli_error($con));
      while($row_booking_staff=mysqli_fetch_assoc($result_booking_staff))
      {
        if($row_booking_staff["status"]=='Finish')
        {
          $finished_bookings_packages++;
        }
        else if($row_booking_staff["status"]=='Accept')
        {
          $Upcoming_bookings_packages++;
        }
      }

    }

  }
  ?>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-icon">
                        <div
                          class="icon-big text-center icon-primary bubble-shadow-small"
                        >
                          <i class="fas fa-users"></i>
                        </div>
                      </div>
                      <div class="col col-stats ms-3 ms-sm-0">
                        <div class="numbers">
                          <p class="card-category">Customers</p>
                          <h5 ><?php echo $customer_count; ?></h5>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                   <?php
                    if($system_usertype=="Admin" || $system_usertype=="Clerk")
                    {
                      ?>
                        <div class="row align-items-center">
                          <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                              <i class="fas fa-solid fa-folder-open"></i>
                            </div>
                          </div>
                          <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                              <p class="card-category">Packages</p>
                              <h5 ><?php echo $package_count; ?></h5>
                            </div>
                          </div>
                        </div>
                      <?php
                    }
                    else
                    {
                      ?>
                      <div class="row align-items-center">
                          <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                              <i class="fas fa-solid fa-user-times"></i>
                            </div>
                          </div>
                          <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                              <p class="card-category">Leave</p>
                              <h5 ><?php echo $Leave; ?></h5>
                            </div>
                          </div>
                      </div>
                      <?php
                    }
                    ?>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <?php
                      if($system_usertype=="Admin" || $system_usertype=="Clerk")
                      {
                        ?>
                          <div class="row align-items-center">
                            <div class="col-icon">
                              <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-luggage-cart"></i>
                              </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                              <div class="numbers">
                                <p class="card-category">Total Income</p>
                                <h5 ><?php echo $total_Income; ?></h5>
                              </div>
                            </div>
                          </div>
                         <?php
                      }
                      else
                      {
                        ?>
                        <div class="row align-items-center">
                            <div class="col-icon">
                              <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-check-circle"></i>
                              </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                              <div class="numbers">
                                <p class="card-category">Finished Works</p>
                                <h5 ><?php echo $finished_bookings_packages; ?></h5>
                                </div>
                            </div>
                          </div>
                        <?php
                      }
                    ?>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                  <div class="card-body">
                    <?php
                      if($system_usertype=="Admin" || $system_usertype=="Clerk")
                      {
                        ?>
                        <div class="row align-items-center">
                          <div class="col-icon">
                            <div
                              class="icon-big text-center icon-secondary bubble-shadow-small"
                            >
                              <i class="far fa-check-circle"></i>
                            </div>
                          </div>
                          <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                              <p class="card-category">Bookings</p>
                              <h5 ><?php echo $Booking_count; ?></h5>
                            </div>
                          </div>
                        </div>
                        <?php
                      }
                      else
                      {
                        ?>
                          <div class="row align-items-center">
                            <div class="col-icon">
                              <div
                                class="icon-big text-center icon-secondary bubble-shadow-small"
                              >
                                <i class="far fa-calendar-check"></i>
                              </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                              <div class="numbers">
                                  <p class="card-category">Upcomming Works</p>
                                  <h5 ><?php echo $Upcoming_bookings_packages; ?></h5>
                              </div>
                            </div>
                          </div>
                        <?php
                      }
                    ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Booking Statistics</div>
                      <!--<div class="card-tools">
                        <a
                          href="#"
                          class="btn btn-label-success btn-round btn-sm me-2"
                        >
                          <span class="btn-label">
                            <i class="fa fa-pencil"></i>
                          </span>
                          Export
                        </a>
                        <a href="#" class="btn btn-label-info btn-round btn-sm">
                          <span class="btn-label">
                            <i class="fa fa-print"></i>
                          </span>
                          Print
                        </a>
                      </div>-->
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="chart-container" style="min-height: 375px">
                      <div id="piechart" style="width: 400px; height: 400px;"></div>
                    </div>
                    <div id="myChartLegend"></div>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Booking Statistics</div>
                      <!--<div class="card-tools">
                        <a
                          href="#"
                          class="btn btn-label-success btn-round btn-sm me-2"
                        >
                          <span class="btn-label">
                            <i class="fa fa-pencil"></i>
                          </span>
                          Export
                        </a>
                        <a href="#" class="btn btn-label-info btn-round btn-sm">
                          <span class="btn-label">
                            <i class="fa fa-print"></i>
                          </span>
                          Print
                        </a>
                      </div>-->
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="chart-container" style="min-height: 375px">
                      <div id="columnchart_material" style="width: 400px; height: 400px;"></div>
                    </div>
                    <div id="myChartLegend"></div>
                  </div>
                </div>
              </div>
              <!-- <div class="col-md-4">
                <div class="card card-primary card-round">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Daily Bookings</div>
                      <div class="card-tools">
                        <div class="dropdown">
                          <button
                            class="btn btn-sm btn-label-light dropdown-toggle"
                            type="button"
                            id="dropdownMenuButton"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                          >
                            Export
                          </button>
                          <div
                            class="dropdown-menu"
                            aria-labelledby="dropdownMenuButton"
                          >
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#"
                              >Something else here</a
                            >
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-category">March 25 - April 02</div>
                  </div>
                  <div class="card-body pb-0">
                    <div class="mb-4 mt-2">
                      <h1>$4,578.58</h1>
                    </div>
                    <div class="pull-in">
                      <canvas id="dailySalesChart"></canvas>
                    </div>
                  </div>
                </div>
                <div class="card card-round">
                  <div class="card-body pb-0">
                    <div class="h1 fw-bold float-end text-primary">+5%</div>
                    <h2 class="mb-2">17</h2>
                    <p class="text-muted">Users online</p>
                    <div class="pull-in sparkline-fix">
                      <div id="lineChart"></div>
                    </div>
                  </div>
                </div>
              </div> 
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                      <h4 class="card-title">Users Geolocation</h4>
                      <div class="card-tools">
                        <button
                          class="btn btn-icon btn-link btn-primary btn-xs"
                        >
                          <span class="fa fa-angle-down"></span>
                        </button>
                        <button
                          class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card"
                        >
                          <span class="fa fa-sync-alt"></span>
                        </button>
                        <button
                          class="btn btn-icon btn-link btn-primary btn-xs"
                        >
                          <span class="fa fa-times"></span>
                        </button>
                      </div>
                    </div>
                    <p class="card-category">
                      Map of the distribution of users around the world
                    </p>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="table-responsive table-hover table-sales">
                          <table class="table">
                            <tbody>
                              <tr>
                                <td>
                                  <div class="flag">
                                    <img
                                      src="assets/img/flags/id.png"
                                      alt="indonesia"
                                    />
                                  </div>
                                </td>
                                <td>Indonesia</td>
                                <td class="text-end">2.320</td>
                                <td class="text-end">42.18%</td>
                              </tr>
                              <tr>
                                <td>
                                  <div class="flag">
                                    <img
                                      src="assets/img/flags/us.png"
                                      alt="united states"
                                    />
                                  </div>
                                </td>
                                <td>USA</td>
                                <td class="text-end">240</td>
                                <td class="text-end">4.36%</td>
                              </tr>
                              <tr>
                                <td>
                                  <div class="flag">
                                    <img
                                      src="assets/img/flags/au.png"
                                      alt="australia"
                                    />
                                  </div>
                                </td>
                                <td>Australia</td>
                                <td class="text-end">119</td>
                                <td class="text-end">2.16%</td>
                              </tr>
                              <tr>
                                <td>
                                  <div class="flag">
                                    <img
                                      src="assets/img/flags/ru.png"
                                      alt="russia"
                                    />
                                  </div>
                                </td>
                                <td>Russia</td>
                                <td class="text-end">1.081</td>
                                <td class="text-end">19.65%</td>
                              </tr>
                              <tr>
                                <td>
                                  <div class="flag">
                                    <img
                                      src="assets/img/flags/cn.png"
                                      alt="china"
                                    />
                                  </div>
                                </td>
                                <td>China</td>
                                <td class="text-end">1.100</td>
                                <td class="text-end">20%</td>
                              </tr>
                              <tr>
                                <td>
                                  <div class="flag">
                                    <img
                                      src="assets/img/flags/br.png"
                                      alt="brazil"
                                    />
                                  </div>
                                </td>
                                <td>Brasil</td>
                                <td class="text-end">640</td>
                                <td class="text-end">11.63%</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mapcontainer">
                          <div
                            id="world-map"
                            class="w-100"
                            style="height: 300px"
                          ></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="card card-round">
                  <div class="card-body">
                    <div class="card-head-row card-tools-still-right">
                      <div class="card-title">New Customers</div>
                      <div class="card-tools">
                        <div class="dropdown">
                          <button
                            class="btn btn-icon btn-clean me-0"
                            type="button"
                            id="dropdownMenuButton"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                          >
                            <i class="fas fa-ellipsis-h"></i>
                          </button>
                          <div
                            class="dropdown-menu"
                            aria-labelledby="dropdownMenuButton"
                          >
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#"
                              >Something else here</a
                            >
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-list py-4">
                      <div class="item-list">
                        <div class="avatar">
                          <img
                            src="assets/img/jm_denis.jpg"
                            alt="..."
                            class="avatar-img rounded-circle"
                          />
                        </div>
                        <div class="info-user ms-3">
                          <div class="username">Jimmy Denis</div>
                          <div class="status">Graphic Designer</div>
                        </div>
                        <button class="btn btn-icon btn-link op-8 me-1">
                          <i class="far fa-envelope"></i>
                        </button>
                        <button class="btn btn-icon btn-link btn-danger op-8">
                          <i class="fas fa-ban"></i>
                        </button>
                      </div>
                      <div class="item-list">
                        <div class="avatar">
                          <span
                            class="avatar-title rounded-circle border border-white"
                            >CF</span
                          >
                        </div>
                        <div class="info-user ms-3">
                          <div class="username">Chandra Felix</div>
                          <div class="status">Sales Promotion</div>
                        </div>
                        <button class="btn btn-icon btn-link op-8 me-1">
                          <i class="far fa-envelope"></i>
                        </button>
                        <button class="btn btn-icon btn-link btn-danger op-8">
                          <i class="fas fa-ban"></i>
                        </button>
                      </div>
                      <div class="item-list">
                        <div class="avatar">
                          <img
                            src="assets/img/talha.jpg"
                            alt="..."
                            class="avatar-img rounded-circle"
                          />
                        </div>
                        <div class="info-user ms-3">
                          <div class="username">Talha</div>
                          <div class="status">Front End Designer</div>
                        </div>
                        <button class="btn btn-icon btn-link op-8 me-1">
                          <i class="far fa-envelope"></i>
                        </button>
                        <button class="btn btn-icon btn-link btn-danger op-8">
                          <i class="fas fa-ban"></i>
                        </button>
                      </div>
                      <div class="item-list">
                        <div class="avatar">
                          <img
                            src="assets/img/chadengle.jpg"
                            alt="..."
                            class="avatar-img rounded-circle"
                          />
                        </div>
                        <div class="info-user ms-3">
                          <div class="username">Chad</div>
                          <div class="status">CEO Zeleaf</div>
                        </div>
                        <button class="btn btn-icon btn-link op-8 me-1">
                          <i class="far fa-envelope"></i>
                        </button>
                        <button class="btn btn-icon btn-link btn-danger op-8">
                          <i class="fas fa-ban"></i>
                        </button>
                      </div>
                      <div class="item-list">
                        <div class="avatar">
                          <span
                            class="avatar-title rounded-circle border border-white bg-primary"
                            >H</span
                          >
                        </div>
                        <div class="info-user ms-3">
                          <div class="username">Hizrian</div>
                          <div class="status">Web Designer</div>
                        </div>
                        <button class="btn btn-icon btn-link op-8 me-1">
                          <i class="far fa-envelope"></i>
                        </button>
                        <button class="btn btn-icon btn-link btn-danger op-8">
                          <i class="fas fa-ban"></i>
                        </button>
                      </div>
                      <div class="item-list">
                        <div class="avatar">
                          <span
                            class="avatar-title rounded-circle border border-white bg-secondary"
                            >F</span
                          >
                        </div>
                        <div class="info-user ms-3">
                          <div class="username">Farrah</div>
                          <div class="status">Marketing</div>
                        </div>
                        <button class="btn btn-icon btn-link op-8 me-1">
                          <i class="far fa-envelope"></i>
                        </button>
                        <button class="btn btn-icon btn-link btn-danger op-8">
                          <i class="fas fa-ban"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                      <div class="card-title">Transaction History</div>
                      <div class="card-tools">
                        <div class="dropdown">
                          <button
                            class="btn btn-icon btn-clean me-0"
                            type="button"
                            id="dropdownMenuButton"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                          >
                            <i class="fas fa-ellipsis-h"></i>
                          </button>
                          <div
                            class="dropdown-menu"
                            aria-labelledby="dropdownMenuButton"
                          >
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#"
                              >Something else here</a
                            >
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-body p-0">
                    <div class="table-responsive">
                       Projects table 
                      <table class="table align-items-center mb-0">
                        <thead class="thead-light">
                          <tr>
                            <th scope="col">Payment Number</th>
                            <th scope="col" class="text-end">Date & Time</th>
                            <th scope="col" class="text-end">Amount</th>
                            <th scope="col" class="text-end">Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <th scope="row">
                              <button
                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                              >
                                <i class="fa fa-check"></i>
                              </button>
                              Payment from #10231
                            </th>
                            <td class="text-end">Mar 19, 2020, 2.45pm</td>
                            <td class="text-end">$250.00</td>
                            <td class="text-end">
                              <span class="badge badge-success">Completed</span>
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">
                              <button
                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                              >
                                <i class="fa fa-check"></i>
                              </button>
                              Payment from #10231
                            </th>
                            <td class="text-end">Mar 19, 2020, 2.45pm</td>
                            <td class="text-end">$250.00</td>
                            <td class="text-end">
                              <span class="badge badge-success">Completed</span>
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">
                              <button
                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                              >
                                <i class="fa fa-check"></i>
                              </button>
                              Payment from #10231
                            </th>
                            <td class="text-end">Mar 19, 2020, 2.45pm</td>
                            <td class="text-end">$250.00</td>
                            <td class="text-end">
                              <span class="badge badge-success">Completed</span>
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">
                              <button
                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                              >
                                <i class="fa fa-check"></i>
                              </button>
                              Payment from #10231
                            </th>
                            <td class="text-end">Mar 19, 2020, 2.45pm</td>
                            <td class="text-end">$250.00</td>
                            <td class="text-end">
                              <span class="badge badge-success">Completed</span>
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">
                              <button
                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                              >
                                <i class="fa fa-check"></i>
                              </button>
                              Payment from #10231
                            </th>
                            <td class="text-end">Mar 19, 2020, 2.45pm</td>
                            <td class="text-end">$250.00</td>
                            <td class="text-end">
                              <span class="badge badge-success">Completed</span>
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">
                              <button
                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                              >
                                <i class="fa fa-check"></i>
                              </button>
                              Payment from #10231
                            </th>
                            <td class="text-end">Mar 19, 2020, 2.45pm</td>
                            <td class="text-end">$250.00</td>
                            <td class="text-end">
                              <span class="badge badge-success">Completed</span>
                            </td>
                          </tr>
                          <tr>
                            <th scope="row">
                              <button
                                class="btn btn-icon btn-round btn-success btn-sm me-2"
                              >
                                <i class="fa fa-check"></i>
                              </button>
                              Payment from #10231
                            </th>
                            <td class="text-end">Mar 19, 2020, 2.45pm</td>
                            <td class="text-end">$250.00</td>
                            <td class="text-end">
                              <span class="badge badge-success">Completed</span>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>-->
            </div> 
<?php 
}
else{// other users redirect to index page
	echo '<script>window.location.href="index.php";</script>';
}
?>
