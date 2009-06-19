<form action="{url to='Auth/Login'}" method="post" class="prepend-3 span-11 append-3">
    <fieldset>
        <legend>Enter Your Credentials</legend>
        <input type="hidden" name="destination" value="{$destination}">
        <div>
            <label for="username">Username</label><br>
            <input id="username" class="title" type="text" name="username" value="{$smarty.request.username}">
        </div>
        <div>
            <label for="password">Password</label><br>
            <input id="password" class="title" type="password" name="password" value=""><br>
            {if $smarty.request.username}
            <div class="error">
                Incorrect username or password.
            </div>
            {/if}
        </div>
    </fieldset>
    
    <p>
        <button type="submit" class="button positive">
            <img src="{$root}styles/blueprint/icons/tick.png" alt=""/> Login
        </button>
    </p>
</form>