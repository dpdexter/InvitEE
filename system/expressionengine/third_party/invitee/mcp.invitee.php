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
 * Invitee Module Control Panel File
 *
 * @package		  ExpressionEngine
 * @subpackage	Addons
 * @category	  Module
 * @author		  Kevin Thompson (Brilliant2)
 * @link		    http://www.brilliant2.com/
 */

class Invitee_mcp {
	
	// Private Variables
	private $EE;
	private $_action  = 'index';
	private $_data    = array();
	private $_form_id;
	private $_page    = 'forms';
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
		
		// Load Assets
		$this->_include_theme_css('screen');
		
		// Load Libraries & Helpers
		$this->EE->load->library('table');
	  $this->EE->load->helper( 'form' );
		
		// Define Module Pages
		$this->EE->cp->set_right_nav(array(
			'invitee_page_forms_index'        => INVITEE_BASE_URL . AMP . 'method=forms',
			'invitee_page_templates_index'    => INVITEE_BASE_URL . AMP . 'method=templates',
			'invitee_page_settings_index'     => INVITEE_BASE_URL . AMP . 'method=settings',
			'invitee_page_instructions_index' => INVITEE_BASE_URL . AMP . 'method=instructions'
		));
	  
	  // Get Mailing Lists
		$list_array = array();
		$this->_data['lists'] = array();
	  $query = $this->EE->db->get('invitee_lists');
		foreach( $query->result() as $list )
		{
		  $list_array[$list->list_id] = $list->list_name;
		}
		$this->_data['lists'] = $list_array;
		
		// Define Page Data
		$page_data      = explode( '_', !$this->EE->input->get('method') ? $this->_page : $this->EE->input->get('method') );
		$this->_page    = $page_data[0];
	  $this->_action  = isset( $page_data[1] ) ? $page_data[1] : $this->_action;
		$this->_data['mailchimp_api_key'] = $this->EE->config->item('mailchimp_api_key');
		
		// Highlight the active page
		$this->_insert_css('.rightNav a[href$="' . $this->_page . '"] { background:#788c99; color:#fff !important; } ');
		
