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
	require 'rb-mysql.php';
	R::setup( 'mysql:host=localhost;dbname=tasks','root', '' );
	

	if (isset ($_POST['nazwa']))
	{
		$zm = R::load( 'task', $_POST['task'] );
		$zm->nazwa = $_POST['nazwa'];
		R::store( $zm ); 
	}
	if (isset ($_POST['opis']))
	{
		$zm = R::load( 'task', $_POST['task'] );
		$zm->opis = $_POST['opis'];
		R::store( $zm ); 
	}
	if (isset ($_SESSION['user']))
	{
		$id_pro = $_POST['task'];
		
		$log = R::findOne('user', 'id = ? ', [$_SESSION['user']]);
		echo "Zalogowny:  ".$log->login;
		
		$tas = R::findOne('task', 'id = ? ', [$_POST['task']]);
		
		?>
		<form action="index.php" method="post">
		<input type="hidden" name="wlog"	required>
		<button type="submit">Wyloguj się</button>
		</form>	
		
		<a href="main.php">Powrót</a>
		<br>
		
		<?php echo $tas->nazwa ?>
 		<form action="edit_project.php" method="post">
		<input type="text" placeholder="Nazwa" name="nazwa" required>
		<input type="hidden" name="task" value= <?php echo '"'.$id_pro.'"'; ?>> 
		<button type="submit" name="submit">Zmień</button>
		</form>
		
		<?php echo $tas->opis ?><!-- wrzycić to od razu do pola do edycji-->
		<form action="edit_project.php" method="post">
		<input type="text" placeholder="opis" name="opis" required>
		<input type="hidden" name="task" value= <?php echo '"'.$id_pro.'"'; ?>> 
		<button type="submit" name="submit">Zmień</button>
		</form>
		<?php
		//dodać przekazanie właściciela
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
