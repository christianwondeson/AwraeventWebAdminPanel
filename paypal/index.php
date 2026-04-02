<?php 
if(isset($_GET['status']))
{
	
}
else 
{
	if(isset($_GET['amt']))
	{
require dirname( dirname(__FILE__) ).'/include/eventconfig.php';
require_once dirname( dirname(__FILE__) ).'/include/brand.php';
$ppBase = awraevent_public_base_url();
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
 <form action="https://www.sandbox.paypal.com/cgi-bin/webscr"
            method="post" target="_top">
            <input type='hidden' name='business'
                value='sb-zs6qp1731331@business.example.com'> <input type='hidden'
                name='item_name' value='Awra Event Orders'> <input type='hidden'
                name='item_number' value='Ord#<?php echo rand(111111,999999);?>'> <input type='hidden'
                name='amount' value='<?php echo $_GET['amt']; ?>'> <input type='hidden'
                name='no_shipping' value='1'> <input type='hidden'
                name='currency_code' value='USD'> 
				<input type="hidden" name="rm" value="2" />
<input type="hidden" name="lc" value=""/>
<input type="hidden" name="no_note" value="1"/>
            <input type='hidden' name='cancel_return'
                value='<?php echo htmlspecialchars($ppBase, ENT_QUOTES, 'UTF-8'); ?>/paypal/order_process.php'>
            <input type='hidden' name='return'
                value='<?php echo htmlspecialchars($ppBase, ENT_QUOTES, 'UTF-8'); ?>/paypal/order_process.php'>
            <input type="hidden" name="cmd" value="_xclick"> <input
                type="submit" style="display:none;" name="pay_now" id="pay_now"
                Value="Pay Now">
        </form>
    <script>
$("#pay_now").trigger('click'); 
</script>
<style>
#pay_now
{
	display:none;
}
</style>
<?php } } ?>