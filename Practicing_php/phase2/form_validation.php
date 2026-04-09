<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form validation</title>
</head>
<body>
    <form action="form_validation.php" method="post">
        Name: <input type="text" name="name" required> <br><br>
        Email: <input type="email" name="email" required> <br><br>
        password: <input type='password' name='password' required> <br><br>
        Comment: <textarea name="comment" rows="5" cols="40" required></textarea> <br><br>
        <input type="submit" name="submit" value="Submit">
    </form>
</body>
</html>

<?php
echo "<br> . <br>";
if(isset($_POST['submit'])) {
    $user = $_POST['name'];
    $email = $_POST['email'];

    if(empty($user)) {
        echo "user name is required pls fill it out";
    }elseif(strlen($user) <= 3) {
        echo "user name should be at least in 4 charachters ";
    }elseif(!preg_match("/^[a-zA-Z\s]*$/", $user)) {
        echo "user name should be in alphabet";
    }
    else {
        echo $user . "<br>";
    }
    $pass = $_POST['password'];

    $upprcase = preg_match("/[A-Z]/", $pass);
    $lowercase = preg_match("@[a-z]@", $pass);
    $numbers = preg_match("@[0-9]@", $pass);
    $symbols = preg_match("@[^\w]@", $pass);
    
    if(empty($pass)) {
        echo "pls provide a strong password";
    }elseif(!$upprcase) {
        echo "the password should contain at least 1 uppercase letter" . "<br>";
    }elseif(!$lowercase) {
        echo "the password should contain at least 1 lowercase letter " ."<br>";
    }elseif(!$numbers) {
        echo "the password should contain at least 1 number" . "<br>";
    }elseif(!$symbols) {
        echo "the password should contain at least 1 symbol" . "<br>";
    }elseif(strlen($pass) < 8) {
        echo "the password should be at least 8 charachters long" . "<br>";
    }else {
        echo "the password is strong" . "<br>";
    }

    // echo $user;
}
?>