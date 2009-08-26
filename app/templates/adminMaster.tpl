<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <title>{$title} - MvcSkel Startup Application</title>

        <link rel="icon" href="{$root}images/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="{$root}images/favicon.ico" type="image/x-icon" />

        <link rel="stylesheet" href="{$root}styles/blueprint/screen.css,style.css" type="text/css" media="screen">

        <!--[if lt IE 8]><link rel="stylesheet" href="{$root}styles/blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->

        {if $auth->checkAuth()}
        <script type="text/javascript" src="{$root}js/prototype-1.6.0.3.js,init.js,FormUtils.js,Profile.js"></script>
        {else}
        <script type="text/javascript" src="{$root}js/prototype-1.6.0.3.js,init.js,SignupPage.js"></script>
        {/if}
        <script type="text/javascript">
            var mvcskel_root = '{$root}';
        </script>
    </head>
    <body>
        <div class="container">

            <div id="header" class="span-17">
                <h1><a id="logo" href="{$root}">MvcSkel Startup Application</a></h1>
            </div>
            <div id="mainlinks" class="span-7 last">
                {if $auth->getAuth()}
                Welcome, <a href="{url to='Member'}">{$auth->getAuthData('fname')}</a>
                <a href="{url to='Auth/Logout'}">Logout</a>
                {else}
                <a href="{url to='Auth/Login'}">Sing In</a>
                <a href="{url to='Signup'}">Sing Up</a>
                <a href="{url to='Signup/ForgotPassword'}">Forgot Password?</a>
                {/if}
            </div>

            <hr>
            <div id="header" class="span-24 last">
                <h2 class="alt">{$title}</h2>
            </div>

            <div id="sidebar" class="span-4">
                <div class="box">
                    <a href="{url to='Admin'}">Admin Dashboard</a><br>
                    <a href="{url to='Admin/Users'}">User Management</a><br>
                    <a href="{url to='Admin/ShowConfig'}">Show Current Config</a><br>
                    <a href="{url to='Admin/PhpInfo'}">Call phpinfo()</a>
                </div>
            </div>

            <div id="content" class="span-20 last">
                {include file=$bodyTemplate}
            </div>

            <hr class="space">
            <div id="footer" class="span-24 last">
                Your Company &copy; {$smarty.now|date_format:"%Y"}
            </div>
        </div>
    </body>
</html>