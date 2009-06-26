<form id="formSignup" class="prepend-3 span-11 append-3" action="{url to=$form->getSourceAction()}" method="post">
    <input type="hidden" name="mvcskel_form_id" value="{$form->getId()}" />
    <fieldset>
        <legend>Enter Email address</legend>
        <p>
            Please enter registered email address below. New password
            will be generated and sent to you inbox.
        </p>
        {include file='field.tpl' name='email' label='Email address' type='text' class='text'}
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
            <img src="{$root}styles/blueprint/icons/tick.png" alt=""/> Submit
        </button>
    </p>
</form>
