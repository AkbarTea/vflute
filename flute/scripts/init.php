<?php
require_once('service.php');
// Диспатчер. Делает запрос $request в соответствии со структурой $urlconf.
function init($request = array(), $urlconf = array()) {
  // Массив HTTP-ответа.
  $response = array();

  // Шаблон страницы по умолчанию.
  $template = 'page';

  // Массив текущего вывода модулей.
  $c = array();

  // Пробергаем по всем определениям ресурсов и для подходящих URL
  // вызываем процедуры их модулей, соответствующие методу HTTP-запроса.
  $q = isset($request['url']) ? $request['url'] : '';
  $method = isset($request['method']) ? $request['method'] : 'get';
  foreach ($urlconf as $url => $r) {
    $matches = array();
    if ($url == '' || $url[0] != '/') {
      // Если не регулярное выражение, то поверяем на равенство.
      if ($url != $q) {
        continue;
      }
    }
    else {
      // Проверяем соответствие URL запроса регулярному выражению.
      if (!preg_match_all($url, $q, $matches)) {
        continue;
      }
    }

    // Аутентификация и инициализация $request['user'].
    if (isset($r['auth'])) {
      require_once($r['auth'] . '.php');
      $auth = auth($request, $r);
      if ($auth) {
        // Аутентификация вернула заголовки 401.
        return $auth;
      }
    }

    // Шаблон всей страницы можно перекрыть для обрабатываемого ресурса в $urlconf.
    if (isset($r['tpl'])) {
      $template = $r['tpl'];
    }

    // Обработка запроса модулем.
    if (!isset($r['module'])) {
      continue;
    }
    require_once($r['module'] . '.php');
    // Собираем имя функции из имени модуля и метода запроса.
    $func = sprintf('%s_%s', $r['module'], $method);
    if (!function_exists($func)) {
      continue;
    }

    // Собираем параметры в массив.
    $params = array('request' => $request);
    array_shift($matches);
    foreach ($matches as $key => $match) {
      $params[$key] = $match[0];
    }

    // Вызываем обработчик запроса в модуле передавая параметры из $params.
    if ($result = call_user_func_array($func, $params)) {
      if (is_array($result)) {
        $response = array_merge($response, $result);
        // Первый модуль отработал запрос и выставил редирект или not found или forbidden.
        // Другие модули уже не отрабатывают запрос.
        // Т.е. важно в каком порядке стоят модули в массиве $res.
        if (!empty($response['headers'])) {
          return $response;
        }
      }
      else {
        $c['#content'][$r['module']] = $result;
      }
    }
  }

  // Если есть вывод модулей, то выводим его через шаблон страницы или шаблон в $urlconf.
  if (!empty($c)) {
    $c['#request'] = $request;
    $c['page']["$request[url]"] = $request['url'];
    $response['entity'] = theme($template, $c);
  }
  else {
    $response = not_found();
  }

  // Браузер определяет кодировку страницы по кодировке, выдаваемой вебсервером в заголовке HTTP-ответа.
  $response['headers']['Content-Type'] = 'text/html; charset=' . conf('charset');

  return $response;
}