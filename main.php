<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Główna</title>
    <link rel="stylesheet" href="main_style.css"/>
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
		$link_address = "edit.php";
		echo "$log->imie $log->nazwisko";
			?>
	<ul>
			<li><a href="<?php echo "index.php?wlog="?>">Wyloguj się</a></li>
			<li><a href="edit.php">Edytuj konto</a></li>
			<li><a href="new.php">Utwórz nowy projekt</a></li>
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
			
			<h2>PROJEKTY</h2>
			<div class="array_projects"><ul>
			<?php
			$part = R::find('part', ' id_user = ? ', [$_SESSION['user']] );
			
			foreach ($part as $pro)
			{
				$na = R::findOne('task', 'id = ?',[$pro->id_task] );
				
				?>
				
				<li><a href="project.php?id=<?php echo "$pro->id_task"?>"><?php echo "$na->nazwa"?></a></li>
				
				<?php
			}
			?>
			</div></div><div class = "bottom"><h3>NOTIFICATIONS</h3>
			<?php
			$part = R::find('notification', ' id_user = ? ', [$_SESSION['user']] );
			foreach ($part as $pro)
			{
				echo $pro->tresc.'<br>';
			}
	}

	else
	{	
	
		?></div>
		<div class="banner2">
		Musisz się zalogować <br><br>
			<a href="index.php">Powrót</a>
		</div>
		<?php
		
	}
?>
</div>
	</div>
	</div>
</body>
</html>
