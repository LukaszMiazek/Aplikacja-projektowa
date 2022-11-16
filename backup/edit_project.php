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
	
	if (isset ($_POST['task_id_delete']))
	{
		$del = R::find('part', ' id_task = ?', [ $_POST['task_id_delete'] ] );
		
		foreach ($del as $dl)
		{ 
			R::trash( $dl );
		}
		
		$delete = R::findOne('task', ' id = ?', [$_POST['task_id_delete'] ]);
		R::trash( $delete );
		
		header('Location: main.php');
	}
	if (isset ($_POST['uzytkownik']))
	{
			$log = R::findOne('user', 'login = ? ', [$_POST ['uzytkownik']]);
			
			$rol = R::findOne('part', 'id_user = ? AND id_task = ?', [ $log['id'], $_POST['task_id'] ]);
			
			if(empty($rol))
			{
				$par = R::dispense('part');
				$par->id_user=$log['id'];
				$par->id_task=$_POST['task_id'];
				$par->role=3;;
				$id = R::store( $par );
			}
			else
			{
				$zm = R::load( 'part', $rol['id'] );
				$zm->role = 3;
				R::store( $zm );
			}
			
			$rol = R::findOne('part', 'id_user = ? AND id_task = ? ', [ $_SESSION['user'] , $_POST['task_id'] ]);
			
			$zm = R::load( 'part', $rol['id'] );
			$zm->role = 2;
			R::store( $zm );
		
			header('Location: main.php');
	}
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
		
		if(file_exists('Profil/'.$log->id.'.png'))
		{
			?><img src="Profil/<?php echo $log->id ?>.png" alt=":(" width="42" height="42" style="obrazek"><?php
		}
		else 
		{
			?><img src="Profil/default.png" alt=":(" width="42" height="42" style="obrazek"><?php
		}
		
		
		$tas = R::findOne('task', 'id = ? ', [$_POST['task']]);
		
		?>
		<form action="index.php" method="post">
		<input type="hidden" name="wlog"	required>
		<button type="submit">Wyloguj się</button>
		</form>	
		<br>
		
		<?php
		echo '<a href="project.php?id='.$id_pro.'">Powrót</a>';
		?>
		
 		<form action="edit_project.php" method="post">
		<input type="text" value="<?php echo $tas->nazwa ?>" name="nazwa" required>
		<input type="hidden" name="task" value= <?php echo '"'.$id_pro.'"'; ?>> 
		<button type="submit" name="submit">Zmień</button>
		</form>
		
		<form action="edit_project.php" method="post">
		<textarea name="opis" required ><?php echo $tas->opis ?></textarea>
		<input type="hidden" name="task" value= <?php echo '"'.$id_pro.'"'; ?>> 
		<button type="submit" name="submit">Zmień</button>
		</form>
		
		Zmiana właściciela projektu
		<form action="" method="post">
		<input type="text" name="uzytkownik" required>
		<input type="hidden" name="task_id" value= <?php echo '"'.$id_pro.'"'; ?>> 
		<button type="submit">Dodaj</button>
		</form>
		
		Usuń projekt
		<form action="" method="post">
		<input type="hidden" name="task_id_delete" value= <?php echo '"'.$id_pro.'"'; ?>> 
		<button type="submit">Usuń</button>
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
