{foreach from=$meta item='mv' key='mk'}
    {if $mk!='title' && $mv}
        <meta name="{$mk}" content="{$mv}" />
    {/if}
{/foreach}
