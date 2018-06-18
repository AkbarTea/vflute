<?php
$hn = 'localhost';
$db = 'virtual_flute';
$un = 'root';
$pw = '';
global $connection;
$connection = new mysqli($hn, $un, $pw, $db);
// if ($connection->connect_error) die($connection->connect_error);

function db_command($query) {
	global $connection;
	$result = $connection->query($query);
	return $result;
}

function getUserId($login) {
	$query = "
    SELECT * 
    FROM users
    WHERE login = '$login'";
  	$result = db_command($query);
  	$row = $result->fetch_array(MYSQLI_ASSOC);
  	return $row['id'];
}

function getSharedTracksArray() {
	$query = "
		    SELECT * 
		    FROM tracks 
		    JOIN users 
		    ON (tracks.author_id = users.id)
		    WHERE tracks.shared = 1
	    ";
		$result = db_command($query);
		if (!$result) {
		echo "error";
		exit();
	}
	$rows = $result->num_rows;

	$tracks = array();
	for ($i=0; $i < $rows ; $i++) { 
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$tracks[] = array(
		  'trackname' => $row['trackname'],
		  'url' => '../data/tracks/' . $row['trackname'],
		  'author_id' => $row['author_id'], 
		  'name' => $row['name'], 
		  'views' => $row['views'], 
		  'rating' => $row['rating'], 
		);
	}
	return $tracks;
}

function getUserTracksArray($login, $type) {
	$query = "
		SELECT * 
		FROM tracks 
		JOIN users 
		ON (tracks.author_id = users.id)
		WHERE users.login = '$login'
	";
	$result = db_command($query);
	if (!$result) {
		print('null result in getUserTracks');
	}
	$rows = $result->num_rows;
	$tracks = array();
	for ($i=0; $i < $rows ; $i++) { 
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$tracks[] = array(
		  'trackname' => $row['trackname'],
		  'url' => '../data/tracks/' . $row['trackname'],
		  'author_id' => $row['author_id'], 
		  'name' => $row['name'], 
		  'views' => $row['view'], 
		  'rating' => $row['rating'], 
		  'type' => $type, 
		  'shared' => $row['shared'], 
		);
	}

	return $tracks;
}

function db_deleteTrack($trackname) {
	$query = "
		DELETE
		FROM tracks
		WHERE trackname = '$trackname'
	";
	return $result = db_command($query);
}

function db_shareTrack($trackname) {
	$query = "
		UPDATE tracks
		SET shared = 1
		WHERE trackname = '$trackname'
	";
	return $result = db_command($query);
}

function db_addUser($user) {
	$query = "
      INSERT INTO users (login, password, name, surname, email, gender) 
      VALUES('$user[login]', '$user[password]', '$user[name]', '$user[surname]', '$user[email]', '$user[gender]')
    ";
    return $result = db_command($query);
}

function db_changeTrackRating($trackname, $type) {
	$login = $_SERVER['PHP_AUTH_USER'];
	$user_id = getUserId($login);
	if ($type == 'up') {
		$query = "
		UPDATE tracks
		SET rating = rating + 1
		WHERE trackname = '$trackname'
		";
	} else  {
		$query = "
		UPDATE tracks
		SET rating = rating - 1
		WHERE trackname = '$trackname'
		";
	}
	if (!db_command($query)) {
		return false;
	}

	$query = "
      INSERT INTO votes (track_name, user_id, type)
      VALUES('$trackname', $user_id, '$type')
    ";

    return $result = db_command($query);

}

function db_isTrackViewedByUser($trackname) {
	$login = $_SERVER['PHP_AUTH_USER'];
	$currentUserId = getUserId($login);
	$query = "
	    SELECT * 
	    FROM votes
	    WHERE user_id = $currentUserId AND track_name = '$trackname'
    ";
  	$result = db_command($query);
    if (!$result) {
    	return db_updateViews($trackname);
    }
    	return db_updateViews($trackname);
}

function getLastCurrentUserVoteForTrack($trackname) {
	//get last current user vote for current track
	$login = $_SERVER['PHP_AUTH_USER'];
	$currentUserId = getUserId($login);
	$query = "
		    SELECT * 
		    FROM votes 
		    WHERE user_id = $currentUserId AND track_name = '$trackname'
		    ORDER BY id DESC LIMIT 1
	    ";
		$result = db_command($query);
		if (!$result) {
		echo "error";
		exit();
	}
	return $row = $result->fetch_array(MYSQLI_ASSOC);
}

function getSharedTracksAndVotesArray() {
	$query = "
		    SELECT * 
		    FROM tracks 
		    JOIN users ON (tracks.author_id = users.id)
		    WHERE tracks.shared = 1
		    ORDER BY rating DESC
	    ";
		$result = db_command($query);
		if (!$result) {
		echo "error";
		exit();
	}
	$rows = $result->num_rows;

	$tracks = array();
	for ($i=0; $i < $rows ; $i++) { 
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$row2 = getLastCurrentUserVoteForTrack($row['trackname']);
		// $isViewed = db_isTrackViewedByUser($row['trackname']);
		// db_updateViews($row['trackname']);
		// if (isset($row2['type'])) {
			// db_updateViews($row['trackname']);
		// }
		$tracks[] = array(
		  'trackname' => $row['trackname'],
		  'url' => '../data/tracks/' . $row['trackname'],
		  'author_id' => $row['author_id'], 
		  'name' => $row['name'], 
		  'views' => $row['view'], 
		  'rating' => $row['rating'], 
		  'current_user_vote' => isset($row2['type']) ? $row2['type'] : 'empty', 
		);
	}
	return $tracks;
}

function db_updateViews($trackname) {
	$query = "
		UPDATE tracks
		SET view = view + 1
		WHERE trackname = '$trackname'
	";
	return db_command($query);
}