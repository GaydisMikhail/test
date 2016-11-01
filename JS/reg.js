/*==========================================================*/
// Код для регистрации 
// нажатие на кнопку регистрации
$('#registration').click(function() {
	registration(
		$('#name').val(),
		$('#mail').val(),
		$('#phone').val(),
		$('#pas').val(),
		$('#pas2').val()
		);
});
// регистрация
function registration(name,mail,phone,pas,pas2) {
	// делаем поле вывода сообщения все поля поумолчянию
	clearMessage();
	// проверка емэйла
	if(mail == '' ) {
		$('#registration-message').html('Емэйл не заполнен!');
		$('#mail').css('border-color','red');
		return false;
	} else {
		$('#mail').css('border-color','#ccc');
	}
	// валидация емэйла
	if(!(checkEmail(mail))) {
		$('#registration-message').html('Email указан не верно!');
		$('#mail').css('border-color','red');
		return false;
	} else {
		$('#mail').css('border-color','#ccc');
	}
	// пустой ли пароль?
	if(pas == '') {
		$('#registration-message').html('Пароль не указан!');
		$('#pas').css('border-color','red');
		return false;
	} else {
		$('#pas').css('border-color','#ccc');
	}
	// проверка паролей
	if(pas != pas2) {
		$('#registration-message').html('Пароли не совпадают!');
		$('#pas, #pas2').css('border-color','red');
		return false;
	} else {
		$('#pas, #pas2').css('border-color','#ccc');
	}
	// если все ок, то пускаем запрос
	$('#registration-message').html('');
	$.ajax({
		url:'/',
		type:'post',
		cashe:'false',
		data:{
			'registration':'0',
			'name':name,
			'mail':mail,
			'phone':phone,
			'pas':pas
		},
		dataType:'json',
		success:function(data) {
			if(data.status == 'ok') {
				$('#registration-message').parent().css('color','green');
			} else {
				$('#registration-message').parent().css('color','red');
			}
			$('#registration-message').html(data.message);
		}
	});
}

/*==========================================================*/
// код для входа
// нажатие на кнопку входа
$('#login').click(function() {
	login(
		$('#mailLogin').val(),
		$('#pasLogin').val()
		);
});
// вход
function login(mail,pas) {
	// делаем поле вывода сообщения все поля поумолчянию
	clearMessage();
	// проверка емэйла
	if(mail == '' ) {
		$('#login-message').html('Емэйл не заполнен!');
		$('#mailLogin').css('border-color','red');
		return false;
	} else {
		$('#mailLogin').css('border-color','#ccc');
	}
	// валидация емэйла
	if(!(checkEmail(mail))) {
		$('#login-message').html('Email указан не верно!');
		$('#mailLogin').css('border-color','red');
		return false;
	} else {
		$('#mailLogin').css('border-color','#ccc');
	}
	// пустой ли пароль?
	if(pas == '') {
		$('#login-message').html('Пароль не указан!');
		$('#pasLogin').css('border-color','red');
		return false;
	} else {
		$('#pasLogin').css('border-color','#ccc');
	}
	// если все ок, то пускаем запрос
	$.ajax({
		url:'/',
		type:'post',
		cashe:'false',
		data:{
			'login':'0',
			'mail':mail,
			'pas':pas
		},
		dataType:'json',
		success:function(data) {
			if(data.status == 'ok') {
				$('#login-message').parent().css('color','green');
			} else {
				$('#login-message').parent().css('color','red');
			}
			$('#login-message').html(data.message);
		}
	});
}

/*==========================================================*/
// вспомогательные функции
// проверка емэйла
function checkEmail(email) {
	var pattern=/[0-9a-z_]+@[0-9a-z_]+\.[a-z]{2,5}/i;
	result = pattern.test(email);
	return result;
}

// приводи все поля к виду по умолчанию
function clearMessage() {
	$('.message').html('');
	$('.message').parent().css('color','red');

	$('.input-group input').css('border-color','#ccc');
}