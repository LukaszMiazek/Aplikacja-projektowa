<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>edycja</title>
    <link rel="stylesheet" href="style.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css"/>
</head>
<body>
<?php
	session_start();
	require 'rb-mysql.php';
	R::setup( 'mysql:host=localhost;dbname=tasks','root', '' );
	
	if (isset ($_POST['submitimg']))
	{
		include_once "class.img.php";
		$img = new Image($_FILES["img"]["tmp_name"]);
		$img->SetMaxSize(1200);
		$img->Save('Profil/'.$_SESSION['user'].'.png'); 
	}
	if (isset ($_POST['login']))
	{
		$zm = R::load( 'user', $_SESSION['user'] );
		$zm->login = $_POST['login'];
		R::store( $zm ); 
	}
	if (isset ($_POST['haslo0']))
	{
		$check = R::findOne('user', 'id = ? ', [$_SESSION['user']]);
		
		if($check->haslo == $_POST['haslo0'] && $_POST['haslo1'] == $_POST['haslo2'])
		{
		$zm = R::load( 'user', $_SESSION['user'] );
		$zm->haslo = $_POST['haslo1'];
		R::store( $zm );
		}
		if($check->haslo != $_POST['haslo0'])
		{
			echo 'Podano złe hasło';
		}
		if($_POST['haslo1'] != $_POST['haslo2'])
		{
			echo 'Podane hasła nie są takie same!';
		}
	}
	if (isset ($_POST['imie']))
	{
		$zm = R::load( 'user', $_SESSION['user'] );
		$zm->imie = $_POST['imie'];
		R::store( $zm ); 
	}
	if (isset ($_POST['nazwisko']))
	{
		$zm = R::load( 'user', $_SESSION['user'] );
		$zm->nazwisko = $_POST['nazwisko'];
		R::store( $zm ); 
	}
	if (isset ($_SESSION['user']))
	{
		$log = R::findOne('user', 'id = ? ', [$_SESSION['user']]);
		
		echo "Zalogowny:  ".$log->login
		?>
		<form action="index.php" method="post">
		<input type="hidden" name="wlog"	required>
		<button type="submit">Wyloguj się</button>
		</form>	
		
		<a href="main.php">Powrót</a>
		
		<?php
		if(file_exists('Profil/'.$log->id.'.png'))
		{
			?><img src="Profil/<?php echo $log->id ?>.png" alt=":(" width="42" height="42" style="obrazek"><?php
		}
		else 
		{
			?><img src="Profil/default.png" alt=":(" width="42" height="42" style="obrazek"><?php
		}
		?>
		
		Obrazki tylko w formacie PNG
		<form action="edit.php" method="post" enctype='multipart/form-data'>
		<input name="img" type="file" required>
		<button type="submit" name="submitimg">Zmień</button>
		</form>
		
		<?php echo $log->login ?>
 		<form action="edit.php" method="post">
		<input type="text" placeholder="Login" name="login" required>
		<button type="submit" name="submit">Zmień</button>
		</form>
		
		<form action="edit.php" method="post">
		<input type="password" placeholder="Stare hasło" name="haslo0" required>
		<input type="password" placeholder="Nowe hasło" name="haslo1" required>
		<input type="password" placeholder="Powtórz nowe hasło" name="haslo2" required>
		<button type="submit" name="submit">Zmień</button>
		</form>
		
		<?php echo $log->imie ?>
		<form action="edit.php" method="post">
		<input type="text" placeholder="Imię" name="imie" required>
		<button type="submit" name="submit">Zmień</button>
		</form>
		
		<?php echo $log->nazwisko ?>
		<form action="edit.php" method="post">
		<input type="text" placeholder="Nazwisko" name="nazwisko" required>
		<button type="submit" name="submit">Zmień</button>
		</form>
		<?php
	}
	else
	{
		?>
		MUSISZ SIĘ ZALOGOWAĆ!
		<a href="index.php">Powrót</a>
		<?php
	}
	
?>
</body>
</html>
