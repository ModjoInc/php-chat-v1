<?php
$bdd = new PDO('mysql:host=localhost;dbname=chatPHP;charset=utf8', 'root', 'root');

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
	for ($i=0; $i < $length-1; $i++) {	
		echo('<p>');
		echo('<span class="time">'.$sql_data[$i]['date'].'</span><br>');
		echo('<span class="name">'.$sql_data[$i]['nom'].'</span> : '.$sql_data[$i]['content']);
		echo('</p>');
	}
		echo('<p id="lastReceived">');
		echo('<span class="time">'.$sql_data[$length-1]['date'].'</span><br>');
		echo('<span class="name">'.$sql_data[$i]['nom'].'</span> : '.$sql_data[$length-1]['content']);
		echo('</p>');

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="refresh" content="">				
	<link rel="stylesheet" href="style.css">
	<title>Document</title>
</head>
<body>
	
</body>
</html>