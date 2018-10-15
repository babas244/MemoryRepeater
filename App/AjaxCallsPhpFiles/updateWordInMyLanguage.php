<?php
header("Content-Type: application/json; charset=UTF-8");

session_start();

if (isset($_SESSION['id'])) {
	if (isset($_POST['newWordInMyLanguage']) && isset($_POST['idInDdb']) && isset($_GET["idTopic"])) { //update word in my language 
		
		$idTopic = htmlspecialchars($_GET['idTopic']);	
		$newWordInMyLanguage = htmlspecialchars($_POST['newWordInMyLanguage']);
		$idInDdb = htmlspecialchars($_POST['idInDdb']);

		require '../../log_in_bdd.php';		
		
		require '../../isIdTopicSafeAndMatchUser.php';		
		
		$reqUpdateWordInMyLanguage = $bdd -> prepare('UPDATE translations 
			SET wordInMyLanguage=:newWordInMyLanguage
			WHERE id=:idInDdb AND idUser=:idUser AND idTopic=:idTopic');
			$reqUpdateWordInMyLanguage -> execute(array(
			'idInDdb' => $idInDdb,
			'idUser' => $_SESSION['id'],
			'idTopic' => $idTopic,
			'newWordInMyLanguage' => $newWordInMyLanguage));				
		$reqUpdateWordInMyLanguage->closeCursor();
	}
	else {
		echo "Une des variables n'est pas définie.";
	}
}
else {
	echo 'disconnected';	
}?>