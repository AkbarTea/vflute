<?php

function front_get($request) { ob_start(); ?>
  <h1><?php echo "Main Page" ?></h1>
  <?php
  return ob_get_clean();

  // Пример ответа веб-сервиса.
  return array('headers' => array('Content-Type' => 'application/xml'), 'entity' => '<document />');
  // Пример возврата контента.
  return theme('template', '123');
  // Пример запрета доступа.
  return access_denied();
  // Пример возврата ресурс не найден.
  return not_found();
}

// Обработчик запросов методом POST.
function front_post($request) {
    $redirect = redirect_manager($request);
  if (!is_null($redirect)) {
    return $redirect;
  } else {
    return redirect('new-location');
  }
}