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
 * Invitee Module Create Template View
 *
 * @package		  ExpressionEngine
 * @subpackage	Addons
 * @category	  Module
 * @author		  Kevin Thompson (Brilliant2)
 * @link		    http://www.brilliant2.com/
 */
?>

<? 
/* Template Title 
******************************/ ?>
<?= form_open( $form_action, '', $hidden ) ?>
<?= form_label( '<em class="required">* </em>' . lang( 'invitee_template_title' ), 'template_title' ) ?>
<?php if (lang( 'invitee_template_title_instructions' ) != ''): ?>
<div class="instruction_text">
  <p><?= lang( 'invitee_form_title_instructions' ) ?></p>
</div>
<?php endif; ?>
<?= form_fieldset( '', array( 'class' => 'holder' ) ) ?>
<?= form_input( array( 'name' => 'template_title', 'id' => 'template_title' ), set_value('template_title', isset($template->template_title) ? $template->template_title : '') ) ?>
<?= form_error('template_title') ?>
<?= form_fieldset_close() ?>

<? 
/* Template Subject
******************************/ ?>
<?= form_label( '<em class="required">* </em>' . lang( 'invitee_template_subject' ), 'template_subject' ) ?>
<?php if (lang( 'invitee_template_subject_instructions' ) != ''): ?>
<div class="instruction_text">
  <p><?= lang( 'invitee_template_subject_instructions' ) ?></p>
</div>
<?php endif; ?>
<?= form_fieldset( '', array( 'class' => 'holder' ) ) ?>
<?= form_input( array( 'name' => 'template_subject', 'id' => 'template_subject' ), set_value('template_subject', isset($template->template_subject) ? $template->template_subject : '') ) ?>
<?= form_error('template_subject') ?>
<?= form_fieldset_close() ?>

<? 
/* Template Content
******************************/ ?>
<?= form_open( $form_action, array(), array() ) ?>
<?= form_label( '<em class="required">* </em>' . lang( 'invitee_template_content' ), 'template_content' ) ?>
<?php if (lang( 'invitee_template_content_instructions' ) != ''): ?>
<div class="instruction_text">
  <p><?= lang( 'invitee_template_content_instructions' ) ?></p>
</div>
<?php endif; ?>
<?= form_fieldset( '', array( 'class' => 'holder table' ) ) ?>
<?= form_textarea( array( 'name' => 'template_content', 'id' => 'template_content' ), set_value('template_content', isset($template->template_content) ? $template->template_content : '') ) ?>
<?= form_error('template_content') ?>
<?= form_fieldset_close() ?>

<? 
/* Template Actions
******************************/ ?>
<div class="formActions">
<?= form_submit('submit_form', lang('submit'), 'class="submit"') ?>
</div>
<?= form_close() ?>
