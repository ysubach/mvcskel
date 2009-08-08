<h3>Summary Information</h3>
<p>
    Total users: {$totalUsers}<br>
    Last day unique logins: {$uniqueLogins}
</p>

<h3>New Users of This Week</h3>
<table>
    {foreach from=$newUsers item='newUser'}
    <tr>
        <td>{$newUser->id}</td>
        <td>{$newUser->email}</td>
        <td>{$newUser->fname}</td>
        <td>{$newUser->registrationDT}</td>
    </tr>
    {/foreach}
</table>
