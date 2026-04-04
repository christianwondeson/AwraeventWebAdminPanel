<?php 
include 'include/top.php';
include 'include/sidebar.php';

$userRows = $event->query('SELECT * FROM `tbl_user` ORDER BY `id` DESC');
$userCount = $userRows instanceof mysqli_result ? $userRows->num_rows : 0;
?>
        <div class="content-body">
            <!-- row -->
			<div class="container-fluid">
				<div class="form-head mb-4 d-flex flex-wrap align-items-center">
					<div class="me-auto">
						<h2 class="font-w600 mb-0">User Management</h2>
						<p class="text-muted mb-0 small">App customers from <code>tbl_user</code> (sign up via mobile app). Staff admin accounts are in <code>admin</code>, not listed here.</p>
					</div>	
					
				</div>
				<div class="row">
					
					<div class="col-xl-12 col-lg-12">
					     <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                                <h4 class="card-title mb-0">User List</h4>
                                <span class="badge badge-primary"><?php echo (int) $userCount; ?> user(s)</span>
                            </div>
                            <div class="card-body">
								<?php if ($userCount === 0) { ?>
								<div class="alert alert-light border text-center py-5">
									<h5 class="text-dark">No app users yet</h5>
									<p class="text-muted mb-2">Rows appear here after customers register in the <strong>Flutter customer app</strong> (API <code>e_reg_user.php</code>), or if you insert test rows into <code>tbl_user</code>.</p>
									<p class="small text-muted mb-0">See <code>sql/sample_app_user.sql</code> in the project for an optional test insert (edit phone/password before running).</p>
								</div>
								<?php } ?>
                                <div class="table-responsive">
                                    <table id="example3" class="display" style="min-width: 845px">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Mobile</th>
                                                <th>Join date</th>
                                                <th>Status</th>
                                                <th>Referred by (code)</th>
                                                <th>Own invite code</th>
                                                <th>Wallet</th>
                                            </tr>
                                        </thead>
                                        <tbody>
										<?php 
										$i = 0;
										if ($userRows instanceof mysqli_result) {
											while ($row = $userRows->fetch_assoc()) {
												$i++;
												$pic = isset($row['pro_pic']) && trim((string) $row['pro_pic']) !== '' ? $row['pro_pic'] : 'images/profile/pic1.svg';
											?>
                                            <tr>
                                                <td><img class="rounded-circle" width="35" height="35" src="<?php echo htmlspecialchars($pic, ENT_QUOTES, 'UTF-8'); ?>" alt=""></td>
                                                <td><?php echo htmlspecialchars((string) ($row['name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
												<td><?php echo htmlspecialchars((string) ($row['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
												<td><?php echo htmlspecialchars((string) ($row['ccode'] ?? '') . ' ' . (string) ($row['mobile'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
												<td><?php echo htmlspecialchars((string) ($row['rdate'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
												<?php if (($row['status'] ?? 0) == 1) { ?>
                                                <td><a class="drop" href="javascript:void(0);" data-id="<?php echo (int) $row['id']; ?>" data-status="0" data-type="update_status" data-coll-type="user"><span class="badge badge-danger">Deactivate</span></a></td>
												<?php } else { ?>
												<td><a class="drop" href="javascript:void(0);" data-id="<?php echo (int) $row['id']; ?>" data-status="1" data-type="update_status" data-coll-type="user"><span class="badge badge-success">Activate</span></a></td>
												<?php } ?>
												<td><?php echo htmlspecialchars((string) ($row['refercode'] ?? '—'), ENT_QUOTES, 'UTF-8'); ?></td>
												<td><?php echo htmlspecialchars((string) ($row['code'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
												<td><?php echo htmlspecialchars((string) ($row['wallet'] ?? '0') . ($set['currency'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                            </tr>
<?php
											}
										}
?>
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
