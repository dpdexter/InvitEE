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
 * Invitee Module Forms List View
 *
 * @package		  ExpressionEngine
 * @subpackage	Addons
 * @category	  Module
 * @author		  Kevin Thompson (Brilliant2)
 * @link		    http://www.brilliant2.com/
 */
?>
<div class="formArea">
  <?php
  if( $mailchimp_api_key == '' )
  {
    printf( lang( 'invitee_mailchimp_api_key_required' ), '<a href="' . INVITEE_BASE_URL . AMP . 'method=settings">', '</a>' );
  }
  else if ( count( $lists ) == 0 )
  {
    printf( lang( 'invitee_mailchimp_list_required' ), '<a href="http://mailchimp.com" target="_blank">', '</a>' );
  }
  else if ( $templates->num_rows() == 0 )
  {
    printf( lang( 'invitee_notification_template_required' ), '<a href="' . INVITEE_BASE_URL . AMP . 'method=templates_create">', '</a>' );
  }
  else
  {
  ?>
  <div class="formHeading">
    <div class="newTemplate" style="margin-left:15px;">
      <a href="<?= INVITEE_BASE_URL . AMP . 'method=forms_create' ?>" class="submit"><?= lang( 'invitee_form_create' ) ?></a>
    </div>
    <?= lang('invitee_forms') ?>
  </div>
  <?php if ( count($forms) > 0 ): ?>
  <?= form_open( $form_action, array(), array() ) ?>
  <?php
  $cp_table_template['table_open'] = '<table class="templateTable" border="0" cellspacing="0" cellpadding="0">';
  $this->table->set_template($cp_table_template);
  $this->table->set_heading(
  	lang( 'invitee_form_title'),
  	lang( 'invitee_form_code'),
  	lang( 'invitee_form_lists'),
  	lang( 'invitee_form_invites'),
  	lang( 'invitee_form_invites_converted'),
  	array(
  	 'data'   => form_checkbox('select_all', 'true', FALSE, 'class="toggle_all" id="select_all"'),
  	 'style'  => 'width:40px'
  	)
  );

  foreach($forms as $form)
  {
  	$this->table->add_row(
  		'<a href="' . $form['edit_link'] . '">' . $form['form_title'] . '</a>',
  		$form['form_code'],
  		$form['lists'],
  		$form['form_invites'],
  		$form['form_invites_converted'],
  		form_checkbox( $form['toggle'] )
  	);
  }
  echo $this->table->generate();
  ?>
  <div class="tableSubmit">
  <?= form_submit('submit', lang( 'invitee_forms_delete' ), 'class="submit"') ?>
  </div>
  <?= form_close() ?>
  <?php else: ?>
    <div class="content">
      <a href="<?= INVITEE_BASE_URL . AMP . 'method=forms_create' ?>"><?= lang('invitee_form_create') ?></a>
    </div>
  <?php endif; ?>
  <?php
  }
?>
</div>