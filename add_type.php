<?php 
include 'include/top.php';
include 'include/sidebar.php';
?>
        <div class="content-body">
            <!-- row -->
			<div class="container-fluid">
				<div class="form-head mb-4 d-flex flex-wrap align-items-center">
					<div class="me-auto">
						<h2 class="font-w600 mb-0">Type & Price Management</h2>
						
					</div>	
					
				</div>
				<div class="row">
					
					<div class="col-xl-12 col-lg-12">
					 <?php 
								if(isset($_GET['id']))
								{
									$data = $event->query("select * from  tbl_type_price where id=".$_GET['id']."")->fetch_assoc();
									?>
									<div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Edit Type & Price</h4>
                            </div>
                            <div class="card-body">
                               
                                    <form method="post" enctype="multipart/form-data">
                                    
                                    
                                        <div class="form-group mb-3">
                                            <label>Select Event</label>
                                            <select name="eid" class="form-control select2-single" required>
											<option value="" disabled selected>Select Event</option>
											<?php 
											$cat = $event->query("select * from tbl_event");
											while($row = $cat->fetch_assoc())
											{
												?>
												<option value="<?php echo $row['id'];?>" <?php if($data['eid'] == $row['id']){echo 'selected';}?>><?php echo $row['title'];?></option>
												<?php 
											}
											?>
											</select>
                                        </div>
										
                                       <div class="form-group mb-3">
                                            <label>Event  Type</label>
                                            <input type="text" class="form-control"  name="etype" value="<?php echo $data['type'];?>" placeholder="Enter Event Type"  required="">
											<input type="hidden" name="type" value="edit_type"/>
										<input type="hidden" name="id" value="<?php echo $_GET['id'];?>"/>
                                        </div>
										
										<div class="form-group mb-3">
                                            <label>Event  Ticket Price</label>
                                            <input type="text" class="form-control  numberonly" value="<?php echo $data['price'];?>" name="price" placeholder="Enter Ticket Price"  required="">
                                        </div>
										
										<div class="form-group mb-3">
                                            <label>Event  Ticket Limit</label>
                                            <input type="text" class="form-control numberonly" value="<?php echo $data['tlimit'];?>" name="tlimit" placeholder="Enter Ticket Limit"  required="">
                                        </div>
										
										
										 <div class="form-group mb-3">
                                            <label>Sponsore Status</label>
                                            <select name="status" name="status" class="form-control " required>
											<option value="">Select Status</option>
											<option value="1" <?php if($data['status'] == 1){echo 'selected';}?>>Publish</option>
											<option value="0" <?php if($data['status'] == 0){echo 'selected';}?>>UnPublish</option>
											</select>
                                        </div>
                                        
										
                                    
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-rounded btn-primary"><span class="btn-icon-start text-primary"><i class="fa fa-list"></i>
                                    </span>Edit Type & price</button>
                                    </div>
                                </form>
                               
                            </div>
                        </div>
									<?php 
								}
								else 
								{
								?>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Add Type & Price</h4>
                            </div>
                            <div class="card-body">
                               
                                    <form method="post" enctype="multipart/form-data">
                                    
                                    
                                        <div class="form-group mb-3">
                                            <label>Select Event</label>
                                            <select name="eid" class="form-control select2-single" required>
											<option value="" disabled selected>Select Event</option>
											<?php 
											$cat = $event->query("select * from tbl_event");
											while($row = $cat->fetch_assoc())
											{
												?>
												<option value="<?php echo $row['id'];?>"><?php echo $row['title'];?></option>
												<?php 
											}
											?>
											</select>
                                        </div>
                                        
										<div class="form-group mb-3">
                                            <label>Event  Type</label>
                                            <input type="text" class="form-control"  name="etype" placeholder="Enter Event Type"  required="">
											<input type="hidden" name="type" value="add_type"/>
                                        </div>
										
										<div class="form-group mb-3">
                                            <label>Event  Ticket Price</label>
                                            <input type="text" class="form-control  numberonly"  name="price" placeholder="Enter Ticket Price"  required="">
                                        </div>
										
										<div class="form-group mb-3">
                                            <label>Event  Ticket Limit</label>
                                            <input type="text" class="form-control numberonly"  name="tlimit" placeholder="Enter Ticket Limit"  required="">
                                        </div>
										
										 <div class="form-group mb-3">
                                            <label>Ticket Status</label>
                                            <select name="status" name="status" class="form-control " required>
											<option value="">Select Status</option>
											<option value="1">Publish</option>
											<option value="0">UnPublish</option>
											</select>
                                        </div>
                                        
										
                                    
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-rounded btn-primary"><span class="btn-icon-start text-primary"><i class="fa fa-list"></i>
                                    </span>Add Type & Price</button>
                                    </div>
                                </form>
                               
                            </div>
                        </div>
						 <?php } ?>
					</div>
						
					
					
					
				</div>
            </div>
			
        </div>
       
	</div>
    
   <?php include 'include/footer.php';?>
   
</body>

</html>