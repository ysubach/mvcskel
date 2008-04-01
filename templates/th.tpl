<!--{strip}-->
<th>
  <!--{* Enable sorting by default *}-->
  <!--{if $sorting!="false"}-->
  <a href="<!--{$columns.$alias.sortLink}-->"><!--{$viewName}--></a>
  <!--{if $sortColumn==$alias}-->
    &nbsp;
    <!--{if $sortDirection==1}-->
      <img src="pix/asc_order.gif" border="0">
    <!--{else}-->
      <img src="pix/desc_order.gif" border="0">
    <!--{/if}-->
  <!--{/if}-->
  <!--{else}-->
  <!--{$viewName}-->
  <!--{/if}-->
</th>
<!--{/strip}-->
