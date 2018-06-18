<?php
  require_once('../scripts/mydb.php');
// auth_basic.php - HTTP Authentication Script v 1.0
//##################################################

function auth(&$request, $r) {
  $users = array(
    'admin' => '123',
    'testuser' => '123',
  );

  $query = "
  SELECT *
  FROM users";
  $result = db_command($query);
  $rows = $result->num_rows;
  for ($i=0; $i < $rows; $i++) { 
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $users[$row['login']] = $row['password'];
  }
  
  $whiteList = array(
    'admin',
    'admin2',
  );

  if (empty($user) && !empty($_SERVER['PHP_AUTH_USER'])) {
    if (in_array($_SERVER['PHP_AUTH_USER'], $whiteList)) 
      $role = 'admin';
    else
      $role = 'user';

    $user = array(
      'login' => $_SERVER['PHP_AUTH_USER'],
      'pass' => $users[$_SERVER['PHP_AUTH_USER']],
      'role' => $role
    );
    $request['user'] = $user;
  }
  if (!isset($_SERVER['PHP_AUTH_USER']) || empty($user) || $_SERVER['PHP_AUTH_USER'] != $user['login'] || $_SERVER['PHP_AUTH_PW'] != $user['pass']) {
    unset($user);
    $response = array(
      'headers' => array(sprintf('WWW-Authenticate: Basic realm="%s"', conf('sitename')), 'HTTP/1.0 401 Unauthorized'),
      'entity' => theme('401', $request),
    );
    return $response;
  }
}