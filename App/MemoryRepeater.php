<?php 
session_start();

require '../log_in_bdd.php';

require '../sessionAuthentication.php';


if (isset($_SESSION['id']) && isset($_GET["idTopic"])) {
	require '../isIdTopicSafeAndMatchUser.php';
	$idTopic = htmlspecialchars($_GET['idTopic']);	
	$reqGetTopic = $bdd -> prepare('SELECT topic, colorBackGround FROM topics WHERE idUser=:idUser AND id=:idTopic');
		$reqGetTopic -> execute(array(
		'idUser' => $_SESSION['id'],
		'idTopic' => $idTopic));
		$resultat = $reqGetTopic -> fetch();
		$_SESSION['topic'] = $resultat['topic'];
		$backgroundColorToDo = $resultat['colorBackGround'];
		if ($reqGetTopic->rowCount() == 0) {
			header("Location: ../logout.php");
		}
	$reqGetTopic -> closeCursor();
	$userNameDisplayed = strlen($_SESSION['user']) < 16 ? $_SESSION['user'] : substr($_SESSION['user'], 0,15)."...";
	$foreignLanguage = strlen($_SESSION['topic']) < 30 ? $_SESSION['topic'] : substr($_SESSION['topic'], 0,30)."...";					
}
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Memory repeater</title>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
		<meta name="robots" content="noindex,nofollow">
		<link rel="stylesheet" href="MemoryRepeater.css" />
    </head>
    <body>
		<!-- -->
