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
 * Invitee Module Install/Update File
 *
 * @package		  ExpressionEngine
 * @subpackage	Addons
 * @category	  Module
 * @author		  Kevin Thompson (Brilliant2)
 * @link		    http://www.brilliant2.com/
 */

class Invitee_upd {
	
	// Public Variables
	public $version = INVITEE_VERSION;
	
	// Private Variables
	private $EE;
	private $_module_actions;
	private $_module_tables;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
    
    // Define Custom Database Tables
		$this->_module_tables = array(
		  
		  // Forms Table
		  'invitee_forms'  => array(
		    
		    // Fields
		    'fields'  => array(
    		  'form_id'  => array(
    		    'type'            => 'INT',
    		    'constraint'      => 10,
    		    'auto_increment'  => TRUE,
    		    'unsigned'        => TRUE
    		  ),
    		  'site_id' => array(
    		    'type'            => 'INT',
    		    'constraint'      => 10,
    		    'unsigned'        => TRUE
    		  ),
    		  'form_title'  => array(
    		    'type'            => 'VARCHAR',
    		    'constraint'      => 255,
    		    'null'            => FALSE
    		  ),
    		  'form_code'  => array(
    		    'type'            => 'VARCHAR',
    		    'constraint'      => 255,
    		    'null'            => FALSE
    		  ),
    		  'form_subscribers'  => array(
    		    'type'            => 'INT',
    		    'constraint'      => 10,
    		    'unsigned'        => TRUE
    		  ),
    		  'template_id'  => array(
    		    'type'            => 'INT',
    		    'constraint'      => 10,
    		    'unsigned'        => TRUE
    		  ),
    		),
    		
    		// Keys
    		'keys'  => array(
    		  'form_id'  => TRUE
    		)
		  ),	
		  
		  // Lists Table
		  'invitee_lists'  => array(
		    
		    // Fields
		    'fields'  => array(
    		  'list_id'  => array(
    		    'type'            => 'VARCHAR',
    		    'constraint'      => 255,
    		    'null'            => FALSE
    		  ),
    		  'site_id'  => array(
    		    'type'            => 'INT',
    		    'constraint'      => 10,
    		    'unsigned'        => TRUE
    		  ),
    		  'list_name'  => array(
    		    'type'            => 'VARCHAR',
    		    'constraint'      => 255,
    		    'null'            => FALSE
    		  ),
    		  'list_web_id'  => array(
    		    'type'            => 'VARCHAR',
    		    'constraint'      => 255,
    		    'null'            => FALSE
    		  ),
    		  'list_member_count'  => array(
    		    'type'            => 'INT',
    		    'constraint'      => 10,
    		    'unsigned'        => TRUE
    		  )
    		),
    		
    		// Keys
    		'keys'  => array(
    		  'list_id' => TRUE
    		)
		  ),	
		  
		  // Lists Table
		  'invitee_form_lists'  => array(
		    
		    // Fields
		    'fields'  => array(
    		  'id'  => array(
    		    'type'            => 'INT',
    		    'constraint'      => 10,
    		    'auto_increment'  => TRUE,
    		    'unsigned'        => TRUE
    		  ),
    		  'form_id'  => array(
    		    'type'            => 'VARCHAR',
    		    'constraint'      => 255,
    		    'null'            => FALSE
    		  ),
    		  'list_id'  => array(
    		    'type'            => 'VARCHAR',
    		    'constraint'      => 255,
    		    'null'            => FALSE
    		  )
    		),
    		
    		// Keys
    		'keys'  => array(
    		  'id'  => TRUE
    		)
		  ),		
		  
		  // Email Address Table
		  'invitee_email_addresses'  => array(
		    
		    // Fields
		    'fields'  => array(
    		  'email_id'  => array(
    		    'type'            => 'INT',
    		    'constraint'      => 10,
    		    'auto_increment'  => TRUE,
    		    'unsigned'        => TRUE
    		  ),
    		  'email_address' => array(
    		    'type'            => 'VARCHAR',
    		    'constraint'      => 255,
    		    'null'            => FALSE
    		  )
    		),
    		
    		// Keys
    		'keys'  => array(
    		  'email_id'  => TRUE
    		)
		  ),		
		  
		  // Invites Address Table
		  'invitee_invites'  => array(
		    
		    // Fields
		    'fields'  => array(
    		  'invite_id'  => array(
    		    'type'            => 'INT',
    		    'constraint'      => 10,
    		    'auto_increment'  => TRUE,
    		    'unsigned'        => TRUE
    		  ),
    		  'subscriber_id' => array(
    		    'type'            => 'INT',
    		    'constraint'      => 10,
    		    'unsigned'        => TRUE
    		  ),
    		  'invitee_id' => array(
    		    'type'            => 'INT',
    		    'constraint'      => 10,
    		    'unsigned'        => TRUE
    		  ),
    		  'form_id' => array(
    		    'type'            => 'INT',
    		    'constraint'      => 10,
    		    'unsigned'        => TRUE
    		  ),
    		  'invite_visited' => array(
    		    'type'            => 'INT',
    		    'constraint'      => 10,
    		    'unsigned'        => TRUE,
    		    'default'         => 0
    		  ),
    		  'invite_converted' => array(
    		    'type'            => 'INT',
    		    'constraint'      => 10,
    		    'unsigned'        => TRUE,
    		    'default'         => 0
    		  ),
    		  'invited_at' => array(
    		    'type'            => 'INT',
    		    'constraint'      => 10,
    		    'unsigned'        => TRUE
    		  ),
    		  'invite_converted_at' => array(
    		    'type'            => 'INT',
    		    'constraint'      => 10,
    		    'unsigned'        => TRUE
    		  ),
    		  'invite_visited_at' => array(
    		    'type'            => 'INT',
    		    'constraint'      => 10,
    		    'unsigned'        => TRUE
    		  )
    		),
    		
    		// Keys
    		'keys'  => array(
    		  'invite_id'  => TRUE
    		)
		  ),		
		  
		  // Notification Templates Table
		  'invitee_templates'  => array(
		    
		    // Fields
		    'fields'  => array(
    		  'template_id'  => array(
    		    'type'            => 'INT',
    		    'constraint'      => 10,
    		    'auto_increment'  => TRUE,
    		    'unsigned'        => TRUE
    		  ),
    		  'site_id'  => array(
    		    'type'            => 'INT',
    		    'constraint'      => 10,
    		    'unsigned'        => TRUE
    		  ),
    		  'template_title' => array(
    		    'type'            => 'VARCHAR',
    		    'constraint'      => 255,
    		    'null'            => FALSE
    		  ),
    		  'template_subject' => array(
    		    'type'            => 'VARCHAR',
    		    'constraint'      => 255,
    		    'null'            => FALSE
    		  ),
    		  'template_content' => array(
    		    'type'            => 'TEXT',
    		    'null'            => FALSE
    		  )
    		),
    		
    		// Keys
    		'keys'  => array(
    		  'template_id'  => TRUE
    		)
		  ),
		);
		
