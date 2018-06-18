<?php

// Выключаем отображение ошибок после отладки.
define('DISPLAY_ERRORS', 1);

// По возможности кладём скрипты и включаемые файлы выше
// публично доступной директории из соображений безопасности.

// Папки со скриптами и модулями.
define('INCLUDE_PATH', 
  '../scripts' . PATH_SEPARATOR .
  '../modules' . PATH_SEPARATOR .
  '../data/xml' . PATH_SEPARATOR .
  '../theme'
  );

// Храним настройки в массиве чтоб легче было смотреть (print_r),
// хранить (serialize), оверрайдить и не плодить глобалов.
$conf = array(
  'sitename' => 'Demo Framework',
  'theme' => '../theme',
  'data' => '../data',
  'charset' => 'UTF-8',
  'clean_urls' => TRUE,
  'display_errors' => 1,
  'date_format' => 'Y.m.d',
  'date_format_2' => 'Y.m.d H:i',
  'date_format_3' => 'd.m.Y',
  'basedir' => '/flute/public_html',
  'login' => 'admin',
  'password' => '123',
  'admin_mail' => 'sin@kubsu.ru',
);

// Определения ресурсов для диспатчера.
$urlconf = array(
  '' => array('module' => 'front', 'tpl' => 'basepage'),
  '/^\/$/' => array('module' => 'front', 'tpl' => 'basepage'),
  '/^admin$/' => array('module' => 'admin', 'auth' => 'auth_basic', 'tpl' => 'basepage'),
  '/^registration$/' => array('module' => 'registration', 'auth' => 'auth_basic', 'tpl' => 'basepage'),
  '/^urilist$/' => array('module' => 'urilist', 'auth' => 'auth_basic', 'tpl' => 'basepage'),
  '/^flute$/' => array('module' => 'flute', 'auth' => 'auth_basic', 'tpl' => 'basepage'),
  '/^user_compositions$/' => array('module' => 'user_compositions', 'auth' => 'auth_basic', 'tpl' => 'basepage'),
  '/^compositions$/' => array('module' => 'compositions', 'auth' => 'auth_basic', 'tpl' => 'basepage'),
  '/^about$/' => array('module' => 'about', 'auth' => 'auth_basic', 'tpl' => 'basepage'),
);

// Отрубаем кеш.
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
// Выдаем кодировку.
header('Content-Type: text/html; charset=' . $conf['charset']);