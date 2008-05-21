<div align="center">
  <form action="index.php" method="post">
    <input type="hidden" name="action" value="ForgetPassword">    
    <table>
      <tbody>
	<tr>
	  <td colspan="2">
	    <!--{include file="errors.tpl"}-->
	  </td>
	</tr>
	<tr>
	  <td colspan="2">
	    Your password will be changed, and sent on your email
	  </td>
	</tr>
	<tr>
	  <td>
	    Please enter your login:
	  </td>
	  <td>
	    <input type="text" name="login" value="<!--{$forgetLogin}-->">
	  </td>
	</tr>
	<tr>
	  <td colspan="2" align="center">
	    <input class="but" type="submit" value="Process">
	  </td>
	</tr>
      </tbody>
    </table>
  </form>
</div>
