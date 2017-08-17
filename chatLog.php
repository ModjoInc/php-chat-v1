<?php
$bdd = new PDO('mysql:host=localhost;dbname=chatPHP;charset=utf8', 'root', 'root');

	$req = $bdd->query('SELECT * FROM messages INNER JOIN users ON messages.users_idusers = users.idusers ORDER BY idmessages ASC');

	while($messages = $req -> fetch()){
	// echo('<pre>');
		echo('<p>');
		echo($messages['date'].' '.$messages['nom'].': '.'<br>');
		echo($messages['content'].'</p>');
	// echo('<pre>');
	};

?>