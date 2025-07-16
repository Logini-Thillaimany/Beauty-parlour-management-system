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
if($system_usertype!="Guest")
{
include("connection.php");

//insert code start
if(isset($_POST["btnsave"]))
{
	$sql_insert="INSERT INTO message(message_id,from_id,to_id,date,time,subject,message,readstatus,inboxdelete,sentdelete)
							VALUES('".mysqli_real_escape_string($con,$_POST["txtmessageid"])."',
									'".mysqli_real_escape_string($con,$system_user_id)."',
									'".mysqli_real_escape_string($con,$_POST["txttoid"])."',
									'".mysqli_real_escape_string($con,date("Y-m-d"))."',
									'".mysqli_real_escape_string($con,date("H:i:s"))."',
									'".mysqli_real_escape_string($con,$_POST["txtsubject"])."',
									'".mysqli_real_escape_string($con,$_POST["txtmessage"])."',
									'".mysqli_real_escape_string($con,1)."',
									'".mysqli_real_escape_string($con,1)."',
									'".mysqli_real_escape_string($con,1)."')";
	$result_insert=mysqli_query($con,$sql_insert) or die("sql error in sql_insert ".mysqli_error($con));
	if($result_insert)
	{
		echo '<script>alert("Successfully Insert");
						window.location.href="index.php?page=message.php&option=add";</script>';
	}
}
//insert code end
?>
<body>
<?php
if(isset($_GET["option"]))
{
	if($_GET["option"]=="add")
	{
		//add form
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header justify-content-center">
						<div class="card-title"> Compose Messsage</div>
					</div>
					<div class="card-body">
						<div class="row justify-content-center" >
							<!-- form start -->
							<form method="POST" action="" class="col-md-12">
								
								<!-- one row start -->
								<div class="form-group">
									<div class="row justify-content-center">
										<!-- column one start -->
										<div class="col-md-8 col-lg-6">									
											<label for="txtmessageid">message ID</label>
											<?php
												$sql_generatedid="SELECT message_id FROM message ORDER BY message_id DESC LIMIT 1";
												$result_generatedid=mysqli_query($con,$sql_generatedid) or die("sql error in sql_generatedid ".mysqli_error($con));
												if(mysqli_num_rows($result_generatedid)==1)
												{// for  except from the first submission
													$row_generatedid=mysqli_fetch_assoc($result_generatedid);
													$generatedid=++$row_generatedid["message_id"];
												}
												else
												{//For first time submission
													$generatedid="MSG0000001";
												}
											?>
											<input type="text" class="form-control" name="txtmessageid" id="txtmessageid" required placeholder="message ID" value="<?php echo $generatedid;?>" readonly />
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-8 col-lg-6">
											<label for="txtformid">From id</label>
											<input type="text" class="form-control" name="txtformid" id="txtformid" value="<?php echo $system_username; ?>" readonly required placeholder="From"/>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- one row end -->
								
								<!-- second row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">									
											<label for="txttoid">TO ID</label>
											<select class="form-control" name="txttoid" id="txttoid" required placeholder="TO">
												<option value="select"> Select </option>
												<?php
												if($system_usertype=="Customer")
												{//if login user is Customer
													$sql_loaduser="SELECT user_id,username,usertype FROM login WHERE (usertype='Admin' OR usertype='Clerk') AND status='Active' ";
												}
												else if($system_usertype=="MakeupArtist" || $system_usertype=="SaloonService"  )
												{//if login user is MakeupArtist or SaloonService
													$sql_loaduser="SELECT user_id,username,usertype FROM login WHERE usertype!='Customer' AND status='Active'  ";
												}
												else
												{//if login user is admin or clerk
													$sql_loaduser="SELECT user_id,username,usertype FROM login WHERE status='Active' ";
												}
												$result_loaduser=mysqli_query($con,$sql_loaduser) or die("sql error in sql_loaduser ".mysqli_error($con));
												while($row_loaduser=mysqli_fetch_assoc($result_loaduser))
												{
													if($row_loaduser["usertype"]=="Customer")
													{//if Load user is customer 
														$sql_loadusername="SELECT name FROM customer WHERE customer_id='$row_loaduser[user_id]'";
													}
													else 
													{/// if load user is other than customer
														$sql_loadusername="SELECT name FROM staff WHERE staff_id='$row_loaduser[user_id]'";
													}	
													$result_loadusername=mysqli_query($con,$sql_loadusername) or die("sql error in sql_loadusername ".mysqli_error($con));
													$row_loadusername=mysqli_fetch_assoc($result_loadusername);
													echo'<option value="'.$row_loaduser["user_id"].'">'.$row_loadusername["name"].' - '.$row_loaduser["usertype"].'</option>';
												}
												?>
											</select>
										</div>
										<!-- column one end -->
										<!-- column two start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtsubject">subject</label>
											<textarea class="form-control" name="txtsubject" id="txtsubject" required placeholder="subject"></textarea>
										</div>
										<!-- column two end -->
									</div>
								</div>
								<!-- second row end -->							
								
								<!-- fourth row start -->
								<div class="form-group">
									<div class="row">
										<!-- column one start -->
										<div class="col-md-6 col-lg-6">
											<label for="txtmessage">message</label>
											<textarea class="form-control" name="txtmessage" id="txtmessage" required placeholder="message here"></textarea>
										</div>
										<!-- column one end -->										
									</div>
								</div>
								<!-- fourth row end -->
								
								<!-- button start -->
								<div class="form-group">
									<div class="row">
										<div class="col-md-6 col-lg-12">	
											<center>
												<a href="index.php?page=message.php&option=view"><input type="button" class="btn btn-primary" name="btngoback" id="btngoback"  value="Go Back"/></a>
												<input type="reset" class="btn btn-danger" name="btnclear" id="btnclear"  value="Clear"/>
												<input type="submit" class="btn btn-success" name="btnsave" id="btnsave"  value="Send"/>
											</center>
										</div>
									</div>
								</div>
								<!-- button end -->
								
							</form>
							<!-- form end -->
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	else if($_GET["option"]=="view")
	{
		//view table
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Inbox Message Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=message.php&option=add"><button class="btn btn-primary">Compose Message</button></a> 
							<a href="index.php?page=message.php&option=sent"><button class="btn btn-primary">View sent Message</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>Message ID</th>
										<th>From</th>
										<th>Date</th>
										<th>Time</th>
										<th>Subject</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT message_id,from_id,date,time,subject,readstatus FROM message WHERE to_id='$system_user_id' AND inboxdelete='1'";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{
										$sql_fromusertype="SELECT usertype, user_id FROM login WHERE user_id='$row_view[from_id]'";
										$result_fromusertype=mysqli_query($con,$sql_fromusertype) or die("sql error in sql_fromusertype ".mysqli_error($con));
										$row_fromusertype=mysqli_fetch_assoc($result_fromusertype);
										
										if($row_fromusertype["usertype"]=="Customer")
										{//if from user is customer 
											$sql_fromusername="SELECT name FROM customer WHERE customer_id='$row_fromusertype[user_id]'";
										}
										else 
										{/// if from user is other than customer
											$sql_fromusername="SELECT name FROM staff WHERE staff_id='$row_fromusertype[user_id]'";
										}	
										$result_fromusername=mysqli_query($con,$sql_fromusername) or die("sql error in sql_fromusername ".mysqli_error($con));
										$row_fromusername=mysqli_fetch_assoc($result_fromusername);
										
										echo '<tr>';
										if($row_view["readstatus"]==1)
										{
											echo '<td><font color="red">'.$row_view["message_id"].'</font></td>';
										}
										else
										{
											echo '<td>'.$row_view["message_id"].'</td>';
										}
											
											echo '<td>'.$row_fromusername["name"].' - '.$row_fromusertype["usertype"].'</td>';
											echo '<td>'.$row_view["date"].'</td>';
											echo '<td>'.$row_view["time"].'</td>';
											echo '<td>'.$row_view["subject"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=message.php&option=fullview&pk_message_id_i='.$row_view["message_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=message.php&option=delete&pk_message_id_i='.$row_view["message_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
											echo '</td>';
										echo '</tr>';
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	else if($_GET["option"]=="sent")
	{
		//view table
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Sent Message Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<a href="index.php?page=message.php&option=add"><button class="btn btn-primary">Compose Message</button></a> 
							<a href="index.php?page=message.php&option=view"><button class="btn btn-primary">View Inbox Message</button></a><br><br>
							<table id="basic-datatables" class="display table table-striped table-hover">
								<thead>
									<tr>
										<th>Message ID</th>
										<th>To</th>
										<th>Date</th>
										<th>Time</th>
										<th>Subject</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_view="SELECT message_id,to_id,date,time,subject FROM message WHERE from_id='$system_user_id' AND sentdelete='1'";
									$result_view=mysqli_query($con,$sql_view) or die("sql error in sql_view ".mysqli_error($con));
									while($row_view=mysqli_fetch_assoc($result_view))
									{
										$sql_tousertype="SELECT usertype, user_id FROM login WHERE user_id='$row_view[to_id]'";
										$result_tousertype=mysqli_query($con,$sql_tousertype) or die("sql error in sql_tousertype ".mysqli_error($con));
										$row_tousertype=mysqli_fetch_assoc($result_tousertype);
										
										if($row_tousertype["usertype"]=="Customer")
										{//if to user is customer 
											$sql_tousername="SELECT name FROM customer WHERE customer_id='$row_tousertype[user_id]'";
										}
										else 
										{/// if to user is other than customer
											$sql_tousername="SELECT name FROM staff WHERE staff_id='$row_tousertype[user_id]'";
										}	
										$result_tousername=mysqli_query($con,$sql_tousername) or die("sql error in sql_tousername ".mysqli_error($con));
										$row_tousername=mysqli_fetch_assoc($result_tousername);
										
										echo '<tr>';
											echo '<td>'.$row_view["message_id"].'</td>';
											echo '<td>'.$row_tousername["name"].' - '.$row_tousertype["usertype"].'</td>';
											echo '<td>'.$row_view["date"].'</td>';
											echo '<td>'.$row_view["time"].'</td>';
											echo '<td>'.$row_view["subject"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=message.php&option=fullview&pk_message_id_s='.$row_view["message_id"].'"><button class="btn btn-success btn-sm"><i class="fa fa-eye"></i> View</button></a> ';
												echo '<a onclick="return delete_confirm()" href="index.php?page=message.php&option=delete&pk_message_id_s='.$row_view["message_id"].'"><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button></a> ';
											echo '</td>';
										echo '</tr>';
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	else if($_GET["option"]=="fullview")
	{
		//fullview table
		if(isset($_GET["pk_message_id_i"]))
		{
			$get_pk_message_id=$_GET["pk_message_id_i"];
			$backoption= "view";
			
			$sql_update="UPDATE message SET readstatus='0' WHERE message_id='$get_pk_message_id'";
			$result_update=mysqli_query($con,$sql_update) or die("sql error in sql_update ".mysqli_error($con));
		}
		else
		{
			$get_pk_message_id=$_GET["pk_message_id_s"];
			$backoption= "sent";
		}
		
		
		$sql_fullview="SELECT * FROM message WHERE message_id='$get_pk_message_id'";
		$result_fullview=mysqli_query($con,$sql_fullview) or die("sql error in sql_fullview ".mysqli_error($con));
		$row_fullview=mysqli_fetch_assoc($result_fullview);
		
		// Name for from ID
		$sql_fromusertype="SELECT usertype, user_id FROM login WHERE user_id='$row_fullview[from_id]'";
		$result_fromusertype=mysqli_query($con,$sql_fromusertype) or die("sql error in sql_fromusertype ".mysqli_error($con));
		$row_fromusertype=mysqli_fetch_assoc($result_fromusertype);
		
		if($row_fromusertype["usertype"]=="Customer")
		{//if from user is customer 
			$sql_fromusername="SELECT name FROM customer WHERE customer_id='$row_fromusertype[user_id]'";
		}
		else 
		{/// if from user is other than customer
			$sql_fromusername="SELECT name FROM staff WHERE staff_id='$row_fromusertype[user_id]'";
		}	
		$result_fromusername=mysqli_query($con,$sql_fromusername) or die("sql error in sql_fromusername ".mysqli_error($con));
		$row_fromusername=mysqli_fetch_assoc($result_fromusername);
		
		//name for TO ID
		$sql_tousertype="SELECT usertype, user_id FROM login WHERE user_id='$row_fullview[to_id]'";
		$result_tousertype=mysqli_query($con,$sql_tousertype) or die("sql error in sql_tousertype ".mysqli_error($con));
		$row_tousertype=mysqli_fetch_assoc($result_tousertype);
		
		if($row_tousertype["usertype"]=="Customer")
		{//if to user is customer 
			$sql_tousername="SELECT name FROM customer WHERE customer_id='$row_tousertype[user_id]'";
		}
		else 
		{/// if to user is other than customer
			$sql_tousername="SELECT name FROM staff WHERE staff_id='$row_tousertype[user_id]'";
		}	
		$result_tousername=mysqli_query($con,$sql_tousername) or die("sql error in sql_tousername ".mysqli_error($con));
		$row_tousername=mysqli_fetch_assoc($result_tousername);
										
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">Message Full Details</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="display table table-striped table-hover">
								<tr><th>Message ID</th><td><?php echo $row_fullview["message_id"]; ?></td></tr>
								<tr><th>From</th><td><?php echo $row_fromusername["name"].' - '.$row_fromusertype["usertype"] ; ?></td></tr>
								<tr><th>TO </th><td><?php echo $row_tousername["name"].' - '.$row_tousertype["usertype"] ; ?></td></tr>
								<tr><th>Date</th><td><?php echo $row_fullview["date"]; ?></td></tr>
								<tr><th>Time</th><td><?php echo $row_fullview["time"]; ?></td></tr>
								<tr><th>Subject</th><td><?php echo $row_fullview["subject"]; ?></td></tr>
								<tr><th>Message</th><td><?php echo $row_fullview["message"]; ?></td></tr>
								<tr>			
									<td colspan="2">
										<center>
											<a href="index.php?page=message.php&option=<?php echo $backoption; ?>"><button class="btn btn-primary">Go Back</button></a> 
										</center>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	else if($_GET["option"]=="edit")
	{
		//No edit form
		
	}
	else if($_GET["option"]=="delete")
	{
		//delete code
		if(isset($_GET["pk_message_id_i"]))
		{
			$get_pk_message_id=$_GET["pk_message_id_i"];
			$backoption= "view";
			$sql_delete="UPDATE message SET inboxdelete='0', readstatus='0' WHERE message_id='$get_pk_message_id'";
		}
		else
		{
			$get_pk_message_id=$_GET["pk_message_id_s"];
			$backoption= "sent";
			$sql_delete="UPDATE message SET sentdelete='0' WHERE message_id='$get_pk_message_id'";
		}
		
		
		$result_delete=mysqli_query($con,$sql_delete) or die("sql error in sql_delete ".mysqli_error($con));
		if($result_delete)
		{
				echo '<script>alert("Successfully Deleted");
						window.location.href="index.php?page=message.php&option='.$backoption.'";</script>';
	
		}	
	
	}
}
?>
</body>
<?php 
}
else
{
	echo '<script> window.location.href="index.php";</script>';
}
?>