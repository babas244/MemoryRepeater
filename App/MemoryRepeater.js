var isMylanguageInput = true;
var myLanguage = "français";

oDOMWordInMyLanguage = document.getElementById("wordInMyLanguage");
oDOMWordInForeignLanguage = document.getElementById("wordInForeignLanguage");
oDOMPronunciationForeignWord = document.getElementById("pronunciationForeignWord");

oDOMSubmitFormTranslation = document.getElementById("submitFormTranslation");
oDOMIsMylanguageInput = document.getElementById("isMylanguageInput");

oDOMWordInMyLanguage.focus();

oDOMWordInMyLanguage.addEventListener("input", checkIfFormIsSendable, false);
oDOMWordInForeignLanguage.addEventListener("input", checkIfFormIsSendable, false);

document.getElementById("changeInputLanguage").addEventListener("click", changeInputLanguage, false);

function checkIfFormIsSendable() { // check if translation form is correct
	if (oDOMWordInMyLanguage.value !== "") { // if the user has already entered anything in his language
		oDOMWordInForeignLanguage.disabled = false;		
	}
	
	if (oDOMWordInForeignLanguage.value !== "") {
		oDOMWordInMyLanguage.disabled = false;
		oDOMPronunciationForeignWord.disabled = false;
	}

	if (oDOMWordInMyLanguage.value !== "" && oDOMWordInForeignLanguage.value !== "") {
		oDOMSubmitFormTranslation.disabled = false;
	}
	else {
		oDOMSubmitFormTranslation.disabled = true;
	}
	
}

function changeInputLanguage() { // triggered when user presses double arrow button of translation
	if (isMylanguageInput) { // if the initial word was in my language
		document.getElementById("containerInputTranslationsWithoutSubmit").style.flexDirection = "row-reverse";
		isMylanguageInput = false;
		oDOMIsMylanguageInput.value = 0;
		if (oDOMWordInMyLanguage.value ==="") {oDOMWordInMyLanguage.disabled = true;}
		oDOMWordInForeignLanguage.disabled = false;
		oDOMWordInForeignLanguage.placeholder = "Mot initial en " + foreignLanguage;
		oDOMWordInMyLanguage.placeholder = "en " + myLanguage;
	}
	else { // if the initial word was in foreign language
		document.getElementById("containerInputTranslationsWithoutSubmit").style.flexDirection = "row";	
		isMylanguageInput = true;
		oDOMIsMylanguageInput.value = 1;
		oDOMWordInMyLanguage.disabled = false;
		if (oDOMWordInForeignLanguage.value ==="") {oDOMWordInForeignLanguage.disabled = true};
		oDOMWordInMyLanguage.placeholder = "Mot initial en " + myLanguage;
		oDOMWordInForeignLanguage.placeholder = "en " + foreignLanguage;		
		
	}
	
}

function showTranslation(idTranslation) { // triggered when user presses button to hidden translation
	var isMylanguageInputInReminder = document.getElementById("containerWordReminder"+idTranslation).getAttribute('data-isMylanguageInput') === "0" ? false : true;
	var oDOMShowTranslation = document.getElementById('showTranslation' + idTranslation);
	if (isMylanguageInputInReminder) {
		var oDOMwordInForeignLanguage = document.getElementById("wordInForeignLanguage"+idTranslation);
		if (oDOMwordInForeignLanguage.style.display === 'none') {
			oDOMwordInForeignLanguage.style.display = 'inline';
			oDOMShowTranslation.style.backgroundColor = 'green';
		}
		else {
			oDOMwordInForeignLanguage.style.display = 'none';
			oDOMShowTranslation.style.backgroundColor = 'orange';
			document.getElementById('pronunciationForeignWord'+idTranslation).style.display = 'none'; // not forget to hide the pronunciation too
		}
	}
	else {
		var oDOMWordInMyLanguage = document.getElementById("wordInMyLanguage"+idTranslation);
		if (oDOMWordInMyLanguage.style.display === 'none') {
			oDOMWordInMyLanguage.style.display = 'inline';
			oDOMShowTranslation.style.backgroundColor = 'green';
		}
		else {
			oDOMWordInMyLanguage.style.display = 'none';
			oDOMShowTranslation.style.backgroundColor = 'orange';
		}
	}
}

