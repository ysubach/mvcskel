{if $pager}
<div class="pager">
    <div class="pagerPart">Pages {$currentPageText} of {$pagesTotal}</div>
    <form class="pagerPart" action="{url cc=1}" method="POST">
        Go to Page
        {html_options name='page' selected=$currentPage
        options=$pager.pages
        onchange="javascript:form.submit();"}
    </form>
    {if $pager.prev.page===0 || $pager.prev.page>0}
    <div class="pagerPart">
        <a href="{url cc=1 page=$pager.prev.page}">previous page</a>
    </div>
    {/if}
    {if $pager.next.page>0}
    <div class="pagerPart">
        <a href="{url cc=1 page=$pager.next.page}">next Page</a>
    </div>
    {/if}
</div>
{else}
<div class="vspace"></div>
{/if}
