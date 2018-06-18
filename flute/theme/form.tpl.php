<div class="wrapper">
<form method="post" name="registration" id="target" action="">
  <ul class="wrapper">
    <h3 class="title">Registration</h3>
    <li class="form-row">
      <div class="title">Login</div>
      <input type="text" placeholder="Enter login" name="login" 
    value="<?php print $c['values']['email']; ?>" <?php if ($c['errors']['email']) {print 'class="error" value=""';}?>>
    </li>
    <li class="form-row">
      <div class="title">Name</div>
      <input type="text" placeholder="Enter name" name="name"
        value="<?php print $c['values']['name']; ?>" class=" <?php if ($c['errors']['name']) echo "error" ?>">
    </li>
    <li class="form-row">
      <div class="title">Surname</div>
      <input type="text" placeholder="Enter surname" name="surname"
    value="<?php print $c['values']['surname']; ?>" <?php if ($c['errors']['surname']) {print 'class="error"';} ?>>
    </li>
    <li class="form-row">
      <div class="title">Password</div>
      <input type="text" placeholder="Enter password" name="password" 
    value="<?php print $c['values']['email']; ?>" <?php if ($c['errors']['email']) {print 'class="error" value=""';}?>>
    </li>
    <li class="form-row">
      <div class="title">Repeat password</div>
      <input type="text" placeholder="Repeat password" name="repeat_password" 
    value="<?php print $c['values']['email']; ?>" <?php if ($c['errors']['email']) {print 'class="error" value=""';}?>>
    </li>
    <li class="form-row">
      <div class="title">Email</div>
      <input type="text" placeholder="Enter email" name="email" 
    value="<?php print $c['values']['email']; ?>" <?php if ($c['errors']['email']) {print 'class="error" value=""';}?>>
    </li>
    
    <li class="form-row">
      <div class="title">Gender</div>
      
      <label for="contactChoice1">
      Male
      <input type="radio" id="contactChoice1"
        name="gender" value="Male" checked>
      </label>

      
      <label for="contactChoice2">
      Female
      <input type="radio" id="contactChoice2"
        name="gender" value="Female">
      </label>
    </li>
    <li class="form-row">
      <div class="title">
      Agree to the terms of privacy
      </div>
      <input name="confirm" type="checkbox">
    </li>
    <li class="form-row">
    <!-- <form method="POST" id="reg" name="registration">
      <input type="submit" name="registration" value="Зарегистрироваться" class="button">         
    </form> -->
      <input type="submit" value="Register" class="button">         
    </li>
  </ul>
</form>
<?php
// if (!empty($c['messages'])) {
//   print('<div id="messages">');
//   // Выводим все сообщения.
//   foreach ($c['messages'] as $message) {
//     print($message);
//   }
//   print('</div>');
// }?>
</div>

<noscript>JavaScript отключён!</noscript>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript">
  $('input')
</script>

