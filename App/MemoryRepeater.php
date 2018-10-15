<?php 
session_start();

require '../log_in_bdd.php';

require '../sessionAuthentication.php';


if (isset($_SESSION['id']) && isset($_GET["idTopic"])) {
	require '../isIdTopicSafeAndMatchUser.php';
	$idTopic = htmlspecialchars($_GET['idTopic']);	
	$reqGetTopic = $bdd -> prepare('SELECT topic, colorBackGround FROM languages WHERE idUser=:idUser AND id=:idTopic');
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
        <title>Memory repeater - <?php echo $foreignLanguage;?></title>
        <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
		<meta name="robots" content="noindex,nofollow">
		<link rel="stylesheet" href="memoryRepeater.css" />
    </head>
    <body>
		<!-- -->
<?php
		$idTranslationInDdb = array();
				
		if (isset($_POST['newWordInForeignLanguage']) && isset($_POST['idInDdb'])) { //update word in foreign language
			$newWordInForeignLanguage = htmlspecialchars($_POST['newWordInForeignLanguage']);
			$idInDdb = htmlspecialchars($_POST['idInDdb']);

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

		if (isset($_POST['newPronunciationForeignWord']) && isset($_POST['idInDdb'])) { //update pronunciation of foreign word
			$newWordInForeignLanguage = htmlspecialchars($_POST['newPronunciationForeignWord']);
			$idInDdb = htmlspecialchars($_POST['idInDdb']);

			$reqUpdateWordInForeignLanguage = $bdd -> prepare('UPDATE translations 
				SET pronunciationForeignWord=:pronunciationForeignWord
				WHERE id=:idInDdb AND idUser=:idUser AND idTopic=:idTopic');
				$reqUpdateWordInForeignLanguage -> execute(array(
				'idInDdb' => $idInDdb,
				'idUser' => $_SESSION['id'],
				'idTopic' => $idTopic,
				'pronunciationForeignWord' => $newWordInForeignLanguage));				
			$reqUpdateWordInForeignLanguage->closeCursor();
		}		

		
		if (isset($_POST['IsDelete']) && isset($_POST['idInDdb'])) { // if a translation was asked to be deleted
			$idInDdb = htmlspecialchars($_POST['idInDdb']);

			$reqDeleteTranslation = $bdd -> prepare('DELETE FROM translations 
				WHERE id=:idInDdb AND idUser=:idUser AND idTopic=:idTopic');
				$reqDeleteTranslation -> execute(array(
				'idInDdb' => $idInDdb,
				'idUser' => $_SESSION['id'],
				'idTopic' => $idTopic));				
			$reqDeleteTranslation->closeCursor();
		}		
		
		if (isset($_POST['IsToRepeat']) && isset($_POST['idInDdb'])) { // User wants to repeat the same recall step 
			$idInDdb = htmlspecialchars($_POST['idInDdb']);

			$reqRepeatRecall = $bdd -> prepare('UPDATE translations 
				SET datePreviewsRecall = NOW()
				WHERE id=:idInDdb AND idUser=:idUser AND idTopic=:idTopic');
				$reqRepeatRecall -> execute(array(
				'idInDdb' => $idInDdb,
				'idUser' => $_SESSION['id'],
				'idTopic' => $idTopic));				
			$reqRepeatRecall->closeCursor();
		}		

        if (isset($_POST['IsToDowngrade']) && isset($_POST['idInDdb'])) { // User wants to downgrade recall step 
			$idInDdb = htmlspecialchars($_POST['idInDdb']);
echo"coucou";
			$reqDowngradeRecall = $bdd -> prepare('UPDATE translations 
				SET datePreviewsRecall = NOW(),  rankRepetition = rankRepetition - 1
				WHERE id=:idInDdb AND idUser=:idUser AND idTopic=:idTopic');
				$reqDowngradeRecall -> execute(array(
				'idInDdb' => $idInDdb,
				'idUser' => $_SESSION['id'],
				'idTopic' => $idTopic));				
			$reqDowngradeRecall->closeCursor();
		}		

        
		if (isset($_POST['IsApproved']) && isset($_POST['idInDdb'])) { // User asks to go to next recall step 
			$idInDdb = htmlspecialchars($_POST['idInDdb']);

			$reqRepeatRecall = $bdd -> prepare('UPDATE translations 
				SET datePreviewsRecall = NOW(), rankRepetition = rankRepetition + 1
				WHERE id=:idInDdb AND idUser=:idUser AND idTopic=:idTopic');
				$reqRepeatRecall -> execute(array(
				'idInDdb' => $idInDdb,
				'idUser' => $_SESSION['id'],
				'idTopic' => $idTopic));				
			$reqRepeatRecall->closeCursor();
		}		
		
		
		if (isset($_POST['wordInMyLanguage']) && isset($_POST['wordInForeignLanguage'])) { // user entered a new translation 
			$wordInMyLanguage = htmlspecialchars($_POST['wordInMyLanguage']);
			$wordInForeignLanguage = htmlspecialchars($_POST['wordInForeignLanguage']);
			$pronunciationForeignWord = isset($_POST['pronunciationForeignWord']) ? htmlspecialchars($_POST['pronunciationForeignWord']) : "";
			$isMylanguageInput = htmlspecialchars($_POST['isMylanguageInput']);

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
		<div id="greyLayerOnPage"><img src="ajax-loader.gif" alt="loading..."/></div>
		<div id="containerForm">
			<form id="formEnterTranslations" method="POST" action="memoryRepeater.php?idTopic=<?php echo $idTopic; ?>">
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
						<div id="frameFormPronunciationForeignWord" class="frameFormInputLanguages">
							<input type="text" placeHolder="prononciation" id="pronunciationForeignWord" name="pronunciationForeignWord"  maxlength="255" disabled>
						</div>					
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

			$reqFetchWordsToRemind = $bdd -> prepare('SELECT id,wordInMyLanguage,wordInForeignLanguage,pronunciationForeignWord,isMylanguageInput,dateCreation 
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
					
					$isMylanguageInputInReminders = $translationsList['isMylanguageInput'];
					$displayMyLanguage = $isMylanguageInputInReminders ? "inline" : "none";
					$arrowShowsWordToGuess = $isMylanguageInputInReminders ? "->" :"<-";
					$displayForeignLanguage = $isMylanguageInputInReminders ? "none" : "inline";
					$IdinDdbFetched = $translationsList['id'];
					$wordInForeignLanguageFetched = $translationsList['wordInForeignLanguage'];
					$pronunciationForeignWordFetched = $translationsList['pronunciationForeignWord'];
					$buttonPronunciationDisabledOrNot = $pronunciationForeignWordFetched ==='' ? 'disabled' : '';
					$wordInMyLanguageFetched = $translationsList['wordInMyLanguage'];
					
					echo 	'<div id="containerWordReminder'.$i.'" class="containerWordReminder" data-isMylanguageInput="'.$isMylanguageInputInReminders.'" data-rankRepetition="'.($rankRepetition+1).'">
								<div id="containerWordInMyLanguage'.$i.'" class="wordReminder wordInMyLanguage" onclick="editWordInMyLanguage('.$i.')">'
									.'<span id="wordInMyLanguage'.$i.'" data-idInDdb="'.$IdinDdbFetched.'" style="display:'.$displayMyLanguage.'">'
									.$wordInMyLanguageFetched
									.'</span>'
								.'</div>'
								.'<button id="showTranslation'.$i.'" onclick="showTranslation('.$i.')">'
									.$arrowShowsWordToGuess
								.'</button>'
								.'<div id="containerWordInForeignLanguage'.$i.'" class="wordReminder wordInForeignLanguage" onclick="editWordInForeignLanguage('.$i.')">'
									.'<span id="wordInForeignLanguage'.$i.'" data-idInDdb="'.$IdinDdbFetched.'" style="display:'.$displayForeignLanguage.'">'
										.$wordInForeignLanguageFetched
									.'</span>'	
								.'</div>'
								.'<button id="showPronunciation'.$i.'" onclick="showPronunciation('.$i.')"'.$buttonPronunciationDisabledOrNot.'>
									>'
								.'</button>'
								.'<div id="containerPronunciationForeignWord'.$i.'" class="wordReminder pronunciationForeignWord" onclick="editPronunciationForeignLanguage('.$i.')">'
									.'<span id="pronunciationForeignWord'.$i.'" data-idInDdb="'.$IdinDdbFetched.'" style="display:none">'
										.$pronunciationForeignWordFetched
									.'</span>'
								.'</div>'
								.'<div class="rankRepetition">'
									.($rankRepetition+1)
									.'<span class="delayedTimeOfRecall">'
										.'+'.delayedTimeOfRecall($translationsList['dateCreation'],$rankRepetition)
									.'<span>'	
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

		function delayedTimeOfRecall($sDateCreation,$rankRepetition) {
			global $aRepetitionTimes;
			$oDateNormalRecall = new DateTime($sDateCreation);
			//echo "DateNormalRecall=".date_format($oDateNormalRecall,"Y-m-d h:i:s").'<Br><Br>';
			date_add($oDateNormalRecall,date_interval_create_from_date_string($aRepetitionTimes[$rankRepetition]." HOUR")); // $aRepetitionTimes[$rankRepetition]
			//echo '$oDateNormalRecall après = '.date_format($oDateNormalRecall,"Y-m-d h:i:s").'<Br><Br>';
			$oDateNow = new DateTime();
			//echo "now =".date_format($oDateNow,"Y-m-d H:i:s").'<Br><Br>';
			$diff = date_diff($oDateNormalRecall,$oDateNow);
			$days = $diff->format("%a");
			
			return $days === '0' ? $diff->format("%hh") : $days.'j';
		}
?>
		<button id="seeAllItemsInDb" onclick="seeAllItemsInDb()">voir tout</button>
		<script>
			var idTopic = <?php echo $idTopic; ?>;
			var foreignLanguage = "<?php echo $foreignLanguage; ?>";
		</script> 
		<script src="memoryRepeater.js"></script>
    
	</body>
</html>