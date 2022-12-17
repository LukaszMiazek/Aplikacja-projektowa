<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>nowy</title>
    <link rel="stylesheet" href="new.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css"/>
</head>
<body>
 <div class="banner">
<?php
	session_start();	

	if (isset ($_SESSION['user']))
	{
		require 'rb-mysql.php';
		R::setup( 'mysql:host=localhost;dbname=tasks','root', '' );
		
		$log = R::findOne('user', 'id = ? ', [$_SESSION['user']]);
	
		echo "$log->imie $log->nazwisko";
			?>	
		<ul>
			<li><a href="index.php">Wyloguj się</a></li>
			<li><a href="main.php">Powrót</a></li>
		</ul>
</div>		



		<div class = "center">
		<?php
		if(file_exists('Profil/'.$log->id.'.png'))
			{
				?>
				<div class="imgcontainer"><img src="Profil/<?php echo $log->id ?>.png" alt=":(" width="100" height="100" class="obrazek"></div><?php
			}
			else 
			{
				?><div class="imgcontainer"><img src="Profil/default.png" alt=":(" width="100" height="100" class="obrazek"></div><?php
			}
			?>
		<br>
		<?php
		if (!isset ($_POST['nazwa']))
		{	
			?>
				<div class="form">
					<div class="tab-content">

						<div class="tab-body">
								<form action="new.php" method="post">
									
													<div class="form-element">
														Nazwa projektu:
														<br>
														<input type="text" name="nazwa" required>
														<br>
														Opis:
														<br>
														<textarea name="opis" ></textarea>
														<br>
														<button type="submit">Utwórz</button>
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
			
			$ids=$id;
			
			$par = R::dispense('part');
			$par->id_user=$_SESSION['user'];
			$par->id_task=$id;
			$par->role=3;
			$id = R::store( $par );
			
			header('Location: project.php?id='.$ids);
		}
	}
	else
	{	
		?>
		<div class="banner">
		MUSISZ SIĘ ZALOGOWAĆ!
		<ul>
			<li><a href="index.php">Powrót</a></li></ul>
		</div>
		<?php
		
	}
?>
</body>
</html>
