<form id="formSignup" class="prepend-3 span-11 append-3" action="{url to=$form->getSourceAction()}" method="post">
    <input type="hidden" name="mvcskel_form_id" value="{$form->getId()}" />
    <fieldset>
        <legend>Fill in the Registration Form</legend>
        {include file='field.tpl' name='username' label='Username' type='text' class='text'}
        {include file='field.tpl' name='password' label='Password' type='password' class='text'}
        {include file='field.tpl' name='pass2' label='Confirm Password' type='password' class='text'}
    </fieldset>
    <fieldset>
        <legend>Personal Information</legend>
        {include file='field.tpl' name='email' label='Email Address' type='text' class='text'}
        {include file='field.tpl' name='fname' label='First Name' type='text' class='text'}
    </fieldset>
    <fieldset>
        <legend>Spam Protection</legend>
        <div>
            <label for="captcha">Type the code shown</label>
            <input type="text" class="text" id="captcha" name="captcha" value="{$smarty.request.captcha}">
            <img class="captcha" id="captchaImage" src="{url to='Signup/Captcha'}" alt="Type the code shown"><br>
            {if $form->haveError('captcha')}<div class="error">{$form->getError('captcha')}</div>{/if}
            <a id="captchaChange" href="javascript:">change image</a>

        </div>
    </fieldset>
    <p>
        <button type="submit" class="button positive">
            <img src="{$root}styles/blueprint/icons/tick.png" alt=""/> Join
        </button>
    </p>
</form>
