<?php
    error_reporting(E_ALL &  ~E_NOTICE);
    session_start();
    
    $salt = 'super_secret_salt';
    
    if ($_POST['from_login_form'] == 1) {
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');
    
        if (!$username && !$password) {
            $loginError = "Please enter a username and password.";
        }
        if ($username && !$password) {
            $loginError = "Please enter a password.";
        }
        if (!$username && $password) {
            $loginError = "No username entered.";
        }
    } else {
        $loginError = NULL;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Submission Tracker
        </title>
        <script type='text/javascript' src='bower_components/jquery/dist/jquery.min.js'></script>
    </head>
    
    <body>
        <?php include 'static/header.php';?>
        
        <div class='mainWrapper' <?php if ($loginError) {echo "style='opacity: .3'";} ?>>
            <h1>Hi there!</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do 
                eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut 
                enim ad minim veniam, quis nostrud exercitation ullamco laboris 
                nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor 
                in reprehenderit in voluptate velit esse cillum dolore eu 
                fugiat nulla pariatur. Excepteur sint occaecat cupidatat non 
                proident, sunt in culpa qui officia deserunt mollit anim id est 
                laborum.</p>
            </div>
            
            <div class='loginForm' id='loginWindow' <?php if ($loginError) {
                    echo "style='display: block'";} ?>>
                <form method='post' action='index.php'>
                    <label for='login_username'>Username</label>
                    <input type='text' id='login_username' name='username' placeholder='Username'><br>
                    <label for='login_password'>Password</label>
                    <input type='password' id='login_password' name='password'><br>
                    <input type='submit' id='login_submit' name='submit' value='Log In'>
                    <input type='hidden' name='from_login_form' value='1'>
                </form>
                
                <form method='post' action='index.php'>
                    <input type='hidden' name='from_login_form' value='0'>
                    <input type='submit' id='login_submit' name='submit' value='Cancel'>
                </form>
                
                <span class='warning' id='loginWarning'>
                    <?php echo $loginError; ?>
                </span>
            </div>
        
    </body>
</html>