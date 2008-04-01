<!--{* {{$table}} add/edit page *}-->

<div align="center">
<form action="index.php" method="post">
  <input type="hidden" name="action" value="{{$table}}/{{$tplName}}">
  <table class="form" cellspacing="0">
    <tbody>
      <tr>
        <td colspan="2" class="no-padding">
          <!--{include file="errors.tpl"}-->
        </td>
      </tr>
      {{section name="field" loop=$properties}}<tr>
	    <td class="fieldName">{{$properties[field]->name|capitalize}}</td>
    	<td>
		  <input type="text" name="{{$properties[field]->name}}" value="<!--{$object->{{$properties[field]->name}}}-->" size="{{$properties[field]->len}}">
        </td>
      </tr>
	  {{/section}}<tr>
	    <td colspan="2" align="center">
	      <input class="but" type="submit" value="Save">
	      <input type="button" class="but" onclick="history.back();" value="Cancel" />
	      <p><span class="rq">*</span> <span class="comment">required fields</span></p>
	    </td>
      </tr>
    </tbody>
  </table>
</form>
</div>
