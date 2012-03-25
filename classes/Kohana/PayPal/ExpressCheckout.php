<?php defined('SYSPATH') or die('No direct script access.');
/**
 * PayPal ExpressCheckout integration.
 *
 * @see  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_ECGettingStarted
 *
 * @package    Kohana
 * @author     Kohana Team
 * @copyright  (c) 2009 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class Kohana_PayPal_ExpressCheckout extends PayPal {

	// Default parameters
	protected $_default = array('PAYMENTACTION' => 'Sale');

	/**
	 * Make an SetExpressCheckout call.
	 *
	 * @param  array   NVP parameters
	 */
	public function set(array $params = NULL)
	{
		if ($params === NULL)
		{
			// Use the default parameters
			$params = $this->_default;
		}
		else
		{
			// Add the default parameters
			$params += $this->_default;
		}

		return $this->_post('SetExpressCheckout', $params);
	}
	
	/**
	 * Make an GetExpressCheckoutDetails call
	 * 
	 * @param  string   Token returned by SetExpressCheckout
	 * @return array    Checkout details 
	 */
	public function get_details($token)
	{
		return $this->_post('GetExpressCheckoutDetails', array('TOKEN' => $token));
	}
	
	/**
	 * Make an DoExpressCheckoutPayment call
	 * 
	 * @param  array    $params retrieved from GetExpressCheckoutDetails call
	 * @return array    Response data
	 */
	public function do_payment(array $params)
	{
		$params = $params === NULL ? $this->_default : $params + $this->_default;
	
		return $this->_post('DoExpressCheckoutPayment', $params);
	}

} // End PayPal_ExpressCheckout
