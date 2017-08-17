<?php	
	session_start();
// Connection à la DB
	$bdd = new PDO('mysql:host=localhost;dbname=chatPHP;charset=utf8', 'root', 'root');


// Si un ID est connu en session, voir si msg à envoyer, récup le nom d'utilisateur et invit à entrer msg
	if(isset($_SESSION['userId'])){
		// Si message posté, entrée du msg en DB
		if(isset($_POST['message'])){
			$userId = $_SESSION['userId'];
			$message = $_POST['message'];
			$req = $bdd->prepare("INSERT INTO messages(content, users_idusers) VALUES (?, ?) ");
			$req->execute(array($message, $userId));
		};
		?>
		<form method="post">
			<input type="text" name="message">
			<input type="submit" name="envoyer">
		</form>

		<?php
	}
// Si pas d'ID connu en session, vérifier qu'il n'y a pas une tentative de login ou signup, à défaut affichage d'une fenêtre pour se connecter ou s'inscrire
	else{
		if (isset($_POST['LogIn']) OR isset($_POST['SignUp'])){
			// Login ?
			if (isset($_POST['LogIn'])){
				print_r($_POST);
				$email = $_POST['email'];
				$password = $_POST['password'];
				$req = $bdd->query("SELECT * FROM users WHERE email = '$email' ");
				$sql_data= $req->fetchAll(PDO::FETCH_ASSOC);
				echo('<pre>');
				print_r($sql_data[0]);
				echo('<pre>');
				if($password == $sql_data[0]['password']){
					echo('mdp ok');
					$_SESSION['userId'] = $sql_data[0]['idusers'];
				} else {
					$msg = 'mot de passe incorrect';
				}
			}

			// Sign Up ?
			if (isset($_POST['SignUp'])){

			}
			// Après LogIn ou SignUp, proposition envoi msg
			?>
				<form method="post">
					<input type="text" name="message">
					<input type="submit" name="envoyer">
				</form>
			<?php
		} else{ // Sinon, proposition de login ou signup
		?>
		Log in
		<form method="post">
			<input type="" name="email" placeholder="email">
			<input type="password" name="password" value="" placeholder="password">
			<input type="submit" name="LogIn" value="Log In">

		</form><br>
		or Sign Up
		<form method="post">
			<input type="name" placeholder="name">
			<input type="email" name="email" placeholder="email">
			<input type="password" name="password" value="" placeholder="password">
			<input type="password" name="password" value="" placeholder="password">
			<input type="submit" name="SignUp" value="Sign Up">
		</form>
		<?php
		}

		// $req = $bdd->query('SELECT * FROM messages INNER JOIN users ON messages.users_idusers = users.idusers ORDER BY idmessages ASC');

		// while($messages = $req -> fetch()){
		// // echo('<pre>');
		// 	echo('<p>');
		// 	echo($messages['date'].' '.$messages['nom'].': '.'<br>');
		// 	echo($messages['content'].'</p>');
		// // echo('<pre>');
		// };

		// $_SESSION['user'] = ;
	}



?>