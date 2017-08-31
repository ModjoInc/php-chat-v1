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
			<input type="name" name="name" placeholder="name">
			<input type="email" name="email" placeholder="email">
			<input type="password" name="password1" value="" placeholder="password">
			<input type="password" name="password2" value="" placeholder="password">
			<input type="submit" name="SignUp" value="Sign Up">
		</form>');
}

// Fonction affichant l'HTML pour l'envoi de msg
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
	try{
		$bdd = new PDO('mysql:host=localhost;dbname=chatPhp;charset=utf8', 'root', 'root');
	} catch (Exception $e){
		    echo('Erreur : ' . $e->getMessage());
	}

// Si clic sur 'déconnect', destruction de la session
	if(isset($_POST['deconnect'])){
			session_destroy();
		}

// Si un ID est connu en session,
	if(isset($_SESSION['userId'])){		
		// Si message posté, entrée du msg en DB
		if(isset($_POST['message'])){
			$userId = $_SESSION['userId'];
			$message = $_POST['message'];
			// echo($userId);
			// echo($message);
			// return;
			$req = $bdd->prepare("INSERT INTO messages (content, users_idusers) VALUES (? , ?) ");
			$req->execute(array($message, $userId));
		};
	// Dans tous les cas, proposer d'envoyer un msg en plus:
	invitMsg();
	}
// Si pas d'ID connu en session, vérifier qu'il n'y a pas une tentative de login ou signup, à défaut affichage d'une fenêtre pour se connecter ou s'inscrire
	else{
		if (isset($_POST['LogIn']) OR isset($_POST['SignUp'])){
			// Préparer la var pour réception msg d'erreur
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
					if(password_verify($password, $sql_data[0]['password'])){
						$_SESSION['userId'] = $sql_data[0]['idusers'];
						$_SESSION['name'] = $sql_data[0]['nom'];
					} else {
						$msg = 'Mot de passe incorrect. Réessayer.';
					}
				} else{
					$msg = 'Email inconnu. Veuillez créer un compte.';	
				}
			}
			// tentative de Sign Up ?
			if (isset($_POST['SignUp'])){
				$name = $_POST['name'];
				$email = $_POST['email'];
				$password1 = $_POST['password1'];
				$password2 = $_POST['password2'];
				// Vérifier que l'email est pas connu en DB
				$req = $bdd->query("SELECT * FROM users WHERE email = '$email' ");
				$sql_data= $req->fetchAll(PDO::FETCH_ASSOC);
				if(isset($sql_data[0]['email'])){
					$msg = "Email déjà utilisé. Veuillez utiliser un autre email ou vous connecter";
					// Vérifier que les password sont identiques
					if($password1 != $password2){
						$msg = "Veuilez entrer deux password identiques.";
					}
				} else{ // Si c'est OK, sanitizer, valider hasher le pwd et faire l'insertion en DB
					$name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
					if ($name){
						$email = filter_var($email, FILTER_VALIDATE_EMAIL);
						if ($email){
							$password = password_hash($password1, PASSWORD_DEFAULT);
							$req = $bdd->prepare("INSERT INTO users(nom, email, password) VALUES (?, ?, ?) ");
							$req->execute(array($name, $email, $password));
							// Récupérer ensuite l'ID et pseudo pour enregistrement de session
							$req = $bdd->query("SELECT * FROM users WHERE email = '$email' ");
							$sql_data= $req->fetchAll(PDO::FETCH_ASSOC);
							$_SESSION['userId'] = $sql_data[0]['idusers'];
							$_SESSION['name'] = $sql_data[0]['nom'];
						} else{
							$msg = "Veuillez entrer un email valide";
						}
						
					}
					else{
						$msg = "Veuillez entrer un nom valide";
					}					
				}
			}
			// Après LogIn ou SignUp si erreur affichage msg erreur
			if($msg != ''){
				echo($msg . '<br>');
				invitLog();
			} else{ // Sinon invit envoi de msg
				invitMsg();
			}			
		} else{ // Si rien en POST, proposition de login ou signup
			invitLog();
		}
	}
?>


