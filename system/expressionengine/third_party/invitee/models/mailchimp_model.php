<?php if ( ! defined('BASEPATH')) exit('Invalid file request');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package     ExpressionEngine
 * @author	    ExpressionEngine Dev Team
 * @copyright   Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license     http://expressionengine.com/user_guide/license.html
 * @link		    http://expressionengine.com
 * @since		    Version 2.0
 * @filesource  
 */
 
// ------------------------------------------------------------------------
 
// Load Module Configuration
require_once dirname(__FILE__) . '/../config.php';

// ------------------------------------------------------------------------

/**
 * Invitee MailChimp API Model
 *
 * @package		  ExpressionEngine
 * @subpackage	Addons
 * @category	  Module
 * @author      Kevin Thompson (Brilliant2)
 * @link		    http://www.brilliant2.com/
 */

require_once PATH_THIRD .'invitee/library/MCAPI.class' .EXT;

class Mailchimp_model extends CI_Model {
  
  private $EE;
	private $_site_id = '1';
	private $_mailing_lists;
	
	/**
	 * Class constructor.
	 *
	 * @access	public
	 * @return	void
	 */
	public function __construct()
	{		
		$this->EE 		  =& get_instance();
		$this->_site_id = $this->EE->config->item('site_id');
		$this->_mcapi   = new MCAPI( $this->EE->config->item('mailchimp_api_key') );
	}

  // ------------------------------------------------------------------------
	
	/**
	 * Retrieves the available mailing lists from the API.
	 *
	 * @access	public
	 * @return	array
	 */
	public function get_mailing_lists()
	{
	  // Get Lists
		return $this->_mcapi->lists();
	}	
	
	/**
	 * Subscribes an email address to a given list
	 *
	 * @access	public
	 * @param 	string 		$email 		    Email Address
	 * @param 	string 		$list 		    MailChimp List ID
	 * @param 	string 		$merge_vars   Additional data to store on the MailChimp List
	 * @return 	void
	 */	
	public function subscribe_email( $email, $list, $merge_vars = array() )
	{	  
	  // Subscribe
		$this->_mcapi->listSubscribe(
			$list,
			$email,
			$merge_vars
		);
	}
	
}

/* End of file		: mailchimp_model.php */
/* File location	: /system/expressionengine/third_party/invitee/models/mailchimp_model.php */