<!--{* Show errors *}-->
<!--{* $Id: errors.tpl,v 1.4 2005/03/26 10:14:58 subach Exp $ *}-->
<!--{strip}-->
<!--{if count($errors)>0}-->
<h2>There are errors</h2>
<table cellspacing="0" class="error">
  <tbody>
    <tr>
      <td>
	<!--{foreach key=context item=currentList from=$errors}-->
	<!--{foreach item=error from=$currentList}-->
	<strong><!--{$context}--></strong>: <!--{$error}--><br>
	<!--{/foreach}-->
	<!--{/foreach}-->
      </td>
    </tr>
  </tbody>
</table>
<!--{/if}-->
<!--{/strip}-->
<!--{* Keep this comment at the end of the file
Local variables:
sgml-parent-document:("~/.xemacs/.html_header" "html" "table")
End:
*}-->
