<?php
session_start();
require_once 'config.php';
$userErr = $passErr = $success = $userTakenError ="";
$username = $fname = $lname ="";
//if cookie is set and the session is not- copy the cookie to the session
if((!isset($_SESSION['username'])) && isset($_COOKIE['loggedIn'])) 
{
    $_SESSION['username'] = $_COOKIE['loggedIn'];
}
if(isset($_POST['login']))
{
    $username = safePost($conn, 'username');
    $password = safePost($conn, 'password');
    $rememberMe = safePost($conn, '_remember_me');
    $sql = "SELECT * FROM `USER` WHERE `username` LIKE '$username'";
    $result = $conn->query($sql);
    if(!$result) {die("Query failed".$conn->connect_error);}
    if($result->num_rows != 0) 
    {
        if($row = $result->fetch_assoc())
        {
            $dbusername = $row['username'];
            $dbpassword = $row['pass'];
            if(hashP($password) == $dbpassword)
            {
               $_SESSION["username"] = $username;
               if($rememberMe) 
               {
                   setcookie("loggedIn", $username, time()+60*60*24*30);
               }
            }
            else
            {
               $passErr = "Password is wrong!";
            }
        }
    }
    else 
    {
        $userErr = 'No such username!';
        $username = "";
    }
}
else if(isset($_POST['register']))
{
        $fname= safePost($conn, 'fname');
        $lname= safePost($conn, 'lname');
        $username = safePost($conn, 'username');
        $password = safePost($conn, 'password');
        if($username != "" && $password != "") 
        {
            $sql = "SELECT * FROM `USER` WHERE `username` LIKE '$username'";
            $result = $conn->query($sql);
            if(!$result) {die("Query failed".$conn->connect_error);}
            if($result->num_rows != 0) 
            {
                $userTakenError = "Username is already taken";
            }
            else
            {
                $password = hashP($password);
                $sql = "INSERT INTO `USER` (`id`, `First Name`, `Last Name`, `username`, `pass`, `score`) VALUES (NULL, '$fname', '$lname', '$username', '$password', '0')";
                if($conn->query($sql) !== TRUE)
                { 
                    die("Error on inserting the player in the database".$conn->error);   
                }
                else
                {
                    $success = "Registration successful!";
                }
            }
        }
}
?>
<html>
    <head>
        <title>Login Page</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script>
            $(document).ready(function(){

                $("#signup").click(function()
                {
                    $("#login").hide();
                    $("#register").show();
                    return false;
                });
                $("#signin").click(function()
                {
                    $("#register").hide();
                    $("#login").show();
                    return false;
                });
                $("#registerbtn").click(function checkPass()
                {
                    var errs = "";
                    var pas = $("#registerpsw").val();
                    var pasR = $("#registerpswR").val();
                    if(pas !== pasR) 
                    {
                        errs += "Passwords do not match\n";
                        $("#registerpsw, #registerpswR").css('border-color','red');
                    }
                    else 
                    {
                        $("#registerpsw, #registerpswR").css('border-color', '');
                    }
                    if(pas.length < 8) 
                    {
                        errs+= "Password is too short";
                        $("#registerpsw, #registerpswR").css('border-color','red');
                    }
                    if(errs != "") {alert(errs);}
                    return(errs=="");
                });
                
  
            });
         </script>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
        <?php
            if(!isset($_SESSION['username'])) {
        ?>
        
        
        <div class="banner">
            <div> Welcome to <img src="http://www.clker.com/cliparts/F/a/x/p/M/b/alpha-hi.png" alt="alphaSymbol"/> Learning! </div>
        </div>
        
        <h4 style="text-align: center;">
            <?php echo $success; ?> 
        </h4>
        <div id="formsContainer">
            <div id="box">
                <div id="login">
                    <h1> Sign In </h1>
                    <form  method="post">                         
                        <div class="field"><input id="loginuname" placeholder="Username" name="username" type="text" required value = '<?php echo $username; ?>' > <span class= "error"> <?php echo $userErr; ?> </span></div>
                        <div class="field"><input id="loginpsw" placeholder="Password" name= "password" type="password" required > <span class= "error"> <?php echo $passErr; ?> </span></div>
                        <input id="loginbtn" name = "login" type="submit" value="Sign In">
                        <div id="remCheck">
                            <label for="remember_me">Keep me logged in</label>
                            <input type="checkbox" id="remember_me" name="_remember_me"/>                            
                        </div>
                        <p id="one">Don't have an account? <a href="#" id="signup">Sign up</a></p>
                    </form>
                </div>

                <div id="register" style="display:none" >
                    <h1> Create an account </h1> 
                    <form  method="post" id="registrationForm" name="registrationForm" onsubmit="return checkPass()">                        
                        <div id="namesRow">
                            <div class="field"><input id="firstName" name="fname" placeholder="First name" type="text" required value = '<?php echo $fname; ?>' /></div>
                            <div class="field"><input id ="lastName" name="lname" placeholder="Last name" type="text" required value = '<?php echo $lname; ?>' /></div>
                        </div>

                        <div class="field"><input id ="reguname" name ="username" placeholder="Username" type="text" required  value = '<?php echo $username; ?>' /> <span class= "error"> <?php echo $userTakenError; ?> </span></div>
                        <div class="field"><input id="registerpsw" name ="password" placeholder="Enter a password" type="password" required></div>
                        <div class="field"><input id="registerpswR" name ="passwordR" placeholder="Repeat password" type="password" required></div>
                        <input id="registerbtn" name="register" type="submit" value="Sign up">
                        <p id="two">Already have an account? <a class="signin" href="#" id="signin">Sign in</a></p>
                    </form>
                </div>
            </div>
        </div>
        
        
        <?php 
            } else {
                header("Location:home.php");
            }
        ?>
       </body>
</html>
