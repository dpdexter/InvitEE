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
 * Invitee Module Notification Templates View
 *
 * @package		  ExpressionEngine
 * @subpackage	Addons
 * @category	  Module
 * @author		  Kevin Thompson (Brilliant2)
 * @link		    http://www.brilliant2.com/
 */
?>
<div class="formArea">
  <div class="formHeading">
    <div class="newTemplate" style="margin-left:15px;">
      <a href="<?= INVITEE_BASE_URL . AMP . 'method=templates_create' ?>" class="submit"><?= lang( 'invitee_templates_create' ) ?></a>
    </div>
    <?= lang( 'invitee_templates' ) ?>
  </div>
  <?php if ( count($templates) > 0 ): ?>
  <?= form_open( $form_action, array(), array() ) ?>
  <?php
  $cp_table_template['table_open'] = '<table class="templateTable" border="0" cellspacing="0" cellpadding="0">';
  $this->table->set_template($cp_table_template);
  $this->table->set_heading(
  	lang( 'invitee_template_title'),
  	array(
  	 'data'   => form_checkbox('select_all', 'true', FALSE, 'class="toggle_all" id="select_all"'),
  	 'style'  => 'width:40px'
  	)
  );

  foreach($templates as $template)
  {
  	$this->table->add_row(
  		'<a href="' . $template['edit_link'] . '">' . $template['template_title'] . '</a>',
  		form_checkbox( $template['toggle'] )
  	);
  }
  echo $this->table->generate();
  ?>
  <div class="tableSubmit">
  <?= form_submit('submit', lang( 'invitee_templates_delete' ), 'class="submit"') ?>
  </div>
  <?= form_close() ?>
  <?php else: ?>
    <div class="content">
      <a href="<?= INVITEE_BASE_URL . AMP . 'method=templates_create' ?>"><?= lang('invitee_templates_create') ?></a>
    </div>
  <?php endif; ?>
</div>