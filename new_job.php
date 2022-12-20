<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>nowy</title>
    <link rel="stylesheet" href="main_style.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css"/>
</head>
<body>
<div class="banner">
<?php
	session_start();	
	require 'rb-mysql.php';
	R::setup( 'mysql:host=localhost;dbname=tasks','root', '' );

	if (isset ($_SESSION['user']))
	{
		$tid=$_POST['task_id'];
		$sid = strval($tid);
		$log = R::findOne('user', 'id = ? ', [$_SESSION['user']]);
		
		echo "$log->imie $log->nazwisko";
			?>	
		<ul>
			<li><a href="<?php echo "index.php?wlog="?>">Wyloguj się</a></li>
			<li><a href="main.">Powrót</a></li>
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
		if (!isset ($_POST['nazwa']))
		{	
			?>
				<div class="form">
					<div class="tab-content">

						<div class="tab-body">
								<form action="new_job.php" method="post" enctype="multipart/form-data">
									
													<div class="form-element">
														 <input type="text" placeholder="nazwa" name="nazwa" required>
														<br>
														Treść:
														<br>
														<textarea name="tresc" ></textarea>
														<br>
														<input type="date" name="deadline">
														<br>
														<input type="file" class="custom-file-input"  name="image[]" multiple="">
														<br>
														<input type="submit" name='submit' value="Utwórz" target="self">
														<input type="hidden" name="task_id" value= <?php echo '"'.$tid.'"'; ?>> 
														</form>
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
			$op=$_POST ['tresc'];
			$dl=$_POST ['deadline'];

			$job = R::dispense('job');
			$job->nazwa=$na;
			$job->tresc=$op;
			$job->termin=$dl;
			$job->task=$tid;
			$job->tworca=$_SESSION['user'];
			$id = R::store( $job );
			
			$fid=$id;
			
			$par = R::dispense('assignment');
			$par->id_user=$_SESSION['user'];
			$par->id_job=$id;
			$par->rola=1;
			$par->status=0;
			$id = R::store( $par );
			
			if(isset($_FILES['image'])){
			 $errors= array();
			 $file_name = $_FILES['image']['name'];
			 $file_size =$_FILES['image']['size'];
			 $file_tmp =$_FILES['image']['tmp_name']; 
			 $file_type=$_FILES['image']['type'];
			 //$extensions= array("jpeg","jpg","png", "webp", "pdf"); 
			 foreach($file_name as $key => $value){ 
				 $tmp = explode('.',$_FILES['image']['name'][$key]);
				 $file_ext = strtolower(end($tmp));
				 /*if(in_array($file_ext,$extensions)=== false){
					 $errors[]="Rozszerzenie niedozwolone.";
				 } */
				 if($file_size[$key] > 2097152){
					 $errors[]='Plik nie może być większy niż 2 MB.';
				 } 
			 }  
			 if(empty($errors)==true){

				mkdir("Pliki/".$fid, 0777);
				mkdir("Pliki/".$fid."/zalaczniki", 0777);
				 
				 foreach($file_name as $key => $value){ 
					 move_uploaded_file($file_tmp[$key],"Pliki/".$fid."/zalaczniki/".$file_name[$key]);
					 //echo "Pliki poprawnie wysłane!";
				 } 
			 }
			 else{
			 print_r($errors);
			 }
			}
			header('Location: project.php?id='.$tid);
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
