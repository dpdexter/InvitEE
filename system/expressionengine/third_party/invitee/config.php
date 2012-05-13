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

/**
 * Invitee Module Control Panel File
 *
 * @package		  ExpressionEngine
 * @subpackage	Addons
 * @category	  Module
 * @author		  Kevin Thompson (Brilliant2)
 * @link		    http://www.brilliant2.com/
 */

// Module Configuration
$config['module_constant']  = 'INVITEE';
$config['module_version']   = '1.0';

// ------------------------------------------------------------------------

// Define Globals
if (! defined($config['module_constant'] . '_VERSION'))
{
	define($config['module_constant'] . '_NAME', isset($config['module_folder']) ? $config['module_folder'] : basename(dirname(__FILE__)));
	define($config['module_constant'] . '_VERSION', $config['module_version']);
	if (defined('BASE'))
	{
	  define($config['module_constant'] . '_BASE_URL', BASE . AMP . 'C=addons_modules' . AMP . 'M=show_module_cp' . AMP . 'module=' . INVITEE_NAME);
  }
}

/* End of file config.php */
/* Location: /system/expressionengine/third_party/invitee/config.php */