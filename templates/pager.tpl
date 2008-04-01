<!--{* Pager for user area *}-->
<!--{* $Id: pager.tpl,v 1.4 2005/03/26 10:14:58 subach Exp $ *}-->

<!--{if $pagesCount > 0}-->            
<table class="pager" cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td nowrap="nowrap">
	Page [<!--{math equation="x + 1" x=$currentPage}--> of <!--{$pagesCount}-->]&nbsp;&nbsp;
      </td>
      <!--{if $pagesCount > 1}-->      
      <!--{strip}-->
      <td>
	<!--{if $currentPage != 0}-->
	  <a href="<!--{$previousPageLink}-->">&laquo; prev</a><!--{/if}-->
	<!--{section name=pg loop=$pageLinks}-->
	&nbsp;&nbsp;
	<!--{if $pageLinks[pg].link == ''}-->
	<!--{$pageLinks[pg].name}-->
	<!--{else}-->
	<!--{if $currentPage == $pageLinks[pg].name}--><span class="current">
	  <!--{else}--><a class="num" href="<!--{$pageLinks[pg].link}-->"><!--{/if}-->
	<!--{math equation="x + 1" x=$pageLinks[pg].name}-->
	<!--{if $currentPage == $pageLinks[pg].name}--></span>
  	  <!--{else}--></a><!--{/if}-->
	<!--{/if}-->
	<!--{/section}-->
	&nbsp;&nbsp;
	<!--{if $currentPage < ($pagesCount-1)}-->
	  <a href="<!--{$nextPageLink}-->">next &raquo;</a><!--{/if}-->
      </td>
      <!--{/strip}-->
      <!--{/if}-->      
    </tr>
  </tbody>
</table>
<!--{/if}-->

<!--{* Keep this comment at the end of the file
Local variables:
sgml-parent-document:("~/.xemacs/.html_header" "body" ())
End:
*}-->
