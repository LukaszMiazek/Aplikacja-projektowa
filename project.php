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
	
	if (isset ($_POST['uzytkownik']))
	{
			$log = R::findOne('user', 'login = ? ', [$_POST ['uzytkownik']]);
		
			$uz=$log->id;
			$ro=$_POST ['rola'];
			$id_t=$_POST ['task_id'];

			$par = R::dispense('part');
			$par->id_user=$uz;
			$par->id_task=$id_t;
			$par->role=$ro;
			$id = R::store( $par );
	}
	if (isset ($_SESSION['user']))
	{
		
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
		
		echo '<a href="main.php">Powrót</a>';
		
		$id_pro = $_GET['id'];
		?>
		
		<form action="index.php" method="post">
		<input type="hidden" name="wlog"	required>
		<button type="submit">Wyloguj się</button>
		</form>	
		
		<?php 
			$acc = R::findOne('part', 'id_task = ? AND id_user=? AND role=3', [$id_pro, $_SESSION['user']] );
			

			
			if(!empty($acc))
			{
		?>
			<form action="edit_project.php" method="post">
				<input type="hidden" name="task" value= <?php echo '"'.$id_pro.'"'; ?>> 
				<button type="submit">Edytuj projekt</button>
			</form>	
		<?php 
			}
		?>
		
		<?php 
			$us = R::findOne('task', 'id = ?', [$id_pro] );
			
			echo $us->nazwa;
			echo '<br>OPIS:';
			echo $us->opis;
		?>
		
		<br>
		Dodaj użytkownika
		<form action="" method="post">
		<input type="text" name="uzytkownik" required>
		<select name="rola">
		  <option value="1">Członek</option>
		  <option value="2">Moderator</option>
		</select>
		<input type="hidden" name="task_id" value= <?php echo '"'.$id_pro.'"'; ?>> 
		<button type="submit">Dodaj</button>
		</form>	
		
		<?php
		
		$part = R::find('part', ' id_task = ? ', [$id_pro] );
		
		foreach ($part as $usr)
		{
			$us = R::findOne('user', 'id = ?', [$usr->id_user] );
			echo '<br>'.$us['login'];
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
