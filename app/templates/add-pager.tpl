<!--{assign var="name" value="`$item`/AddEdit"}-->
<!--{if $linkTitle==""}-->
    <!--{assign var="linkTitle" value="Add `$item`"}-->
<!--{/if}-->    
<table class="add" cellspacing="0">
  <tr>
    <td>
      <a href="<!--{url _name=$name new=1}-->"><!--{$linkTitle}--></a>
    </td>
    <td align="right">
      <!--{include file="pager.tpl"}-->
    </td>
  </tr>
</table>