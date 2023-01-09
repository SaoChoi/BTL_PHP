<?php
if (isset($_POST['relogin'])) {
        session_start();
        unset($_SESSION['role']);
        unset($_SESSION['userId']);
        include './index.php';
}
$_SESSION['isLogin'] = false;

echo $_SESSION['isLogin'];
?>

<!DOCTYPE html>
<html>

<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="Style\logout.css">
        <link rel="icon" href="Images\Logo.PNG" type="image/x-icon">
        <title>Log out</title>

</head>

<body>
        <div id="background">
                <img id="bgimage" src="Images/Logoutbg.png" alt="Good bye" width="100%" height="100%" />
                <div id="area">
                </div>
                <div id="message">
                        <form action="" method="post">
                                <p>You have logged out, click sign in to continue</p>
                                <input type="submit" name="relogin" value="Sign in" formaction="index.php">
                        </form>
                </div>
        </div>
</body>

</html>