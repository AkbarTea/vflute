<?php

function about_get($request) { 
  return 'About';
}

// Обработчик запросов методом POST.
function about_post($request) {
    $redirect = redirect_manager($request);
  if (!is_null($redirect)) {
    return $redirect;
  } else {
    return redirect('new-location');
  }
}