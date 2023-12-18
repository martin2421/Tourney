<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="styles-start.css">
     <title>Tourney</title>
</head>

<body>

     <section id="main-content">
          <!-- Logo -->
          <a href="#">
               <img id="logo" src="images/logo.svg" alt="logo">
          </a>

          <!-- Title -->
          <h1 id="title">Tourney</h1>

          <!-- Buttons -->
          <div class="buttons-group">
               <button class='btn' id='button-signin'>Sign In</button>
               <button class='btn' id='button-signup'>Sign Up</button>
          </div>
     </section>

     <!-- Blanket -->
     <div id='blanket'></div>

     <!-- SignIn -->
     <div class='modal' id='modal-signin'>
          <h2 class="modal-title">Sign In</h2><br>

          <form method='post' action='controller.php'>
               <input type='hidden' name='page' value='StartPage'>
               <input type='hidden' name='command' value='SignIn'>

               <label for='signin-username'>Username</label><br>
               <input id='signin-username' type='text' name='Username'>
               <?php if (!empty($error_msg_username_signin))
                    echo $error_msg_username_signin; ?><br><br><br><br>

               <label for='signin-password'>Password</label><br>
               <input id='signin-password' type='password' name='Password'>
               <?php if (!empty($error_msg_password_signin))
                    echo $error_msg_password_signin; ?><br>

               <input class='cancel' id='cancel-signin' type='button' value='Cancel'>
               <input class='submit' id='submit-signin' type='submit' value="Sign In">
          </form>
     </div>

     <!-- SignUp -->
     <div class='modal' id='modal-signup'>
          <h2 class='modal-title'>Sign Up</h2><br>
          
          <form method='post' action='controller.php'>
               <input type='hidden' name='page' value='StartPage'>
               <input type='hidden' name='command' value='SignUp'>

               <label for='signup-username'>Username</label><br>
               <input id='signup-username' type='text' name='Username'>
               <?php if (!empty($error_msg_username_signup))
                    echo $error_msg_username_signup; ?><br><br>

               <label for='signup-password'>Password</label><br>
               <input id='signup-password' type='password' name='Password'> <br><br>

               <label for='signup-email'>Email</label><br>
               <input id='signup-email' type='text' name='Email'> <br><br>

               <label for="signup-organizer">Organizer?</label><br>
               <input id="signup-organizer" type="checkbox" name='Organizer'>

               <input class='cancel' id='cancel-signup' type='button' value='Cancel'>
               <input class='submit' id='submit-signup' type='submit' value="Sign Up">
          </form>
     </div>

</body>

<script>

     // Sign In
     document.getElementById('button-signin').addEventListener('click', function () {
          hide_signup_modal_window();
          show_signin_modal_window();
     });
     document.getElementById('cancel-signin').addEventListener('click', function () {
          hide_signin_modal_window();
     });

     // Sign Up
     document.getElementById('button-signup').addEventListener('click', function () {
          hide_signin_modal_window();
          show_signup_modal_window();
     });
     document.getElementById('cancel-signup').addEventListener('click', function () {
          hide_signup_modal_window();
     });

     document.getElementById('blanket').addEventListener('click', function () {
          hide_signin_modal_window();
          hide_signup_modal_window();
     });

     // Sign In
     function show_signin_modal_window() {
          document.getElementById('blanket').style.display = 'block';
          document.getElementById('modal-signin').style.display = 'block';
     }

     function hide_signin_modal_window() {
          document.getElementById('blanket').style.display = 'none';
          document.getElementById('modal-signin').style.display = 'none';
     }

     // Sign Up
     function show_signup_modal_window() {
          document.getElementById('blanket').style.display = 'block';
          document.getElementById('modal-signup').style.display = 'block';
     }

     function hide_signup_modal_window() {
          document.getElementById('blanket').style.display = 'none';
          document.getElementById('modal-signup').style.display = 'none';
     }

     <?php
     if (!empty($display_modal_window)) {
          if ($display_modal_window == 'signin')
               echo 'show_signin_modal_window();'; // echo JavaScript code
          else if ($display_modal_window == 'signup')
               echo 'show_signup_modal_window();';
     }
     ?>
</script>

</html>