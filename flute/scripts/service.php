<?php 
// Возвращает редирект 302 с заголовком Location.
function redirect($l = NULL, $body = NULL) {
  if (is_null($l)) {
    $location = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  }
  else {
    $location = 'http://' . $_SERVER['HTTP_HOST'] . conf('basedir') . url($l);
  }

  if (!is_null($body)) {
    $location .= '?' . $body['name'] . '=' . $body['data'];
  }

  return array('headers' => array('Location' => $location));
}

function redirect_manager($request) {
  if (isset($request['post']['mainpage'])) {
    return redirect('/');
  } elseif (isset($request['post']['compositions'])) {
    return redirect('compositions');
  } elseif (isset($request['post']['user_compositions'])) {
    return redirect('user_compositions');
  } elseif (isset($request['post']['registrationpage'])) {
    return redirect('registration');
  } elseif (isset($request['post']['flute'])) {
    return redirect('flute');
  } elseif (isset($request['post']['about'])) {
    return redirect('about');
  } else {
    return NULL;
  }
}

// Возвращает 403.
function access_denied() {
  return array(
    'headers' => array('HTTP/1.1 403 Forbidden'),
    'entity' => theme('403'),
  );
}

// Возвращает 404.
function not_found($content = NULL) {
  return array(
    'headers' => array('HTTP/1.1 404 Not Found'),
    'entity' => theme('404', $content),
  );
}

// Функция загрузки шаблона с использованием буферизации вывода.
// $t - tamplate name
// $с - content
// возвращает строку
function theme($t, $c = array()) {
  // Путь к файлу шаблона.
  $template = conf('theme') . '/' . str_replace('/', '_', $t) . '.tpl.php';

  // Если нет файла шаблона, то просто печатаем данные слитно.
  if (!file_exists($template)) {
    return implode('', $c);
  }

  // Начинаем буферизацию вывода.
  ob_start();
  // Парсим и включаем файл шаблона, весь вывод попадает в буфер.
  include $template;
  // Достаем содержимое буфера.
  $contents = ob_get_contents();
  // Оканчиваем буферизацию очищая буфер.
  ob_end_clean();
  // Возвращаем контент.
  return $contents;
}

// Возвращает параметр конфигурации из settings.php.
function conf($key) {
  global $conf;
  return isset($conf[$key]) ? $conf[$key] : FALSE;
}

// Формирует сокращенные URL для ссылок или для текущей страницы.
function url($addr = '', $params = array()) {
  global $conf;
  // Если вызвали без параметров, до делаем ссылку на текущую страницу.
  if ($addr == '' && isset($_GET['q'])) {
    $addr = strip_tags($_GET['q']);
  }
  // В зависимоти от настроек проекта генерируем чистые ссылки или ссылки с параметром.
  $clean = conf('clean_urls');
  $r = $clean ? '/' : '?q=';
  $r .= strip_tags($addr);
  // Добавляем параметры.
  if (count($params) > 0) {
    $r .= $clean ? '?' : '&';
    $r .= implode('&', $params);
  }
  return $r;
}



