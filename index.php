<?php
    error_reporting(E_ALL &  ~E_NOTICE);
    session_start();
    
    //CSS transitions are not supported in IE 8 or 9
    if(preg_match('/(?i)msie [5-9]/',$_SERVER['HTTP_USER_AGENT'])) {
        echo "<h1>Please upgrade to a modern browser like Chrome, Firefox, Edge, or Internet Explorer version 10+</h1>";
        die();
    }

//handles logout requests
    if ($_POST['logout'] == 'Logout') {
        $_SESSION = array();
        session_destroy();
        header("Location: index.php");
    }
    
    //error handling from the login form on this page
    if ($_POST['from_login_form'] && !$_POST['forgot_PW']) {
        $name = filter_input(INPUT_POST, 'username');
        $userPW = filter_input(INPUT_POST, 'password');
        
        if (!$name && !$userPW) {
            $loginError = "Please enter a username and password.";
        } elseif ($name && !$userPW) {
            $loginError = "Please enter a password.";
        } elseif (!$name && $userPW) {
            $loginError = "No username entered.";
        } else {
            //checks to see if the username and password matches.
            require_once 'model/registrationDB.php';
            
            $result = login($name, $userPW);
            if (!empty($result)) {
                //var_dump($result);
                $_SESSION['username'] = $result['username'];
                $_SESSION['firstname'] = $result['first_name'];
                $_SESSION['lastname'] = $result['last_name'];
                $_SESSION['email'] = $result['email'];
                $_SESSION['role'] = $result['role'];
                $_SESSION['userID'] = $result['userID'];
                $_SESSION['username'] = $result['username'];
                $_SESSION['lastLogin'] = $result['last_login'];
                $_SESSION['firstLogin'] = TRUE;

            } else {
                $loginError = "Invalid username or password";
            }
        }
        
    } elseif ($_POST['from_login_form'] && $_POST['forgot_PW']) {
        header("Location: forgotPW.php?q=recovery");
    } else {
        $loginError = NULL;
    }
    
    //Error handling from the registration script
    if (isset($_GET['err'])) {
        //Get variables from the registration page if there is an error
        $newUserError = TRUE;
        $nameBlank = filter_input(INPUT_GET, 'u');
        $firstNameBlank = filter_input(INPUT_GET, 'f');
        $lastNameBlank = filter_input(INPUT_GET, 'l');
        $emailBlank = filter_input(INPUT_GET, 'e');
        $emailInvalid = filter_input(INPUT_GET, 'ei');
        $userPWBlank = filter_input(INPUT_GET, 'pw');
        $userPW2Blank = filter_input(INPUT_GET, 'pw2');
        $userPWTooShort = filter_input(INPUT_GET, 'pws');
        $nameFilled = filter_input(INPUT_GET, 'username');
        $firstNameFilled = filter_input(INPUT_GET, 'firstname');
        $lastNameFilled = filter_input(INPUT_GET, 'lastname');
        $emailFilled = filter_input(INPUT_GET, 'email');
            
        //gets the role from the 'r' parameter
        $errRole = filter_input(INPUT_GET, 'r');
        if ($errRole == 'a') {
            $errRole = 'Author';
        }
        if ($errRole == 'r') {
            $errRole = 'Reviewer';
        }
        $newUserErrorRole = 'Registration: New '.$errRole;

        if ($_GET['err'] == 'pwm') {
            $newUserError = 'Passwords do not match.';
            $userPWError = TRUE;
        }
        if ($_GET['err'] == 'u') {
            $newUserError = 'Username is taken';
            $nameError = TRUE;
        }
    }
    
    //Handle routing if the session is set
    if ($_SESSION['role'] == 'author') {
        header("Location: usersUser.php");
    }
    if ($_SESSION['role'] == 'reviewer') {
        header("Location: usersUser.php");
    }
    if ($_SESSION['admin'] == 'reviewer') {
        header("Location: usersAdmin.php");
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
            
            <div class='loginForm' id='loginWindow' <?php if ($loginError || $newUserError) {
                    echo "style='display: block'";} ?>>
                <div <?php 
                    if ($newUserError) {
                        echo "style='display: none'";
                    }
                ?>>
                <form method='post' action='index.php'>
                    <label for='login_username'>Username
                    <span class='warning' id='loginWarning'>
                        <?php echo $loginError; ?>
                    </span>
                    </label>
                    <input type='text' id='login_username' name='username' placeholder='Username'><br>
                    <label for='login_password'>Password</label>
                    <input type='password' id='login_password' name='password'><br><br>
                    <input type='hidden' name='from_login_form' value='1'>
                    <input type='submit' id='login_submit' name='submit' value='Log In'>
                </form>
                
                <form method='post' action='index.php'>
                    <input type='hidden' name='from_login_form' value='0'>
                    <input type='submit' id='loginCancelButton' name='submit' value='Cancel'>
                </form>
                
                <form method='post' action='index.php'>
                    <input type='hidden' name='from_login_form' value='1'>
                    <input type='hidden' name='forgot_PW' value='1'>
                    <input type='submit' id='forgotPWbutton' id='login_submit' name='submit' 
                              value='Click here if you forgot your password'>
                </form>
                </div>

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
                    <button class='registrationButton' id="newAuthorRegister">Author - You'll be submitting papers</button><br><br>
                    <button class='registrationButton' id="newReviewerRegister">Reviewer - You'll be reviewing papers</button><br><br>
                    <form method='post' action='index.php'>
                        <input type='hidden' name='from_login_form' value='0'>
                        <input type='submit' id='roleCancelButton' name='submit' value='Cancel'>
                    </form>
                </div>

                <div id='newUserForm' class='newUserForm'
                    <?php 
                        //keep this section displayed with no slide effect if
                        //there is an error
                        if ($newUserError) {
                            echo "style='display: block;'";
                        } 
                    ?>
                >
                    <form class='newUserRegisterForm' method='post' action='registration.php'>
                        <label for='authorUsername'>Username
                            <span class='miniWarning'>
                                <?php if ($nameError) {
                                    echo " ".$newUserError;
                                }
                                if ($nameBlank) {
                                    echo "Username cannot be blank";
                                }
                                ?>
                            </span>
                        </label>
                        <input type='text' id='username' name='username' 
                            <?php 
                                if ($nameFilled && !$nameError) {
                                    echo " value='".$nameFilled."' ";
                                }
                            ?>   
                               placeholder="Username">
                        <span class='warning'><?php echo $taken_username; ?></span><br>
                        <label for='firstName'>First Name
                            <span class='miniWarning'>
                                <?php if ($firstNameBlank) {
                                    echo "First name cannot be blank";
                                }
                                ?>
                            </span>
                        </label>
                        <input type='text' id='firstName' name='firstName' 
                            <?php 
                                if ($firstNameFilled) {
                                    echo " value='".$firstNameFilled."' ";
                                }
                            ?>                               
                               placeholder="First Name"><br>
                        <label for='lastName'>Last Name
                            <span class='miniWarning'>
                                <?php if ($lastNameBlank) {
                                    echo "Last name cannot be blank";
                                }
                                ?>
                            </span>                        
                        </label>
                        <input type='text' id='lastName' name='lastName' 
                            <?php 
                                if ($lastNameFilled) {
                                    echo " value='".$lastNameFilled."' ";
                                }
                            ?>                               
                               placeholder="Last Name"><br>
                        <label for='authorEmail'>Email
                            <span class='miniWarning'>
                                <?php if ($emailBlank && !$emailInvalid) {
                                    echo "Email field cannot be blank";
                                }
                                if (!$emailBlank && $emailInvalid) {
                                    echo "Not a valid email format";
                                }
                                ?>
                            </span>                         
                        </label>
                        <input type='text' id='email' name='email' 
                                <?php 
                                    if ($emailFilled) {
                                        echo " value='".$emailFilled."' ";
                                    }
                                ?>                               
                               placeholder="Email"><br>
                        <label for='password'>Password (min 8 characters)
                            <span class='miniWarning'>
                                <?php if ($userPWError) {
                                    echo " ".$newUserError;
                                }
                                if ($userPWBlank) {
                                    echo "Please enter a password";
                                }
                                if ($userPWTooShort) {
                                    echo "Password too short";
                                }
                                ?>
                            </span>                        
                        </label>
                        <input type='password' id='password' name='password'><br>
                        <label for='password2'>Confirm Password
                            <span class='miniWarning'>
                                <?php if ($userPWError) {
                                    echo " ".$newUserError;
                                }
                                if ($userPW2Blank) {
                                    echo "Please confirm password entered";
                                }
                                ?>
                            </span>
                        </label>
                        <input type='password' id='password' name='passwordConfirm'><br><br>
                        <input type='hidden' name='role' 
                        <?php 
                            if ($errRole) {
                                echo "value=".$errRole;
                            }
                        ?>       
                        >
                        <input id='createAccount' type='submit' value='Create Account'>
                    </form>
                    <form method='post' action='index.php'>
                        <input type='hidden' name='from_login_form' value='0'>
                        <input class='secondaryButton' type='submit' id='createAccountCancel' name='submit' value='Cancel'>
                    </form>

                </div>
            </div>
        <script type='text/javascript' src='js/loginWindow.js'></script>
    </body>
</html>