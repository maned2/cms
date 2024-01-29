<?php
/**
 * Created by Yakushev Roman.
 * User: Roman
 * Date: 23.01.15
 * Time: 16:39
 * To more info see: http://ykushev.ru/
 */

defined('_YRNEXEC') or die;

//$ava = $this->tmpurl.'images/avatar.jpg';

?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Bootstrap -->
        <link href="<?php echo $this->tmpurl;?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo $this->tmpurl;?>css/bootstrap-theme.min.css" rel="stylesheet">
        <link href="<?php echo $this->tmpurl;?>css/theme.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/">sitename</a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <?php $this->menu('main_menu');?>
                    <ul class="nav navbar-nav navbar-right">
                        <?php $this->module('user');?>
                    </ul>
                </div><!--/.nav-collapse -->

            </div>
        </nav>

    <?php //TODO: 404 страница, вынести контейнер в отдельный файд?>
        <div class="container">

            <div class="col-sm-8"><?php //а если колонки справа не будет?....?>
                <?php $this->alerts();?>
                <?php $this->component(); ?>
                <pre>UTC+0 = <?php $tmpdatetime = new DateTime();
                    echo $tmpdatetime->format(DATETIMEFORMAT);?>
                </pre>
            </div>
            <aside class="col-sm-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Panel title</h3>
                    </div>
                    <div class="panel-body">
                        Panel content
                    </div>
                </div>
            </aside>

        </div>

        <footer class="footer">
            <div class="container">
                <p>&copy; <?php echo date('Y'); ?> sitename</p>
            </div>
        </footer>
        <pre>
        <?php
        $json = '{"core.login.site":{"6":1,"2":1},"core.login.admin":{"6":1},"core.login.offline":[],"core.admin":{"8":1},"core.manage":{"7":1},"core.create":{"6":1,"3":1},"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}';
        //var_dump(json_decode($json,true));
        ?>
        </pre>

        <?php $this->debug('<pre>','</pre>');?>

        <!-- jQuery (necessary for Bootstraps JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="<?php echo $this->tmpurl;?>js/bootstrap.min.js"></script>
        <script src="<?php echo $this->tmpurl;?>js/theme.js"></script>
     </body>
</html>