<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>zadanie</title>
    <link rel="stylesheet" href="main_style.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css"/>
</head>
<body>
<?php
	session_start();	
	require 'rb-mysql.php';
	R::setup( 'mysql:host=localhost;dbname=tasks','root', '' );
	
	require 'notification.php';
	
	$jid=$_GET['id'];
	$chk=false;
	
	function delete_directory($dirname) {
 
    if(!file_exists($dirname))
        return false;
	
    if (is_dir($dirname))
         $dir_handle = opendir($dirname);
 
    if (!$dir_handle)
         return false;
 
    while($file = readdir($dir_handle)) {
 
        if ($file != '.' && $file != '..') {
            if (!is_dir($dirname."/".$file))
                unlink($dirname."/".$file);
            else
                delete_directory($dirname.'/'.$file);          
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
 
    return true;
	}
	
	if (isset ($_POST['deadline']))
	{
		$load = R::load('job', $_POST['job_id'] );
		$load->termin = $_POST['deadline'];
		R::store( $load );
	}
	if (isset ($_POST['correct']))
	{	
		$find = R::findOne('assignment', ' id_user = ? AND id_job = ? ', [ $_POST['user_id'], $jid ]);
		$load = R::load('assignment',$find['id']);
		$load->status = 3;
		R::store( $load );
	}
	if (isset ($_POST['end']))
	{	
		$find = R::findOne('assignment', ' id_user = ? AND id_job = ? ', [ $_POST['user_id'], $jid ]);
		$load = R::load('assignment',$find['id']);
		$load->status = 2;
		R::store( $load );
		
		$jb = R::findOne('job', 'id = ?', [$jid] );
		
		if($_POST['corr']==1) notification(6,$jb['tworca'],$_SESSION['user'],$jb['task'],$jid);
		else notification(4,$jb['tworca'],$_SESSION['user'],$jb['task'],$jid);
	}
	if (isset ($_POST['file_delete']))
	{
		$del=$_POST['file_delete'];
		
		$del = str_replace("_???_space_???_", " " ,$del);
		
		unlink($del);
	}
	if (isset ($_POST['job_delete']))
	{	
		$part = R::find('assignment', ' id_job = ? ', [$_POST['job_delete']] );
		R::trashAll( $part );
		
		$jb = R::findOne('job', 'id = ?', [$_POST['job_delete']] );
		$back = $jb['task'];
		R::trash( $jb );
		
		$dirname="Pliki/".$_POST['job_delete'];
		
		delete_directory("Pliki/".$_POST['job_delete']);
		
		header('Location: project.php?id='.$back);
	}
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
					 $errors[]='Plik nie mo??e by?? wi??kszy ni?? 2 MB.';
				 } 
			 }  
			 if(empty($errors)==true){

				$ndir = "Pliki/".$jid."/".$_POST['user_id'];
				
				if ( !is_dir($ndir) )
				{
					mkdir($ndir, 0777);
				}
				 
				 foreach($file_name as $key => $value){ 
					 move_uploaded_file($file_tmp[$key],"Pliki/".$jid."/".$_POST['user_id']."/".$file_name[$key]);
					 echo "Pliki poprawnie wys??ane!";
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
			
			$check = R::findOne('part', 'id_user = ? AND id_task = ?', [ $log->id, $_POST['task'] ]);
		
			if(!empty($check))
			{
				$check2 = R::findOne('assignment', ' id_user = ? AND id_job = ? ', [ $log->id, $jid ]);
				if(empty($check2))
				{
					$par = R::dispense('assignment');
					$par->id_user=$uz;
					$par->id_job=$jid;
					$par->rola=2;
					$par->status=1;
					$id = R::store( $par );
					
					$job = R::findOne('job', 'id = ? ', [$jid]);
					notification(2,$uz,$_SESSION['user'],$job['task'],$jid);
				}
			}
			else
			{
				$chk=true;
				//echo "W tym projekcie nie ma u??ytkownmika o takim loginie ";
			}
	}
	?>
	<div class="banner">
	<?php
	if (isset ($_SESSION['user']))
	{
		
		$log = R::findOne('user', 'id = ? ', [$_SESSION['user']]);
		$us = R::findOne('job', 'id = ?', [$jid] );
		echo "$log->imie $log->nazwisko";
			?>	
		<ul>
			<li><a href="<?php echo "index.php?wlog="?>">Wyloguj si??</a></li>
			<li><a href="<?php echo "project.php?id=".$us['task']?>">Powr??t</a></li>
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
			//$id_pro = $_GET['id'];
	?>
		
		<?php 
			$ass = R::findOne('assignment', 'id_job = ? AND id_user = ?', [$jid, $_SESSION['user']] );
			
			if($ass['rola']==1) $access=1;
			else $access=2;
		
		if( $access == 1)
		{
		?>
		
		<br>
		Dodaj u??ytkownika
		<form action="" method="post">
		<input type="text" name="uzytkownik" required>
		<input type="hidden" name="task_id" value= <?php echo '"'.$jid.'"'; ?>> 
		<input type="hidden" name="task" value= <?php echo '"'.$us['task'].'"'; ?>> 
		<button type="submit">Dodaj</button>
		</form>	
		
		<?php
		if($chk==true) echo "W tym projekcie nie ma u??ytkownmika o takim loginie ";
		?>
		
		<form action="" method="post">
		<input type="hidden" name="task_id" value= <?php echo '"'.$jid.'"'; ?>> 
		<input type="hidden" name="job_delete" value= <?php echo '"'.$jid.'"'; ?>> 
		<button type="submit" >Usu?? zadanie</button>
		</form>	
		
		<?php
		}
			echo $us['nazwa'];
			echo '<br>Tresc:<br>';
			echo $us['tresc'];
			echo '<br><br>Termin:<br>';
			echo $us['termin'];
			if( $access == 1)
			{
				?>
				<form action="" method="post">
				<input type="hidden" name="job_id" value= <?php echo '"'.$jid.'"'; ?>> 
				<input type="date" name="deadline" required>
				<button type="submit" >Zmie?? termin</button>
				</form>	
				<?php
			}
			echo '<br>';
			echo 'Za????czniki';
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
			
			
			
		
		$part = R::find('assignment', ' id_job = ? AND status > 0', [$jid] );
		
		foreach ($part as $usr)
		{
			$u = R::findOne('user', 'id = ?', [$usr->id_user] );
			
			echo '<br>'.$u['login'];
			
			if($usr->status == 2) echo " (Zako??czono)";
			
			if( $access==1 && $u['id'] != $_SESSION['user'])
			{
			?>
				<form action="" method="post">
				<input type="hidden" name="user_id" value= <?php echo '"'.$u['id'].'"'; ?>> 
				<input type="hidden" name="task_id" value= <?php echo '"'.$jid.'"'; ?>> 
				<button type="submit" name="user_del">Usu??</button>
				</form>	
			<?php
			
			$katalog = 'Pliki/'.$jid.'/'.$u['id'];
			if(is_dir($katalog))
			{
				echo 'Przes??ane pliki: <br>';
				
				$pliki = scandir($katalog);
				foreach($pliki as $plik)
				{
					if($plik != '.' && $plik != '..')
					{
						echo '<a href="';
						echo 'Pliki/'.$jid.'/'.$u['id'].'/'.$plik;
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
		
		$curr = date("Y-m-d");
		
		//echo $curr; echo $us['termin'];
		
		if($us['termin'] > $curr && $_SESSION['user'] != $us['tworca'])
		{
			if($ass['status']==1 || $ass['status']==3)
			{
			?>
			<br>
			Wy??lij Pliki
			<form action="" method="post" enctype="multipart/form-data">
				<input type="file" class="custom-file-input"  name="sub[]" multiple="">
				<input type="hidden" name="user_id" value= <?php echo '"'.$_SESSION['user'].'"'; ?>> 
				<input type="submit" name='submit' value="Wy??lij" target="self">
			</form>
			
			<?php
			if($ass['status']==1)
			{
			?>
			Powiadom o uko??czeniu zadania
			<form action="" method="post">
				<input type="hidden" name="user_id" value= <?php echo '"'.$_SESSION['user'].'"'; ?>> 
				<input type="hidden" name="corr" value=0> 
				<input type="submit" name='end' value="Zako????z" target="self">
			</form>
			<?php
			}
			else if($ass['status']==3)
			{
			?>
			Powiadom o poprawkach
			<form action="" method="post">
				<input type="hidden" name="user_id" value= <?php echo '"'.$_SESSION['user'].'"'; ?>>
				<input type="hidden" name="corr" value=1> 
				<input type="submit" name='end' value="Popraw" target="self">
			</form>
			<?php
			}
			}
			else
			{
				echo "Zadanie uko??czone!";
				?>
				<form action="" method="post">
				<input type="hidden" name="user_id" value= <?php echo '"'.$_SESSION['user'].'"'; ?>> 
				<input type="submit" name='correct' value="Popraw" target="self">
				</form>
				<?php
			}
		}
		else if($us['termin'] <= $curr)
		{
			echo "Temin zadania min???? <br>";
		}
		$katalog = 'Pliki/'.$jid.'/'.$_SESSION['user'];
		if(is_dir($katalog))
		{
			echo 'Twoje wys??ane pliki <br>';
			
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
					
					$file='Pliki/'.$jid.'/'.$_SESSION['user'].'/'.$plik;
					
					$file = str_replace(" ", "_???_space_???_", $file);
					
					if($ass['status']==1 || $ass['status']==3)
					{
					?>
						<form action="" method="post">
						<input type="hidden" name="file_delete" value= <?php echo $file; ?>> 
						<input type="submit" name='submit' value="Usu??" target="self">
						</form>
					<?php
					}
				}
			}
		}
	}
	else
	{	
	
		?></div>
		<div class="banner2">
		Musisz si?? zalogowa?? <br><br>
			<a href="index.php">Powr??t</a>
		</div>
		<?php
		
	}
?>
</body>
</html>
