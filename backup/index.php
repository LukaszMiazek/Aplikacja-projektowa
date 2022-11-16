<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css"/>
</head>
<body>
<?php
session_start();	
if (isset ($_POST['wlog']))
{
		unset($_POST['wlog']);
		session_destroy();
}
if (isset ($_POST['login']))
{
	require 'rb-mysql.php';
	R::setup( 'mysql:host=localhost;dbname=tasks','root', '' );
	
	$lo = $_POST['login'];
	$ha = $_POST['haslo'];
	
	$log = R::findOne('user', 'login = ? ', [$lo]);
	
	if (empty($log)) 
	{
		header('Location: index.php');
	}
	else
	{
		if($log->login == $lo && $log->haslo == $ha)
		{
			header('Location: main.php');
			$_SESSION['user'] = $log->id;
		}
		else
		{
			header('Location: index.php');
		}
	}
}
else
{
?>
	<header>
	</header>
		<div class="form">
			<div class="tab-content">
				<div class="tab-body active">
                    <form class="login-form" action="index.php" method="post">
                        <div class="form-element">
                             <input type="login" placeholder="Login" name="login" required>
                        </div>
                        <div class="form-element">
                            <input type="password" placeholder="Hasło" name="haslo" required>
                        </div>
                        <div class="form-element">
                            <button type="submit">Zaloguj</button>
							
							<a href=rejestracja.php>Utwórz konto</a>
                        </div>
                </form>
				</div>
			</div>
		</div>
<?php
}
?>
</body>
</html>
