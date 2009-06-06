<form action="{url to='Auth/Login'}" method="post" class="prepend-3 span-11 append-3">
    <fieldset>
        <legend>Enter Your Credentials</legend>
        <input type="hidden" name="destination" value="{$destination}">
        <p>
            <label for="username">Username</label><br>
            <input id="username" class="title" type="text" name="username" value="{$smarty.request.username}" />
        </p>
        <p>
            <label for="password">Password</label><br>
            <input id="password" class="title" type="password" name="password" value="{$smarty.request.password}" /><br />
        </p>
        {if $smarty.request.username}
        <div class="error">
            Incorrect username or password.
        </div>
        {/if}
        <p>
            <button type="submit" class="button positive">
                <img src="{$root}styles/blueprint/plugins/buttons/icons/tick.png" alt=""/> Login
            </button>
        </p>
    </fieldset>
</form>