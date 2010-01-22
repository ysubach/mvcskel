{include file="pager.tpl"}
<table>
    <thead>
        <tr>
            <th>ID {include file='sorter.tpl' id='id'}</th>
            <th>Username {include file='sorter.tpl' id='un'}</th>
            <th>First name {include file='sorter.tpl' id='fn'}</th>
            <th>Email {include file='sorter.tpl' id='em'}</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        {section name="i" loop=$objects}
        <tr class="{cycle values='odd,even'}">
            <td>{$objects[i]->id}</td>
            <td>{$objects[i]->username}</td>
            <td>{$objects[i]->fname}</td>
            <td>{$objects[i]->email}</td>
            <td width="1%" nowrap><a href="{url to=Member id=$objects[i]->id}">edit</a></td>
        </tr>
        {/section}
    </tbody>
</table>
{include file="pager.tpl"}

<button class="button positive" onclick="javascript:location = '{url to=Member new=1}';">
    <img src="{$root}styles/blueprint/icons/tick.png" alt=""/> Add User
</button>