<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <title>MvcSkel
    <!--{if $title}-->
      &nbsp;-&nbsp;<!--{$title}-->
    <!--{/if}-->
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel="icon" href="pix/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="pix/favicon.ico" type="image/x-icon" />
    <STYLE TYPE="text/css">
      @import url(styles/style.css,font.css);
    </STYLE>
  </head>
  <body>
    <!-- TOP TABLE -->
<div align="center">
<table class="header" cellspacing="0">
  <tr>
    <td>
	  <a href="<!--{url _name='Main'}-->"><img src="pix/logo.png" alt="MvcSkel"></a>
    </td>
	<!--{strip}-->
	<td class="login">
      <!--{if $__auth->getAuth()}-->
		you are logged as '<!--{$__currentUser->login}-->'
		<a href="<!--{url _factory="Action" _name="Logout"}-->">Logout</a>
	  <!--{else}-->
	    <a href="<!--{url _name="Login"}-->">Login</a>&nbsp;|&nbsp;
	    <a href="<!--{url _name="ForgetPassword"}-->">Forget password?</a>&nbsp;|&nbsp;
	    <a href="<!--{url _name="Registration"}-->">Registration</a>
	  <!--{/if}-->
    </td>
	<!--{/strip}-->
  </tr>
</table>
	    
<!--{include file="tabs.tpl"}-->
<table class="content" cellspacing="0">
  <tr>
    <td class="content">
    <h1><!--{$title}--></h1>

<!--{include file=$bodyTemplate}-->

    </td>
  </tr>
</table>
<table class="footer" cellspacing="0">
  <tbody>
    <tr>
      <td class="footer">
        Powered by <a href="http://trac.whirix.com/mvcskel/">MvcSkel</a>.<br />
        Copyright &copy; <a href="http://www.whirix.com/">Whirix Ltd.</a>
      </td>
    </tr>
  </tbody>
</table>
</div>
  </body>
</html>
