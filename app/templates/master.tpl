<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>MvcSkel</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link rel="icon" href="{$root}images/favicon.ico" type="image/x-icon" />
		<link rel="shortcut icon" href="{$root}images/favicon.ico" type="image/x-icon" />
		<link rel="stylesheet" href="{$root}styles/blueprint/screen.css" type="text/css" media="screen, projection">
        <link rel="stylesheet" href="{$root}styles/blueprint/print.css" type="text/css" media="print">    
        <!–[if IE]><link rel="stylesheet" href="{$root}styles/blueprint/lib/ie.css" type="text/css" media="screen, projection"><![endif]–>
	</head>
	<body>
        <div class="container">
            <div class="header column span-24">
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
             </div>
             <div class="column span-24">
                {include file=$bodyTemplate}
             </div>
             <div class="footer column span-24">
                 fOOTER
             </div>
        </div>
    </body>
</html>
