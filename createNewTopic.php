<?php
session_start(); 

require 'log_in_bdd.php';

require 'sessionAuthentication.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <title>create new topic</title>
        <meta charset="utf-8"/>
		<meta name="robots" content="noindex,nofollow">
    </head>
    <body>

<?php
if (isset($_SESSION['id']) && isset($_POST['newTopic']) && isset($_POST['colorBackGround']) && isset($_POST['colorFont'])) {
	if ($_POST['newTopic']=="") {
		echo"<script>alert('Le champ est vide. Recommencez.');window.open('manageTopics.php');</script>"; //Il faut penser à interdire aussi le cas où le champ contient le caractère  |.
	}
	else {
		$newTopic = htmlspecialchars($_POST['newTopic']);
		$colorBackGround = htmlspecialchars($_POST['colorBackGround']);
		$colorFont = htmlspecialchars($_POST['colorFont']);
		
		// déterminer si le topic existe déjà
		$bTopicAlreadyExists = false;	
		$reqCheckifTopicAlreadyExists = $bdd->prepare('SELECT topic FROM languages WHERE idUser=:idUser');
			$reqCheckifTopicAlreadyExists -> execute (array(
			'idUser' => $_SESSION['id']));
			while ($donnees = $reqCheckifTopicAlreadyExists -> fetch()) {
				if ($newTopic == $donnees['topic']) {
					$bTopicAlreadyExists = true;
				}
			}
		$reqCheckifTopicAlreadyExists -> closeCursor();
				
		if ($bTopicAlreadyExists) { //rediriger l'utilisateur vers manageTopics si le topic existe déjà
			echo"<script>alert('Ce sujet de note existe déjà. Trouvez un autre nom.');window.open('manageTopics.php');</script>";	
		}
		else {
			// Insérer le nouveau topic. 
			$reqInsertTopic = $bdd->prepare('INSERT INTO languages(topic, idUser, colorBackGround, colorFont) VALUE (:topic, :idUser, :colorBackGround, :colorFont)');
			$reqInsertTopic -> execute (array(
				'topic' => $newTopic,
				'idUser' => $_SESSION['id'],
				'colorBackGround' => $colorBackGround,
				'colorFont' => $colorFont));
			//echo "Le nouveau Sujet ".$newTopic." a été crée.";
			$reqInsertTopic -> closeCursor();
			
			// Déterminer l'id du topic fraichement créé dans languages
			$reqGetIdOfTopic = $bdd->prepare('SELECT id FROM languages WHERE topic=:topic AND idUser=:idUser');
			$reqGetIdOfTopic -> execute (array(
				'topic' => $newTopic,
				'idUser' => $_SESSION['id']));
			$result = $reqGetIdOfTopic -> fetch(); 
			$idTopic = $result['id'];
			$reqGetIdOfTopic -> closeCursor();
			//echo "\nId = ".$idTopic;
			
			// insérer "01" comme catégorie root de la table Notes
			$reqInsertRootIntoNotesTable = $bdd->prepare('INSERT INTO notes(idUser,idTopic,idNote,content,dateCreation) VALUES (:idUser,:idTopic,"01",:newNote,NOW())');
			$reqInsertRootIntoNotesTable -> execute(array(
				'idUser' => $_SESSION['id'],
				'idTopic' => $idTopic, 
				'newNote' => $newTopic));
			$reqInsertRootIntoNotesTable -> closeCursor();	
			
			// Créer des labelTitles dans la base de données, par défaut pour que l'user en ait de toutes façons
			$reqCreateDefaultLabelTitleOfToDoList = $bdd->prepare('INSERT INTO todo_userlabelstitles(idUser,idTopic,rankLabelTitle,content) 
															VALUES 	(:idUser,:idTopic,"0","Pour quand?"),
																	(:idUser,:idTopic,"1","Categorie"),
																	(:idUser,:idTopic,"2","Duree"),
																	(:idUser,:idTopic,"3","Faire ou")');
			$reqCreateDefaultLabelTitleOfToDoList -> execute(array(
				'idUser' => $_SESSION['id'],
				'idTopic' => $idTopic));
			$reqCreateDefaultLabelTitleOfToDoList -> closeCursor();	
			
			// Créer les labels dans la base de données, par défaut pour que l'user en ait de toutes façons
			$reqCreateDefaultLabelOfToDoList = $bdd->prepare('INSERT INTO todo_userlabels(idUser,idTopic,idLabelTitle, rankLabel,content) 
				VALUES 	(:idUser,:idTopic,(SELECT id FROM todo_userlabelstitles WHERE idUser=:idUser AND idTopic=:idTopic AND rankLabelTitle="0"),"0","à la une"),
						(:idUser,:idTopic,(SELECT id FROM todo_userlabelstitles WHERE idUser=:idUser AND idTopic=:idTopic AND rankLabelTitle="0"),"1","je ne sais pas"),
						(:idUser,:idTopic,(SELECT id FROM todo_userlabelstitles WHERE idUser=:idUser AND idTopic=:idTopic AND rankLabelTitle="0"),"2","bientôt"),
						(:idUser,:idTopic,(SELECT id FROM todo_userlabelstitles WHERE idUser=:idUser AND idTopic=:idTopic AND rankLabelTitle="0"),"3","souvent"),
						(:idUser,:idTopic,(SELECT id FROM todo_userlabelstitles WHERE idUser=:idUser AND idTopic=:idTopic AND rankLabelTitle="0"),"4","un jour"),
						(:idUser,:idTopic,(SELECT id FROM todo_userlabelstitles WHERE idUser=:idUser AND idTopic=:idTopic AND rankLabelTitle="1"),"0","je ne sais pas"),
						(:idUser,:idTopic,(SELECT id FROM todo_userlabelstitles WHERE idUser=:idUser AND idTopic=:idTopic AND rankLabelTitle="1"),"1","catégorie1"),
						(:idUser,:idTopic,(SELECT id FROM todo_userlabelstitles WHERE idUser=:idUser AND idTopic=:idTopic AND rankLabelTitle="1"),"2","catégorie2"),
						(:idUser,:idTopic,(SELECT id FROM todo_userlabelstitles WHERE idUser=:idUser AND idTopic=:idTopic AND rankLabelTitle="2"),"0","je ne sais pas"),
						(:idUser,:idTopic,(SELECT id FROM todo_userlabelstitles WHERE idUser=:idUser AND idTopic=:idTopic AND rankLabelTitle="2"),"1","rapide"),
						(:idUser,:idTopic,(SELECT id FROM todo_userlabelstitles WHERE idUser=:idUser AND idTopic=:idTopic AND rankLabelTitle="2"),"2","long"),
						(:idUser,:idTopic,(SELECT id FROM todo_userlabelstitles WHERE idUser=:idUser AND idTopic=:idTopic AND rankLabelTitle="3"),"0","je ne sais pas"),
						(:idUser,:idTopic,(SELECT id FROM todo_userlabelstitles WHERE idUser=:idUser AND idTopic=:idTopic AND rankLabelTitle="3"),"1","lieu 1"),
						(:idUser,:idTopic,(SELECT id FROM todo_userlabelstitles WHERE idUser=:idUser AND idTopic=:idTopic AND rankLabelTitle="3"),"2","lieu 2")');
			$reqCreateDefaultLabelOfToDoList -> execute(array(
				'idUser' => $_SESSION['id'],
				'idTopic' => $idTopic));
			$reqCreateDefaultLabelOfToDoList -> closeCursor();	
			
			header ('Location: manageTopics.php');
		}
	}
	
	exit; // ?
}
	

?>


	</body>
</html>