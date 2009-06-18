<form id="formProfile" class="prepend-3 span-11 append-3" action="{url to=$form->getSourceAction()}" method="post">
    <input type="hidden" name="mvcskel_form_id" value="{$form->getId()}" />
    <fieldset>
        <legend>Credentials</legend>
        {include file='field.tpl' name='username' label='Username' type='text' class='text'}
        <div>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" value="" class="text">
        </div>
        {include file='field.tpl' name='pass2' label='Password repeat' type='password' class='text'}
    </fieldset>
    <fieldset>
        <legend>Personal Information</legend>
        {include file='field.tpl' name='email' label='Email address' type='text' class='text'}
        {include file='field.tpl' name='fname' label='First name' type='text' class='text'}
    </fieldset>
    <p>
        <button type="submit" class="button positive">
            <img src="{$root}styles/blueprint/plugins/buttons/icons/tick.png" alt=""/> Join
        </button>
    </p>
</form>
