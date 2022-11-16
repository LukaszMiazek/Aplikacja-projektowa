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

	if (isset ($_SESSION['user']))
	{
		require 'rb-mysql.php';
		R::setup( 'mysql:host=localhost;dbname=tasks','root', '' );
		
		$log = R::findOne('user', 'id = ? ', [$_SESSION['user']]);
		
		echo "Zalogowany: ".$log->login;
		
		if(file_exists('Profil/'.$log->id.'.png'))
		{
			?><img src="Profil/<?php echo $log->id ?>.png" alt=":(" width="42" height="42" style="obrazek"><?php
		}
		else 
		{
			?><img src="Profil/default.png" alt=":(" width="42" height="42" style="obrazek"><?php
		}
		?>
		
		<form action="index.php" method="post">
		<input type="hidden" name="wlog"	required>
		<button type="submit">Wyloguj się</button>
		</form>	
		
		<a href="edit.php">Edytuj konto</a>
		<br>
		<a href="new.php">Utwórz nowy projekt</a>
		<br>
		PROJEKTY:
		<?php
		$part = R::find('part', ' id_user = ? ', [$_SESSION['user']] );
		foreach ($part as $pro)
		{
			echo '<br>';
			$na = R::findOne('task', 'id = ?', [$pro->id_task] );
			echo '<a href="project.php?id=';
			echo $pro->id_task;
			echo '">';
			echo $na->nazwa;
			echo '</a>';
		}
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
