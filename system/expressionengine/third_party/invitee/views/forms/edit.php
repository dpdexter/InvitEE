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
 * Invitee Module Create Form View
 *
 * @package		  ExpressionEngine
 * @subpackage	Addons
 * @category	  Module
 * @author		  Kevin Thompson (Brilliant2)
 * @link		    http://www.brilliant2.com/
 */
?>

<? 
/* Form Title 
******************************/ ?>
<?= form_open( $form_action, '', $hidden ) ?>
<?= form_label( '<em class="required">* </em>' . lang( 'invitee_form_title' ), 'form_title' ) ?>
<?php if (lang( 'invitee_form_title_instructions' ) != ''): ?>
<div class="instruction_text">
  <p><?= lang( 'invitee_form_title_instructions' ) ?></p>
</div>
<?php endif; ?>
<?= form_fieldset( '', array( 'class' => 'holder' ) ) ?>
<?= form_input( array( 'name' => 'form_title', 'id' => 'form_title' ), set_value('form_title', isset($form->form_title) ? $form->form_title : '') ) ?>
<?= form_error('form_title') ?>
<?= form_fieldset_close() ?>


<? 
/* Form Code
******************************/ ?>
<?= form_label( '<em class="required">* </em>' . lang( 'invitee_form_code' ), 'form_code' ) ?>
<?php if (lang( 'invitee_form_code_instructions' ) != ''): ?>
<div class="instruction_text">
  <p><?= lang( 'invitee_form_code_instructions' ) ?></p>
</div>
<?php endif; ?>
<?= form_fieldset( '', array( 'class' => 'holder' ) ) ?>
<?= form_input( array( 'name' => 'form_code', 'id' => 'form_code' ), set_value('form_code', isset($form->form_code) ? $form->form_code : '') ) ?>
<?= form_error('form_code') ?>
<?= form_fieldset_close() ?>


<? 
/* MailChimp Lists 
******************************/ ?>
<?= form_label( '<em class="required">* </em>' . lang( 'invitee_form_lists' ), 'form_lists' ) ?>
<?php if (lang( 'invitee_form_lists_instructions' ) != ''): ?>
<div class="instruction_text">
  <p><?= lang( 'invitee_form_lists_instructions' ) ?></p>
</div>
<?php endif; ?>
<?= form_fieldset( '', array( 'class' => 'holder' ) ) ?>
<?= form_multiselect( 'form_lists[]', $lists, set_value('form_lists', isset($form->lists) ? $form->lists : '') ) ?>
<?= form_error('form_lists') ?>
<?= form_fieldset_close() ?>


<? 
/* Form Notification Templates
******************************/ ?>
<?= form_label( '<em class="required">* </em>' . lang( 'invitee_form_template' ), 'form_template' ) ?>
<?php if (lang( 'invitee_form_template_instructions' ) != ''): ?>
<div class="instruction_text">
  <p><?= lang( 'invitee_form_template_instructions' ) ?></p>
</div>
<?php endif; ?>
<?= form_fieldset( '', array( 'class' => 'holder' ) ) ?>
<?= form_dropdown( 'form_template', $templates, set_value('form_template', isset($form->template_id) ? $form->template_id : '') ) ?>
<?= form_error('form_template') ?>
<?= form_fieldset_close() ?>

<? 
/* Form Actions
******************************/ ?>
<div class="formActions">
<?= form_submit('submit_form', lang('submit'), 'class="submit"') ?>
</div>
<?= form_close() ?>
