<h1>Login</h1>
<h2>user/user OR admin/admin</h2>
<form action="{url to='Auth/Login'}" method="post">
    <input type="hidden" name="destination" value="{$destination}">
    Username: <input type="text" name="username" value="{$smarty.request.username}" /><br />
    Password: <input type="password" name="password" value="{$smarty.request.password}" /><br />
    <input type="submit" value="Login"/>
</form>