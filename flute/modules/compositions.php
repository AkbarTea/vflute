<?php
require_once('../scripts/service.php');
require_once('../scripts/mydb.php');

function compositions_get($request) {

  $login = $_SERVER['PHP_AUTH_USER'];
  // $tracks = getSharedTracksArray();
  $tracks = getSharedTracksAndVotesArray();
  if (!$tracks) {
    return 'Track list is empty :(';
  }

  $pageContent = '';
  foreach ($tracks as $value) {
    $pageContent .= theme('track_row', $value);
  }
  $content['content'] = $pageContent;
  $pageContent = theme('tracklist', $content);

  return $pageContent;
}

// Обработчик запросов методом POST.
function compositions_post($request) {
  $redirect = redirect_manager($request);
  if (!is_null($redirect)) {
    return $redirect;
  } elseif (isset($request['post']['track_rating_up'])) {
    db_changeTrackRating($request['post']['trackname'], 'up');
    db_isTrackViewedByUser($request['post']['trackname']);
    return redirect();
  } elseif (isset($request['post']['track_rating_down'])) {
    db_changeTrackRating($request['post']['trackname'], 'down');
    db_isTrackViewedByUser($request['post']['trackname']);
    return redirect();
  } else {
    return redirect('new-location');
  }
}