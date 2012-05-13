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
 * Invitee Module Instructions View
 *
 * @package		  ExpressionEngine
 * @subpackage	Addons
 * @category	  Module
 * @author		  Kevin Thompson (Brilliant2)
 * @link		    http://www.brilliant2.com/
 */
?>
<h1>Getting Started</h1>
<br />
<p>In order to create your first Invitee form, please complete the following steps:</p>
<ol>
  <li>Enter You MailChimp API Key on the <a href="<?= INVITEE_BASE_URL . AMP . "method=settings" ?>">Settings</a> page.</li>
  <li>Create a <a href="<?= INVITEE_BASE_URL . AMP . "method=templates" ?>">notification template</a> that will be sent to invited users.</li>
  <li>Create a <a href="<?= INVITEE_BASE_URL . AMP . "method=forms" ?>">subscription form</a> that will display the subscription and invitation fields.</li>
</ol>
<br />
<hr />
<br />
<h1>Using Invitee in Your Templates</h1>
<br />

<p>Once you"ve created a form, you can use the {exp:invitee} tag pair to insert your form into a template.
<br />
<br />
<h2>Parameters</h2>
<br />
<ul>
  <li><strong>form (required)</strong> &mdash; The code of the Invitee form you would like to associate with this subscription form.</li>
  <li><strong>return</strong> &mdash; The url that you would like the form to send the user to after successfully subscribing. If return is not defined, the user will be redirected to the current page.</li>
  <li><strong>id</strong> &mdash; An id attribute to be added to the form tag.</li>
  <li><strong>class</strong> &mdash; A class attribute to be added to the form tag.</li>
</ul>
<br />
<br />
<h2>Fields</h2>
<br />
<p>Invitee allows you to define any number of fields on your sign up forms, but requires you include at the very least, an "email" field and a submit button. Including a textarea with the name, "invite_list", will allow your users to specify additional email addresses to invite.</p>
<p>Any additional form fields will be available as variables within your notification templates, and will be mapped to custom fields in your MailChimp mailing lists where possible.</p>
<br />
<h2>Example</h2>

<pre><code><strong>{exp:invitee:form return="myform/thankyou" id="formid" class="formclass" form="interest"}</strong>

   	&lt;label&gt;First Name&lt;/label&gt;
   	&lt;input name="first_name" /&gt;
   
  	&lt;label&gt;Last Name&lt;/label&gt;
   	&lt;input name="last_name" /&gt;

   	&lt;label&gt;Your Email&lt;/label&gt;
   	&lt;input name="email" /&gt;

	{lists}
		&lt;input type="checkbox" name="lists[]" id="list_{list_id}" value="{list_id}" /&gt; 
		&lt;label for="list_{list_id}">{list_name}&lt;/label&gt;
	{/lists}
	
	&lt;label&gt;Invite your friends&lt;/label&gt;
   	&lt;textarea name="invite_list"&gt;&lt;/textarea&gt;

	&lt;input type="sumbit" value="sign-up" /&gt; 

<strong>{/exp:invitee:form}</strong>
</code></pre>