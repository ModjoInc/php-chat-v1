<?php
try{
	$bdd = new PDO('mysql:host=localhost;dbname=chatPhp;charset=utf8', 'root', 'root');
} catch (Exception $e){
	    echo('Erreur : ' . $e->getMessage());
}

	$req = $bdd->query('SELECT * FROM messages INNER JOIN users ON messages.users_idusers = users.idusers ORDER BY idmessages DESC');

	// while($messages = $req -> fetch()){
	// // echo('<pre>');
	// 	echo('<p>');
	// 	echo($messages['date'].' '.$messages['nom'].': '.'<br>');
	// 	echo($messages['content'].'</p>');
	// // echo('<pre>');
	// };

	$sql_data= $req->fetchAll(PDO::FETCH_ASSOC);
	// echo('<pre>');
	// print_r($sql_data);
	// echo('<pre>');
	$length = count($sql_data);
	for ($i=0; $i < $length; $i++) {	
		echo('<p>');
		echo('<span class="time">'.$sql_data[$i]['date'].'</span><br>');
		echo('<span class="name">'.$sql_data[$i]['nom'].'</span> : '.$sql_data[$i]['content']);
		echo('</p>');
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="refresh" content="1">				
	<title>Document</title>
</head>
<body>
	
</body>
</html>