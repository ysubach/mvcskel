{if $auth->getAuth()}
Welcome, <a href="{url to='Member'}">{$auth->getAuthData('fname')}</a>
<a href="{url to='Auth/Logout'}">Logout</a>
{else}
<a href="{url to='Auth/Login'}">Sign In</a>
<a href="{url to='Signup'}">Sign Up</a>
<a href="{url to='Signup/ForgotPassword'}">Forgot Password?</a>
{/if}