	  // Set Page Title
		$this->EE->cp->set_variable( 'cp_page_title', lang( 'invitee_module_name' ) . ' – ' . lang( 'invitee_page_' . $this->_page . '_' . $this->_action) );
	}
	
	// ----------------------------------------------------------------

	/**
	 * Index Method
	 * 
	 * Route to the appropriate page.
	 *
	 * @return 	mixed view data
	 */
	public function index()
	{
	  // Retreive Forms
	  $this->EE->db->where( 'site_id', $this->EE->config->item('site_id') );
	  $forms = $this->EE->db->get( 'invitee_forms' );
	  
	  // Retreive Templates
	  $this->EE->db->where( 'site_id', $this->EE->config->item('site_id') );
	  $templates = $this->EE->db->get( 'invitee_templates' );
	  
	  if( $this->EE->config->item('mailchimp_api_key') == '' 
	    || $forms->num_rows() == 0
	    || $templates->num_rows() == 0)
	  {
	    // Show Instructions
      $this->EE->functions->redirect( INVITEE_BASE_URL . AMP . 'method=instructions'); 
	  }
	  else
	  {
	    // Show Forms
      $this->EE->functions->redirect( INVITEE_BASE_URL . AMP . 'method=forms');
	  }
	}
	
	// ----------------------------------------------------------------

	/**
	 * Forms Method
	 * 
	 * List of existing forms
	 *
	 * @return 	mixed view data
	 */
	public function forms()
	{	  
	  // Insert List JavaScript
	  $this->_insert_js(
	    '$(".toggle_all").toggle(
				function(){
					$("input.toggle").each(function() {
						this.checked = true;
					});
				}, function (){
					var checked_status = this.checked;
					$("input.toggle").each(function() {
						this.checked = false;
					});
				}
			);'
		);
	  
	  // Retreive Forms
	  $this->EE->db->where( 'site_id', $this->EE->config->item('site_id') );
	  $query = $this->EE->db->get( 'invitee_forms' );
	  
	  // Instantiate Forms Array
	  $this->_data['forms'] = array();
	  
	  foreach ( $query->result_array() as $form )
	  {
	    $form['edit_link']        = INVITEE_BASE_URL . AMP . 'method=forms_edit&form_id=' . $form['form_id'];
	    $form['toggle']           = array(
	      'name'  => 'forms[]',
	      'class' => 'toggle',
	      'value' => $form['form_id']
	    );
	    
	    // Build Lists Output
	    $form['lists'] = '';
	    $query = $this->EE->db->get_where('invitee_form_lists',array(
	      'form_id' => $form['form_id']
	    ));
	    foreach($query->result_array() as $row)
	    {
	      if( array_key_exists( $row['list_id'], $this->_data['lists'] ) )
	      {
	        $form['lists'] .= ($form['lists'] != '' ? '<br />' : '') . '<a href="https://us1.admin.mailchimp.com/lists/dashboard/overview?id=' . $row['list_id'] . '" target="_blank">' . $this->_data['lists'][$row['list_id']] . '</a>';	       
	      }
	    }
	    
	    $form['form_invites']  = $this->EE->db->where(array(
	      'form_id' => $form['form_id']
	    ))->count_all_results('invitee_invites');
	    
	    $form['form_invites_converted'] = $this->EE->db->where(array(
	      'form_id'                 => $form['form_id'],
	      'invite_converted_at != ' => 'NULL'
	    ))->count_all_results('invitee_invites');
	    
	    $this->_data['forms'][] = $form;
	  }
	  
	  // Get Notification Templates
	  $this->EE->db->where( 'site_id', $this->EE->config->item('site_id') );
	  $this->_data['templates'] = $this->EE->db->get( 'invitee_templates' );
	  
	  // Define View Data
	  $this->_data['form_action'] = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=' . INVITEE_NAME . AMP . 'method=forms_delete'; 
	  
	  // Load Page View
    return $this->EE->load->view( $this->_page . '/' . $this->_action, $this->_data, TRUE );
	}
	
	// ----------------------------------------------------------------

	/**
	 * Forms Create
	 * 
	 * Create new signup form
	 *
	 * @return 	mixed view data
	 */
	public function forms_create()
	{	  
	  // Define View Data
	  $this->_data['form_action'] = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=' . INVITEE_NAME . AMP . 'method=forms_create'; 
	  
	  // Load Page View
    return $this->forms_edit();
	}
	
	// ----------------------------------------------------------------

	/**
	 * Forms Edit
	 * 
	 * Create new signup form
	 *
	 * @return 	mixed view data
	 */
	public function forms_edit()
	{
	  // Load Form Validation Library
		$this->EE->load->library('form_validation');
		
		// Set Current Form ID
		$this->_form_id = $this->EE->input->post('form_id');
	  
	  // Configure Form Validation
	  $rules  = array(
      array(
        'field'   => 'form_title',
        'label'   => lang( 'invitee_form_title' ),
        'rules'   => 'required'
      ),
      array(
        'field'   => 'form_code',
        'label'   => lang( 'invitee_form_code' ),
        'rules'   => 'required|alpha_dash|callback__unique_form_code'
      ),
      array(
        'field'   => 'form_template',
        'label'   => lang( 'invitee_form_template' ),
        'rules'   => 'required'
      ),
      array(
        'field'   => 'form_lists',
        'label'   => lang( 'invitee_form_lists' ),
        'rules'   => 'required'
      )
    );
    
	  $this->EE->form_validation->set_rules( $rules )->set_error_delimiters('<div class="notice">', '</div>');
		$this->EE->form_validation->set_message('_unique_form_code', lang('invitee_unique_form_code'));
	  
	  // Validate Form Submission
	  if ( $this->EE->form_validation->run() )
	  {   
      // Build Data Insert
      $data = array(
          'form_title'  => $this->EE->input->post( 'form_title' ),
          'form_code'   => $this->EE->input->post( 'form_code' ),
          'template_id' => $this->EE->input->post( 'form_template' ),
          'site_id'     => $this->EE->config->item('site_id')
      );
      
      if ( $this->EE->input->post('form_id') != '' )
      { 
        $form_id = $this->EE->input->post('form_id');
        
        // Update Data
        if ( $this->EE->db->where( 'form_id', $this->EE->input->post('form_id') )->update( 'invitee_forms', $data ) )
        {
          // Set Success Message
          $this->EE->session->set_flashdata( 'message_success', lang( 'invitee_form_updated' ) );
        }
      }
      else
      {      
        // Insert Data
        if ( $this->EE->db->insert( 'invitee_forms', $data ) )
        {
          // Set Success Message
          $this->EE->session->set_flashdata( 'message_success', lang( 'invitee_form_created' ) );
          $form_id = $this->EE->db->insert_id('form_id');
        } 
      }
      
      // Update List Associations
      $this->EE->db->delete('invitee_form_lists',array(
        'form_id' => $form_id
      ));
      foreach( $this->EE->input->post( 'form_lists' ) as $list_id )
      {
        $this->EE->db->insert('invitee_form_lists',array(
          'form_id' => $form_id,
          'list_id' => $list_id
        ));
      }
      
      // Redirect To Form Page
      $this->EE->functions->redirect( INVITEE_BASE_URL . AMP . 'method=forms'); 
	  }
	  
	  // Build Form View	  
	  $this->_data['hidden'] = array();
    
	  if( $this->EE->input->get('form_id') )
	  {
      $form = $this->EE->db->get_where("invitee_forms","form_id = '" . $this->EE->input->get('form_id') . "'");
      $this->_data['form'] = $form->row();
      $this->_data['hidden']['form_id'] = $this->EE->input->get('form_id');
    
      $this->_data['form']->lists = array();
      $query = $this->EE->db->get_where('invitee_form_lists',array(
        'form_id' => $this->EE->input->get('form_id')
      ));
      foreach( $query->result() as $row )
      {
        $this->_data['form']->lists[] = $row->list_id;
      }
	  }
	  
	  // Get Notification Templates
	  $this->EE->db->where( 'site_id', $this->EE->config->item('site_id') );
	  $templates = $this->EE->db->get( 'invitee_templates' );
	  foreach ( $templates->result_array() as $template )
	  {
	    $this->_data['templates'][$template['template_id']] = $template['template_title'];
	  }
	  
	  // Define Form Action
	  if( ! isset($this->_data['form_action']) ) $this->_data['form_action'] = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=' . INVITEE_NAME . AMP . 'method=forms_edit' . AMP . 'form_id=' . $this->EE->input->get('form_id');
	  
	  // Load Page View
    return $this->EE->load->view( $this->_page . '/edit', $this->_data, TRUE );
	}
	
	// --------------------------------------------------------------------

	/**
	 * Check for existing code
	 *
	 * @return	bool
	 */	
	function _unique_form_code( $code )
	{
	  $forms = $this->EE->db->get_where( 'invitee_forms', array(
	    'form_id !='  => $this->_form_id,
	    'form_code'   => $code,
	    'site_id'     => $this->EE->config->item('site_id')
	  ));
	  return ($forms->num_rows() == 0 ? TRUE : FALSE);
	}
	
	// ----------------------------------------------------------------

	/**
	 * Forms Delete
	 * 
	 * Delete Selected Forms
	 *
	 * @return 	null
	 */
	public function forms_delete()
	{		
	  // Get Posted Suggestions
	  $forms = $this->EE->input->post('forms');
		
		if( $this->EE->input->post('confirm_delete') )
		{
		  
		  // Delete Suggestions
		  foreach ( $forms as $form )
		  {
		    $this->EE->db->delete( 'invitee_forms', array(
		      'form_id'   => $form,
		      'site_id'       => $this->EE->config->item('site_id')
		    ));
		    $this->EE->db->delete( 'invitee_invites', array(
		      'form_id'   => $form
		    ));
		    $this->EE->db->delete( 'invitee_form_lists', array(
		      'form_id'   => $form
		    ));
		  }
		  
      // Set Success Message and Redirect to List Page
      $this->EE->session->set_flashdata( 'message_success', lang( 'invitee_form' . ( count($forms) > 1 ? 's' : '' ) . '_deleted' ) );
      $this->EE->functions->redirect( INVITEE_BASE_URL . AMP . 'method=forms');
		}
		
	  // Define View Data
	  foreach ( $forms as $id )
	  {
  	  $query = $this->EE->db->select( 'form_title' )->get_where( 'invitee_forms', array(
  	    'form_id' => $id,
  	    'site_id'     => $this->EE->config->item('site_id')
  	  ));
  	  if( $query->num_rows() > 0 )
  	  {
	      $this->_data['forms'][$id] = $query->row('form_title'); 
  	  }
	  }
	  $this->_data['form_action'] = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=' . INVITEE_NAME . AMP . 'method=forms_delete';
	  
	  // Load Page View
    return $this->EE->load->view( $this->_page . '/' . $this->_action, $this->_data, TRUE );
	}
	
	// ----------------------------------------------------------------

	/**
	 * Templates Method
	 * 
	 * List of existing templates
	 *
	 * @return 	mixed view data
	 */
	public function templates()
	{
	  // Instantiate Templates Array
	  $this->_data['templates'] = array();
	  
	  // Insert List JavaScript
	  $this->_insert_js(
	    '$(".toggle_all").toggle(
				function(){
					$("input.toggle").each(function() {
						this.checked = true;
					});
				}, function (){
					var checked_status = this.checked;
					$("input.toggle").each(function() {
						this.checked = false;
					});
				}
			);'
		);
	  
	  // Retreive Templates
	  $this->EE->db->where( 'site_id', $this->EE->config->item('site_id') );
	  $query = $this->EE->db->get( 'invitee_templates' );
	  
	  $this->_data['templates'] = array();
	  
	  foreach ( $query->result_array() as $template )
	  {
	    $template['edit_link']        = INVITEE_BASE_URL . AMP . 'method=templates_edit&template_id=' . $template['template_id'];
	    $template['toggle']           = array(
	      'name'  => 'templates[]',
	      'class' => 'toggle',
	      'value' => $template['template_id']
	    );
	    $this->_data['templates'][] = $template;
	  }
	  
	  // Define View Data
	  $this->_data['form_action'] = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=' . INVITEE_NAME . AMP . 'method=templates_delete'; 
	  
	  // Load Page View
    return $this->EE->load->view( $this->_page . '/' . $this->_action, $this->_data, TRUE );
	}
	
	// ----------------------------------------------------------------

	/**
	 * Templates Create
	 * 
	 * Create new notification template
	 *
	 * @return 	mixed view data
	 */
	public function templates_create()
	{
	  // Define View Data
	  $this->_data['form_action'] = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=' . INVITEE_NAME . AMP . 'method=templates_create'; 
    return $this->templates_edit();
	}
	
	// ----------------------------------------------------------------

	/**
	 * Templates Edit
	 * 
	 * Edit and update templates
	 *
	 * @return 	mixed view data
	 */
	public function templates_edit()
	{
	  // Load Form Validation Library	  
		$this->EE->load->library('form_validation');
		
		// Configure Form Validation
	  $rules  = array(
      array(
        'field'   => 'template_title',
        'label'   => lang( 'invitee_template_title' ),
        'rules'   => 'required'
      ),
      array(
        'field'   => 'template_subject',
        'label'   => lang( 'invitee_template_subject' ),
        'rules'   => 'required'
      ),
      array(
        'field'   => 'template_content',
        'label'   => lang( 'invitee_template_content' ),
        'rules'   => 'required'
      )
    );
	  $this->EE->form_validation->set_rules( $rules )->set_error_delimiters('<div class="notice">', '</div>');
	  
	  // Validate Form Submission
	  if ( $this->EE->form_validation->run() )
	  {   
      // Build Data Insert
      $data = array(
          'template_title'    => $this->EE->input->post( 'template_title' ),
          'template_subject'  => $this->EE->input->post( 'template_subject' ),
          'template_content'  => $this->EE->input->post( 'template_content' ),
          'site_id'           => $this->EE->config->item('site_id')
      );
      
      if ( $this->EE->input->post('template_id') != '' )
      {        
        // Update Data
        if ( $this->EE->db->where( 'template_id', $this->EE->input->post('template_id') )->update( 'invitee_templates', $data ) )
        {
          // Set Success Message and Redirect to List Page
          $this->EE->session->set_flashdata( 'message_success', lang( 'invitee_template_updated' ) );
          $this->EE->functions->redirect( INVITEE_BASE_URL . AMP . 'method=templates'); 
        }
      }
      else
      {      
        // Insert Data
        if ( $this->EE->db->insert( 'invitee_templates', $data ) )
        {
          // Set Success Message and Redirect to List Page
          $this->EE->session->set_flashdata( 'message_success', lang( 'invitee_template_created' ) );
          $this->EE->functions->redirect( INVITEE_BASE_URL . AMP . 'method=templates'); 
        } 
      }
	  }
	  
	  // Check for template
	  $this->_data['hidden'] = array();
	  if( $this->EE->input->get('template_id') )
	  {
	    $template = $this->EE->db->get_where("invitee_templates","template_id = '" . $this->EE->input->get('template_id') . "'");
	    $this->_data['template'] = $template->row();
	    $this->_data['hidden']['template_id'] = $this->EE->input->get('template_id');
	  }
	  
	  // Define View Data
	  if( ! isset($this->_data['form_action']) ) $this->_data['form_action'] = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=' . INVITEE_NAME . AMP . 'method=templates_edit';
	  
	  // Load Page View
    return $this->EE->load->view( $this->_page . '/edit', $this->_data, TRUE );
	}
	
	// ----------------------------------------------------------------

	/**
	 * Templates Delete
	 * 
	 * Delete Selected Templates
	 *
	 * @return 	null
	 */
	public function templates_delete()
	{		
	  // Get Posted Suggestions
	  $templates = $this->EE->input->post('templates');
		
		if( $this->EE->input->post('confirm_delete') )
		{
		  
		  // Delete Suggestions
		  foreach ( $templates as $template )
		  {
		    $this->EE->db->delete( 'invitee_templates', array(
		      'template_id'   => $template,
		      'site_id'       => $this->EE->config->item('site_id')
		    ));
		  }
		  
      // Set Success Message and Redirect to List Page
      $this->EE->session->set_flashdata( 'message_success', lang( 'invitee_template' . ( count($templates) > 1 ? 's' : '' ) . '_deleted' ) );
      $this->EE->functions->redirect( INVITEE_BASE_URL . AMP . 'method=templates');
		}
		
	  // Define View Data
	  foreach ( $templates as $id )
	  {
  	  $query = $this->EE->db->select( 'template_title' )->get_where( 'invitee_templates', array(
  	    'template_id' => $id,
  	    'site_id'     => $this->EE->config->item('site_id')
  	  ));
  	  if( $query->num_rows() > 0 )
  	  {
	      $this->_data['templates'][$id] = $query->row('template_title'); 
  	  }
	  }
	  $this->_data['form_action'] = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=' . INVITEE_NAME . AMP . 'method=templates_delete';
	  
	  // Load Page View
    return $this->EE->load->view( $this->_page . '/' . $this->_action, $this->_data, TRUE );
	}

	// --------------------------------------------------------------------

	/**
	 * Settings page
	 *
	 * @return	mixed view data
	 */
	public function settings()
	{
	  // Load Libraries & Helpers
		$this->EE->load->library('table');
		$this->EE->load->library('javascript');
		$this->EE->load->helper('form');
    
    // Define View Data
		$this->_data['action_url']        = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=' . INVITEE_NAME . AMP.'method=save_settings';
		$this->_data['mailchimp_api_key'] = $this->EE->config->item('mailchimp_api_key');
	  
	  // Load Page View
    return $this->EE->load->view( $this->_page . '/' . $this->_action, $this->_data, TRUE );
	}
	
	// --------------------------------------------------------------------

	/**
	 * Update Module Settings
	 *
	 * @return	void
	 */	
	public function save_settings()
	{		
	  // Collect Setting Data
		$config['mailchimp_api_key'] = $this->EE->input->post('mailchimp_api_key');
		
		// Update Settings In Database
		$this->EE->config->_update_config($config);
		
		// Update Lists In Database
		if($config['mailchimp_api_key'] != '')
		{
  		$this->EE->load->model( 'mailchimp_model' );
    	$mailing_lists = $this->EE->mailchimp_model->get_mailing_lists();
    	
    	// Clear List Data
  	  $query = $this->EE->db->delete('invitee_lists',array(
  	    'site_id' => $this->EE->config->item('site_id')
  	  ));
  	
    	foreach( $mailing_lists['data'] as $list )
    	{
    	  // Prep List Insert
    	  $insert = array(
    	    'list_id'           => $list['id'],
    	    'site_id'           => $this->EE->config->item('site_id'),
    	    'list_name'         => $list['name'],
    	    'list_web_id'       => $list['web_id'],
    	    'list_member_count' => $list['stats']['member_count']
    	  );
    	  $this->EE->db->insert('invitee_lists',$insert);    	    
    	} 
		}
		
		// Set Success Message and Redirect User
		if( $this->EE->input->post('submit') == lang('invitee_settings_update_api') )
		{
		  $this->EE->session->set_flashdata('message_success', lang('invitee_settings_api_updated'));
		}
		else
		{
		  $this->EE->session->set_flashdata('message_success', lang('invitee_settings_updated'));
		}
		$this->EE->functions->redirect(INVITEE_BASE_URL . AMP . 'method=settings');
	}
	
	// --------------------------------------------------------------------

	/**
	 * Instructions
	 *
	 * @return	mixed view data
	 */	
	public function instructions()
	{		
	  // Load Page View
    return $this->EE->load->view( $this->_page . '/' . $this->_action, $this->_data, TRUE );
	}

	
	// --------------------------------------------------------------------
  // UTILITY CLASSES
	// --------------------------------------------------------------------

	/**
	 * Theme URL
	 * 
	 * @return string theme folder URL
	 */
	private function _theme_url()
	{
	  if (! isset($this->cache['theme_url']))
	  {
  		$theme_folder_url = $this->EE->config->item('theme_folder_url');
  		if (substr($theme_folder_url, -1) != '/') $theme_folder_url .= '/';
  		$this->cache['theme_url'] = $theme_folder_url . 'third_party/' . INVITEE_NAME . '/'; 
	  }
	  return $this->cache['theme_url'];
	}

	// --------------------------------------------------------------------

	/**
	 * Include Theme CSS
	 * 
	 * @return null
	 */
	private function _include_theme_css($file)
	{
		$this->EE->cp->add_to_head('<link rel="stylesheet" type="text/css" href="' . $this->_theme_url() . 'css/' . $file . '.css?' . INVITEE_VERSION . '" />');
	}

	// --------------------------------------------------------------------

	/**
	 * Include Theme JS
	 * 
	 * @return null
	 */
	private function _include_theme_js($file)
	{
		$this->EE->cp->add_to_foot('<script type="text/javascript" src="' . $this->_theme_url() . 'js/' . $file . '.js?' . INVITEE_VERSION . '"></script>');
	}

	// --------------------------------------------------------------------

	/**
	 * Insert CSS
	 * 
	 * @return null
	 */
	private function _insert_css($css)
	{
		$this->EE->cp->add_to_head('<style type="text/css">' . $css . '</style>');
	}

	// --------------------------------------------------------------------

	/**
	 * Insert JS
	 * 
	 * @return null
	 */
	private function _insert_js($js)
	{
		$this->EE->cp->add_to_foot('<script type="text/javascript">' . $js . '</script>');
	}
}

/* End of file mcp.invitee.php */
/* Location: /system/expressionengine/third_party/invitee/mcp.invitee.php */