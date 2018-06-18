<?php
require_once("../scripts/service.php");
require_once("../scripts/mydb.php");

function registration_get($request) {
	// Массив для временного хранения сообщений пользователю.
	$messages = array();

	// В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
	// Выдаем сообщение об успешном сохранении.
	if (!empty($_COOKIE['save'])) {
		// Удаляем куку, указывая время устаревания в прошлом.
		setcookie('save', '', 100000);
		// Если есть параметр save, то выводим сообщение пользователю.
		$messages[] = 'Спасибо, результаты сохранены.';
	}

	// Складываем признак ошибок в массив.
	$errors = array();
	$errors['name'] = !empty($_COOKIE['name_error']);
	$errors['surname'] = !empty($_COOKIE['surname_error']);
	$errors['email'] = !empty($_COOKIE['email_error']);

	// Выдаем сообщения об ошибках.
	if ($errors['name']) {
		// Удаляем куку, указывая время устаревания в прошлом.
		setcookie('name_error', '', 100000);
		// Выводим сообщение.
		$messages[] = '<div class="error">Заполните имя.</div>';
	}
	if ($errors['surname']) {
		setcookie('surname_error', '', 100000);
		$messages[] = '<div class="error">Заполните фамилию.</div>';
	} 
	if ($errors['email']) {
		setcookie('email_error', '', 100000);
		$messages[] = '<div class="error">Заполните email.</div>';
	}
	// TODO: тут выдать сообщения об ошибках в других полях.

	// Складываем предыдущие значения полей в массив, если есть.
	$values = array();
	$values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
	$values['surname'] = empty($_COOKIE['surname_value']) ? '' : $_COOKIE['surname_value'];
	$values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];

	$formContent['values']['name'] = $values['name'];
	$formContent['values']['email'] = $values['email'];
	$formContent['values']['surname'] = $values['surname'];
	$formContent['errors']['name'] = $errors['name'];
	$formContent['errors']['surname'] = $errors['surname'];
	$formContent['errors']['email'] = $errors['email'];
	$formContent['messages'] = $messages;

	$pageContent = theme('form', $formContent);
	return $pageContent;
}

function registration_post($request) {
	$redirect = redirect_manager($request);
	if (!is_null($redirect)) {
		return $redirect;
	} else {
		// Проверяем ошибки.
		$errors = FALSE;
		if (empty($_POST['name'])) {
			// Выдаем куку на день с флажком об ошибке в поле fio.
			setcookie('name_error', '1', time() + 24 * 60 * 60);
			setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
			$errors = TRUE;
		} else {
			// Сохраняем ранее введенное в форму значение на месяц.
			setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
		}

		if (empty($_POST['surname'])) {
			setcookie('surname_error', '1', time() + 24 * 60 * 60);
			setcookie('surname_value', $_POST['surename'], time() + 30 * 24 * 60 * 60);
			$errors = TRUE;
		} else {
			setcookie('surname_value', $_POST['surname'], time() + 30 * 24 * 60 * 60);
		}

		if (empty($_POST['email'])) {
			setcookie('email_error', '1', time() + 24 * 60 * 60);
			setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
			$errors = TRUE;
		} else {
			setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
		}

		if ($errors) {
		// При наличии ошибок перезагружаем страницу и завершаем работу скрипта.
		// header('Location: task12.php');
		// exit();
			$location = 'http://' . 'localhost' . conf('basedir') . url('registration');
			return array('headers' => array('Location' => $location));
			return redirect('registration');
		}

		else {
			// Удаляем Cookies с признаками ошибок.
			setcookie('fio_error', '', 100000);
			setcookie('name_error', '', 100000);
			setcookie('surname_error', '', 100000);
		}

		// Сохранение в XML-документ.
		// require_once("../handlers/formtoxml.php");

		// Сохраняем куку с признаком успешного сохранения.
		setcookie('save', '1');
		$user['login'] = fix_string($_POST['login']);;
		$user['password'] = fix_string($_POST['password']);;
		$user['name'] = fix_string($_POST['name']);;
		$user['surname'] = fix_string($_POST['surname']);;
		$user['email'] = fix_string($_POST['email']);;
		$user['gender'] = fix_string($_POST['gender']);;
		db_addUser($user);
		// print_r($user);
		// exit();
		// formtoxml(conf('data'));

		// Делаем перенаправление.
		// header('Location: task12.php');
		// $location = 'http://' . 'localhost' . conf('basedir') . url('registration');
		// return array('headers' => array('Location' => $location));
		return redirect('registration');
	}
}

