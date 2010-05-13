{if $auth->checkRole('Administrator')}
<h3 class="caps">Admin Section</h3>
<div class="box">
    <a href="{url to='Admin'}">Dashboard</a><br>
    <a href="{url to='Admin/Users'}">Users</a>
</div>
{/if}
