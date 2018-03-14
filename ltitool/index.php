<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Media Builder Login</title>
<link href="js/login.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div id="main">
        <div id="login">
            <h2>Login to Media Builder</h2>
            <form id="enterance" action="scripts/logincheck.php" method="post">
            <label>UserName :</label>
            <input id="username" name="username" placeholder="username" type="text">
            <label>Password :</label>
            <input id="password" name="password" placeholder="**********" type="password">
            <input name="submit" type="submit" value=" Login ">
            <span><?php echo $error; ?></span>
            </form>
        </div>
    </div>
</body>
</html>