		// Define Module Actions
		$this->_module_actions = array(
		  'invite_visit',
		  'subscribe'
		);
	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Installation Method
	 *
	 * @return 	boolean 	TRUE
	 */
	public function install()
	{
	  
	  /* Install Module
	  ******************************/
	  
	  // Define Module Data
		$mod_data = array(
			'module_name'			    => ucwords(INVITEE_NAME),
			'module_version'		  => INVITEE_VERSION,
			'has_cp_backend'		  => 'y',
			'has_publish_fields'	=> 'n'
		);
		
		// Insert Module Data
		$this->EE->db->insert( 'modules', $mod_data );
  	
  	// Insert Module Actions
  	foreach( $this->_module_actions as $method )
  	{
  	  $this->EE->db->insert('actions', array(
  	  	'class'		=>  ucwords(INVITEE_NAME),
    		'method'	=> $method
    	));
  	}
	  
	  /* Generate Custom Tables
	  ******************************/
		
		// Load DB Forge
		$this->EE->load->dbforge();
		
		// Generate Custom Database Tables
		foreach( $this->_module_tables as $table => $data )
		{
		  foreach( $data['keys'] as $key => $primary )
		  {
		    $this->EE->dbforge->add_key( $key, $primary );
		  }
      $this->EE->dbforge->add_field( $data['fields'] );
      $this->EE->dbforge->create_table( $table );
		}
		  
		return TRUE;
	}

	// ----------------------------------------------------------------
	
	/**
	 * Uninstall Method
	 *
	 * @return 	boolean 	TRUE
	 */	
	public function uninstall()
	{
	  
	  /* Cleanup Database
	  ******************************/
	  
		// Get Module ID
	  $mod  = $this->EE->db->select( 'module_id' )->get_where( 'modules', array('module_name' => ucwords(INVITEE_NAME) ) )->row( 'module_id' );
	  
	  // Remove Module From Nateive EE Tables
    $this->EE->db->where( 'module_id', $mod )->delete( 'module_member_groups' );
    $this->EE->db->where( 'module_name', INVITEE_NAME )->delete( 'modules' );
    $this->EE->db->where( 'class', ucwords(INVITEE_NAME) )->delete( 'actions' );

    // Load DB Forge
    $this->EE->load->dbforge();
    
    // Drop Custom Database Tables
		foreach ( $this->_module_tables as $table => $data )
		{
      $this->EE->dbforge->drop_table( $table );
		}
		
		return TRUE;
	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Module Updater
	 *
	 * @return 	boolean 	TRUE
	 */	
	public function update( $current = '' )
	{
	 
	  // This is not the method you're looking for ... 
	  
		return TRUE;
	}
	
}

/* End of file upd.invitee.php */
/* Location: /system/expressionengine/third_party/invitee/upd.invitee.php */