<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once("service/models/config.php");
if (isUserLoggedIn()) {
    header("Location: index.php");
    die();
}
?>
<?php
if (!empty($_POST)) {
    $errors = array();
    $login = trim($_POST["login"]);
    $password = trim($_POST["password"]);
    $remember_choice = trim($_POST["remember_me"]);

    //Perform some validation
    //Feel free to edit / change as required
    if ($login == "") {
        $errors[] = lang("ACCOUNT_SPECIFY_USERNAME");
    }
    if ($password == "") {
        $errors[] = lang("ACCOUNT_SPECIFY_PASSWORD");
    }

    //End data validation
    if (count($errors) == 0) {
        //A security note here, never tell the user which credential was incorrect
        if (!loginExists($login)) {
            $errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
        } else {
            $userdetails = fetchUserDetails($login);

            //See if the user's account is activation
            if ($userdetails["active"] == 0) {
                $errors[] = lang("ACCOUNT_INACTIVE");
            } else {
                //Hash the password and use the salt from the database to compare the password.
                $entered_pass = generateHash($password, $userdetails["password"]);

                if ($entered_pass != $userdetails["password"]) {
                    //Again, we know the password is at fault here, but lets not give away the combination incase of someone bruteforcing
                    $errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
                } else {
                    //passwords match! we're good to go'
                    //Construct a new logged in user object
                    //Transfer some db data to the session object
                    $loggedInUser = new loggedInUser();
                    $loggedInUser->email = $userdetails["email"];
                    $loggedInUser->name = $userdetails["name"];
                    $loggedInUser->user_id = $userdetails["id"];
                    $loggedInUser->country_id = $userdetails["country_id"];
                    $loggedInUser->group_id = $userdetails["group_id"];
                    $loggedInUser->hash_pw = $userdetails["password"];
                    $loggedInUser->display_login = $userdetails["login"];
                    $loggedInUser->clean_login = $userdetails["login_clean"];
                    $loggedInUser->remember_me = $remember_choice;
                    $loggedInUser->remember_me_sessid = generateHash(uniqid(rand(), true));
//                    //Update last sign in
//                    $loggedInUser->updatelast_sign_in();
                    $params = array();
                    $params["action_user"] = "LOGIN";
                    $params["description"] = "El usuario se ha autenticado.";
                    createAdminHistoryDataBase($params);

                    if ($loggedInUser->remember_me == 0)
                        $_SESSION["biAdmin"] = $loggedInUser;
                    else if ($loggedInUser->remember_me == 1) {
                        $db->sql_query("INSERT INTO " . $db_table_prefix . "session VALUES('" . time() . "', '" . serialize($loggedInUser) . "', '" . $loggedInUser->remember_me_sessid . "')");
                        setcookie("biAdmin", $loggedInUser->remember_me_sessid, time() + parseLength($remember_me_length));
                    }
                    $website_path = $_SESSION["website_path"];
					if($remember_choice==0 || $remember_choice==1){
                    	header("Location: " . $websiteUrl . "destroySession.php?action=destroy&login=$login&remember_me=$remember_choice&website_path=$website_path");
					}
                    //Redirect to user account page
                    //header("Location: index.php");
                    die();
                }
            }
        }
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Login | <?php echo $websiteName; ?> </title>
        <link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/custom.css" rel="stylesheet" type="text/css" />
        <script  type="text/javascript"  src="js/1.7.1/jquery.min.js"></script>
        <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/bootstrap-carousel.js"></script>
        <script src="assets/js/bootstrap-transition.js"></script>
    </head>
    <body>

        <div class="modal-ish">
            <div class="modal-header">
                <h2>Inicio de Sesion</h2>
            </div>
            <div class="modal-body">     
<?php
if (!empty($_POST)) {
    ?>
    <?php
    if (count($errors) > 0) {
        ?>
                        <div id="errors">
        <?php errorBlock($errors); ?>
                        </div>     
        <?php
    }
}
?> 

                <?php
                if (($_GET['status']) == "success") {

                    echo "<p>Your account was created successfully. Please login.</p>";
                }
                ?>
                <form name="newUser" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                    <p>
                        <label>Usuario:</label>
                        <input type="text"  name="login" />
                    </p>

                    <p>
                        <label>Contrase&ntilde;a:</label>
                        <input type="password" name="password" />
                    </p>

                    <p>
                        <input type="checkbox" name="remember_me" value="1" />	
                        <label><small>&iquest;Recuerdame?</small></label>
                    </p>      
            </div>


            <div class="modal-footer">
                <input type="submit" class="btn btn-primary" name="new" id="newfeedform" value="Iniciar Sesion" />
            </div>
        </div>
        </form>        
        <div class="clear"></div>
        <p style="margin-top:30px; text-align:center;">
            <a href="./forgot-password.php">&iquest;Olvido su Contrase&ntilde;a?</a>
        </p>

    </body>
</html>


