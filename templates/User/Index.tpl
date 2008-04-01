<!--{* User list *}-->

<!--{include file="errors.tpl"}-->

<!--{include file="add-pager.tpl" item="User"}-->

  <table class="list" cellspacing="1">
    <tbody>
      <tr>
        <!--{include file="th.tpl" viewName="Login" sorting="true" alias="login" sortLink="`$columns.login.sortLink`"}-->
        <!--{include file="th.tpl" viewName="Rights" sorting="true" alias="rights" sortLink="`$columns.rights.sortLink`"}-->
        <!--{include file="th.tpl" viewName="Email" sorting="true" alias="email" sortLink="`$columns.email.sortLink`"}-->
        <!--{include file="th.tpl" viewName="Fname" sorting="true" alias="fname" sortLink="`$columns.fname.sortLink`"}-->
        <th>Actions</th>
      </tr>
      <!--{section name="list" loop=$objects}-->
      <tr class="<!--{cycle values="odd,even"}-->">
        <td><!--{$objects[list]->login}--></td>
        <td><!--{$objects[list]->rights}--></td>
        <td><!--{$objects[list]->email}--></td>
        <td><!--{$objects[list]->fname}--></td>
        <td width="1%" class="actions">   
          <a href="<!--{url _name="User/AddEdit" id=$objects[list]->id}-->">edit</a>|
      	  <a href="<!--{url _factory=Action _name="User/Delete" id=$objects[list]->id}-->" onClick="return confirm('Please confirm User deletion!');">delete</a>	
	    </td>
      </tr>
      <!--{sectionelse}-->
      <tr>
	    <td colspan="6" class="no-items">Empty user set.</td>
      </tr>
      <!--{/section}-->
    </tbody>
  </table>

<!--{include file="add-pager.tpl" item="User"}-->