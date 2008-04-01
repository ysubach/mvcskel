<!--{* Login page *}-->
<!--{* $Id: login.tpl,v 1.3 2005/03/26 10:14:58 subach Exp $ *}-->

<div align="center">
  <form action="index.php" method="post">
    <input type="hidden" name="action" value="Login">
    <input type="hidden" name="destination" value="<!--{$destination}-->">
    <table class="form" cellspacing="0">
      <tbody>
	<tr>
	  <td colspan="2">
	    <!--{include file="errors.tpl"}-->
	  </td>
	</tr>
	<tr>
	  <td>Username:</td>
	  <td><input class="input" type="text" name="username" size="10"></td>
	</tr>
	<tr>
	  <td>Password:</td>
	  <td><input class="input" type="password" name="password" size="10"></td>
	</tr>
	<tr>
      <td>&nbsp;</td>
	  <td><input class="but" type="submit" value="Login">
      <p><a href="<!--{url _name="ForgetPassword"}-->">Forget password?</a></p>
      </td>
	</tr>
      </tbody>
    </table>
  </form>
 </div>
