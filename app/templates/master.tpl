
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>MvcSkel</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link rel="icon" href="images/favicon.ico" type="image/x-icon" />
		<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
		<style type="text/css">@import url(styles/style.css,font.css);</STYLE>
	</head>
	<body>
        HeaDER 
        {if $auth->getAuth()}
        Hi, {$auth->getAuthData('fname')}!
        <a href="{url to='Member'}">For user</a>
        {if $auth->checkRole('Administrator')}
        <a href="{url to='Admin'}">For admin</a>
        {/if}
        <a href="{url to='Auth/Logout'}">Logout</a>
        {else}
        <a href="{url to='Auth/Login'}">Login</a>
        {/if}
        
        {include file=$bodyTemplate}
        
        fOOTER
    </body>
</html>