function formToXml($pathPrefix) {
	$xml = new DomDocument("1.0");

	print_r($_POST);

	$regform = $xml->createElement("regform");
	$xml->formatOutput = true;
	$xml->appendChild($regform);

	$name_str = fix_string($_POST['name']);
	$surename_str = fix_string($_POST['surname']);
	$email_str = fix_string($_POST['email']);
	$gender_str = fix_string($_POST['gender']);
	$biography_str = fix_string($_POST['biography']);

	if (isset($_POST['superpowers'])) {
		foreach ($_POST['superpowers'] as $item) {
		  $superpower_str = fix_string($item);
		  $superpowers_arr[] = $xml->createElement("superpower", $superpower_str);
		} 
	}


	// print_r($superpowers);

	$name = $xml->createElement("name", $name_str);
	// $name->setAttribute("id", 1);
	$surename = $xml->createElement("surename", $surename_str);
	$email = $xml->createElement("email", $email_str);
	$gender = $xml->createElement("gender", $gender_str);
	$biography = $xml->createElement("biography", $biography_str);

	if (isset($_POST['confirm'])) {
	$confirm_str = fix_string($_POST['confirm']);
	$confirm = $xml->createElement("confirm", $confirm_str);
	}
	else {
	$confirm = $xml->createElement("confirm", "off");
	}

	$regform->appendChild($name);
	$regform->appendChild($surename);
	$regform->appendChild($email);
	$regform->appendChild($gender);
	$regform->appendChild($biography);

	if (isset($superpowers_arr)) {
	$superpowers = $xml->createElement("superpowers");

	foreach ($superpowers_arr as $item) {
	  $superpowers->appendChild($item);
	}
	}
	else {
	$superpowers = $xml->createElement("superpowers", "Regular man");
	//$superpowers->appendChild($item);
	}

	$regform->appendChild($superpowers);
	$regform->appendChild($confirm);


	echo "<xmp>" . $xml->saveXML() . "</xmp>";

	//  Уникальность имени файла
	$filename = $pathPrefix . "/xml/reg_report_" . "$_POST[name]" . ".xml";
	if (!$xml->load($filename)) {
	$xml->save($filename) or die("Error, unable to save XML file.");    
	}
	else {
	$postfix = 1;
	$filename = $pathPrefix . "/xml/reg_report_" . "$_POST[name]" . "_$postfix" .".xml";
	while ($xml->load($filename) and $postfix <= 10) {
	  $postfix++;
	  $filename = $pathPrefix . "/xml/reg_report_" . "$_POST[name]" . "_$postfix" . ".xml";
	  echo $filename;
	}
	$xml->save($filename) or die("Error, unable to save XML file.");
	}
}

function add_user($connection, $fn, $sn, $un, $pw) {
	$query = "INSERT INTO users VALUES('$fn', '$sn', '$un', '$pw')";
	$result = $connection->query($query);
	if (!$result) die($connection->error);
}

function fix_string($string) {
	$string = stripslashes($string);// Удаляет экранирование символов (вырезает обратные слеши из строки)
	$string = strip_tags($string);//полностью очищает введенные данные от HTML
	$string = htmlentities($string);//заменяет все угловые скобки HTML тегов
	return $string;
}

function mysql_fix_string($connection, $string) {
	$string = $connection->real_escape_string($string);
	$string = fix_string($string);
	return $string;
}