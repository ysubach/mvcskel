<!--{strip}-->
<!--{section name="col" loop=$columns}-->
<th>
  <a href="<!--{$columns[col].sortLink}-->"><!--{$columns[col].view_name}--></a>
  <!--{if $sortColumn==$columns[col].alias}-->
    <!--{if $sortDirection==1}--><img src="image/asc_order.gif">
    <!--{else}--><img src="image/desc_order.gif"<!--{/if}-->
  <!--{/if}-->
</th>
<!--{/section}-->
<!--{/strip}-->
