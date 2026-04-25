<?php 
include 'include/top.php';
include 'include/sidebar.php';
?>
        <div class="content-body">
            <!-- row -->
			<div class="container-fluid">
				<div class="form-head mb-4 d-flex flex-wrap align-items-center">
					<div class="me-auto">
						<h2 class="font-w600 mb-0">Setting  Management</h2>
						
					</div>	
					
				</div>
				<div class="row">
					
					<div class="col-xl-12 col-lg-12">
				
									<div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Edit Setting </h4>
                            </div>
                            <div class="card-body">
                               
                                      <h5 class="h5_set"><i class="fa fa-gear fa-spin"></i>  General  Information</h5>
				<form method="post" enctype="multipart/form-data">
                                       <div class="row">
									    <div class="form-group col-3">
                                            <label><span class="text-danger">*</span> Website Name</label>
                                            <input type="text" class="form-control " placeholder="Enter Store Name" value="<?php echo $set['webname'];?>" name="webname" required="">
											<input type="hidden" name="type" value="edit_setting"/>
										<input type="hidden" name="id" value="1"/>
                                        </div>
										
                                      <div class="form-group col-3" style="margin-bottom: 48px;">
                                            <label><span class="text-danger">*</span> Website Image</label>
                                            <div class="custom-file">
                                                <input type="file" name="weblogo">
                                                <br>
												<br>
												<img src="<?php echo $set['weblogo'];?>" width="60" height="60"/>
                                            </div>
                                        </div>
										
										<div class="form-group col-3">
									<label for="awra-timezone">Timezone</label>
									<select name="timezone" id="awra-timezone" class="form-control" required>
									<option value="">Select timezone</option>
									<?php
								$tzPreferred = awraevent_default_timezone();
								$tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
								$tzlist = array_values(array_unique(array_merge(
									array($tzPreferred),
									array_values(array_filter($tzlist, static function ($z) use ($tzPreferred) {
										return $z !== $tzPreferred;
									}))
								)));
								$limit = count($tzlist);
								for ($k = 0; $k < $limit; $k++) {
									$z = $tzlist[$k];
									?>
									<option value="<?php echo htmlspecialchars($z, ENT_QUOTES, 'UTF-8'); ?>" <?php if ($z === $set['timezone']) {
										echo 'selected';
									} ?>><?php echo htmlspecialchars($z, ENT_QUOTES, 'UTF-8'); ?></option>
									<?php
								}
									?>
									</select>
									<small class="text-muted">Default for Ethiopia: <?php echo htmlspecialchars(awraevent_default_timezone(), ENT_QUOTES, 'UTF-8'); ?></small>
								</div>
										
										<div class="form-group col-3">
                                            <label><span class="text-danger">*</span> Currency</label>
                                            <input type="text" class="form-control" placeholder="ETB (Ethiopian Birr)"  value="<?php echo htmlspecialchars($set['currency'], ENT_QUOTES, 'UTF-8'); ?>" name="currency" required="" maxlength="12">
											<small class="text-muted">Use <strong>ETB</strong> for Ethiopian Birr (displayed in the app).</small>
                                        </div>
										
										
										
										
										
										
	
	<div class="form-group col-12">
										<h5 class="h5_set"><i class="fa fa-signal"></i> Onesignal Information</h5>
										</div>
										<div class="form-group col-6">
                                            <label><span class="text-danger">*</span> User App Onesignal App Id</label>
                                            <input type="text" class="form-control " placeholder="Enter User App Onesignal App Id"  value="<?php echo $set['one_key'];?>" name="one_key" required="">
                                        </div>
										
										<div class="form-group col-6">
                                            <label><span class="text-danger">*</span> User  App Onesignal Rest Api Key</label>
                                            <input type="text" class="form-control " placeholder="Enter User Boy App Onesignal Rest Api Key"  value="<?php echo $set['one_hash'];?>" name="one_hash" required="">
                                        </div>
	
										
										
										<div class="form-group col-12">
										<h5 class="h5_set"><i class="fa fa-user-plus" aria-hidden="true"></i> Refer And Earn Information</h5>
										</div>
										
										<div class="form-group col-6">
                                            <label><span class="text-danger">*</span> Sign Up Credit</label>
                                            <input type="text" class="form-control numberonly" placeholder="Enter Sign Up Credit"  value="<?php echo $set['scredit'];?>" name="scredit" required="">
                                        </div>
										
										<div class="form-group col-6">
                                            <label><span class="text-danger">*</span> Refer Credit</label>
                                            <input type="text" class="form-control numberonly" placeholder="Enter Refer Credit"  value="<?php echo $set['rcredit'];?>" name="rcredit" required="">
                                        </div>
										
										
										<div class="form-group mb-3 col-4">
                                            <label><span class="text-danger">*</span> Sms Type</label>
                                           <select class="form-control" name="sms_type">
										   <option value="">select sms type</option>
										   <option value="Msg91" <?php if($set['sms_type'] == 'Msg91'){echo 'selected';}?>>Msg91</option>
										   <option value="AfroMessage" <?php if(stripos((string)($set['sms_type'] ?? ''), 'afro') !== false){echo 'selected';}?>>AfroMessage (OTP / 2FA)</option>
										   <option value="Twilio" <?php if($set['sms_type'] == 'Twilio'){echo 'selected';}?>>Twilio</option>
										  
										   </select>
                                        </div>
                                        <p class="text-muted small col-12">AfroMessage: store your API token in <strong>Msg91 Auth Key</strong> or set <code>AFROMESSAGE_API_TOKEN</code> in PHP. Optional identifier (short code id): <strong>Msg91 Otp Template Id</strong> or <code>AFROMESSAGE_FROM</code>. Sender: <code>AFROMESSAGE_SENDER</code>. To hide the OTP in API responses: <code>AFROMESSAGE_RETURN_OTP=0</code>.</p>
										
										<div class="form-group mb-3 col-12">
										<h5 class="h5_set"><i class="fas fa-sms"></i> Msg91 Sms Configurations</h5>
										</div>
	                                    
										<div class="form-group mb-3 col-6">
                                            <label><span class="text-danger">*</span>Msg91 Auth Key</label>
                                            <input type="text" class="form-control " placeholder="Msg91 Auth Key"  value="<?php echo $set['auth_key'];?>" name="auth_key" required="">
                                        </div>
										
										<div class="form-group mb-3 col-6">
                                            <label><span class="text-danger">*</span> Msg91 Otp Template Id</label>
                                            <input type="text" class="form-control " placeholder="Msg91 Otp Template Id"  value="<?php echo $set['otp_id'];?>" name="otp_id" required="">
                                        </div>
										
										
										<div class="form-group mb-3 col-12">
										<h5 class="h5_set"><i class="fas fa-sms"></i> Twilio Sms Configurations </h5>
										</div>
										
										<div class="form-group mb-3 col-4">
                                            <label><span class="text-danger">*</span>Twilio Account SID</label>
                                            <input type="text" class="form-control " placeholder="Twilio Account SID"  value="<?php echo $set['acc_id'];?>" name="acc_id" required="">
                                        </div>
										
										<div class="form-group mb-3 col-4">
                                            <label><span class="text-danger">*</span> Twilio Auth Token</label>
                                            <input type="text" class="form-control " placeholder="Twilio Auth Token"  value="<?php echo $set['auth_token'];?>" name="auth_token" required="">
                                        </div>
										
										<div class="form-group mb-3 col-4">
                                            <label><span class="text-danger">*</span> Twilio Phone Number</label>
                                            <input type="text" class="form-control " placeholder="Twilio Phone Number"  value="<?php echo $set['twilio_number'];?>" name="twilio_number" required="">
                                        </div>
										
										
										<div class="form-group mb-3 col-12">
										<h5 class="h5_set"><i class="fa fa-phone"></i> Otp Configurations</h5>
										</div>
										
										<div class="form-group mb-3 col-4">
                                            <label><span class="text-danger">*</span> Otp Auth In Sign up ? </label>
                                            <select class="form-control" name="otp_auth">
										   <option value="">Select Option</option>
										   <option value="Yes" <?php if($set['otp_auth'] == 'Yes'){echo 'selected';}?>>Yes</option>
										   <option value="No" <?php if($set['otp_auth'] == 'No'){echo 'selected';}?>>No</option>
										   
										   </select>
                                        </div>
										
										
	
										
										<div class="form-group mb-4" style="margin-top: 40px;">
                                        <button type="submit" class="btn btn-rounded btn-primary"><span class="btn-icon-start text-primary"><i class="fa fa-gear fa-spin"></i>
                                    </span>Edit Setting</button>
                                    </div>
											</div>
                                    </form> 
                               
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