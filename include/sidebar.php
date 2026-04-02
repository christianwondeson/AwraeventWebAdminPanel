<?php 
if(isset($_SESSION['eventname']))
{
	
}
else 
{
	?>
	<script>
	window.location.href="index.php";
	</script>
	<?php 
}
?>
 <div class="deznav">
            <div class="deznav-scroll">
				<ul class="metismenu" id="menu">
					
                   
					
					 <li><a href="dashboard.php" class="ai-icon" aria-expanded="false">
							<i class="flaticon-025-dashboard"></i>
							<span class="nav-text">Dashboard</span>
						</a>
					</li>
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
						<i class="flaticon-043-menu"></i>
							<span class="nav-text">Category</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="add_category.php">Add Category</a></li>
							<li><a href="list_category.php">List Category</a></li>
                        </ul>
                    </li>
					
					<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
						<i class="flaticon-381-flag"></i>
							<span class="nav-text">Country Code</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="add_code.php">Add Country Code</a></li>
							<li><a href="list_code.php">List Country Code</a></li>
                        </ul>
                    </li>
					
					<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
						<i class="flaticon-381-gift"></i>
							<span class="nav-text">Coupon Code</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="add_ccode.php">Add Coupon Code</a></li>
							<li><a href="list_ccode.php">List Coupon Code</a></li>
                        </ul>
                    </li>
					
                    <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-speaker"></i>
							<span class="nav-text">Events</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="add_events.php">Add Events</a></li>
                            <li><a href="list_events.php">List Events</a></li>
                            
                        </ul>
                    </li>
					
					<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-speaker"></i>
							<span class="nav-text">Type & Price</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="add_type.php">Add Type & Price</a></li>
                            <li><a href="list_type.php">List Type & Price</a></li>
                            
                        </ul>
                    </li>
					
					 <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-speaker"></i>
							<span class="nav-text">Cover images</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="add_cover.php">Add Cover images</a></li>
                            <li><a href="list_cover.php">List Cover images</a></li>
                            
                        </ul>
                    </li>
					
					 <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-speaker"></i>
							<span class="nav-text">Events Gallery</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="add_gallery.php">Add Gallery</a></li>
                            <li><a href="list_gallery.php">List Gallery</a></li>
                            
                        </ul>
                    </li>
					
					<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-381-speaker"></i>
							<span class="nav-text">Events Sponsore</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="add_sponsore.php">Add Sponsore</a></li>
                            <li><a href="list_sponsore.php">List Sponsore</a></li>
                            
                        </ul>
                    </li>
					
					 <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="flaticon-043-menu"></i>
							<span class="nav-text">Pages</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="add_pages.php">Add Pages</a></li>
                            <li><a href="list_pages.php">List Pages</a></li>
                            
                        </ul>
                    </li>
					
					 <li><a href="payment-list.php" class="ai-icon" aria-expanded="false">
							<i class="flaticon-381-id-card"></i>
							<span class="nav-text">Payment List</span>
						</a>
					</li>
					
					<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="fa fa-question-circle"></i>
							<span class="nav-text">Faq Category</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="add_fcat.php">Add Category</a></li>
                            <li><a href="list_fcat.php">List Category</a></li>
                            
                        </ul>
                    </li>
					
					<li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<i class="fa fa-question-circle"></i>
							<span class="nav-text">Faq</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="add_faq.php">Add Faq</a></li>
                            <li><a href="list_faq.php">List Faq</a></li>
                            
                        </ul>
                    </li>
					
					<li><a href="userlist.php" class="ai-icon" aria-expanded="false">
							<i class="flaticon-381-user"></i>
							<span class="nav-text">User List</span>
						</a>
					</li>
					
                   <li><a href="setting.php" class="ai-icon" aria-expanded="false">
							<i class="flaticon-381-settings-1"></i>
							<span class="nav-text">Setting</span>
						</a>
					</li>
                </ul>
				
			</div>
        </div>