<html>

<body>
<form action="phpform.php" method="post">
name: <input type="text" name="user_name" > <br> <br>
email: <input type="email" name="email"> <br> <br>
comment: <textarea name="message" rows="5" cols="40"> </textarea> <br> <br>
<input type="submit" name="submit" value="submit">
</form>
</body>

</html>

<?php
if($_POST['submit']) {
    echo $_POST['user_name'] ."<br>";
    echo $_POST['email'] ."<br>";
    echo $_POST['message'] ."<br>";

    
    }

?>