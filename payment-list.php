<?php 
include 'include/top.php';
include 'include/sidebar.php';
?>
        <div class="content-body">
            <!-- row -->
			<div class="container-fluid">
				<div class="form-head mb-4 d-flex flex-wrap align-items-center">
					<div class="me-auto">
						<h2 class="font-w600 mb-0">Payment Management</h2>
						
					</div>	
					
				</div>
				<div class="row">
					
					<div class="col-xl-12 col-lg-12">
					     <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Payment List</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example3" class="display" style="min-width: 845px">
                                        <thead>
                                            <tr>
                                                <th>Sr No.</th>
                                               <th>Payment <br>Gateway <br>Name</th>
												<th>Payment <br>Gateway <br>Subtitle</th>
                                                <th>Payment <br>Gateway <br>Image</th>
                                                
                                                
                                                <th>Payment <br>Gateway <br>Status</th>
												 <th>Show <br>On <br>Wallet?</th>
                                                <th>Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
											 $stmt = $event->query("SELECT * FROM `tbl_payment_list` where id!=3");
$i = 0;
while($row = $stmt->fetch_assoc())
{
	$i = $i + 1;
											?>
                                                <tr>
                                                <td>
                                                    <?php echo $i; ?>
                                                </td>
                                                <td> <?php echo $row['title']; ?></td>
												<td style="max-width:200px;"> <?php echo $row['subtitle']; ?></td>
                                                <td class="align-middle">
                                                   <img src="<?php echo $row['img']; ?>" width="60" height="60"/>
                                                </td>
                                                
                                               
												<?php if($row['status'] == 1) { ?>
												
                                                <td><span class="badge badge-success">Publish</span></td>
												<?php } else { ?>
												
												<td>
												<span class="badge badge-danger">Unpublish</span></td>
												<?php } ?>
												
												<?php if($row['p_show'] == 1) { ?>
												
                                                <td><span class="badge badge-success">Publish</span></td>
												<?php } else { ?>
												
												<td>
												<span class="badge badge-danger">Unpublish</span></td>
												<?php } ?>
                                                <td>
													<div class="d-flex">
														<a href="edit_payment.php?id=<?php echo $row['id'];?>" class="btn btn-primary shadow btn-xs sharp me-1"><i class="fa fa-pencil"></i></a>
														
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