function editWordInMyLanguage(idTranslation) { // triggered when user asks for updating word in his language of a translation 
	var oDOMWordInMyLanguage = document.getElementById("wordInMyLanguage"+idTranslation);
	if (oDOMWordInMyLanguage.style.display !=='none') {
		var oDOMContainerWordInMyLanguage = document.getElementById("containerWordInMyLanguage"+idTranslation);
		var oDOMFormWordInMyLanguage = document.createElement('form');
		oDOMFormWordInMyLanguage.method='POST';
		oDOMFormWordInMyLanguage.action='memoryRepeater.php?idTopic=' + idTopic;
		var oDOMInputWordInMyLanguage = document.createElement('input'); 
		oDOMInputWordInMyLanguage.type = 'text'; 
		oDOMInputWordInMyLanguage.name = 'newWordInMyLanguage';
		oDOMInputWordInMyLanguage.value = oDOMWordInMyLanguage.textContent;
		var oDOMHiddenIdInDdb = document.createElement('input');
		oDOMHiddenIdInDdb.type = 'hidden';
		oDOMHiddenIdInDdb.name = 'idInDdb';
		oDOMHiddenIdInDdb.value = oDOMWordInMyLanguage.getAttribute('data-idInDdb');
		oDOMContainerWordInMyLanguage.appendChild(oDOMFormWordInMyLanguage);
		oDOMFormWordInMyLanguage.appendChild(oDOMInputWordInMyLanguage);
		oDOMFormWordInMyLanguage.appendChild(oDOMHiddenIdInDdb);
		oDOMInputWordInMyLanguage.addEventListener('blur', function() {
			oDOMFormWordInMyLanguage.removeChild(oDOMInputWordInMyLanguage);
			oDOMFormWordInMyLanguage.removeChild(oDOMHiddenIdInDdb);
		},false); 
		oDOMInputWordInMyLanguage.focus();
	}
}

function editWordInForeignLanguage(idTranslation) { // triggered when user asks for updating foreign word of a translation 
	var oDOMWordInForeignLanguage = document.getElementById("wordInForeignLanguage"+idTranslation);
	if (oDOMWordInForeignLanguage.style.display !=='none') {
		var oDOMContainerWordInForeignLanguage = document.getElementById("containerWordInForeignLanguage"+idTranslation);
		var oDOMFormWordInForeignLanguage = document.createElement('form');
		oDOMFormWordInForeignLanguage.method='POST';
		oDOMFormWordInForeignLanguage.action='memoryRepeater.php?idTopic=' + idTopic;
		var oDOMInputWordInForeignLanguage = document.createElement('input'); 
		oDOMInputWordInForeignLanguage.type = 'text'; 
		oDOMInputWordInForeignLanguage.name = 'newWordInForeignLanguage';
		oDOMInputWordInForeignLanguage.value = oDOMWordInForeignLanguage.textContent;
		var oDOMHiddenIdInDdb = document.createElement('input');
		oDOMHiddenIdInDdb.type = 'hidden';
		oDOMHiddenIdInDdb.name = 'idInDdb';
		oDOMHiddenIdInDdb.value = oDOMWordInForeignLanguage.getAttribute('data-idInDdb');
		oDOMContainerWordInForeignLanguage.appendChild(oDOMFormWordInForeignLanguage);
		oDOMFormWordInForeignLanguage.appendChild(oDOMInputWordInForeignLanguage);
		oDOMFormWordInForeignLanguage.appendChild(oDOMHiddenIdInDdb);
		oDOMInputWordInForeignLanguage.addEventListener('blur', function() {
			oDOMFormWordInForeignLanguage.removeChild(oDOMInputWordInForeignLanguage);
			oDOMFormWordInForeignLanguage.removeChild(oDOMHiddenIdInDdb);
		},false); 
		oDOMInputWordInForeignLanguage.focus();
	}
}

function editPronunciationForeignLanguage(idTranslation) { // triggered when user asks for updating pronunciation of foreign word 
	var oDOMPronunciationForeignWord = document.getElementById("pronunciationForeignWord"+idTranslation);
	if (oDOMPronunciationForeignWord.style.display !=='none') {
		var oDOMContainerPronunciationForeignWord = document.getElementById("containerPronunciationForeignWord"+idTranslation);
		var oDOMFormPronunciationForeignWord = document.createElement('form');
		oDOMFormPronunciationForeignWord.method='POST';
		oDOMFormPronunciationForeignWord.action='memoryRepeater.php?idTopic=' + idTopic;
		var oDOMInputPronunciationForeignWord = document.createElement('input'); 
		oDOMInputPronunciationForeignWord.type = 'text'; 
		oDOMInputPronunciationForeignWord.name = 'newPronunciationForeignWord';
		oDOMInputPronunciationForeignWord.value = oDOMPronunciationForeignWord.textContent;
		var oDOMHiddenIdInDdb = document.createElement('input');
		oDOMHiddenIdInDdb.type = 'hidden';
		oDOMHiddenIdInDdb.name = 'idInDdb';
		oDOMHiddenIdInDdb.value = oDOMPronunciationForeignWord.getAttribute('data-idInDdb');
		oDOMContainerPronunciationForeignWord.appendChild(oDOMFormPronunciationForeignWord);
		oDOMFormPronunciationForeignWord.appendChild(oDOMInputPronunciationForeignWord);
		oDOMFormPronunciationForeignWord.appendChild(oDOMHiddenIdInDdb);
		oDOMInputPronunciationForeignWord.addEventListener('blur', function() {
			oDOMFormPronunciationForeignWord.removeChild(oDOMInputPronunciationForeignWord);
			oDOMFormPronunciationForeignWord.removeChild(oDOMHiddenIdInDdb);
		},false); 
		oDOMInputPronunciationForeignWord.focus();
	}
}



