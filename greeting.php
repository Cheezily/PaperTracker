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
        
        <?php if ($loginError || $newUserError || $newUserRequested || $newAuthorRequested || $newReviewerRequested) {
            echo "<div class='mainWrapper' style='opacity: .3'>";
            } elseif ($newLoginRequest) {echo "<div class='mainWrapper fadeOutMainWrapper'>";
            } else {echo "<div class='mainWrapper'>";}?>
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
            
            <!--The login form will be displayed if there are any /registration 
                errors or if the form was requested by clicking the login 
                button in the header-->
            <?php if ($loginError || $newUserError || $newLoginRequest) { ?>
            
                <?php if ($newLoginRequest) {echo "<div class='loginForm loginFormAppear' id='loginWindow'>";} 
                elseif (!$newLoginRequest && ($loginError || $newUserError)) {echo "<div class='loginForm' id='loginWindow'>";} ?>

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
        
            <?php } ?>

            <!--Registration forms.  Starts hidden-->
            <?php if ($newUserRequested || $newAuthorRequested || $newReviewerRequested) { ?>
                <?php if ($newAuthorRequested || $newReviewerRequested) { ?>
                    <div class="loginForm loginFormDisappear" id="registrationDialog">
                <?php } else { ?>
                    <div class="loginForm loginFormAppear" id="registrationDialog">
                <?php } ?>
                        <p id='roleTitle'>
                            <?php if ($newUserError) {
                                echo $newUserErrorRole;
                            } else {
                                echo 'Please select your role:';
                            } 
                            ?>
                        </p>

                    <div class='roleSelection'>   
                        <form method="post" action='index.php'>
                        <input type="submit" class='registrationButton' name="newAuthorRegister" value="Author - You'll be submitting papers"><br>
                        </form>
                        <form method="post" action='index.php'>
                        <input type="submit" class='registrationButton' name="newReviewerRegister" value="Reviewer - You'll be reviewing papers"><br>
                        </form>
                        <form method='post' action='index.php'>
                            <input type='hidden' name='from_login_form' value='0'>
                            <input type='submit' id='roleCancelButton' name='submit' value='Cancel'>
                        </form>
                    </div>
                </div>
            <?php } ?>
                
                <?php if ($newUserError || $newAuthorRequested || $newReviewerRequested) {
                    if ($newUserError && !$newAuthorRequested && !$newReviewerRequested) { 
                    echo "<div class='loginForm newUserForm'>";
                    } else { 
                    echo "<div class='loginForm newUserForm newUserFormAppear'>";} ?>
                    <form class='newUserRegisterForm' method='post' action='registration.php'>
                        <?php if ($newAuthorRequested || ($errRole === 'Author')) {echo "<h4>New Author Registration:</h4>";}?>
                        <?php if ($newReviewerRequested || ($errRole === 'Reviewer')) {echo "<h4>New Reviewer Registration:</h4>";}?>
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
                            if ($newAuthorRequested) {
                                echo "value='Author'";
                            }
                            if ($newReviewerRequested) {
                                echo "value='Reviewer'";
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
                <?php } ?>

        
    </body>
</html>