<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC ZONE</title>
    <link rel="icon" type="image/jpg/png" href="../img/logo.png">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="wrapper" id="signup" style="display:none;">
      <form  id="myform" method="post" action="register.php">
        <h1>Sign Up</h1>
        <div class="input-box">
          
           <input type="text" name="fname" id="fname" placeholder="firstname" required>
        </div>
        <div class="input-box">
          
          <input type="text" name="lname" id="lname" placeholder="lastname" required>
       </div>
        <div class="input-box">
           
            <input type="email" name="email" id="email" placeholder="Email" required>
        </div>
        <div class="input-box">
            <input type="password" name="password" id="password" placeholder="Password" required>
        </div>
        <div class="input-box">
            <input type="password" name="cpass" id="cpass" placeholder="Confirm Password" required>
        </div>
       <button type="submit" class="btn" value="Sign Up" name="signUp">Sign Up</button>
      </form>
     
    
      <div class="Login-Link">
        <p>Already Have Account ?
        <button class="bt" id="signInButton">Sign In</button></p>
      </div>
    </div>

    <div class="wrapper" id="signIn">
        <h1>Log In</h1>
        <form id="myform" method="post" action="register.php">
          <div class="input-box">
             
              <input type="email" name="email" id="email" placeholder="Email" required>

          </div>
          <div class="input-box">
            
              <input type="password" name="password" id="password" placeholder="Password" required>
          </div>
          
         <button type="submit" class="btn" value="Sign In" name="signIn">Sign In</button>
        </form>
       
        <div class="Signup-Link">
            <p>Don't have an account yet?
                <button class="bt" id="signUpButton">Sign Up</button>
            </p>
        </div>
    </div>     
      <script src="../js/script.js"></script>
      
</body>
</html>