<?php
    error_reporting(E_ALL &  ~E_NOTICE);
    session_start();
    
    $salt = 'super_secret_salt';
    
    if ($_POST['from_login_form'] && !$_POST['forgot_PW']) {
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
    } elseif ($_POST['from_login_form'] && $_POST['forgot_PW']) {
        header("Location: forgotPW.php?q=recovery");
    } else {
        $loginError = NULL;
    }
    
    //Error handling from the registration script
    if (isset($_GET['err'])) {
        if ($_GET['err'] == 'pwm') {
            $newUserError = 'Passwords do not match.';
            $passwordError = TRUE;
            
            $errRole = filter_input(INPUT_GET, 'r');
            if ($errRole == 'a') {
                $errRole = 'Author';
            }
            if ($errRole == 'r') {
                $errRole = 'Reviewer';
            }
            $newUserErrorRole = 'Registration: New '.$errRole;
        }
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
        
        <div class='mainWrapper' <?php if ($loginError || $newUserError) {echo "style='opacity: .3'";} ?>>
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
            
            <div class='loginForm' id='loginWindow' <?php //if ($loginError || $newUserError) {
                    //echo "style='display: block'";} ?>>
                <div <?php 
                    if ($newUserError) {
                        echo "style='display: none'";
                    }
                ?>>
                <form method='post' action='index.php'>
                    <label for='login_username'>Username</label>
                    <input type='text' id='login_username' name='username' placeholder='Username'><br>
                    <label for='login_password'>Password</label>
                    <input type='password' id='login_password' name='password'><br>
                    <input type='hidden' name='from_login_form' value='1'>
                    <input type='submit' id='login_submit' name='submit' value='Log In'>
                </form>
                
                <form method='post' action='index.php'>
                    <input type='hidden' name='from_login_form' value='0'>
                    <input type='submit' id='login_submit' name='submit' value='Cancel'>
                </form>
                
                <form method='post' action='index.php'>
                    <input type='hidden' name='from_login_form' value='1'>
                    <input type='hidden' name='forgot_PW' value='1'>
                    <input type='submit' class='forgotPWbutton' id='login_submit' name='submit' 
                              value='Click here if you forgot your password'>
                </form>
                </div>
                <span class='warning' id='loginWarning'>
                    <?php echo $loginError; ?>
                </span>
            </div>

            <!--Registration forms.  Starts hidden. Displayed via JS or 
            error handling in PHP-->
            <div class="loginForm" id="registrationDialog" 
                <?php if ($newUserError) {
                    echo "style='display: block; height: 600px;'";} 
                ?>
            >
                <p id='roleTitle'>
                    <?php if ($newUserError) {
                        echo $newUserErrorRole;
                    } else {
                        echo 'Please select your role:';
                    } 
                    ?>
                </p>
                
                <div class='roleSelection' 
                    <?php 
                        if ($newUserError) {
                            echo "style='display: none'";
                        } 
                    ?>
                >
                    <button class='registrationButton' id="newAuthorRegister">Author - You'll be submitting papers</button><br>
                    <button class='registrationButton' id="newReviewerRegister">Reviewer - You'll be reviewing papers</button>
                    <form method='post' action='index.php'>
                        <input type='hidden' name='from_login_form' value='0'>
                        <input type='submit' id='login_submit' name='submit' value='Cancel'>
                    </form>
                </div>

                <div id='newUserForm' class='newUserForm'
                    <?php 
                        //keep this section displayed with no slide effect if
                        //there is an error
                        if ($newUserError) {
                            echo "style='display: block;";
                        } 
                    ?>
                >
                    <form class='newUserRegisterForm' method='post' action='registration.php'>
                        <label for='authorUsername'>Username</label>
                        <input type='text' id='authorUsername' name='authorUsername' placeholder="Username">
                        <span class='warning'><?php echo $taken_username; ?></span><br>
                        <label for='firstName'>First Name</label>
                        <input type='text' id='firstName' name='firstName' placeholder="First Name"><br>
                        <label for='lastName'>Last Name</label>
                        <input type='text' id='lastName' name='lastName' placeholder="Last Name"><br>
                        <label for='authorEmail'>Email</label>
                        <input type='text' id='email' name='email' placeholder="Email"><br>
                        <label for='password'>Password</label>
                        <input type='password' id='password' name='password'><br>
                        <label for='password2'>Confirm Password
                            <span class='miniWarning'>
                                <?php if ($passwordError) {
                                    echo " ".$newUserError;
                                }
                                ?>
                            </span>
                        </label>
                        <input type='password' id='password' name='passwordConfirm'><br><br>
                        <input type='hidden' name='role'>
                        <input type='submit' value='submit'>
                    </form>
                    <form method='post' action='index.php'>
                        <input type='hidden' name='from_login_form' value='0'>
                        <input type='submit' id='login_submit' name='submit' value='Cancel'>
                    </form>

                </div>
            </div>
        <script type='text/javascript' src='js/loginWindow.js'></script>
    </body>
</html>