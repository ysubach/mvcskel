<form id="formProfile" class="prepend-3 span-11 append-3" action="{url to=$form->getSourceAction()}" method="post">
    <input type="hidden" name="mvcskel_form_id" value="{$form->getId()}" />
    <fieldset>
        <legend>Credentials</legend>
        {include file='fieldAjax.tpl' name='username' label='Username' type='text' class='text'}
        <div>
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" value="" class="text">
            <div class="error" id="error-password" style="display:none;"></div>
        </div>
        {include file='fieldAjax.tpl' name='pass2' label='Password repeat' type='password' class='text'}
    </fieldset>
    <fieldset>
        <legend>Personal Information</legend>
        {include file='fieldAjax.tpl' name='email' label='Email address' type='text' class='text'}
        {include file='fieldAjax.tpl' name='fname' label='First name' type='text' class='text'}
    </fieldset>
    {if $auth->checkRole('Administrator') && $auth->getAuthData('id')!=$object->id}
    <fieldset>
        <legend>User Permissions</legend>
        <div>
            <label for="roles">User Role</label><br>
            {html_options id='roles' name='roles'
            values=$roles output=$roles selected=$object->roles}
            <div class="error" id="error-role" style="display:none;"></div>
        </div>
    </fieldset>
    {/if}
    <p>
        <button id="formProfileSubmit" class="button positive" onclick="javascript:return false;">
            <img src="{$root}styles/blueprint/icons/tick.png" alt=""/> Update
        </button>
    </p>
    <div class="clearBoth" style="clear:both;"></div>
    {include file='formStatus.tpl'}
</form>
