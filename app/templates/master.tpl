<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        <title>{$title|default:$meta.title}</title>
        {insert name='render' file='meta.tpl'}

        <link rel="icon" href="{$root}images/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="{$root}images/favicon.ico" type="image/x-icon" />

        <link rel="stylesheet" href="{$root}min/?g=css&v={$version}" type="text/css" media="screen">

        <!--[if lt IE 8]><link rel="stylesheet" href="{$root}min/g=css_ie&v={$version}" type="text/css" media="screen, projection"><![endif]-->

        <script type="text/javascript">
            var mvcskel_root = '{$root}';
        </script>
        <script type="text/javascript" src="{$root}min/?g=js&v={$version}"></script>
    </head>
    <body>
        <div class="container">

            <div id="header" class="span-17">
                <h1><a id="logo" href="{$root}">MvcSkel Startup Application</a></h1>
            </div>
            <div id="mainlinks" class="span-7 last">
                {insert name='render' file='headerLinks.tpl'}
            </div>

            <hr>
            <div class="span-24 last">
                <h2 class="alt">{$title}</h2>
            </div>

            <div id="content" class="span-17 colborder">
                {include file=$bodyTemplate}
            </div>
            <div id="sidebar" class="span-6 last">
                {insert name=render file=adminSection.tpl}

                <h3 class="caps">Useful Links</h3>
                <div class="box">
                    <a href="http://code.google.com/p/mvcskel/">Project Home Page</a>
                </div>

                <h3 class="caps">Used Libraries</h3>
                <div class="box">
                    <a href="http://www.doctrine-project.org">Doctrine ORM</a><br>
                    <a href="http://wiki.github.com/joshuaclayton/blueprint-css">Blueprint CSS Framework</a><br>
                    <a href="http://www.smarty.net/">Smarty Template Engine</a><br>
                    <a href="http://pear.php.net/">PEAR Libraries</a><br>
                    <a href="http://code.google.com/p/minify/">minify</a><br>
                    <a href="http://jquery.com/">jQuery</a>
                </div>
            </div>

            <hr class="space">
            <div id="footer" class="span-24 last">
                Your Company &copy; {$smarty.now|date_format:"%Y"}
            </div>
        </div>
    </body>
</html>