function openTranslationMenu(idTranslation) {  // faire une div de tout le menu et vérifier si a des enfants donc afficher, sinon créer
	var oDOMTranslationMenu = document.getElementById('translationMenu'+idTranslation);
	var oDOMContainerWordReminder = document.getElementById('containerWordReminder'+idTranslation);
	var oDOMcontainerTranslationMenu = document.getElementById('containerTranslationMenu'+idTranslation);
	var oDOMcontainerPronunciationForeignWord = document.getElementById("containerPronunciationForeignWord"+idTranslation);
	
	if (oDOMTranslationMenu.value === "+") {
		oDOMTranslationMenu.value = '-';
		oDOMTranslationMenu.textContent = '-';
		oDOMcontainerPronunciationForeignWord.style.display = 'none';
		if (oDOMcontainerTranslationMenu.hasChildNodes()) {
			oDOMcontainerTranslationMenu.style.display = 'block';
			oDOMcontainerPronunciationForeignWord.style.display = "none";			
		}
		else {
			var oDOMRecallApproved = document.createElement('button'); // button go to next recall
			oDOMRecallApproved.textContent = 'V';
			oDOMRecallApproved.className = 'recallApproved translationMenu';
			oDOMRecallApproved.id = 'recallApproved' + idTranslation;
			oDOMRecallApproved.addEventListener('click', function () {
				var oDOMFormRecallApproved = document.createElement('form');
				oDOMFormRecallApproved.method='POST';
				oDOMFormRecallApproved.action='memoryRepeater.php?idTopic=' + idTopic;
				var oDOMHiddenIdInDdb = document.createElement('input');
				oDOMHiddenIdInDdb.type = 'hidden';
				oDOMHiddenIdInDdb.name = 'idInDdb';
				oDOMHiddenIdInDdb.value = document.getElementById('wordInMyLanguage'+idTranslation).getAttribute('data-idInDdb');
				var oDOMHiddenIsApproved = document.createElement('input');
				oDOMHiddenIsApproved.type = 'hidden';
				oDOMHiddenIsApproved.name = 'IsApproved';
				oDOMHiddenIsApproved.value = 1;
				oDOMcontainerTranslationMenu.appendChild(oDOMFormRecallApproved);
				oDOMFormRecallApproved.appendChild(oDOMHiddenIsApproved);
				oDOMFormRecallApproved.appendChild(oDOMHiddenIdInDdb);
				oDOMFormRecallApproved.submit();
			},false);
			oDOMcontainerTranslationMenu.appendChild(oDOMRecallApproved);
			
			var oDOMRecallIsToRepeat = document.createElement('button');
			oDOMRecallIsToRepeat.textContent = 'C';
			oDOMRecallIsToRepeat.className = 'recallIsToRepeat translationMenu';
			oDOMRecallIsToRepeat.id = 'recallIsToRepeat' + idTranslation;
			oDOMRecallIsToRepeat.addEventListener('click', function () {
				var oDOMFormRecallIsToRepeat = document.createElement('form');
				oDOMFormRecallIsToRepeat.method='POST';
				oDOMFormRecallIsToRepeat.action='memoryRepeater.php?idTopic=' + idTopic;
				var oDOMHiddenIdInDdb = document.createElement('input');
				oDOMHiddenIdInDdb.type = 'hidden';
				oDOMHiddenIdInDdb.name = 'idInDdb';
				oDOMHiddenIdInDdb.value = document.getElementById('wordInMyLanguage'+idTranslation).getAttribute('data-idInDdb');
				var oDOMHiddenIsToRepeat = document.createElement('input');
				oDOMHiddenIsToRepeat.type = 'hidden';
				oDOMHiddenIsToRepeat.name = 'IsToRepeat';
				oDOMHiddenIsToRepeat.value = 1;
				oDOMcontainerTranslationMenu.appendChild(oDOMFormRecallIsToRepeat);
				oDOMFormRecallIsToRepeat.appendChild(oDOMHiddenIsToRepeat);
				oDOMFormRecallIsToRepeat.appendChild(oDOMHiddenIdInDdb);
				oDOMFormRecallIsToRepeat.submit();
			},false);			
			oDOMcontainerTranslationMenu.appendChild(oDOMRecallIsToRepeat);	
			
			if (oDOMContainerWordReminder.getAttribute('data-rankRepetition') !=="1") { // button downgrade recall step if step > 1
				var oDOMRecallIsToDowngrade = document.createElement('button'); 
				oDOMRecallIsToDowngrade.textContent = '<';
				oDOMRecallIsToDowngrade.className = 'recallIsToDownGrade translationMenu';
				oDOMRecallIsToDowngrade.id = 'recallIsToDownGrade' + idTranslation;
				oDOMRecallIsToDowngrade.addEventListener('click', function () {
					var oDOMFormRecallIsToDowngrade = document.createElement('form');
					oDOMFormRecallIsToDowngrade.method='POST';
					oDOMFormRecallIsToDowngrade.action='memoryRepeater.php?idTopic=' + idTopic;
					var oDOMHiddenIdInDdb = document.createElement('input');
					oDOMHiddenIdInDdb.type = 'hidden';
					oDOMHiddenIdInDdb.name = 'idInDdb';
					oDOMHiddenIdInDdb.value = document.getElementById('wordInMyLanguage'+idTranslation).getAttribute('data-idInDdb');
					var oDOMHiddenisToDowngrade = document.createElement('input');
					oDOMHiddenisToDowngrade.type = 'hidden';
					oDOMHiddenisToDowngrade.name = 'IsToDowngrade';
					oDOMHiddenisToDowngrade.value = 1;
					oDOMcontainerTranslationMenu.appendChild(oDOMFormRecallIsToDowngrade);
					oDOMFormRecallIsToDowngrade.appendChild(oDOMHiddenisToDowngrade);
					oDOMFormRecallIsToDowngrade.appendChild(oDOMHiddenIdInDdb);
					oDOMFormRecallIsToDowngrade.submit();
				},false);			
				oDOMcontainerTranslationMenu.appendChild(oDOMRecallIsToDowngrade);	
			}
			
            
			var oDOMDeleteTranslation = document.createElement('button'); // button delete translation
			oDOMDeleteTranslation.textContent = 'X';
			oDOMDeleteTranslation.className = 'deleteTranslation translationMenu';
			oDOMDeleteTranslation.id = 'deleteTranslation' + idTranslation;
			oDOMDeleteTranslation.addEventListener('click', function () {
				var oDOMFormDeleteTranslation = document.createElement('form');
				oDOMFormDeleteTranslation.method='POST';
				oDOMFormDeleteTranslation.action='memoryRepeater.php?idTopic=' + idTopic;
				var oDOMHiddenIdInDdb = document.createElement('input');
				oDOMHiddenIdInDdb.type = 'hidden';
				oDOMHiddenIdInDdb.name = 'idInDdb';
				oDOMHiddenIdInDdb.value = document.getElementById('wordInMyLanguage'+idTranslation).getAttribute('data-idInDdb');
				var oDOMHiddenIsDelete = document.createElement('input');
				oDOMHiddenIsDelete.type = 'hidden';
				oDOMHiddenIsDelete.name = 'IsDelete';
				oDOMHiddenIsDelete.value = 1;
				oDOMcontainerTranslationMenu.appendChild(oDOMFormDeleteTranslation);
				oDOMFormDeleteTranslation.appendChild(oDOMHiddenIsDelete);
				oDOMFormDeleteTranslation.appendChild(oDOMHiddenIdInDdb);
				oDOMFormDeleteTranslation.submit();
			},false);
			oDOMcontainerTranslationMenu.appendChild(oDOMDeleteTranslation);
		}			
	}
	else { // so oDOMTranslationMenu.value = '-'
		oDOMcontainerTranslationMenu.style.display = 'none';
		oDOMcontainerPronunciationForeignWord.style.display = "inline";
		oDOMTranslationMenu.value = '+';
		oDOMTranslationMenu.textContent = '+';			
	}
}  

function showPronunciation(idTranslation) { // button showPronunciation was clicked 
	var oDOMPronunciationForeignWord = document.getElementById('pronunciationForeignWord'+idTranslation);
	if (oDOMPronunciationForeignWord.style.display === 'none') {
		oDOMPronunciationForeignWord.style.display = 'inline';
	}
	else {
		oDOMPronunciationForeignWord.style.display = 'none';
	}	
}
