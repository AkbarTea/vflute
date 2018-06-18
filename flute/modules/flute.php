<?php
require_once('../scripts/service.php');
function flute_get($request) {
  $pageContent = theme('flute');
  return $pageContent;
}

// Обработчик запросов методом POST.
function flute_post($request) {
  $redirect = redirect_manager($request);
  if (!is_null($redirect)) {
    return $redirect;
  } elseif (isset($request['files'])) {
  	// return redirect('new-location');
	print_r($request['files']);
	print_r($request['post']);

  // name of the directory where the files should be stored
	$targetdir = '../data/tracks/';
	// name of targetfile to save
  $targetfile = $targetdir . $request['post']['fname'];
  // tmp name of file loaded on server
	$tmpfile = $request['files']['data']['tmp_name'];

	if (move_uploaded_file($tmpfile, $targetfile)) {
		// file uploaded
		print("success file upload");
    // add track to db
    require_once('../scripts/mydb.php');
    $trackname = $request['post']['fname'];
    $author_id = getUserId($_SERVER['PHP_AUTH_USER']);
    $views = 0;
    $rating = 0;
    $shared = 0;
    $query = "
      INSERT INTO tracks (trackname, author_id, rating, shared, view) 
      VALUES('$trackname', $author_id, $rating, $shared, $views)
    ";
    $result = db_command($query);
	} else { 
		// file upload failed
		print("file upload failed");
	}
	exit();
  } else {
    return redirect('new-location');
  }
}

// [data] => Array
//         (
//             [name] => blob
//             [type] => audio/ogg
//             [tmp_name] => /opt/lampp/temp/phpvmYYDB
//             [error] => 0
//             [size] => 20879
//         )