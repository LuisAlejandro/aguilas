<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head profile="http://gmpg.org/xfn/11">
        <title><?= $app_name ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="robots" content="noindex,nofollow" />
        <link rel="shortcut icon" href="themes/<?= $app_theme ?>/images/favicon.ico" />
        <link rel='stylesheet' type='text/css' href='themes/<?= $app_theme ?>/css/style.css' media='screen' />
        <script type="text/javascript" src="themes/<?= $app_theme ?>/js/jquery.js"></script>
        <script type="text/javascript" src="themes/<?= $app_theme ?>/js/easing.js"></script>
        <script type="text/javascript" src="themes/<?= $app_theme ?>/js/lavalamp.js"></script>
        <script type="text/javascript" src="themes/<?= $app_theme ?>/js/validation.js"></script>
        <script type="text/javascript" src="themes/<?= $app_theme ?>/js/ajax.js"></script>
    </head>
    <body>
        <div id="globalWrapper">
            <div id="column-content">
                <div id="portal-top">
                    <ul id="p-personal">
                        <li>
                            <a href="NewUserForm.php">
                                <?= _("NEWUSER") ?>
                            </a>
                        </li>
                        <li>
                            <a href="ChangePasswordForm.php">
                                <?= _("CHANGEPASSWORD") ?>
                            </a>
                        </li>
                        <li>
                            <a href="ResetPasswordForm.php">
                                <?= _("RESETPASSWORD") ?>
                            </a>
                        </li>
                        <li>
                            <a href="ForgotUsernameForm.php">
                                <?= _("REMINDUSER") ?>
                            </a>
                        </li>
                        <li>
                            <a href="DeleteUserForm.php">
                                <?= _("DELETEUSER") ?>
                            </a>
                        </li>
                        <li>
                            <a href="EditProfileForm.php">
                                <?= _("EDITPROFILE") ?>
                            </a>
                        </li>
                        <li>
                            <a href="Browse.php">
                                <?= _("BROWSEUSERS") ?>
                            </a>
                        </li>
                    </ul>
                </div>
                <div id="portal-searchbox">
                    <div id="p-logo">
                        <a href="<?= $app_url ?>"></a>
                    </div>
                    
                    <form action="Search.php" id="searchform">
                        <div class="LSBox">
                            <div id="clock">
                                <?= _("SEARCH:FIELD:DESCRIPTION") ?>
                            </div>
                            <input id="searchInput" value="<?= _("SEARCHUSER") ?>" name="searchInput" type="text" onfocus="this.value=(this.value=='<?= _("SEARCHUSER") ?>') ? '' : this.value;" onblur="this.value=(this.value=='') ? '<?= _("SEARCHUSER") ?>' : this.value;"  />
                        </div>
                    </form>
                </div>
            </div>
            
            <div id="menu">
                <ul id="portal-globalnav">
                    <?php
                        foreach ( $app_links as $key => $value ) {
                            echo '<li><a href="' . $value . '">' . $key . '</a></li>';
                            }
                    ?>
                </ul>
            </div>
            
            <div id="content">
                <div class="visualClear"></div>
