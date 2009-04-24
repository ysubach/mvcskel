{strip}
&nbsp;
<a href="{url cc=1 sortCol=$id sortDir='asc' page=0}">
    {if $sortCol==$id and $sortDir=='asc'}<img src="{$root}images/asc_order_current.gif" />
    {else}<img src="{$root}images/asc_order.gif" />{/if}
</a>
&nbsp;
<a href="{url cc=1 sortCol=$id sortDir='desc' page=0}">
    {if $sortCol==$id and $sortDir=='desc'}<img src="{$root}images/desc_order_current.gif" />
    {else}<img src="{$root}images/desc_order.gif" />{/if}
</a>
{/strip}