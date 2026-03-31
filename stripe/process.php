<?php
require dirname( dirname(__FILE__) ).'/include/eventconfig.php';
$kb = $event->query("SELECT * FROM `tbl_payment_list` where id=2")->fetch_assoc();
$kk = explode(',',$kb['attributes']);
//check if stripe token exist to proceed with payment
if(!empty($_POST['stripeToken'])){
    // get token and user details
    $stripeToken  = $_POST['stripeToken'];
    $custName = $_POST['custName'];
    $custEmail = $_POST['custEmail'];
    $cardNumber = $_POST['cardNumber'];
    $cardCVC = $_POST['cardCVC'];
    $cardExpMonth = $_POST['cardExpMonth'];
    $cardExpYear = $_POST['cardExpYear'];    
    //include Stripe PHP library
    require_once('stripe-php/init.php');    
    //set stripe secret key and publishable key
    $stripe = array(
      "secret_key"      => $kk[1],
      "publishable_key" => $kk[0]
    );    
    \Stripe\Stripe::setApiKey($stripe['secret_key']);    
    //add customer to stripe
    if (isset($_POST['stripeToken'])){
        try {
    $customer = \Stripe\Customer::create(array(
        'email' => $custEmail,
        'source'  => $stripeToken
    ));    
    // item details for which payment made
    $itemName = "groheroe_items";
    $itemNumber = md5(uniqid(rand(), true));
    $itemPrice = $_POST['itemprice'];
	
    $currency = "inr";
    $orderID = md5(uniqid(rand(), true));    
    // details for which payment performed
    $payDetails = \Stripe\Charge::create(array(
        'customer' => $customer->id,
        'amount'   => $itemPrice * 100,
        'currency' => $currency,
        'description' => $itemName,
        'metadata' => array(
            'order_id' => $orderID
        )
    ));
     // get payment details
    $paymenyResponse = $payDetails->jsonSerialize();
    // check whether the payment is successful
    if($paymenyResponse['amount_refunded'] == 0 && empty($paymenyResponse['failure_code']) && $paymenyResponse['paid'] == 1 && $paymenyResponse['captured'] == 1){
        // transaction details 
        
        $amountPaid = $paymenyResponse['amount'];
        $balanceTransaction = $paymenyResponse['balance_transaction'];
        $paidCurrency = $paymenyResponse['currency'];
        $paymentStatus = $paymenyResponse['status'];
        $tid = $paymenyResponse['balance_transaction'];
        
        $paymentDate = date("Y-m-d H:i:s");        
        //insert tansaction details into database
		
       //if order inserted successfully
       if( $paymentStatus == 'succeeded'){
            
        //    $returnArr = array("Transaction_id"=>$tid,"ResponseCode"=>"200","Result"=>"true","ResponseMsg"=>"The payment was successful!!");
          ?>
          <script>
              window.location.href="man.php?Transaction_id=<?php echo $tid;?>&status=success&message=The payment was successful!!"
          </script>
          <?php
       } else{
         
          
       //   $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Payment failed!!");
          ?>
          <script>
              window.location.href="man.php?status=failed&message=payment failed!!"
          </script>
          <?php
       }
    } else{
     //   $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>"Payment failed!!");
     ?>
          <script>
              window.location.href="man.php?status=failed&message=payment failed!!"
          </script>
          <?php
    }
        }
        catch(Stripe_CardError $e) {
  $error1 = $e->getMessage();
 // $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>$error1);
 ?>
          <script>
              window.location.href="man.php?status=failed&message=<?php echo $error1; ?>"
          </script>
          <?php
} catch (Stripe_InvalidRequestError $e) {
  // Invalid parameters were supplied to Stripe's API
  $error2 = $e->getMessage();
 // $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>$error2);
  ?>
          <script>
              window.location.href="man.php?status=failed&message=<?php echo $error2; ?>"
          </script>
          <?php
} catch (Stripe_AuthenticationError $e) {
  // Authentication with Stripe's API failed
  $error3 = $e->getMessage();
 // $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>$error3);
  ?>
          <script>
              window.location.href="man.php?status=failed&message=<?php echo $error3; ?>"
          </script>
          <?php
} catch (Stripe_ApiConnectionError $e) {
  // Network communication with Stripe failed
  $error4 = $e->getMessage();
  // $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>$error4);
   ?>
          <script>
              window.location.href="man.php?status=failed&message=<?php echo $error4; ?>"
          </script>
          <?php
} catch (Stripe_Error $e) {
  // Display a very generic error to the user, and maybe send
  // yourself an email
  $error5 = $e->getMessage();
  // $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>$error5);
   ?>
          <script>
              window.location.href="man.php?status=failed&message=<?php echo $error5; ?>"
          </script>
          <?php
} catch (Exception $e) {
  // Something else happened, completely unrelated to Stripe
  $error6 = $e->getMessage();
 // $returnArr = array("ResponseCode"=>"401","Result"=>"false","ResponseMsg"=>$error6);
  ?>
          <script>
              window.location.href="man.php?status=failed&message=<?php echo $error6; ?>"
          </script>
          <?php
}  
    }
   
   // echo json_encode($returnArr);
} 

?>