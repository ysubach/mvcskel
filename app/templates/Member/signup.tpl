<h2>Sign up to MvcSkel</h2>
<form  class="span-12" action="{url to=$form->getSourceAction()}" method="post">
    <input type="hidden" name="mvcskel_form_id" value="{$form->getId()}" />
    <fieldset>
        <legend>Sign up form</legend>
        {include file='field.tpl' name='username' label='Username' type='text' class='text'}
        {include file='field.tpl' name='password' label='Password' type='password' class='text'}
        {include file='field.tpl' name='pass2' label='Password repeat' type='password' class='text'}
        {include file='field.tpl' name='email' label='Email address' type='text' class='text'}
        {include file='field.tpl' name='fname' label='First name' type='text' class='text'}
        <p>
            <input type="submit" value="Submit form" />
        </p>
    </fieldset>
</form>
