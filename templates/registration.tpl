<div align="center">
  <form action="index.php" method="post">
    <input type="hidden" name="action" value="Registration">
    <table class="form" cellspacing="0">
      <tbody>
	<tr>
	  <td colspan="2">
	    <!--{include file="errors.tpl"}-->
	  </td>
	</tr>
	<tr>
	  <td><span class="rq">*</span>Username:</td>
	  <td><input class="input" type="text" name="login" value="<!--{$object->login}-->"></td>
	</tr>
	<tr>
	  <td><span class="rq">*</span>Password:</td>
	  <td><input class="input" type="password" name="password"></td>
	</tr>
	<tr>
	  <td><span class="rq">*</span>Re-type Password:</td>
	  <td><input class="input" type="password" name="retype_password"></td>
	</tr>
	<tr>
	  <td><span class="rq">*</span>Email:</td>
	  <td><input class="input" type="text" name="email" value="<!--{$object->email}-->"></td>
	</tr>
	<tr>
      <td>&nbsp;</td>
	  <td><input class="but" type="submit" value="Registration">
      <p><span class="rq">*</span> <span class="comment">required fields</span></p>
      </td>
	</tr>
      </tbody>
    </table>
  </form>
</div>
