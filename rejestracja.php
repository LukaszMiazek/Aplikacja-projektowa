<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Rejestracja</title>
	<link rel="stylesheet" type="text/css" href="style.css"> 
	
<style>	

body {font-family: Arial, Helvetica, sans-serif;}

input{
	padding: 10px;
	width:20%;

}

.form-element{
  padding: 2px;
  text-align: center;

}
.link{
	  text-align: center;
}
a:link, a:visited {
  font-size:12px;
  background-color: #f44336;
  color: white;
  padding: 14px 0px;
  width: 20%;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  border-radius: 15px;
  
}

a:hover, a:active {
  background-color: red;
}

.button{
	text-align: center;
}


button {
	font-size:12px;
  background-color: #04AA6D;
  color: white;
  padding: 14px 0px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 20%;
  border-radius: 15px;
  text-align: center;
}

button:hover {
  opacity: 0.8;
}

.center{
  margin: auto;
  width: 50%;
  padding: 200px;
}
input[type="file"] {
    display: none;
}
.plik {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
	
	
}

	
</style>	
</head>
	
</head>

<?php
session_start();
	
if (isset ($_POST['wlog']))
{
		unset($_POST['wlog']);
		session_destroy();
}

$conn = mysqli_connect("localhost", "root", "", "magazyn");

if (mysqli_connect_errno()) {
 echo "Błąd połączenia nr: " . mysqli_connect_errno();
 echo "Opis błędu: " . mysqli_connect_error();
 exit();
}

mysqli_query($conn, 'SET NAMES utf8');
mysqli_query($conn, 'SET CHARACTER SET utf8');
mysqli_query($conn, "SET collation_connection = utf8_polish_ci"); 
?>
<body>
	<header>
	</header>
	
<?php
if (!isset ($_POST['rejstatus']))
{	
	?>
		<div class="center">
		<div class="form">
			<div class="tab-content">

				<div class="tab-body">
						<form action="rejestracja.php" method="post" enctype='multipart/form-data'>
							
											<div class="form-element">
												<label for="login"><b>Login</b></label><br>
												<input type="text" placeholder="Login" name="login" required>
											</div>
											<div class="form-element">
											<label for="login"><b>Hasło</b></label><br>
												<input type="password" placeholder="Hasło" name="haslo"	required>
											</div>
											<div class="form-element">
											<label for="login"><b>Imie</b></label><br>
												<input type="text" placeholder="Imię" name="imie" required>
											</div>
											<div class="form-element">
											<label for="login"><b>Nazwisko</b></label><br>
												<input type="text" placeholder="Nazwisko" name="nazwisko" required>
											</div>
											<div class ="form-element">
											<label for="img" class="plik">
												Załadować PNG
																					</label>
												<input id='img' name="img" type="file"/>
											</div>
												
											
											<div class="form-element">
												<button type="submit" value="Utwórz konto" name="submit">Utwórz konto</button>
											</div>
											<input type="hidden" name="rejstatus" value="0">
							</form>
					</div>

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
