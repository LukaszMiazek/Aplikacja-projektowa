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

	if (isset ($_SESSION['user']))
	{
		$log = R::findOne('user', 'id = ? ', [$_SESSION['user']]);
		
		echo "Zalogowany: ".$log->login
		?>
		<img src="Profil/<?php echo $log->id ?>.png" alt=":(" width="42" height="42" style="obrazek">
		
		<form action="index.php" method="post">
		<input type="hidden" name="wlog"	required>
		<button type="submit">Wyloguj się</button>
		</form>	
		
		<?php
		if (!isset ($_POST['nazwa']))
		{	
			?>
				<div class="form">
					<div class="tab-content">

						<div class="tab-body">
								<form action="new.php" method="post">
									
													<div class="form-element">
														<input type="text" placeholder="nazwa" name="nazwa" required>
														<br>
														Opis:
														<br>
														<textarea name="opis" ></textarea>
														<br>
														<input type="submit" name='submit' value="Utwórz" target="self">
													</div>
									</form>
							</div>

					</div>
				</div>
			<?php
		}
		else
		{
			$na=$_POST ['nazwa'];
			$op=$_POST ['opis'];

			$tas = R::dispense('task');
			$tas->nazwa=$na;
			$tas->opis=$op;
			$tas->wlasciciel=$_SESSION['user'];
			$id = R::store( $tas );
			
			$par = R::dispense('part');
			$par->id_user=$_SESSION['user'];
			$par->id_task=$id;
			$par->role=3;
			$id = R::store( $par );
			
			header('Location: project.php?id='.$id);
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
