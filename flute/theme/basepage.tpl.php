<!DOCTYPE html>
<html>
<head>
	<title>Virtual Flute</title>
	<link rel="stylesheet" type="text/css" href="../css/testpage.css">
	<link rel="stylesheet" type="text/css" href="../css/registration_form.css">
	<link rel="shortcut icon" type="image/x-icon" href="../static/images/logo.ico" />
</head>
<body>
	<div class="container">
		<header role="banner">
			<div class="logo">
				<img src="../static/images/logo.png">
			</div>

			<form method="POST">
				<!-- <input type="submit" name="mainpage" value="Home" class="button"> -->
				<input type="submit" id="record_control" name="flute" value="Home" class="button navbutton" 
				style="<?php echo isset($c['page']['flute']) ?  "background-color: #FF8322" : ""; ?>">

        		<input type="submit" name="compositions" value="Top Compositions" class="button navbutton" 
        		style="<?php echo isset($c['page']['compositions']) ? "background-color: #FF8322" : ""; ?>">
        		
        		<input type="submit" name="user_compositions" value="Your Compositions" class="button navbutton" 
        		style="<?php echo isset($c['page']['user_compositions']) ? "background-color: #FF8322" : ""; ?>">
        		
        		<input type="submit" name="about" value="About" class="button navbutton" 
        		style="<?php echo isset($c['page']['about']) ? "background-color: #FF8322" : ""; ?>">
			</form>
			<form method="POST">
				<div>	
        			<input type="submit" name="registrationpage"value="Registration" class="button">
				</div>
			</form>
		</header>
		<main role="main">	
		<div class="container_main">
			<?php
				foreach ($c['#content'] as $content) {
				  echo $content;
				}
			?>
		</div>
		</main>
		<footer role="contentinfo">
			<h1>FOOTER</h1>
		</footer>
	</div>
</body>
</html>

<script type="text/javascript" src="../js/jquery.js"></script>
