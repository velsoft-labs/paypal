PayPal module for [Kohana 3.x](http://github.com/kohana)

Refer to https://cms.paypal.com/au/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_ECGettingStarted

The first set is to create an instance of the ExpressCheckout object.

$paypal = Paypal::instance('ExpressCheckout');

You then need to create a token to use for processing. This is done by calling the SetExpressCheckout method. You will need to supply the method with specific information about the transaction.

$data = array(
    'AMT'           => 15,
    'CURRENCYCODE'  => 'AUD',
    'RETURNURL'     => url::site('order/checkout', 'http'),
    'CANCELURL'     => url::site('order/cancelled', 'http'),
    'PAYMENTACTION' => 'Sale',
);

$payment = $paypal->SetExpressCheckout($data);

// Check that the response from the Paypal server is ok.

if (Arr::get($payment, 'ACK') === 'Success') {

    // Store token in SESSION
    Session::instance()->set('paypal_token_' . $payment['TOKEN'], Arr::get($_POST, 'AMT'));
    
    // We now send the user to the Paypal site for them to provide their details
    $params = $data;
    $params['token'] = $payment['TOKEN'];
    unset($params['PAYMENTACTION']);
    
    $url = $paypal->redirect_url('express-checkout', $params);
    $this->request->redirect($url);
}

If the user presses the cancel button on the Paypal website they will be redirected to the CANCELURL you provided earlier.
If they entered their details they will be redirected to the RETURNURL.
From this point you can get the details about the user.
As an example you may use their shipping address to calculate an appropriate charge.
In the controller for the RETURNURL, your code have the following code.

// The token will be provided in the query string from Paypal.
$token = Arr::get($_GET, 'token');

if ($token) {

    // Check token is valid so you can load details

    // Load the Paypal object
    $paypal = Paypal::instance('ExpressCheckout');
    
    // Get the customers details from Paypal
    $customer = $paypal->GetExpressCheckoutDetails(array('TOKEN'=>$token));
    
    if (Arr::get($customer, 'ACK') === 'Success') {
        
        // Perform any calculations to determine the final charging price
        
        $params = array(
            'TOKEN'     => $token,
            'PAYERID'   => Arr::get($customer, 'PAYERID'),
            'AMT'       => Session::instance()->get('paypal_token_'.$token),
        );

        // Process the payment
        $payment = $paypal->DoExpressCheckoutPayment($params);
        
    }
    
}