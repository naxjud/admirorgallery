<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );


class AvcControllerCollection extends AvcController
{
	/**
	 * Constructor
	 */
	function __construct()
	{
	    parent::__construct();

	    $this->registerTask('send', 'send');
	}

    function send() { 
        
        $session =& JFactory::getSession();
    	$question =  $session->get('cool_captcha');
		$answer = JRequest::getWord('AVC_CAPTCHA');
        
    	if (empty($question) || strtolower( $answer ) != $question ) {

    		JFactory::getApplication()->enqueueMessage(JText::_('AVC_COLLECTION_CAPTCHA_ERROR'), 'error');
			
	        parent::display();

	        return;
	    }
        
		$to  = 'igor.kekeljevic@gmail.com';

		// subject
		$subject = 'Porudzbina sa sajta';

		$mainframe = JFactory::getApplication();
		$AVC_STATE_COLLECTION = $mainframe->getUserStateFromRequest( "AVC_COLLECTION", "AVC_COLLECTION", $mainframe->getCfg("AVC_COLLECTION") );
		$collection = json_decode($AVC_STATE_COLLECTION, true);

		$body = '';

		$body.= '<table cellpadding="10" cellspacing="0" border="1" width="100%"><thead>';
		$body.= '<tr>
			<th>'.JText::_(strtoupper("item_code")).'</th>
			<th>'.JText::_(strtoupper("description")).'</th>
			<th style="text-align:right">'.JText::_(strtoupper("price_per_item")).'</th>
			<th style="text-align:right">'.JText::_(strtoupper("num_of_items")).'</th>
			<th style="text-align:right">'.JText::_(strtoupper("total_price")).'</th>
			</tr>';
		$body.= '</thead><tbody>';

		$priceTotal=0;
		foreach ($collection["order"] as $key => $value) {
			$body.= '<tr>
			<td>'.$key.'</td>
			<td>'.$value["description"].'</td>
			<td style="text-align:right">'.$value["price_per_item"].'</td>
			<td style="text-align:right">'.$value["num_of_items"].'</td>
			<td style="text-align:right">'.(intval($value["num_of_items"])*intval($value["price_per_item"])).'</td>
			</tr>';
			$priceTotal+=(intval($value["num_of_items"])*intval($value["price_per_item"]));
		}

		$body.= '<tr>
		<td colspan="4" style="text-align:right"><h3>'.JText::_("SUM_TOTAL").': </h3></td>
		<td style="text-align:right"><h3>'.$priceTotal.'</h3></td>
		</tr>';
		$priceTotal.=$value["num_of_items"]*$value["price_per_item"];

		$body.= '</tbody></table>';


		$body.= '<h2>'.JText::_("CUSTOMER_DETAILS").'</h2>';
		$body.= '<ul>';
		foreach ($collection["customer"] as $key => $value) {
			$body.= '<li>';
			$body.= JText::_(strtoupper($key)).': '.$value;
			$body.= '</li>';
		}
		$body.= '</ul>';


		$body.= '<h2>'.JText::_("ADDITIONAL_DETAILS").'</h2>';
		$body.= '<ul>';
		foreach ($collection["additional_details"] as $key => $value) {
			$body.= '<li>';
			$body.= JText::_(strtoupper($key)).': '.$value;
			$body.= '</li>';
		}
		$body.= '</ul>';


		// message
		$message = '
		<html>
		<head>
		  <title>Porudzbina sa sajta</title>
		</head>
		<body>
		'.$body.'
		</body>
		</html>
		';

		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: Pejcic Sport Website <joomla@vasiljevski.com >' . "\r\n";

		// Mail it
		if(mail($to, $subject, $message, $headers)){
			JFactory::getApplication()->enqueueMessage(JText::_('AVC_COLLECTION_SEND_SUCCESS'), 'message');
		}else{
			JFactory::getApplication()->enqueueMessage(JText::_('AVC_COLLECTION_SEND_FAIL'), 'error');
		}

        parent::display();
    }
}
