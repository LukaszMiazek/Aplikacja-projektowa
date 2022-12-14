@charset "utf-8";

html{
	 font-family: Arial, sans-serif;
}


body{
	margin: 0px;
	padding: 0px;
	background-image: url("background.png");
	color: white;
	text-transform: uppercase;

}

.banner{
	width: 85%;
	padding:  35px 0px;
	margin: auto;
	display: flex;
    align-items: center;
	justify-content: space-between;
}

.imie_nazwisko{
	width: 300 px;
	cursor: pointer;
	text-transform: uppercase;

}
.center{
	padding-top: 50px;
	text-align: center;
	list-style:none;
	margin: 15px;
	text-decoration: none;
	color: #fff;
}

.banner ul li{
	list-style:none;
	display: inline-block;
	margin: 10px;
	padding-right: 0px;
}
.banner ul li a {
	text-decoration: none;
	color: #fff;
	text-transform: uppercase;
}


.form-element{
  padding: 16px;
  text-align: center;
  color: white; 
}
.link{
	  text-align: center;
}


img.obrazek{
	width: 10%;
	border-radius: 50%;
	
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
  width: 10%;
  border-radius: 15px;
  text-align: center;
}

button:hover {
  opacity: 0.8;
}

input{
	padding: 10px;
	width:16%;
}