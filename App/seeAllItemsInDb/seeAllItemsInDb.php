<?php 
session_start();

require '../../log_in_bdd.php';

require '../../sessionAuthentication.php';


if (isset($_SESSION['id']) && isset($_GET["idTopic"])) {
	require '../../isIdTopicSafeAndMatchUser.php';
	$idTopic = htmlspecialchars($_GET['idTopic']);	
	$reqGetTopic = $bdd -> prepare('SELECT topic, colorBackGround FROM languages WHERE idUser=:idUser AND id=:idTopic');
		$reqGetTopic -> execute(array(
		'idUser' => $_SESSION['id'],
		'idTopic' => $idTopic));
		$resultat = $reqGetTopic -> fetch();
		$_SESSION['topic'] = $resultat['topic'];
		$backgroundColorToDo = $resultat['colorBackGround'];
		if ($reqGetTopic->rowCount() == 0) {
			header("Location: ../../logout.php");
		}
	$reqGetTopic -> closeCursor();
	$userNameDisplayed = strlen($_SESSION['user']) < 16 ? $_SESSION['user'] : substr($_SESSION['user'], 0,15)."...";
	$foreignLanguage = strlen($_SESSION['topic']) < 30 ? $_SESSION['topic'] : substr($_SESSION['topic'], 0,30)."...";					
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Tous les items</title>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="seeAllItemsInDb.css" />
    </head>
    <body>
		<!-- -->
		
		<div id="containerWordReminder" class="containerWordReminder">
			<div id="dateCreationHeader" class="wordReminder dateCreation tableHeader">date d'apprentissage</div>
			<div id="containerWordInMyLanguageHeader" class="wordReminder wordInMyLanguage tableHeader">mon langage</div>
			<div id="containerWordInForeignLanguageHeader" class="wordReminder wordInForeignLanguage tableHeader">langage étranger</div>
			<div id="containerPronunciationForeignWordHeader" class="wordReminder containerPronunciationForeignWord tableHeader">prononciation</div>
			<div id="lengthToLearnHeader" class="wordReminder lengthToLearn tableHeader">durée apprentissage</div>			
		</div>
			
			
		
<?php
			$reqFetchAll = $bdd -> prepare('SELECT id,wordInMyLanguage,wordInForeignLanguage,pronunciationForeignWord,isMylanguageInput,rankRepetition, dateCreation,datePreviewsRecall 
				FROM translations 
				WHERE idUser=:idUser AND idTopic=:idTopic
				ORDER BY rankRepetition, datePreviewsRecall');
				$reqFetchAll -> execute(array(
				'idUser' => $_SESSION['id'],
				'idTopic' => $idTopic));
				
				$i=0;
				
				while ($itemList = $reqFetchAll-> fetch()) {
					
					$IdinDdbFetched = $itemList['id'];
					$wordInForeignLanguageFetched = $itemList['wordInForeignLanguage'];
					$pronunciationForeignWordFetched = $itemList['pronunciationForeignWord'];
					$buttonPronunciationDisabledOrNot = $pronunciationForeignWordFetched ==='' ? 'disabled' : '';
					$wordInMyLanguageFetched = $itemList['wordInMyLanguage'];
					$dateCreation = $itemList['dateCreation'];
					$rankRepetition = $itemList['rankRepetition'];
					$datePreviewsRecall = $itemList['datePreviewsRecall'];
					$dateLearned = $rankRepetition >= 3 ? $datePreviewsRecall : $rankRepetition + 1;
					
					echo 	'<div id="containerWordReminder'.$i.'" class="containerWordReminder">
								<div id="dateCreation'.$i.'" class="wordReminder dateCreation">'
									.'<span id="dateCreation'.$i.'" data-idInDdb="'.$IdinDdbFetched.'">'
									.$dateLearned
									.'</span>'
								.'</div>'
								
								.'<div id="containerWordInMyLanguage'.$i.'" class="wordReminder wordInMyLanguage">'
									.'<span id="wordInMyLanguage'.$i.'" data-idInDdb="'.$IdinDdbFetched.'">'
										.$wordInMyLanguageFetched
									.'</span>'
								.'</div>'
								.'<div id="containerWordInForeignLanguage'.$i.'" class="wordReminder wordInForeignLanguage">'
									.'<span id="wordInForeignLanguage'.$i.'" data-idInDdb="'.$IdinDdbFetched.'">'
										.$wordInForeignLanguageFetched
									.'</span>'	
								.'</div>'
								.'<div id="containerPronunciationForeignWord'.$i.'" class="wordReminder pronunciationForeignWord" onclick="editPronunciationForeignLanguage('.$i.')">'
									.'<span id="pronunciationForeignWord'.$i.'" data-idInDdb="'.$IdinDdbFetched.'">'
										.$pronunciationForeignWordFetched
									.'</span>'
								.'</div>'
								.'<div class="wordReminder lengthToLearn">'
									.'<span class="delayedTimeOfRecall">'
										.'+'.lengthToLearn($dateCreation,$datePreviewsRecall)
									.'<span>'	
								.'</div>'
							.'</div>';
							
				$i++;
				}
			$reqFetchAll->closeCursor();	

			function lengthToLearn($sDateCreation,$sdatePreviewsRecall) {
				$oDateCreation = new DateTime($sDateCreation);
				$oDatePreviewsRecall = new DateTime($sdatePreviewsRecall);
				$diff = date_diff($oDateCreation,$oDatePreviewsRecall);
				$days = $diff->format("%a");
				
				return $days === '0' ? $diff->format("%hh") : $days.'j';
			}	
?>		
		<script src="seeAllItemsInDb.js"></script>
    
	</body>
</html>