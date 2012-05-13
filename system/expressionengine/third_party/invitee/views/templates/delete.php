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
 * Invitee Module Delete Templates View
 *
 * @package		  ExpressionEngine
 * @subpackage	Addons
 * @category	  Module
 * @author		  Kevin Thompson (Brilliant2)
 * @link		    http://www.brilliantretail.com
 */
?>
<?= form_open( $form_action, array(), array() ) ?>
<p><strong><?= count($templates) > 1 ? lang( 'invitee_templates_delete_confirm') : lang( 'invitee_template_delete_confirm') ?></strong></p>

<ul class="entry_list">
<?php
foreach( $templates as $id => $title )
{
  echo '<li>' . $title . '</li>';
  echo form_hidden('templates[]',$id);
}
?>
</ul>
<p class="notice"><?= lang('action_can_not_be_undone') ?></p>

<p><?= form_submit('confirm_delete', lang('delete'), 'class="submit"') ?> <a href="<?= INVITEE_BASE_URL . AMP . 'method=templates'?>">Cancel</a></p>
<?= form_close() ?>