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
					 <?php 
								if(isset($_GET['id']))
								{
									$data = $event->query("select * from tbl_event where id=".$_GET['id']."")->fetch_assoc();
									?>
									<div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Edit Event</h4>
                            </div>
                            <div class="card-body">
                               
                                    <form method="post" enctype="multipart/form-data">
                                    <div class="row">
<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                                    
                                        <div class="form-group mb-3">
                                            <label>Event Name</label>
                                            <input type="text" class="form-control" name="title" value="<?php echo $data['title'];?>" placeholder="Enter Event Name"  required="">
											<input type="hidden" name="type" value="edit_event"/>
											<input type="hidden" name="id" value="<?php echo $_GET['id'];?>"/>
                                        </div>
										</div>
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                                        <div class="form-group mb-3">
                                            <label>Event Image</label>
                                            <div class="input-group">
                                            
                                                <input type="file" name="cat_img">
												
												<img src="<?php echo $data['img'];?>" width="100" height="100"/>
										
                                            
                                        </div>
                                        </div>
										</div>
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Event Cover Image</label>
                                            <div class="input-group">
                                            
                                                <input type="file" name="cover_img">
												
												<img src="<?php echo $data['cover_img'];?>" width="100" height="100"/>
                                            
                                        </div>
                                        </div>
										</div>
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Event Start Date</label>
										<input type="date" name="sdate" class="form-control" value="<?php echo $data['sdate'];?>" placeholder="Select Date" required>
										</div>
										</div>
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                           <label class="form-label">Event Start Time</label>
                                        <div class="input-group">
                                            <input type="time" name="stime" class="form-control" value="<?php echo $data['stime'];?>" required>
                                        </div>
										</div>
										</div>
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                           <label class="form-label">Event End Time</label>
                                        <div class="input-group">
                                            <input type="time" name="etime" class="form-control" value="<?php echo $data['etime'];?>" required>
                                        </div>
										</div>
										</div>
										<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
										 <label class="form-label">Event Latitude</label>
										 <input type="text" class="form-control " name="latitude" value="<?php echo $data['latitude'];?>" placeholder="Enter Latitude"  required="">
										</div>
										</div>
										<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
										 <label class="form-label">Event Longtitude</label>
										 <input type="text" class="form-control " name="longtitude" value="<?php echo $data['longtitude'];?>" placeholder="Enter Longtitude"  required="">
										</div>
										</div>
										
										<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
										
										<div class="form-group mb-3">
										 <label class="form-label">Event Place Name</label>
										 <input type="text" class="form-control " name="pname" value="<?php echo $data['place_name'];?>" placeholder="Enter Place Name"required="">
										</div>
										
										<div class="form-group mb-3">
										 <label class="form-label">Event Full Address</label>
										 <textarea class="form-control" rows="7" name="address" style="resize:none;" required><?php echo $data['address'];?></textarea>
										</div>
										</div>
										<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Event Status</label>
                                            <select name="status" name="status" class="form-control " required>
											<option value="">Select Status</option>
											<option value="1" <?php if($data['status'] == 1){echo 'selected';}?>>Publish</option>
                                        <option value="0" <?php if($data['status'] == 0){echo 'selected';}?>>Unpublish</option>
											</select>
                                        </div>
										<div class="form-group mb-3">
                                            <label>Event Category</label>
                                            <select name="cid" name="status" class="form-control select2-single" required>
											<option value="">Select Category</option>
											<?php 
											$cat = $event->query("select * from tbl_cat");
											while($row = $cat->fetch_assoc())
											{
												?>
												<option value="<?php echo $row['id'];?>" <?php if($data['cid'] == $row['id']){echo 'selected';}?>><?php echo $row['title'];?></option>
												<?php 
											}
											?>
											</select>
                                        </div>
										</div>
										<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Event Description</label>
                                           <textarea class="form-control" rows="5" id="cdesc" name="cdesc" style="resize: none;" required><?php echo $data['description'];?></textarea>
                                        </div>
										</div>
										
										<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Event Disclaimer</label>
                                           <textarea class="form-control" rows="5" id="disclaimer" name="disclaimer" style="resize: none;" required><?php echo $data['disclaimer'];?></textarea>
                                        </div>
										</div>
										 
                                        
										
                                    
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-rounded btn-primary"><span class="btn-icon-start text-primary"><i class="flaticon-381-speaker"></i>
                                    </span>Edit Event</button>
                                    </div>
									</div>
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
                                <h4 class="card-title">Add Event</h4>
                            </div>
                            <div class="card-body">
                               
                                    <form method="post" enctype="multipart/form-data">
                                    <div class="row">
<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                                    
                                        <div class="form-group mb-3">
                                            <label>Event Name</label>
                                            <input type="text" class="form-control" name="title" placeholder="Enter Event Name"  required="">
											<input type="hidden" name="type" value="add_events"/>
                                        </div>
										</div>
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
                                        <div class="form-group mb-3">
                                            <label>Event Image</label>
                                            <div class="input-group">
                                            
                                                <input type="file" name="cat_img" required>
                                            
                                        </div>
                                        </div>
										</div>
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Event Cover Image</label>
                                            <div class="input-group">
                                            
                                                <input type="file" name="cover_img" required>
                                            
                                        </div>
                                        </div>
										</div>
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Event Start Date</label>
										<input type="date" name="sdate" class="form-control" placeholder="Select Date" required>
										</div>
										</div>
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                           <label class="form-label">Event Start Time</label>
                                        <div class="input-group">
                                            <input type="time" name="stime" class="form-control" required>
                                        </div>
										</div>
										</div>
										<div class="col-md-4 col-lg-4 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                           <label class="form-label">Event End Time</label>
                                        <div class="input-group">
                                            <input type="time" name="etime" class="form-control" required>
                                        </div>
										</div>
										</div>
										<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
										 <label class="form-label">Event Latitude</label>
										 <input type="text" class="form-control " name="latitude" placeholder="Enter Latitude"  required="">
										</div>
										</div>
										<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
										 <label class="form-label">Event Longtitude</label>
										 <input type="text" class="form-control " name="longtitude" placeholder="Enter Longtitude"  required="">
										</div>
										</div>
										
										<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
										
										<div class="form-group mb-3">
										 <label class="form-label">Event Place Name</label>
										 <input type="text" class="form-control " name="pname" placeholder="Enter Place Name"required="">
										</div>
										
										<div class="form-group mb-3">
										 <label class="form-label">Event Full Address</label>
										 <textarea class="form-control" rows="7" name="address" style="resize:none;" required></textarea>
										</div>
										</div>
										<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Event Status</label>
                                            <select name="status" name="status" class="form-control " required>
											<option value="">Select Status</option>
											<option value="1">Publish</option>
											<option value="0">UnPublish</option>
											</select>
                                        </div>
										<div class="form-group mb-3">
                                            <label>Event Category</label>
                                            <select name="cid" name="status" class="form-control select2-single" required>
											<option value="" disabled selected>Select Category</option>
											<?php 
											$cat = $event->query("select * from tbl_cat");
											while($row = $cat->fetch_assoc())
											{
												?>
												<option value="<?php echo $row['id'];?>"><?php echo $row['title'];?></option>
												<?php 
											}
											?>
											</select>
                                        </div>
										</div>
										<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Event Description</label>
                                           <textarea class="form-control" rows="5" id="cdesc" name="cdesc" style="resize: none;" required></textarea>
                                        </div>
										</div>
										
										<div class="col-md-6 col-lg-6 col-xs-12 col-sm-12">
										<div class="form-group mb-3">
                                            <label>Event Disclaimer</label>
                                           <textarea class="form-control" rows="5" id="disclaimer" name="disclaimer" style="resize: none;" required></textarea>
                                        </div>
										</div>
										
										 
                                        
										
                                    
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-rounded btn-primary"><span class="btn-icon-start text-primary"><i class="flaticon-381-speaker"></i>
                                    </span>Add Event</button>
                                    </div>
									</div>
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