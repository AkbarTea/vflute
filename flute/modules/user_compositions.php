<?php
require_once('../scripts/service.php');
require_once('../scripts/mydb.php');

function user_compositions_get($request) { 
  // print($_SERVER['PHP_AUTH_USER']);
  // exit();
  $login = $_SERVER['PHP_AUTH_USER'];
  $tracks = getUserTracksArray($login, 'private');
  if (!$tracks) {
    return 'Your track list is empty :(';
  }
  $pageContent = '';
  foreach ($tracks as $value) {
    $pageContent .= theme('track_row', $value);
  }

  return $pageContent;
}

// Обработчик запросов методом POST.
function user_compositions_post($request) {
  $redirect = redirect_manager($request);
  if (!is_null($redirect)) {
    return $redirect;
  } elseif (isset($request['post']['track_delete'])) {
    $targetfile = '../data/tracks/' . $request['post']['trackname'];
    unlink($targetfile);
    db_deleteTrack($request['post']['trackname']);
    return redirect();
  } elseif (isset($request['post']['track_share'])) {
      db_shareTrack($request['post']['trackname']);
    return redirect();
  } else {
    return redirect('new-location');
  }
}