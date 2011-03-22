{strip}
&nbsp;
<a class="sort" href="{url cc=1 sortCol=$id sortDir='asc' page=0}">
    {if $sortCol==$id and $sortDir=='asc'}<span>&dArr;</span>
    {else}&dArr;{/if}
</a>
&nbsp;
<a class="sort" href="{url cc=1 sortCol=$id sortDir='desc' page=0}">
    {if $sortCol==$id and $sortDir=='desc'}<span>&uArr;</span>
    {else}&uArr;{/if}
</a>
{/strip}