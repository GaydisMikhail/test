<?
	session_start();

	require_once $_SERVER['DOCUMENT_ROOT'] .'/bd/connect.php';

	// подключаем необходимые классы, обчно использую такую конструкцию, чтобы ничего не упустить
	function __autoload($class_name) {
	    include $_SERVER['DOCUMENT_ROOT'] .'/PHP/'.$class_name.'Class.php';
	}

	// инициализируем экземпляр класса
	$user = new user();

	// отправка данных на регистрацию
	if(isset($_POST['registration'])) {
		$message = $user->registration($_POST);
		echo json_encode($message);
		exit();
	}

	// отправка данных для входа
	if(isset($_POST['login'])) {
		$message = $user->login($_POST);
		echo json_encode($message);
		exit();
	}

	// if(isset($_POST['logOut'])) {
	// 	$message = $user->logOut();
	// }
?>

<html>
<head>
	<title>Тестовая регистрация</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

	<style type="text/css">
		.input-group-addon {
			/*width: 30%;*/
			min-width: 100px;
		}

		.input-group {
			width: 100%;
		}
	</style>
</head>
<body>

	<div class="container">
		<div class="row">
			<div class="col-sm-6">
				<h3 style="padding-left:5%;">Вход</h3>
				<div style="color:red;"><b id="login-message" class="message"></b></div>
				<br>
				<!-- поля для входа -->
				<div class="input-group">
				  	<span class="input-group-addon">Email</span>
				  	<input type="text" id="mailLogin" class="form-control" placeholder="Email">
				</div>
				<br>
				<div class="input-group">
				  	<span class="input-group-addon">Пароль</span>
				  	<input type="password" id="pasLogin" class="form-control" placeholder="Пароль">
				</div>
				<br>
				<button class="btn btn-default" id="login">Войти</button>
			</div>
			<div class="col-sm-6">
				<h3 style="padding-left:5%;">Регистрация</h3>
				<div style="color:red;"><b id="registration-message" class="message"></b></div>
				<br>
				<!-- поля для регистрации -->
				<div class="input-group">
				  	<span class="input-group-addon">ФИО</span>
				  	<input id="name" type="text" class="form-control" placeholder="ФИО">
				</div>
				<br>
				<div class="input-group">
				  	<span class="input-group-addon" required >Email*</span>
				  	<input id="mail" type="mail" class="form-control" placeholder="Email">
				</div>
				<br>
				<div class="input-group">
				  	<span class="input-group-addon">Телефон</span>
				  	<input id="phone" type="text" class="form-control" placeholder="+7 (123) 456 78-90">
				</div>
				<br>
				<div class="input-group">
				  	<span class="input-group-addon" required >Пароль*</span>
				  	<input id="pas" type="password" class="form-control" placeholder="Пароль">
				</div>
				<br>
				<div class="input-group">
				  	<span class="input-group-addon" required >Пароль*</span>
				  	<input id="pas2" type="password" class="form-control" placeholder="Повторите пароль">
				</div>
				<br>
				<button class="btn btn-default" id="registration">Зарегистрироваться</button>
				<br><br>
				<p>* - поле обязательное для заполнения</p>
			</div>
		</div>
	</div>

</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

<script src="/JS/reg.js"></script>

</html>