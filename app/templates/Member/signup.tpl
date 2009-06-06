<form  class="prepend-3 span-11 append-3" action="{url to=$form->getSourceAction()}" method="post">
    <input type="hidden" name="mvcskel_form_id" value="{$form->getId()}" />
    <fieldset>
        <legend>Fill in the Registration Form</legend>
        {include file='field.tpl' name='username' label='Username' type='text' class='text'}
        {include file='field.tpl' name='password' label='Password' type='password' class='text'}
        {include file='field.tpl' name='pass2' label='Password repeat' type='password' class='text'}
        {include file='field.tpl' name='email' label='Email address' type='text' class='text'}
        {include file='field.tpl' name='fname' label='First name' type='text' class='text'}
        <p>
            <button type="submit" class="button positive">
                <img src="{$root}styles/blueprint/plugins/buttons/icons/tick.png" alt=""/> Join
            </button>
        </p>
    </fieldset>
</form>
