<?php  if ( ! defined("BASEPATH")) exit("No direct script access allowed");

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

/**
 * Invitee Module Language File
 *
 * @package		  ExpressionEngine
 * @subpackage	Addons
 * @category	  Module
 * @author		  Kevin Thompson (Brilliant2)
 * @link		    http://www.brilliant2.com/
 */
 
// Define Language Keys
$lang = array(
	
	// Module Definition
	"invitee_module_name"                     => "Invitee",
	"invitee_module_description"              => "MailChimp subscribe and invite forms.",
	                                          
	// Page Titles                            
	"invitee_page_home_index"                 => "Forms",
	"invitee_page_forms_index"                => "Forms",
	"invitee_page_forms_create"               => "Create Form",
	"invitee_page_forms_edit"                 => "Edit Form",
	"invitee_page_forms_delete"               => "Delete Forms",
	"invitee_page_templates_index"            => "Notification Templates",
	"invitee_page_templates_create"           => "Create Notification Template",
	"invitee_page_templates_edit"             => "Edit Notification Template",
	"invitee_page_templates_delete"           => "Delete Notification Templates",
	"invitee_page_settings_index"             => "Settings",
	"invitee_page_instructions_index"         => "Instructions",
	                                          
	// Requirements                           
	"invitee_mailchimp_api_key_required"      => "Before you can begin creating forms, you will need to define your MailChimp API Key on the %ssettings page%s.",
	"invitee_mailchimp_list_required"         => "Before creating forms, you'll need to create a mailing list on your %sMailChimp account%s so that you have something for users to subscribe to.",
	"invitee_notification_template_required"  => "You will need to create at least one %snotification template%s before creating any forms.",  
	                                          
	// Forms                                  
	"invitee_forms"                           => "Forms",                              
	"invitee_form_create"                     => "Create New Form",                
	"invitee_form_create_failed"              => "New Form Could Not Be Created",
	"invitee_form_created"                    => "Form Created",
	"invitee_form_updated"                    => "Form Updated",
	"invitee_form_update_failed"              => "Form Could Not Be Updated",
	"invitee_form_title"                      => "Form Title",
	"invitee_form_code"                       => "Form Code",
	"invitee_form_lists"                      => "MailChimp Lists",
	"invitee_form_template"                   => "Notification Template",
	"invitee_form_subscribers"                => "Subscribers",
	"invitee_form_invites"                    => "Invites Sent",
	"invitee_form_invites_converted"          => "Invites Converted",
	"invitee_forms_delete"                    => "Delete Selected Forms",
	"invitee_unique_form_code"                => "Form Code field must be unique",
	"invitee_form_delete_confirm"             => "Are you sure you would like to delete this form?",
	"invitee_forms_delete_confirm"            => "Are you sure you would like to delete the following forms?",
	                                          
	// Templates                              
	"invitee_templates"                       => "Notification Templates",                          
	"invitee_template_title"                  => "Template Title",                                  
	"invitee_template_subject"                => "Template Subject",                    
	"invitee_template_content"                => "Template Content",
	"invitee_template_created"                => "Notification Template Created",
	"invitee_template_updated"                => "Notification Template Updated",
	"invitee_templates_create"                => "Create New Notification Template",
	"invitee_templates_delete"                => "Delete Selected Notification Templates",
	"invitee_template_delete_confirm"         => "Are you sure you would like to delete this notification template?",
	"invitee_templates_delete_confirm"        => "Are you sure you would like to delete the following notification templates?",
	"invitee_templates_default_subject"       => "Mailing List Invitation",
	"invitee_templates_default_content"       => "You have been invited to join a mailing list: {link}",
	                                          
	// Settings                               
	"invitee_mailchimp_api_key"               => "MailChimp API Key",
	"invitee_settings_update_api"             => "Update MailChimp API Data",
	"invitee_settings_api_updated"            => "MailChimp API Data Updated",
	"invitee_settings_updated"                => "Settings Updated",
	                                          
	// Instructions                           
	"invitee_form_title_instructions"         => "",
	"invitee_form_code_instructions"          => "",
	"invitee_form_lists_instructions"         => "",
	"invitee_form_template_instructions"      => "",
	"invitee_template_title_instructions"     => "",
	"invitee_template_subject_instructions"   => "Here you can use the field names of your Invitee form as variables. By default, only the {link} variable exists and will be replaced with a link back to the page the form resides on.",
	"invitee_template_content_instructions"   => "Here you can use the field names of your Invitee form as variables. By default, only the {link} variable exists and will be replaced with a link back to the page the form resides on.",
	"invitee_settings_instructions"           => 'You can find, or create a MailChimp API key by visiting the the <a href="https://us1.admin.mailchimp.com/account/api/">API Keys</a> page of you MailChimp account. To create an API key, simply click the "add a key" button on the API Keys page.'

);

/* End of file lang.invitee.php */
/* Location: /system/expressionengine/third_party/invitee/language/english/lang.invitee.php */
