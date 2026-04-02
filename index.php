<?php 
require_once __DIR__ . '/include/eventmania.php';

$login_error = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type']) && $_POST['type'] === 'login') {
	$username = isset($_POST['username']) ? trim((string) $_POST['username']) : '';
	$password = isset($_POST['password']) ? (string) $_POST['password'] : '';
	$h = new Eventmania();
	$count = $h->eventlogin($username, $password, 'admin');
	if ($count != 0) {
		$_SESSION['eventname'] = $username;
		header('Location: dashboard.php', true, 302);
		exit;
	}
	$login_error = true;
}

if(isset($_SESSION['eventname']))
{
	?>
	<script>
	window.location.href="dashboard.php";
	</script>
	<?php 
}
else 
{
}
?>
<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	
	<!-- PAGE TITLE HERE -->
	<title><?php echo $set['webname'].' Login Page';?></title>
	
	<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/png" href="<?php echo $set['weblogo'];?>" />
    <link href="<?php echo awraevent_asset_h('css/style.css'); ?>" rel="stylesheet">

</head>

<body class="vh-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
									<div class="text-center mb-3">
										<img src="<?php echo $set['weblogo'];?>" width="120px" alt="">
									</div>
                                    <h4 class="text-center mb-4">Sign in your account</h4>
									<?php if (!empty($login_error)) { ?>
									<div class="alert alert-danger text-center" role="alert">Invalid username or password.</div>
									<?php } ?>
                                    <form method="post" action="">
                                        <div class="mb-3">
                                            <label class="mb-1"><strong>User Name</strong></label>
                                            <input type="text" class="form-control" name="username" required>
											<input type="hidden" name="type" value="login"/>
                                        </div>
                                        <div class="mb-3">
                                            <label class="mb-1"><strong>Password</strong></label>
                                            <input type="password" class="form-control" name="password" required>
                                        </div>
                                       
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-block">Sign Me In</button>
                                        </div>
                                    </form>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


   <?php 
   include 'include/footer.php';
   ?>
	
</body>
</html>