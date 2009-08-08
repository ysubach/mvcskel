<h3>Summary Information</h3>
<p>
    Total users: {$totalUsers}<br>
    Last day unique logins: {$uniqueLogins}
</p>

<h3>New Users of This Week</h3>
<table id="newUsers">
    {foreach from=$newUsers item='newUser'}
    <tr>
        <td>{$newUser->id}</td>
        <td>{$newUser->email}</td>
        <td>{$newUser->fname}</td>
        <td>{$newUser->registrationDT}</td>
        <td>{$newUser->lastIP}</td>
    </tr>
    {/foreach}
</table>
