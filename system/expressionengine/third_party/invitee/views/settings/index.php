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
 * Invitee Module Settings View
 *
 * @package		  ExpressionEngine
 * @subpackage	Addons
 * @category	  Module
 * @author		  Kevin Thompson (Brilliant2)
 * @link		    http://www.brilliant2.com/
 */
 
echo form_open($action_url);
?>
<div class="formArea">
  <div class="formHeading">
    <?php if ($mailchimp_api_key != ''): ?>
    <div class="newTemplate" style="margin-left:15px;">
      <?=form_submit(array('name' => 'submit', 'value' => lang('invitee_settings_update_api'), 'class' => 'submit'))?>
    </div>
    <?php endif; ?>
    <?= lang( 'invitee_page_settings_index' ) ?>
  </div>
  <?
  $cp_table_template['table_open'] = '<table class="templateTable" border="0" cellspacing="0" cellpadding="0">';
  $this->table->set_template($cp_pad_table_template);
  $this->table->set_heading(
      array('data' => lang('preference'), 'style' => 'width:50%;'),
      lang('setting')
  );

  $this->table->add_row(array(
  		lang( 'invitee_mailchimp_api_key' ),
  		form_input('mailchimp_api_key', $mailchimp_api_key, 'class="field"')
  	)
  );
	
  echo $this->table->generate();

  ?>
  <?php if (lang( 'invitee_settings_instructions' ) != ''): ?>
  <div class="settings_instruction_text">
    <p><?= lang( 'invitee_settings_instructions' ) ?></p>
  </div>
  <?php endif; ?>
  <div class="tableSubmit">
  <?=form_submit(array('name' => 'submit', 'value' => lang('submit'), 'class' => 'submit'))?>
  </div>
</div>

<?=form_close()?>