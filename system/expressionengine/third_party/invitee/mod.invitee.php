<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
require_once dirname(__FILE__) . '/config.php';

// ------------------------------------------------------------------------

/**
 * Invitee Module Front-End File
 *
 * @package		  ExpressionEngine
 * @subpackage	Addons
 * @category	  Module
 * @author		  Kevin Thompson (Brilliant2)
 * @link		    http://www.brilliant2.com/
 */

class Invitee {
	
	// Public Variables
	public $return_data;
	
	// Private Variables
	private $EE;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
	  // Get EE Instance
		$this->EE =& get_instance();
	}
	
	/**
	 * Display Subscription Form
	 *
	 * @return mixed tag data
	 * @author Kevin Thompson
	 */
	public function form()
	{
		// Load Libraries & Helpers
		$this->EE->load->helper( array( 'form' ) );
		
		// Check for Form Parameter
		if (($code = $this->EE->TMPL->fetch_param('form')) === FALSE) return;
		
		// Get Parameters
		$class      = $this->EE->TMPL->fetch_param('class');
		$id         = $this->EE->TMPL->fetch_param('id');
		$return     = $this->EE->TMPL->fetch_param('return');
		
		// Get Form
		$query = $this->EE->db->get_where('invitee_forms',array(
		  'site_id'   => $this->EE->config->item('site_id'),
		  'form_code' => $code
		));
		
		// Check for Results
		if( $query->num_rows() == 0 ) return;
	  
	  // Instantiate Variables
		$form       = $query->row();
	  $form_url   = 'http' . ( !empty($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	  $action_url = $this->EE->functions->fetch_site_index(0, 0) . QUERY_MARKER . 'ACT=' . $this->EE->functions->fetch_action_id( ucwords(INVITEE_NAME), 'subscribe');
	  $hidden     = array(
	    'invitee_return'  => $return != '' ? $return : $form_url,
	    'invitee_form_id' => $form->form_id,
	    'invitee_link'    => $this->EE->functions->fetch_site_index(0, 0) . QUERY_MARKER . 'ACT=' . $this->EE->functions->fetch_action_id( ucwords(INVITEE_NAME), 'invite_visit') . '&form_url=' . base64_encode( $form_url )
	  );
	  
	  // Get Mailing Lists
		$list_array = array();
	  $query = $this->EE->db->get('invitee_lists');
		foreach( $query->result() as $list )
		{
		  $list_array[$list->list_id] = $list->list_name;
		}
	  
	  // Build Tag Variable Array
	  $list_vars = array( 'lists' => array() );
	  
	  $lists = array();
    $query = $this->EE->db->get_where('invitee_form_lists',array(
      'form_id' => $form->form_id
    ));
    foreach( $query->result() as $row )
    {
      $lists[] = $row->list_id;
    }
	  
	  foreach( $lists as $list_id )
	  {
	    if( isset( $list_array[$list_id] ) )
	    {
  	    $list_vars['lists'][] = array(
  	      'list_id'    => $list_id,
  	      'list_name'  => $list_array[$list_id]
  	    ); 
	    }
	  }
	  $vars = array( $list_vars );
	  
	  // Build Form
	  $output = form_open( $action_url, ($id != '' ? 'id="' . $id . '" ' : '') . ($class != '' ? 'class="' . $class . '" ' : ''), $hidden );
		$output .= $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $vars);
	  $output .= form_close();
		
		// Return Parsed Output
		return $this->return_data = $output;
	}
	
	/**
	 * Subscribe Action
	 *
	 * @return null
	 * @author Kevin Thompson
	 */
	function subscribe()
	{
	  // Load Libraries & Models
		$this->EE->load->library('email');
		$this->EE->load->library('template');
		$this->EE->load->model('mailchimp_model');
		
	  // Instantiate User Array
	  $user = array();
	  
	  // Get Current Time
	  $time = time();
	  
	  // Filter Post Data
	  foreach( $_POST as $key => $val )
	  {
	    $_POST[$key] = $this->EE->security->xss_clean($val);
	    
	    // Define User Data
	    if( ! in_array( $key, array('invitee_form_id', 'invitee_return', 'invite_list') ) )
	    {
	      $user[$key] = $_POST[$key];
	    }
	  }
		
		// Get Form
		$query = $this->EE->db->get_where('invitee_forms',array(
		  'form_id' => $_POST['invitee_form_id']
		));
		$form = $query->row();
		
	  // Get Subscriber ID
	  $subscriber_id = $this->_get_email_id( $user['email'] );
	  
	  // Get Subscribe Lists
	  if ( isset($_POST['lists']) )
	  {
	    if( is_array($_POST['lists']) )
	    {
	      $subscribe_lists = $_POST['lists'];
	    }
	    else
	    {
	      if ( strpos(',',$_POST['lists']) )
	      {
	        $subscribe_lists = explode(',',$_POST['lists']);
	      }
	      else
	      {
	        $subscribe_lists = array($_POST['lists']);
	      }
	    }
	  }
	  else
	  {	    
  	  $lists = array();
      $query = $this->EE->db->get_where('invitee_form_lists',array(
        'form_id' => $_POST['invitee_form_id']
      ));
      foreach( $query->result() as $row )
      {
        $subscribe_lists[] = $row->list_id;
      }
	  }
		
		// Subscribe User
		foreach( $subscribe_lists as $list_id )
		{
		  $this->EE->mailchimp_model->subscribe_email( $user['email'], $list_id, $user );
		}
	
		// Check if user was invited to this form
		$query = $this->EE->db->
		  select("invitee_invites.*,invitee_email_addresses.email_address")->
		  join("invitee_email_addresses ","invitee_email_addresses.email_id = invitee_invites.invitee_id")->
		  where(array(
		    "email_address"     => $user['email'],
		    "form_id"           => $_POST['invitee_form_id'],
		    "invite_converted"  => 0
		  ))->
		  get("invitee_invites");
		
		if ( $query->num_rows() > 0 )
		{
		  // Record conversion 
		  $invite = $query->row();
		  $this->EE->db->where( 'invite_id', $invite->invite_id )->update('invitee_invites',array(
		    'invite_converted'    => 1,
		    'invite_converted_at' => $time
		  ));
		}
		
		// Instantiate Notification Variables
		$template = array(
		  'subject' => '',
		  'content' => ''
		);
		$message  = $template;
		
		// Get Notification Template		
		$query = $this->EE->db->where('template_id',$form->template_id)->get('invitee_templates');
		if( $query->num_rows() > 0 )
		{
		  $notification_template = $query->row();
		  $template['subject'] = $notification_template->template_subject;
		  $template['content'] = $notification_template->template_content;
		}
		else
		{
		  // Define Simple Notification Template
		  $template['subject'] = lang( 'invitee_templates_default_subject' );
		  $template['content'] = lang( 'invitee_templates_default_content' );
		}
		
		// Get Invite List
		$invite_list = explode("\n",str_replace(',',"\n",$_POST['invite_list']));
		
		if( count( $invite_list ) > 0 )
		{
  		foreach( $invite_list as $invitee )
  		{	
  		  // Get Invitee Email Address
        $invitee_id = $this->_get_email_id( $invitee );
        
  		  // Create or Update Invite
    		$query = $this->EE->db->get_where('invitee_invites', array(
  		    "invitee_id"  => $invitee_id,
  		    "form_id"     => $_POST['invitee_form_id']
  		  ));
		
    		if ( $query->num_rows() > 0 )
    		{
    		  // Update Invite
    		  $invite = $query->row();
    		  $this->EE->db->where( 'invite_id', $invite->invite_id )->update('invitee_invites',array(
    		    'subscriber_id' => $subscriber_id
    		  ));
    		  
    		  $invite_id = $invite->invite_id;
    		}
    		else
    		{     		  
    		  // Insert Invite
    		  $this->EE->db->insert('invitee_invites',array(
    		    'subscriber_id' => $subscriber_id,
    		    'invitee_id'    => $invitee_id,
    		    'form_id'       => $_POST['invitee_form_id'],
    		    'invited_at'    => $time
    		  ));
  	  
      	  // $this->EE->db->insert_id wasn't working...
      	  $query = $this->EE->db->get_where('invitee_invites',array(
    		    'subscriber_id' => $subscriber_id,
    		    'invitee_id'    => $invitee_id,
    		    'form_id'       => $_POST['invitee_form_id'],
    		    'invited_at'    => $time
      	  ));
      	  $invite = $query->row();
      	  $invite_id = $invite->invite_id;
    		}
		  	
    		// Set Template Variables
    		$vars = $user;
  		  $vars['link'] = $vars['invitee_link'] . '&form_id=' . $_POST['invitee_form_id'] . '&invite_id=' . $invite_id;
		  
    		// Parse Notification Template		
    		$message['subject'] = $this->EE->template->parse_variables($template['subject'], array($vars) );
    		$message['content'] = $this->EE->template->parse_variables($template['content'], array($vars) );
		
    		// Send Invites
        $this->EE->email->mailtype = 'text'; 
        $this->EE->email->debug = TRUE;  
        $this->EE->email->from($user['email']);
        $this->EE->email->to($invitee);
        $this->EE->email->subject($message['subject']);
        $this->EE->email->message($message['content']);
        $this->EE->email->Send();		  
  		}		  
		}
		
		// Redirect User To Return Page
		$this->EE->functions->redirect($this->EE->functions->create_url( $_POST['invitee_return'] ));
	}
	
	
	/**
	 * Record Invite Visit
	 *
	 * @return null
	 * @author Kevin Thompson
	 */
	function invite_visit()
	{
	  // Update Invite Record
	  $this->EE->db->where(array(
	    'invite_id' => $this->EE->input->get('invite_id'),
	    'form_id'   => $this->EE->input->get('form_id')
	  ))->update('invitee_invites',array(
	    'invite_visited_at' => time()
	  ));
	  
	  // Redirect User To Form
    $this->EE->functions->redirect( base64_decode( $this->EE->input->get('form_url') ) );
	}
	
	/**
	 * Get User Email ID
	 *
	 * @return int email id
	 * @author Kevin Thompson
	 */
	function _get_email_id( $email )
	{
	  $query = $this->EE->db->get_where('invitee_email_addresses', array(
	    'email_address' => $email
	  ));
	  
	  if ( $query->num_rows() == 0 ) 
	  {
  	  $this->EE->db->insert('invitee_email_addresses',array(
  		  'email_address' => $email
  	  ));
  	  
  	  // $this->EE->db->insert_id wasn't working...
  	  $query = $this->EE->db->get_where('invitee_email_addresses',array(
  	    'email_address' => $email
  	  ));
  	  $email = $query->row();
  	  $email_id = $email->email_id;
	  }
	  else
	  {
	    $subscriber = $query->row();
	    $email_id = $subscriber->email_id;
	  }
	  
	  return $email_id;
	}
	
}
/* End of file mod.invitee.php */
/* Location: /system/expressionengine/third_party/invitee/mod.invitee.php */