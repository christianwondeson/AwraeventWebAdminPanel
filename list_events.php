<?php 
include 'include/top.php';
include 'include/sidebar.php';
?>
        <div class="content-body">
            <!-- row -->
			<div class="container-fluid">
				<div class="form-head mb-4 d-flex flex-wrap align-items-center">
					<div class="me-auto">
						<h2 class="font-w600 mb-0">Event Management</h2>
						
					</div>	
					
				</div>
				<div class="row">
					
					<div class="col-xl-12 col-lg-12">
					     <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Event List</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example3" class="display" style="min-width: 845px">
                                        <thead>
                                            <tr>
                                                <th>Sr No.</th>
												<th>Event <br>Id.</th>
                                                <th>Event <br>Name</th>
                                                <th>Event<br> Start <br>Date</th>
                                                <th>Event <br>Image</th>
                                                <th>Event<br> Cover <br>Image</th>
                                               
												<th>Event <br>Time</th>
                                                <th>Total <br>Tickets</th>
                                                <th>Publish <br>Status</th>
												 <th>Event <br>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
										<?php 
										$city = $event->query("select * from tbl_event");
										$i=0;
										while($row = $city->fetch_assoc())
										{
											$i = $i + 1;
											?>
                                            <tr>
                                                <td>
                                                    <?php echo $i; ?>
                                                </td>
												<td> <?php echo $row['id']; ?></td>
												<td> <?php echo $row['title']; ?></td>
                                                <td> <?php echo $row['sdate']; ?></td>
												<td> <img src="<?php echo $row['img']; ?>" width="100" height="100"/></td>
												<td> <img src="<?php echo $row['cover_img']; ?>" width="100" height="100"/></td>
												
												<td> <?php echo date("g:i A", strtotime($row['stime'])).'<br> TO <br>'.date("g:i A", strtotime($row['etime'])); ?></td>
												<td><?php $tdata = $event->query("select sum(tlimit) as total_ticket from tbl_type_price where eid=".$row['id']."")->fetch_assoc(); echo empty($tdata['total_ticket']) ? '0 Tickets': $tdata['total_ticket'].' Tickets';?></td>
                                               <?php if($row['status'] == 1) { ?>
												
                                                <td><span class="badge badge-success">Publish</span></td>
												<?php } else { ?>
												
												<td>
												<span class="badge badge-danger">Unpublish</span></td>
												<?php } ?>
												
												
												
                                                <td><span class="badge badge-success"><?php echo $row['event_status']; ?></span></td>
												
                                                <td>
													<div class="d-flex">
														<a href="add_events.php?id=<?php echo $row['id'];?>" class="btn btn-primary shadow btn-xs sharp me-1"><i class="fa fa-pencil"></i></a>
														<a href="list_tickets.php?id=<?php echo $row['id'];?>" class="btn btn-info shadow btn-xs sharp me-1" data-title="show tickets"><i class="fa fa-ticket"></i></a>
													</div>												
												</td>												
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