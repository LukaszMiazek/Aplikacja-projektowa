<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>projekt</title>
    <link rel="stylesheet" href="main_style.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css"/>
</head>
<body>
<?php
	session_start();	
	require 'rb-mysql.php';
	R::setup( 'mysql:host=localhost;dbname=tasks','root', '' );
	
	require 'notification.php';
	
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
	
	if (isset ($_POST['task_delete']))
	{
		$jb = R::findOne('task', 'id = ?', [$_POST['task_delete']] );
		$part = R::find('part', ' id_task= ? ', [$_POST['task_delete']] );
		
		$jobs = R::find('job', ' task = ? ', [$_POST['task_delete']] );
		foreach ($jobs as $job)
		{
			$ass = R::find('assignment', ' id_job = ? ', [ $job->id ] );
			R::trashAll( $ass );
			
			delete_directory("Pliki/".$job->id);
		}
		
		R::trashAll( $jobs );
		R::trashAll( $part );
		R::trash( $jb );
		
		header('Location: main.php');
	}
	if (isset ($_POST['user_mod']))
	{	
		$mod = R::findOne('part', ' id_user = ? AND id_task = ? ', [ $_POST['user_id'], $_POST['task_id'] ]);
		
		$zm = R::load( 'part', $mod['id'] );
		
		if($zm->role == 1)
		{
			$zm->role = 2;
		}
		else
		{
			$zm->role = 1;
		}
		
		R::store( $zm );
	}
	if (isset ($_POST['user_del']))
	{	
		/* $del = R::find('part', ' id_user = ? AND id_task = ? ', [ $_POST['user_id'], $_POST['task_id'] ]);
		foreach ($del as $dl) { R::trash( $dl ); }*/
		
		$del = R::findOne('part', ' id_user = ? AND id_task = ? ', [ $_POST['user_id'], $_POST['task_id'] ]);
		R::trash( $del );
	}
	if (isset ($_POST['uzytkownik']))
	{
			$log = R::findOne('user', 'login = ? ', [$_POST ['uzytkownik']]);
		
			$check = R::findOne('part', 'id_user = ? AND id_task = ?', [ $log->id, $_POST ['task_id'] ]);
		
			if(empty($check))
			{
				$uz=$log->id;
				$ro=$_POST ['rola'];
				$id_t=$_POST ['task_id'];

				$par = R::dispense('part');
				$par->id_user=$uz;
				$par->id_task=$id_t;
				$par->role=$ro;
				$id = R::store( $par );
				
				notification(1,$uz,$_SESSION['user'],$id_t,NULL);
			}
	}?>
	
	
	<div class="banner">
	<?php
	if (isset ($_SESSION['user']))
	{	
		$log = R::findOne('user', 'id = ? ', [$_SESSION['user']]);
	
		echo "$log->imie $log->nazwisko";
			?>	
		<ul>
			<li><a href="<?php echo "index.php?wlog="?>">Wyloguj się</a></li>
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
			$id_pro = $_GET['id'];
	?>
		
		
		
		<?php 
			$acc = R::findOne('part', 'id_task = ? AND id_user=?', [$id_pro, $_SESSION['user']] );
			
			if($acc['role']==3) $access=3;
			else if($acc['role']==2) $access=2;
			else $access=1;

			if( $access == 3 )
			{
		?>
			<form action="edit_project.php" method="post">
				<input type="hidden" name="task" value= <?php echo '"'.$id_pro.'"'; ?>> 
				<button type="submit">Edytuj projekt</button>
			</form>	
			
			<form action="" method="post">
			<input type="hidden" name="task_delete" value= <?php echo '"'.$id_pro.'"'; ?>> 
			<button type="submit">Usuń projekt</button>
			</form>	
		<?php 
			}
		?>
		
		<?php 
			$us = R::findOne('task', 'id = ?', [$id_pro] );
			
			echo 'NAZWA: ';
			echo $us['nazwa'];
			echo '<br>OPIS: ';
			echo $us['opis'];
			
			
		if( $access > 1)
		{
		?>
		
		<br><br>
		Dodaj użytkownika
		<form action="" method="post">
		<input type="text" name="uzytkownik" required>
		<select name="rola">
		  <option value="1">Członek</option>
		<?php if( $access ==3) { ?><option value="2">Moderator</option><?php } ?>
		</select>
		<input type="hidden" name="task_id" value= <?php echo '"'.$id_pro.'"'; ?>> 
		<button type="submit">Dodaj</button>
		</form>	
		<?php
		if( $access > 2)
		{
			?>
			
		
			
			
			<?php
		}
		}
		
		$part = R::find('part', ' id_task = ? ', [$id_pro] );
		
		foreach ($part as $usr)
		{
			$us = R::findOne('user', 'id = ?', [$usr->id_user] );
			
			$nm = R::findOne('role_names', 'id = ? AND id != 1', [$usr->role] );
			
			echo '<br>'.$us['login'].' '.$nm['nazwa'];
			
			if( $access==3 && $usr->role!=3 || $access==2 && $usr->role==1)
			{
			?>
				<form action="" method="post">
				<input type="hidden" name="user_id" value= <?php echo '"'.$us['id'].'"'; ?>> 
				<input type="hidden" name="task_id" value= <?php echo '"'.$id_pro.'"'; ?>> 
				<button type="submit" name="user_del">Usuń</button>
				</form>	
				
				
			<?php
			}
			if( $access==3 && $usr->role!=3 )
			{
			?>
				<form action="" method="post">
				<input type="hidden" name="user_id" value= <?php echo '"'.$us['id'].'"'; ?>>
				<input type="hidden" name="task_id" value= <?php echo '"'.$id_pro.'"'; ?>> 
				<button type="submit" name="user_mod">Moderator</button>
				</form>	
			<?php
			}
		}
		
		if( $access > 1)
		{
		?>
		<form action="new_job.php" method="post">
		<input type="hidden" name="task_id" value= <?php echo '"'.$id_pro.'"'; ?>> 
		<button type="submit">Dodaj nowe zadanie</button>
		</form>	
		ZADANIA:
		 <div class="array_projects"><ul>
		<?php
		}
		
		$job = R::find('job', ' task = ? ', [$id_pro] );
		
		foreach ($job as $jb)
		{
			$ass = R::findOne('assignment', 'id_job = ? AND id_user = ?', [$jb->id, $_SESSION['user']] );
			
			?>
				<li><a href="job.php?id=<?php echo "$jb->id"?>"><?php echo "$jb->nazwa"?></a></li>
			<?php

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
</body>
</html>
