<!--{* {{$table}} list *}-->

<!--{include file="errors.tpl"}-->

<!--{include file="add-pager.tpl" item="{{$table}}"}-->

  <table class="list" cellspacing="1">
    <tbody>
      <tr>
        {{section name="field" loop=$properties}}<!--{include file="th.tpl" viewName="{{$properties[field]->name|capitalize}}" sorting="true" alias="{{$properties[field]->name}}" sortLink="`$columns.{{$properties[field]->name}}.sortLink`"}-->
        {{/section}}<th>Actions</th>
      </tr>
      <!--{section name="list" loop=$objects}-->
      <tr class="<!--{cycle values="odd,even"}-->">
        {{section name="field" loop=$properties}}<td><!--{$objects[list]->{{$properties[field]->name}}}--></td>
        {{/section}}<td width="1%" class="actions">   
          <a href="<!--{url _name="{{$table}}/AddEdit" id=$objects[list]->id}-->">edit</a>|
      	  <a href="<!--{url _factory=Action _name="{{$table}}/Delete" id=$objects[list]->id}-->" onClick="return confirm('Please confirm {{$table}} deletion!');">delete</a>	
	    </td>
      </tr>
      <!--{sectionelse}-->
      <tr>
	    <td colspan="{{$smarty.section.field.total+1}}" class="no-items">Empty {{$table|lower}} set.</td>
      </tr>
      <!--{/section}-->
    </tbody>
  </table>

<!--{include file="add-pager.tpl" item="{{$table}}"}-->