<?php
		$idTranslationInDdb = array();
				
		if (isset($_GET['newWordInMyLanguage']) && isset($_GET['idInDdb'])) { //update word in my language 
			$newWordInMyLanguage = htmlspecialchars($_GET['newWordInMyLanguage']);
			$idInDdb = htmlspecialchars($_GET['idInDdb']);

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

		if (isset($_GET['newWordInForeignLanguage']) && isset($_GET['idInDdb'])) { //update word in foreign language
			$newWordInForeignLanguage = htmlspecialchars($_GET['newWordInForeignLanguage']);
			$idInDdb = htmlspecialchars($_GET['idInDdb']);

			$reqUpdateWordInForeignLanguage = $bdd -> prepare('UPDATE translations 
				SET wordInForeignLanguage=:newWordInForeignLanguage
				WHERE id=:idInDdb AND idUser=:idUser AND idTopic=:idTopic');
				$reqUpdateWordInForeignLanguage -> execute(array(
				'idInDdb' => $idInDdb,
				'idUser' => $_SESSION['id'],
				'idTopic' => $idTopic,
				'newWordInForeignLanguage' => $newWordInForeignLanguage));				
			$reqUpdateWordInForeignLanguage->closeCursor();
		}		
		
		if (isset($_GET['IsDelete']) && isset($_GET['idInDdb'])) { // if a translation was asked to be deleted
			$idInDdb = htmlspecialchars($_GET['idInDdb']);

			$reqDeleteTranslation = $bdd -> prepare('DELETE FROM translations 
				WHERE id=:idInDdb AND idUser=:idUser AND idTopic=:idTopic');
				$reqDeleteTranslation -> execute(array(
				'idInDdb' => $idInDdb,
				'idUser' => $_SESSION['id'],
				'idTopic' => $idTopic));				
			$reqDeleteTranslation->closeCursor();
		}		
		
		if (isset($_GET['IsToRepeat']) && isset($_GET['idInDdb'])) { // User wants to repeat the same recall step 
			$idInDdb = htmlspecialchars($_GET['idInDdb']);

			$reqRepeatRecall = $bdd -> prepare('UPDATE translations 
				SET datePreviewsRecall = NOW()
				WHERE id=:idInDdb AND idUser=:idUser AND idTopic=:idTopic');
				$reqRepeatRecall -> execute(array(
				'idInDdb' => $idInDdb,
				'idUser' => $_SESSION['id'],
				'idTopic' => $idTopic));				
			$reqRepeatRecall->closeCursor();
		}		

		if (isset($_GET['IsApproved']) && isset($_GET['idInDdb'])) { // User asks to go to next recall step 
			$idInDdb = htmlspecialchars($_GET['idInDdb']);

			$reqRepeatRecall = $bdd -> prepare('UPDATE translations 
				SET datePreviewsRecall = NOW(), rankRepetition = rankRepetition + 1
				WHERE id=:idInDdb AND idUser=:idUser AND idTopic=:idTopic');
				$reqRepeatRecall -> execute(array(
				'idInDdb' => $idInDdb,
				'idUser' => $_SESSION['id'],
				'idTopic' => $idTopic));				
			$reqRepeatRecall->closeCursor();
		}		
		
		
		if (isset($_GET['wordInMyLanguage']) && isset($_GET['wordInForeignLanguage'])) { // user entered a new translation 
			$wordInMyLanguage = htmlspecialchars($_GET['wordInMyLanguage']);
			$wordInForeignLanguage = htmlspecialchars($_GET['wordInForeignLanguage']);
			$pronunciationForeignWord = isset($_GET['pronunciationForeignWord']) ? htmlspecialchars($_GET['pronunciationForeignWord']) : "";
			$isMylanguageInput = htmlspecialchars($_GET['isMylanguageInput']);

			$reqInsertInputUserTranslation = $bdd -> prepare('INSERT INTO translations(idUser,idTopic,wordInMyLanguage,
				wordInForeignLanguage,pronunciationForeignWord,isMylanguageInput,rankRepetition,dateCreation,datePreviewsRecall)
				VALUES (:idUser,:idTopic,:wordInMyLanguage,:wordInForeignLanguage,:pronunciationForeignWord,:isMylanguageInput,0,NOW(),NOW())');
				$reqInsertInputUserTranslation -> execute(array(
				'idUser' => $_SESSION['id'],
				'idTopic' => $idTopic,
				'wordInForeignLanguage' => $wordInForeignLanguage,
				'wordInMyLanguage' => $wordInMyLanguage,
				'pronunciationForeignWord' => $pronunciationForeignWord,
				'isMylanguageInput' => $isMylanguageInput)) or die(print_r($reqInsertInputUserTranslation->errorInfo()));				
			$reqInsertInputUserTranslation->closeCursor();
		}		
?>
		<div id="containerForm">
			<form id="formEnterTranslations" method="get" action="MemoryRepeater.php">
				<div id = "containerInputTranslationsWithSubmit">
					<div id = "containerInputTranslationsWithoutSubmit">
						<div id="frameFormMyLanguage" class="frameFormInputLanguages">
							<input type="text" placeHolder="Mot initial en français" id="wordInMyLanguage" name="wordInMyLanguage" maxlength="255">
						</div>
						<div id="changeInputLanguage">
							&#8651
						</div>
						<div id="frameFormForeignLanguage" class="frameFormInputLanguages">
							<input type="text" placeHolder="en <?php echo $foreignLanguage;?>" id="wordInForeignLanguage" name="wordInForeignLanguage"  maxlength="255" disabled>
						</div>
						<input type="hidden" name="idTopic" value="<?php echo $idTopic; ?>"></input>
					</div>
					<input id="submitFormTranslation" type="submit" disabled>
				</div>
				<input type="hidden" id="isMylanguageInput" name="isMylanguageInput" value="1">
			</form>
		</div>

<?php
		$aRepetitionTimes[0] = 1;
		$aRepetitionTimes[1] = 24;
		$aRepetitionTimes[2] = 24*7;
		$aRepetitionTimes[3] = 24*30;
		$aRepetitionTimes[4] = 1*10^13;
		
		// fetch liste of words to be repeated
		$i = 0 ;
		for ($rankRepetition = 0 ; $rankRepetition < count($aRepetitionTimes)-1 ; $rankRepetition ++) {

			$reqFetchWordsToRemind = $bdd -> prepare('SELECT id,wordInMyLanguage,wordInForeignLanguage,pronunciationForeignWord,isMylanguageInput 
				FROM translations 
				WHERE idUser=:idUser AND idTopic=:idTopic 
				AND datePreviewsRecall < SUBDATE(NOW(),INTERVAL :repetionTimeCurrent HOUR) 
				AND rankRepetition=:rankRepetition');
				$reqFetchWordsToRemind -> execute(array(
				'idUser' => $_SESSION['id'],
				'idTopic' => $idTopic,
				'repetionTimeCurrent' => $aRepetitionTimes[$rankRepetition],
				'rankRepetition'=> $rankRepetition));
				
				while ($translationsList = $reqFetchWordsToRemind-> fetch()) {
					
					$displayMyLanguage = $translationsList['isMylanguageInput'] ? "inline" : "none";
					$displayForeignLanguage = $translationsList['isMylanguageInput'] ? "none" : "inline";
					$IdinDdbFetched = $translationsList['id'];
					$wordInForeignLanguageFetched = $translationsList['wordInForeignLanguage'];
					$pronunciationForeignWordFetched = $translationsList['pronunciationForeignWord'];
					$wordInMyLanguageFetched = $translationsList['wordInMyLanguage'];
					
					echo 	'<div id="containerWordReminder'.$i.'" class="containerWordReminder">
								<div id="containerWordInMyLanguage'.$i.'" class="wordReminder wordInMyLanguage" onclick="editWordInMyLanguage('.$i.')">'
									.'<span id="wordInMyLanguage'.$i.'" data-idInDdb="'.$IdinDdbFetched.'" style="display:'.$displayMyLanguage.'">'
									.$wordInMyLanguageFetched
									.'</span>'
								.'</div>'
								.'<button id="showWordMyLanguage'.$i.'" onclick="showTranslation('.$i.')">
									Montrer
								</button>'
								.'<div id="containerWordInForeignLanguage'.$i.'" class="wordReminder wordInForeignLanguage" onclick="editWordInForeignLanguage('.$i.')">'
									.'<span id="wordInForeignLanguage'.$i.'" data-idInDdb="'.$IdinDdbFetched.'" style="display:'.$displayForeignLanguage.'">'
										.$wordInForeignLanguageFetched
									.'</span>'	
								.'</div>'
								.'<div id="containerPronunciationForeignWord'.$i.'" class="wordReminder pronunciationForeignWord">'
									.'<span id="pronunciationForeignWord'.$i.'" data-idInDdb="'.$IdinDdbFetched.'" style="display:'.$displayForeignLanguage.'">'
										.$pronunciationForeignWordFetched
									.'</span>'	
								.'</div>'
								.'<div class="rankRepetition">'
									.($rankRepetition+1)
								.'</div>'
								.'<div id="containerTranslationMenu'.$i.'">'
								.'</div>'
								.'<button id="translationMenu'.$i.'" onclick="openTranslationMenu('.$i.')" value="+">
									+'
								.'</button>'
							.'</div>';
				$i++;
				}
			$reqFetchWordsToRemind->closeCursor();	
		}
?>
		<script>
			var idTopic = <?php echo $idTopic; ?>;
			var foreignLanguage = "<?php echo $foreignLanguage; ?>";
		</script> 
		<script src="MemoryRepeater.js"></script>
    
	</body>
</html>