<form class="prepend-3 span-11 append-3 fu" action="{url to=$form->getSourceAction()}" method="post">
    <input type="hidden" name="mvcskel_form_id" value="{$form->getId()}" />
    <fieldset>
        <legend>Credentials</legend>
        {include file='field.tpl' name='username' label='Username'}
        <div>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" value="" class="text">
        </div>
        {include file='field.tpl' name='pass2' label='Confirm Password' type='password'}
    </fieldset>
    <fieldset>
        <legend>Personal Information</legend>
        {include file='field.tpl' name='email' label='Email Address'}
        {include file='field.tpl' name='fname' label='First Name'}
    </fieldset>
    {if $auth->checkRole('Administrator') && $auth->getAuthData('id')!=$object->id}
    <fieldset>
        <legend>User Permissions</legend>
        <div>
            <label for="roles">User Role</label><br>
            {html_options id='roles' name='roles'
            values=$roles output=$roles selected=$object->roles}
        </div>
    </fieldset>
    {/if}
    <p>
        <button type="submit" class="button positive" disabled="disabled">
            <img src="{$root}styles/blueprint/icons/tick.png" alt=""/> Update
        </button>
    </p>
    <div class="clearBoth" style="clear:both;"></div>
</form>
