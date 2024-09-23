<?php 
  include("connect_to_database.php");

  // Initialize error messages
  $username_error = $email_error = $password_error = $password_confirmation_error = "";
  $form_valid = true;

  if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Username validation
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if(empty($username)){
      $username_error = "Username is required";
    }

    // Email validation
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    if(empty($email)){
      $email_error = "Email is required";
    } elseif(!$email) {
      $email_error = "Invalid email format";
    }

    // Password validation
    $password = $_POST["password"]; // No sanitization for password, use hashing later
    if(empty($password)){
      $password_error = "Password is required";
    } elseif(strlen($password) < 6){
      $password_error = "Password must be at least 6 characters long";
    }

    // Confirm Password validation
    $password_confirmation = $_POST["password_confirmation"];
    if(empty($password_confirmation)){
      $password_confirmation_error = "Password confirmation is required";
    } elseif($password !== $password_confirmation){
      $password_confirmation_error = "Passwords do not match";
    }
    
    // If the form is valid, process the data
    if($form_valid) {
      // Check if the email or username already exists in the database
      $check_query = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
      $result = mysqli_query($conn, $check_query);
      
      if(mysqli_num_rows($result) > 0) {
        // If a match is found, show error
        $row = mysqli_fetch_assoc($result);
        if($row['email'] === $email) {
          $email_error = "Email is already registered";
        }
        if($row['username'] === $username) {
          $username_error = "Username is already taken";
        }
        $form_valid = false; // Set form_valid to false to stop the process
      }
    }

    // If form is still valid (i.e., no duplicates and all other validations pass), insert the new record
    if($form_valid){
      // Hash the password for security
      $hash = password_hash($password, PASSWORD_DEFAULT);

      // Insert data into the database
      $sql = "INSERT INTO users(username, email, password_hash)
              VALUES('$username', '$email', '$hash')";

      if(mysqli_query($conn, $sql)){
        echo "<script>alert('Signup successful!');</script>";
        echo "<script>window.location.href = 'index.html';</script>";

        // Clear the form fields after successful submission
        $username = $email = $password = $password_confirmation = "";
      }
      else{
        echo "<script>alert('Error: Could not sign up. Try again.');</script>";
      }
    }
    
    mysqli_close($conn);
    
  }
?>
<html>
<head>
  <meta charset="utf-8">
  <title>NHIS: Sign in</title>
  <link rel="stylesheet" href="login.css">
  <!-- Page Favicon -->
  <link rel="shortcut icon" href="pictures/Nhislogo2.png">
</head>

<body>
  <div class="login-root">
    <div class="box-root flex-flex flex-direction--column" style="min-height: 100vh;flex-grow: 1;">
      <div class="loginbackground box-background--white padding-top--64">
        <div class="loginbackground-gridContainer">
          <div class="box-root flex-flex" style="grid-area: top / start / 8 / end;">
            <div class="box-root" style="background-image: linear-gradient(white 0%, rgb(247, 250, 252) 33%); flex-grow: 1;">
            </div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 4 / 2 / auto / 5;">
            <div class="box-root box-divider--light-all-2 animationLeftRight tans3s" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 6 / start / auto / 2;">
            <div class="box-root box-background--blue800" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 7 / start / auto / 4;">
            <div class="box-root box-background--blue animationLeftRight" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 8 / 4 / auto / 6;">
            <div class="box-root box-background--gray100 animationLeftRight tans3s" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 2 / 15 / auto / end;">
            <div class="box-root box-background--cyan200 animationRightLeft tans4s" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 3 / 14 / auto / end;">
            <div class="box-root box-background--blue animationRightLeft" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 4 / 17 / auto / 20;">
            <div class="box-root box-background--gray100 animationRightLeft tans4s" style="flex-grow: 1;"></div>
          </div>
          <div class="box-root flex-flex" style="grid-area: 5 / 14 / auto / 17;">
            <div class="box-root box-divider--light-all-2 animationRightLeft tans3s" style="flex-grow: 1;"></div>
          </div>
        </div>
      </div>
      <div class="box-root padding-top--24 flex-flex flex-direction--column" style="flex-grow: 1; z-index: 9;">
        <div class="box-root padding-top--48 padding-bottom--24 flex-flex flex-justifyContent--center">
          <h1><a href="http://blog.stackfindover.com/" rel="dofollow">Welcome to NHIS</a></h1>
        </div>
        <div class="formbg-outer">
          <div class="formbg">
            <div class="formbg-inner padding-horizontal--48">
              <span class="padding-bottom--15">Sign up</span>
              <form id="stripe-login" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
               <div class="field padding-bottom--24">
                  <label for="email">Username</label>
                  <input type="text" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>">
                  <!-- Echo error message -->
                  <p style="color:red;"><?php echo $username_error; ?></p>
                </div>
                <div class="field padding-bottom--24">
                  <label for="email">Email</label>
                  <input type="email" name="email" value="<?php echo htmlspecialchars($email ?? '') ?>">
                  <!-- Echo error message -->
                   <p style="color:red"><?php echo $email_error; ?></p>
                </div>
                <div class="field padding-bottom--24">
                  <div class="grid--50-50">
                    <label for="password">Password</label>
                  </div>
                  <input type="password" name="password">
                  <p style="color:red"><?php echo $password_error; ?></p>
                  <br><br>
                  <div class="grid--50-50">
                    <label for="password">Re-Enter Password</label>
                  </div>
                  <input type="password" name="password_confirmation">
                  <p style="color: red;"><?php echo $password_confirmation_error ?></p>
                </div>
                <div class="field padding-bottom--24">
                  <input type="submit" name="submit" value="Continue">
                </div>
              </form>
            </div>
          </div>
          <div class="footer-link padding-top--24">
            <span>You have an account? <a href="login.php">Sign in</a></span>
            <div class="listing padding-top--24 padding-bottom--24 flex-flex center-center">
              <span><a href="#">© John Empire</a></span>
              <span><a href="#">Contact</a></span>
              <span><a href="#">Privacy & terms</a></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>