<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>zadanie</title>
    <link rel="stylesheet" href="style.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css"/>
</head>
<body>
<?php
	session_start();	
	require 'rb-mysql.php';
	R::setup( 'mysql:host=localhost;dbname=tasks','root', '' );
	
	$jid=$_GET['id'];
	
	if(isset($_FILES['sub'])){
			 $errors= array();
			 $file_name = $_FILES['sub']['name'];
			 $file_size =$_FILES['sub']['size'];
			 $file_tmp =$_FILES['sub']['tmp_name']; 
			 $file_type=$_FILES['sub']['type'];
			 //$extensions= array("jpeg","jpg","png", "webp", "pdf"); 
			 foreach($file_name as $key => $value){ 
				 $tmp = explode('.',$_FILES['sub']['name'][$key]);
				 $file_ext = strtolower(end($tmp));
				 /*if(in_array($file_ext,$extensions)=== false){
					 $errors[]="Rozszerzenie niedozwolone.";
				 } */
				 if($file_size[$key] > 2097152){
					 $errors[]='Plik nie może być większy niż 2 MB.';
				 } 
			 }  
			 if(empty($errors)==true){

				mkdir("Pliki/".$jid."/".$_POST['user_id'], 0777);
				 
				 foreach($file_name as $key => $value){ 
					 move_uploaded_file($file_tmp[$key],"Pliki/".$jid."/".$_POST['user_id']."/".$file_name[$key]);
					 echo "Pliki poprawnie wysłane!";
				 } 
			 }
	}
	if (isset ($_POST['user_del']))
	{	
		$del = R::findOne('assignment', ' id_user = ? AND id_job = ? ', [ $_POST['user_id'], $jid ]);
		R::trash( $del );
	}
	if (isset ($_POST['uzytkownik']))
	{
			$log = R::findOne('user', 'login = ? ', [$_POST ['uzytkownik']]);
		
			$uz=$log->id;

			$par = R::dispense('assignment');
			$par->id_user=$uz;
			$par->id_job=$jid;
			$par->rola=2;
			$par->status=1;
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
			$ass = R::findOne('assignment', 'id = ? AND id_user = ?', [$jid, $_SESSION['user']] );
			
			if($ass['rola']==1) $access=1;
			else $access=2;
		?>
		
		<?php 
		if( $access == 1)
		{
		?>
		
		<br>
		Dodaj użytkownika
		<form action="" method="post">
		<input type="text" name="uzytkownik" required>
		<input type="hidden" name="task_id" value= <?php echo '"'.$jid.'"'; ?>> 
		<button type="submit">Dodaj</button>
		</form>	
		
		<?php
		}
		
		$us = R::findOne('job', 'id = ?', [$jid] );
			
			echo $us['nazwa'];
			echo '<br>Tresc:<br>';
			echo $us['tresc'];
			echo '<br>Termin:<br>';
			echo $us['termin'];
			echo '<br>';
			echo 'Załączniki';
			echo '<br>';
			
			$katalog = 'Pliki/'.$jid.'/zalaczniki';
			$pliki = scandir($katalog);
			foreach($pliki as $plik)
			{
				if($plik != '.' && $plik != '..')
				{
					echo '<a href="';
					echo 'Pliki/'.$jid.'/zalaczniki/'.$plik;
					echo '"';
					echo 'download="'.$plik.'">';
					echo $plik.'<br>';
					echo '</a>';
				}
			}
			
			
			
		
		$part = R::find('assignment', ' id_job = ? ', [$jid] );
		
		foreach ($part as $usr)
		{
			$us = R::findOne('user', 'id = ?', [$usr->id_user] );
			
			echo '<br>'.$us['login'];
			
			if( $access==1 && $us['id'] != $_SESSION['user'])
			{
			?>
				<form action="" method="post">
				<input type="hidden" name="user_id" value= <?php echo '"'.$us['id'].'"'; ?>> 
				<input type="hidden" name="task_id" value= <?php echo '"'.$jid.'"'; ?>> 
				<button type="submit" name="user_del">Usuń</button>
				</form>	
			<?php
			
			$katalog = 'Pliki/'.$jid.'/'.$us['id'];
			if(is_dir($katalog))
			{
				echo 'Przesłane pliki: <br>';
				
				$pliki = scandir($katalog);
				foreach($pliki as $plik)
				{
					if($plik != '.' && $plik != '..')
					{
						echo '<a href="';
						echo 'Pliki/'.$jid.'/'.$us['id'].'/'.$plik;
						echo '"';
						echo 'download="'.$plik.'">';
						echo $plik.'<br>';
						echo '</a>';
					}
				}
			}
			}
			
			echo '<br>';
		}
		?>
		<br>
		Wyślij Pliki
		<form action="" method="post" enctype="multipart/form-data">
			<input type="file" class="custom-file-input"  name="sub[]" multiple="">
			<input type="hidden" name="user_id" value= <?php echo '"'.$_SESSION['user'].'"'; ?>> 
			<input type="submit" name='submit' value="Wyślij" target="self">
		</form>
		
		<?php
		
		$katalog = 'Pliki/'.$jid.'/'.$_SESSION['user'];
		if(is_dir($katalog))
		{
			echo 'Twoje wysłane pliki <br>';
			
			$pliki = scandir($katalog);
			foreach($pliki as $plik)
			{
				if($plik != '.' && $plik != '..')
				{
					echo '<a href="';
					echo 'Pliki/'.$jid.'/'.$_SESSION['user'].'/'.$plik;
					echo '"';
					echo 'download="'.$plik.'">';
					echo $plik.'<br>';
					echo '</a>';
				}
			}
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
