<?php 
if(isset($_POST['payer_status']))
{
	$txtid =  $_POST['txn_id'];
	?>
	<script>
	window.location.href="index.php?tid=<?php echo $txtid;?>&status=payment_success";
	</script>
	<?php 
}
else 
{
?>
<script>
	window.location.href="index.php?status=payment_failed";
	</script>
<?php 
}