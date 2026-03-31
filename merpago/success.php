<?php 

if(isset($_GET['status']))
{
    if($_GET['status'] == 'approved')
    {
        ?>
        <script>
           
		window.location.href="success.php?status=successful&transaction_id="+<?php echo $_GET['payment_id'];?>
		</script>
        
        <?php 
        
    }
}
else 
{
    ?>
    <script>
           
		window.location.href="success.php?status=failed&transaction_id="+<?php echo $_GET['payment_id'];?>
		</script>
    <?php
    
}
