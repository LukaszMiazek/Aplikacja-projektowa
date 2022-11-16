<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Rejestracja</title>
	<link rel="stylesheet" type="text/css" href="style.css"> 
</head>
<?php
session_start();
	
if (isset ($_POST['wlog']))
{
		unset($_POST['wlog']);
		session_destroy();
}
?>
<body>
	<header>
	</header>
	
<?php
if (!isset ($_POST['rejstatus']))
{	
	?>
		<div class="form">
			<div class="tab-content">

				<div class="tab-body">
						<form action="rejestracja.php" method="post" enctype='multipart/form-data'>
							
											<div class="form-element">
												<input type="text" placeholder="Login" name="login" required>
											</div>
											<div class="form-element">
												<input type="password" placeholder="Hasło" name="haslo"	required>
											</div>
											<div class="form-element">
												<input type="text" placeholder="Imię" name="imie" required>
											</div>
											<div class="form-element">
												<input type="text" placeholder="Nazwisko" name="nazwisko" required>
											</div>
											<div class="form-element">
												Obrazki tylko w formacie PNG
												<input name="img" type="file" name="img">
											</div>
											<div class="form-element">
												<button type="submit" value="Utwórz konto" name="submit">Utwórz konto</button>
											</div>
											<input type="hidden" name="rejstatus" value="0">
							</form>
					</div>

			</div>
		</div>
	<?php
}
else if ($_POST['rejstatus'] == "0")
{
	require 'rb-mysql.php';
	R::setup( 'mysql:host=localhost;dbname=tasks','root', '' );

	if (isset ($_POST['login']))
	{
		$lo=$_POST ['login'];
		$ha=$_POST ['haslo'];
		$im=$_POST ['imie'];
		$na=$_POST ['nazwisko'];

		$user = R::dispense( 'user' );
		$user->login=$lo;
		$user->haslo=$ha;
		$user->imie=$im;
		$user->nazwisko=$na;
		$id = R::store( $user );
	
		include_once "class.img.php";
		$img = new Image($_FILES["img"]["tmp_name"]);
		$img->SetMaxSize(1200);
		$img->Save('Profil/'.$id.'.png');
	}
	
	header('Location: index.php');
}
?>		
</body>

</html>