<!--{* User add/edit page *}-->

<div align="center">
<form action="index.php" method="post">
  <input type="hidden" name="action" value="User/AddEdit">
  <table class="form" cellspacing="0">
    <tbody>
      <tr>
        <td colspan="2" class="no-padding">
          <!--{include file="errors.tpl"}-->
        </td>
      </tr>
      <tr>
	    <td class="fieldName">Login</td>
    	<td>
		  <input type="text" name="login" value="<!--{$object->login}-->" size="255">
        </td>
      </tr>
	  <tr>
	    <td class="fieldName">Password</td>
    	<td>
		  <input type="password" name="password" value="" size="255">
        </td>
      </tr>
	  <tr>
	    <td class="fieldName">Retype Password</td>
    	<td>
		  <input type="password" name="retype_password" value="" size="255">
        </td>
      </tr>
	  <tr>
	    <td class="fieldName">Rights</td>
    	<td>
		  <!--{html_options multiple="multiple" size=count($roles) output=$roles values=$roles name="rights[]" selected=$object->getRights()}-->
        </td>
      </tr>
	  <tr>
	    <td class="fieldName">Email</td>
    	<td>
		  <input type="text" name="email" value="<!--{$object->email}-->" size="255">
        </td>
      </tr>
	  <tr>
	    <td class="fieldName">Fname</td>
    	<td>
		  <input type="text" name="fname" value="<!--{$object->fname}-->" size="255">
        </td>
      </tr>
	  <tr>
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