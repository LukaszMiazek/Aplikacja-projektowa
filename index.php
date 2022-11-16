<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css"/>
<style>	

body {font-family: Arial, Helvetica, sans-serif;}

input{
	padding: 10px;
	width:20%;
}

.center{
  margin: auto;
  width: 50%;
  padding: 150px;
}

.imgcontainer {
  text-align: center;
}	

img.avatar{
  width: 15%;
  border-radius: 20%;
}

.form-element{
  padding: 16px;
  text-align: center;
}
.link{
	  text-align: center;
}
a:link, a:visited {
  font-size:12px;
  background-color: #f44336;
  color: white;
  padding: 14px 0px;
  width: 20%;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  border-radius: 15px;
  
}

a:hover, a:active {
  background-color: red;
}

.button{
	text-align: center;
}


button {
	font-size:12px;
  background-color: #04AA6D;
  color: white;
  padding: 14px 0px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 20%;
  border-radius: 15px;
  text-align: center;
}

button:hover {
  opacity: 0.8;
}
	
</style>	
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
<div class="center">
<form action="index.php" method="post">
  <div class="imgcontainer">
    <img src="1.png" alt="Avatar" class="avatar">
  </div>  
	<header>
	</header>
	
		<div class="form">
			<div class="tab-content">
				<div class="tab-body active">
                    <form class="login-form" action="index.php" method="post">
                        <div class="form-element">
						<label for="login"><b>Username</b></label><br>
							<input type="text" placeholder="Login" name="login" required>                      
                        </div>						
                        <div class="form-element">
							<label for="haslo"><b>Password</b></label><br>
                            <input type="password" placeholder="Hasło" name="haslo" required>
                        </div>
                        <div class="button">
                            <button type="submit">Zaloguj</button>
                   </div>
						<div class="link">
							<a href="rejestracja.php" > Utwórz konto</a>
                   </div>
                </form>
				</div>
			</div>
		</div>
		</div>
<?php
}
?>
</body>
</html>
