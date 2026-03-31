<?php 
include 'include/top.php';
include 'include/sidebar.php';
?>
        <div class="content-body">
            <!-- row -->
			<div class="container-fluid">
				<div class="form-head mb-4 d-flex flex-wrap align-items-center">
					<div class="me-auto">
						<h2 class="font-w600 mb-0">User Management</h2>
						
					</div>	
					
				</div>
				<div class="row">
					
					<div class="col-xl-12 col-lg-12">
					     <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">User List</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example3" class="display" style="min-width: 845px">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>mobile</th>
                                                <th>Join Date</th>
                                                <th>Status</th>
                                                <th>Refer Code</th>
                                                <th>Join Code</th>
                                                <th>Wallet</th>
                                            </tr>
                                        </thead>
                                        <tbody>
										<?php 
											 $stmt = $event->query("SELECT * FROM `tbl_user`");
$i = 0;
while($row = $stmt->fetch_assoc())
{
	$i = $i + 1;
											?>
                                            <tr>
                                                <td><img class="rounded-circle" width="35" src="<?php echo $row['pro_pic'];?>" alt=""></td>
                                                <td><?php echo $row['name'];?></td>
												<td><?php echo $row['email'];?></td>
												<td><?php echo $row['mobile'];?></td>
												<td><?php echo $row['rdate'];?></td>
												<?php if($row['status'] == 1) { ?>
                                                <td><a class="drop" data-id="<?php echo $row['id'];?>" data-status="0" data-type="update_status" coll-type="user" href="javascript:void(0);"><div class="badge badge-danger">Make Deactive</div></a></td>
												<?php } else { ?>
												<td><a class="drop" data-id="<?php echo $row['id'];?>" data-status="1" data-type="update_status" coll-type="user" href="javascript:void(0);"><div class="badge badge-success">Make Active</div></a></td>
												<?php } ?>
												<td><?php echo $row['code'];?></td>
												<td><?php echo $row['refercode'];?></td>
												<td><?php echo $row['wallet'].$set['currency'];?></td>
                                               												
                                            </tr>
<?php } ?>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
					</div>
						
					
					
					
				</div>
            </div>
			
        </div>
       
	</div>
    
   <?php include 'include/footer.php';?>
   
</body>

</html>