<p>
    Total users: {$totalUsers}<br>
    Last day unique logins: {$uniqueLogins}
</p>

<h3>New Users of This Week</h3>
<table id="newUsers">
    {foreach from=$newUsers item='newUser'}
    <tr class="{cycle values='even,odd' name='userlist'}">
        <td>{$newUser->id}</td>
        <td>{$newUser->email}</td>
        <td>{$newUser->fname}</td>
        <td>{$newUser->registrationDT|date_format}</td>
        <td>{$newUser->lastIP}</td>
    </tr>
    {foreachelse}
    <tr class="{cycle values='even,odd' name='userlist'}">
        <td colspan="5">No new users</td>
    </tr>
    {/foreach}
</table>
