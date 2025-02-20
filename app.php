<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once __DIR__ . "/required.php";

if ($_SESSION['loggedin'] != true) {
    header('Location: index.php');
    die("Session expired.  Log in again to continue.");
}

require_once __DIR__ . "/pages.php";

$pageid = "home";
if (!empty($_GET['page'])) {
    $pg = strtolower($_GET['page']);
    $pg = preg_replace('/[^0-9a-z_]/', "", $pg);
    if (array_key_exists($pg, PAGES) && file_exists(__DIR__ . "/pages/" . $pg . ".php")) {
        $pageid = $pg;
    } else {
        $pageid = "404";
    }
}

header("Link: <static/fonts/Roboto.css>; rel=preload; as=style", false);
header("Link: <static/css/bootstrap.min.css>; rel=preload; as=style", false);
header("Link: <static/css/material-color/material-color.min.css>; rel=preload; as=style", false);
header("Link: <static/css/app.css>; rel=preload; as=style", false);
header("Link: <static/css/svg-with-js.min.css>; rel=preload; as=style", false);
header("Link: <static/js/fontawesome-all.min.js>; rel=preload; as=script", false);
header("Link: <static/js/jquery-3.3.1.min.js>; rel=preload; as=script", false);
header("Link: <static/js/bootstrap.bundle.min.js>; rel=preload; as=script", false);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title><?php echo $SETTINGS['site_title']; ?></title>

        <link rel="icon" href="static/img/logo.svg">

        <link href="static/css/bootstrap.min.css" rel="stylesheet">
        <link href="static/css/material-color/material-color.min.css" rel="stylesheet">
        <link href="static/css/app.css" rel="stylesheet">
        <link href="static/css/svg-with-js.min.css" rel="stylesheet">
        <script nonce="<?php echo $SECURE_NONCE; ?>">
            FontAwesomeConfig = {autoAddCss: false}
        </script>
        <script src="static/js/fontawesome-all.min.js"></script>
        <?php
        // custom page styles
        if (isset(PAGES[$pageid]['styles'])) {
            foreach (PAGES[$pageid]['styles'] as $style) {
                echo "<link href=\"$style\" rel=\"stylesheet\">\n";
                header("Link: <$style>; rel=preload; as=style", false);
            }
        }
        ?>
    </head>
    <body>

        <?php
// Alert messages
        if (!empty($_GET['msg'])) {
            if (array_key_exists($_GET['msg'], MESSAGES)) {
                // optional string generation argument
                if (empty($_GET['arg'])) {
                    $alertmsg = $Strings->get(MESSAGES[$_GET['msg']]['string'], false);
                } else {
                    $alertmsg = $Strings->build(MESSAGES[$_GET['msg']]['string'], ["arg" => strip_tags($_GET['arg'])], false);
                }
                $alerttype = MESSAGES[$_GET['msg']]['type'];
                $alerticon = "square-o";
                switch (MESSAGES[$_GET['msg']]['type']) {
                    case "danger":
                        $alerticon = "times";
                        break;
                    case "warning":
                        $alerticon = "exclamation-triangle";
                        break;
                    case "info":
                        $alerticon = "info-circle";
                        break;
                    case "success":
                        $alerticon = "check";
                        break;
                }
            } else {
                // We don't have a message for this, so just assume an error and escape stuff.
                $alertmsg = htmlentities($Strings->get($_GET['msg'], false));
                $alerticon = "times";
                $alerttype = "danger";
            }
            echo <<<END
            <div class="row justify-content-center" id="msg-alert-box">
                <div class="col-11 col-sm-6 col-md-5 col-lg-4 col-xl-4">
                    <div class="alert alert-dismissible alert-$alerttype mt-2 p-0 border-0 shadow">
                        <div class="p-2 pl-3">
                            <button type="button" class="close">&times;</button>
                            <i class="fas fa-$alerticon"></i> $alertmsg
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-$alerttype w-0" id="msg-alert-timeout-bar"></div>
                        </div>
                    </div>
                </div>
            </div>
END;
        }
        ?>

        <?php
        // Adjust as needed
        $navbar_breakpoint = "sm";

        // For mobile app
        echo "<script nonce=\"$SECURE_NONCE\">var navbar_breakpoint = \"$navbar_breakpoint\";</script>"
        ?>
        <nav class="navbar navbar-expand-<?php echo $navbar_breakpoint; ?> navbar-dark bg-blue fixed-top">
            <button class="navbar-toggler my-0 py-0" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                <!--<i class="fas fa-bars"></i>-->
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand py-0 mr-auto" href="app.php">
                <img src="static/img/logo.svg" alt="" class="d-none d-<?php echo $navbar_breakpoint; ?>-inline brand-img py-0" />
                <?php echo $SETTINGS['site_title']; ?>
            </a>

            <div class="collapse navbar-collapse py-0" id="navbar-collapse">
                <div class="navbar-nav mr-auto py-0">
                    <?php
                    $curpagefound = false;
                    foreach (PAGES as $id => $pg) {
                        if (isset($pg['navbar']) && $pg['navbar'] === TRUE) {
                            if ($pageid == $id) {
                                $curpagefound = true;
                                ?>
                                <span class="nav-item py-<?php echo $navbar_breakpoint; ?>-0 active">
                                    <?php
                                } else {
                                    ?>
                                    <span class="nav-item py-<?php echo $navbar_breakpoint; ?>-0">
                                        <?php
                                    }
                                    ?>
                                    <a class="nav-link py-<?php echo $navbar_breakpoint; ?>-0" href="app.php?page=<?php echo $id; ?>">
                                        <?php
                                        if (isset($pg['icon'])) {
                                            ?><i class="<?php echo $pg['icon']; ?> fa-fw"></i> <?php
                                        }
                                        $Strings->get($pg['title']);
                                        ?>
                                    </a>
                                </span>
                                <?php
                            }
                        }
                        ?>
                </div>
                <div class="navbar-nav ml-auto py-0" id="navbar-right">
                    <span class="nav-item py-<?php echo $navbar_breakpoint; ?>-0">
                        <a class="nav-link py-<?php echo $navbar_breakpoint; ?>-0" href="<?php echo $SETTINGS['accounthub']['home']; ?>">
                            <i class="fas fa-user fa-fw"></i><span>&nbsp;<?php echo $_SESSION['realname'] ?></span>
                        </a>
                    </span>
                    <span class="nav-item mr-auto py-<?php echo $navbar_breakpoint; ?>-0">
                        <a class="nav-link py-<?php echo $navbar_breakpoint; ?>-0" href="action.php?action=signout">
                            <i class="fas fa-sign-out-alt fa-fw"></i><span>&nbsp;<?php $Strings->get("sign out") ?></span>
                        </a>
                    </span>
                </div>
            </div>
        </nav>

        <div class="container" id="main-content">
            <div>
                <?php
                include_once __DIR__ . '/pages/' . $pageid . ".php";
                ?>
            </div>
            <div class="footer">
                <?php echo $SETTINGS['footer_text']; ?><br />
                Copyright &copy; <?php echo date('Y'); ?> <?php echo $SETTINGS['copyright']; ?>
            </div>
        </div>
        <script src="static/js/jquery-3.3.1.min.js"></script>
        <script src="static/js/bootstrap.bundle.min.js"></script>
        <script src="static/js/app.js"></script>
        <?php
// custom page scripts
        if (isset(PAGES[$pageid]['scripts'])) {
            foreach (PAGES[$pageid]['scripts'] as $script) {
                echo "<script src=\"$script\"></script>\n";
                header("Link: <$script>; rel=preload; as=script", false);
            }
        }
        ?>
    </body>
</html>