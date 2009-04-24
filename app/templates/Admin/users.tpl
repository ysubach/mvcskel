<h2>Users list</h2>
{include file="pager.tpl"}
<table>
<tbody>
    <tr>
        <th>ID {include file='sorter.tpl' id='id'}</th>
        <th>Username {include file='sorter.tpl' id='un'}</th>
        <th>First name {include file='sorter.tpl' id='fn'}</th>
        <th>Email {include file='sorter.tpl' id='em'}</th>
    </tr>
    {section name="i" loop=$objects}
    <tr class="{cycle values='odd,even'}">
        <td>{$objects[i]->id}</td>
        <td>{$objects[i]->username}</td>
        <td>{$objects[i]->fname}</td>
        <td>{$objects[i]->email}</td>
    </tr>
    {/section}
</tbody>
</table>
{include file="pager.tpl"}