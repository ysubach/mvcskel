<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <title>{$title} - MvcSkel Startup Application</title>

        <link rel="icon" href="{$root}images/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="{$root}images/favicon.ico" type="image/x-icon" />

        <link rel="stylesheet" href="{$root}styles/blueprint/screen.css,style.css" type="text/css" media="screen">

        <!--[if lt IE 8]><link rel="stylesheet" href="{$root}styles/blueprint/ie.css" type="text/css" media="screen, projection"><![endif]-->

        <script type="text/javascript" src="{$root}js/prototype-1.6.0.3.js,init.js,SignupPage.js"></script>
    </head>
    <body>
        <div class="container">

            <div id="header" class="span-17">
                <h1><a id="logo" href="{$root}">MvcSkel Startup Application</a></h1>
            </div>
            <div id="mainlinks" class="span-7 last">
                {if $auth->getAuth()}
                Welcome, <a href="{url to='Member'}">{$auth->getAuthData('fname')}</a>.
                <a href="{url to='Auth/Logout'}">Logout</a>
                {else}
                <a href="{url to='Auth/Login'}">Sing In</a>
                <a href="{url to='Signup'}">Sing Up</a>
                <a href="{url to='Member/ForgotPassword'}">Forgot Password?</a>
                {/if}
            </div>

            <hr>
            <div id="header" class="span-24 last">
                <h2 class="alt">{$title}</h2>
            </div>

            <div id="content" class="span-17 colborder">
                {include file=$bodyTemplate}
            </div>
            <div id="sidebar" class="span-6 last">
                <div id="checkEnvironment">
                    <div class="box">
                        <a href="{url to='Main/ShowConfig' v=1}">Show Current Config</a><br>
                        <a href="{url to='Main/PhpInfo' v=1}">Call phpinfo()</a>
                    </div>
                </div>
                <div id="interestingLinks">
                    <h3 class="caps">Useful Links</h3>
                    <div class="box">
                        <a href="http://www.mvcskel.org/api">MvcSkel API Docs</a><br>
                        <a href="http://www.mvcskel.org/doc:start">MvcSkel Tutorials</a><br>
                        <a href="http://code.google.com/p/mvcskel/downloads/list">Download Page</a>
                    </div>
                </div>
                <div id="usedLibraries">
                    <h3 class="caps">Used Libraries</h3>
                    <div class="box">
                        <a href="http://www.doctrine-project.org">Doctrine ORM</a><br>
                        <a href="http://wiki.github.com/joshuaclayton/blueprint-css">Blueprint CSS Framework</a><br>
                        <a href="http://www.smarty.net/">Smarty Template Engine</a><br>
                        <a href="http://pear.php.net/">PEAR Libraries</a>
                    </div>
                </div>
            </div>

            <hr class="space">
            <div id="footer" class="span-24 last">
                Your Company &copy; {$smarty.now|date_format:"%Y"}
            </div>
        </div>
    </body>
</html>
