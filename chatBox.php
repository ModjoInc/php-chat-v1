<?php	
	session_start();
// Fonction affichant l'HTML pour le Login ou Signup
function invitLog(){
				echo('Log in
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
		</form>');
}

function invitMsg(){
	echo($_SESSION['name'].' dit:');
	echo('<form method="post">
			<input type="text" name="message">
			<input type="submit" name="envoyer">
		</form>
		<form method="post">
			<input type="submit" name="deconnect" value="Double cliquez pour vous déconnectez">
		</form>');
}


// Connection à la DB
	$bdd = new PDO('mysql:host=localhost;dbname=chatPHP;charset=utf8', 'root', 'root');

	if(isset($_POST['deconnect'])){
			// unset($_SESSION['userId']);
			session_destroy();
		}
// Si un ID est connu en session, voir si msg à envoyer et sinon invit à entrer msg
	if(isset($_SESSION['userId'])){
		// Si déconnection demandée, détruire la Session
		
		// Si message posté, entrée du msg en DB
		if(isset($_POST['message'])){
			$userId = $_SESSION['userId'];
			$message = $_POST['message'];
			$req = $bdd->prepare("INSERT INTO messages(content, users_idusers) VALUES (?, ?) ");
			$req->execute(array($message, $userId));
		};
		invitMsg();
	}
// Si pas d'ID connu en session, vérifier qu'il n'y a pas une tentative de login ou signup, à défaut affichage d'une fenêtre pour se connecter ou s'inscrire
	else{
		if (isset($_POST['LogIn']) OR isset($_POST['SignUp'])){
			$msg = '';
			// tentative de Login ?
			if (isset($_POST['LogIn'])){
				$email = $_POST['email'];
				$password = $_POST['password'];
				// Récup info DB sur base de l'email
				$req = $bdd->query("SELECT * FROM users WHERE email = '$email' ");
				$sql_data= $req->fetchAll(PDO::FETCH_ASSOC);
 				// Vérifier que l'email et connu et que le mdp est correct
				if($email == $sql_data[0]['email']){
					if($password == $sql_data[0]['password']){
						$_SESSION['userId'] = $sql_data[0]['idusers'];
						$_SESSION['name'] = $sql_data[0]['nom'];
					} else {
						$msg = 'Mot de passe incorrect. Réessayer.';
					}
				} else{
					$msg = 'Email inconnu. Veuillez vous ';	
				}
			}
			// tentative de Sign Up ?
			if (isset($_POST['SignUp'])){
				
			}
			// Après LogIn ou SignUp, proposition envoi msg
			if($msg != ''){
				echo($msg);
				invitLog();
			} else{
				invitMsg();
			}			
		} else{ // Sinon, proposition de login ou signup
			invitLog();
		}
	}
?>