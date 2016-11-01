<?
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] .'/bd/connect.php';

class user {
	// необходимые переменные для работы класса
	private $_pdo;

	// конструктор класса
	function __construct() {
		$this->_pdo = new PDO(connect::$db,connect::$us,connect::$pa);
	}

	// авторизация
	public function login($data) {
		// необходимые переменные
		$message['message'] = 'Вы успешно залогинились!';
		$message['status'] = 'ok';

		/*==========================================================*/
		// проверка входных данных
		if(!($this->checkParam($data))) {
			$message['message'] = 'Неверные входные данные!';
			$message['status'] = 'bad';
			return $message;
		}

		/*==========================================================*/
		// проверка соответсвия эмейла
		$query = $this->_pdo->prepare("SELECT COUNT(mail) FROM users WHERE mail = ?");
		$query->execute(array($data['mail']));
		// вытаскиваем результат
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			$countMail = $row['COUNT(mail)'];
		}
		// 
		if($countMail != 1) {
			$message['message'] = "Логин или пароль неверен!";
			$message['status'] = 'bad';
			return $message;
		}

		/*==========================================================*/
		// проверка соответсвия пароля
		$query = $this->_pdo->prepare("SELECT password FROM users WHERE mail = ?");
		$query->execute(array($data['mail']));
		// вытаскиваем результат
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			$password = $row['password'];
		}
		// 
		if($password != md5($data['pas'])) {
			$message['message'] = 'Логин или пароль неверен!';
			$message['status'] = 'bad';
			return $message;
		}

		/*==========================================================*/
		// начинаем сессию
		session_start();
		// проверка соответсвия пароля
		$query = $this->_pdo->prepare("SELECT name, mail, type, id FROM users WHERE mail = ?");
		$query->execute(array($data['mailLogin']));
		// вытаскиваем результат
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			$_SESSION['user']['name'] = $row['name'];
			$_SESSION['user']['mail'] = $row['mail'];		
			$_SESSION['user']['type'] = $row['type'];
			$_SESSION['user']['id'] = $row['id'];	
		}
		return $message;
	}

	// регистрация
	public function registration($data) {
		// необходимые переменные
		$message['message'] = 'Регистрация прошла успешно!';
		$message['status'] = 'ok';

		/*==========================================================*/
		// проверка наличия регистраций с одного адреса за 1 минут
		$countReg = $this->checkTime();
		if($countReg > 0) {
			$message['message'] = "Повторная регистрация невозможна в течении 1 минут!";
			$message['status'] = 'bad';
			return $message;
		}

		/*==========================================================*/
		// проверка входных данных
		if(!($this->checkParam($data))) {
			$message['message'] = 'Неверные входные данные!';
			$message['status'] = 'bad';
			return $message;
		}

		/*==========================================================*/
		// проверяем наличие эмейла в базе
		// запрос на поиск емэйла
		$emailCheck = $this->_pdo->prepare("SELECT COUNT(mail) FROM users WHERE mail = ?");
		$emailCheck->execute(array($data['mail']));
		// вытаскиваем результат
		while ($row = $emailCheck->fetch(PDO::FETCH_ASSOC)) {
			$result = $row['COUNT(mail)'];
		}
		// если почта уже есть, завершаем процесс регистрации
		if($result == 1) {
			$message['message'] = 'Почта занята';
			$message['status'] = 'bad';
			return $message;
		}

		/*==========================================================*/
		// вставляем в базу данные для регистрации
		// шифруем пароль
		$password = md5($data['pas']);
		// производим вставку данных
		$insertData = $this->_pdo->prepare("INSERT INTO users (name, phone, mail, password) VALUES (? ,? ,? ,?)");
		$result = $insertData->execute(array(
			$data['name'],
			$data['phone'],
			$data['mail'],
			$password
		));
		// проверяем результат операции
		if($result != 1) {
			$message['message'] = 'Ошибка записи данных в базу';
			$message['status'] = 'bad';
			return $message;
		}

		// запись времени регистрации с данного адреса
		$this->writeTime();

		// возвращаем сообщение с результатом операции
		return $message;
	}

	// разлогиниться
	public function logOut() {
		/*==========================================================*/
		// выход из аккаунта
		$_SESSION['user']['name'] = null;
		$_SESSION['user']['mail'] = null;		
		$_SESSION['user']['type'] = null;
		$_SESSION['user']['id'] = null;

		return $message = "Разлогинился!";
	}

	// прогон входных данных для обнаружения нежелательных данных
	private function checkParam($data) {
		$result = true;
		foreach ($data as &$elem) {
			if ($elem != htmlspecialchars($elem, ENT_QUOTES)) {
				$result = false;
			}
		}
		return $result;
	}

	// защита от множественных регистраций запись времени
	private function writeTime() {
		// получаем ip адрес посетителя
		$ip = $_SERVER['REMOTE_ADDR'];

		/*==========================================================*/
		// запись времени регистрации с этого адреса
		$insertData = $this->_pdo->prepare("INSERT INTO ipTable (time, ip) VALUES (? ,?)");
		$result = $insertData->execute(array(
			time(),
			$ip
		));
	}

	// защита от множественных регистраций
	function checkTime() {
		// получаем ip адрес посетителя
		$ip = $_SERVER['REMOTE_ADDR'];

		/*==========================================================*/
		// проверка времени регистрации с этого адреса
		$query = $this->_pdo->prepare("SELECT COUNT(id) FROM ipTable WHERE time > ? AND ip = ?");
		$query->execute(array(time() - 60, $ip));
		// вытаскиваем результат
		while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
			$countId = $row['COUNT(id)'];
		}
		return $countId;
	}
}

?>