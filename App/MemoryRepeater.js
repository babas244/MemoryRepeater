var isMylanguageInput = true;
var myLanguage = "français";

oDOMWordInMyLanguage = document.getElementById("wordInMyLanguage");
oDOMWordInForeignLanguage = document.getElementById("wordInForeignLanguage");
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
	document.getElementById("wordInMyLanguage"+idTranslation).style.display = "inline";
	document.getElementById("wordInForeignLanguage"+idTranslation).style.display = "inline";
}

function editWordInMyLanguage(idTranslation) { // triggered when user asks for updating word in his language of a translation 
	var oDOMContainerWordInMyLanguage = document.getElementById("containerWordInMyLanguage"+idTranslation);
	var oDOMWordInMyLanguage = document.getElementById("wordInMyLanguage"+idTranslation);
	var oDOMFormWordInMyLanguage = document.createElement('form');
	oDOMFormWordInMyLanguage.method='get';
	oDOMFormWordInMyLanguage.action='memoryRepeater.php';
	var oDOMInputWordInMyLanguage = document.createElement('input'); 
	oDOMInputWordInMyLanguage.type = 'text'; 
	oDOMInputWordInMyLanguage.name = 'newWordInMyLanguage';
	oDOMInputWordInMyLanguage.value = oDOMWordInMyLanguage.textContent;
	var oDOMHiddenIdInDdb = document.createElement('input');
	oDOMHiddenIdInDdb.type = 'hidden';
	oDOMHiddenIdInDdb.name = 'idInDdb';
	oDOMHiddenIdInDdb.value = oDOMWordInMyLanguage.getAttribute('data-idInDdb');
	var oDOMHiddenIdTopic = document.createElement('input');
	oDOMHiddenIdTopic.type = 'hidden';
	oDOMHiddenIdTopic.name = 'idTopic';
	oDOMHiddenIdTopic.value = idTopic;
	oDOMContainerWordInMyLanguage.appendChild(oDOMFormWordInMyLanguage);
	oDOMFormWordInMyLanguage.appendChild(oDOMInputWordInMyLanguage);
	oDOMFormWordInMyLanguage.appendChild(oDOMHiddenIdInDdb);
	oDOMFormWordInMyLanguage.appendChild(oDOMHiddenIdTopic);
	oDOMInputWordInMyLanguage.addEventListener('blur', function() {
		oDOMFormWordInMyLanguage.removeChild(oDOMInputWordInMyLanguage);
		oDOMFormWordInMyLanguage.removeChild(oDOMHiddenIdInDdb);
		oDOMFormWordInMyLanguage.removeChild(oDOMHiddenIdTopic);
	},false); 
	oDOMInputWordInMyLanguage.focus();
}

function editWordInForeignLanguage(idTranslation) { // triggered when user asks for updating foreign word of a translation 
	var oDOMContainerWordInForeignLanguage = document.getElementById("containerWordInForeignLanguage"+idTranslation);
	var oDOMWordInForeignLanguage = document.getElementById("wordInForeignLanguage"+idTranslation);
	var oDOMFormWordInForeignLanguage = document.createElement('form');
	oDOMFormWordInForeignLanguage.method='get';
	oDOMFormWordInForeignLanguage.action='memoryRepeater.php';
	var oDOMInputWordInForeignLanguage = document.createElement('input'); 
	oDOMInputWordInForeignLanguage.type = 'text'; 
	oDOMInputWordInForeignLanguage.name = 'newWordInForeignLanguage';
	oDOMInputWordInForeignLanguage.value = oDOMWordInForeignLanguage.textContent;
	var oDOMHiddenIdInDdb = document.createElement('input');
	oDOMHiddenIdInDdb.type = 'hidden';
	oDOMHiddenIdInDdb.name = 'idInDdb';
	oDOMHiddenIdInDdb.value = oDOMWordInForeignLanguage.getAttribute('data-idInDdb');
	var oDOMHiddenIdTopic = document.createElement('input');
	oDOMHiddenIdTopic.type = 'hidden';
	oDOMHiddenIdTopic.name = 'idTopic';
	oDOMHiddenIdTopic.value = idTopic;
	oDOMContainerWordInForeignLanguage.appendChild(oDOMFormWordInForeignLanguage);
	oDOMFormWordInForeignLanguage.appendChild(oDOMInputWordInForeignLanguage);
	oDOMFormWordInForeignLanguage.appendChild(oDOMHiddenIdInDdb);
	oDOMFormWordInForeignLanguage.appendChild(oDOMHiddenIdTopic);
	oDOMInputWordInForeignLanguage.addEventListener('blur', function() {
		oDOMFormWordInForeignLanguage.removeChild(oDOMInputWordInForeignLanguage);
		oDOMFormWordInForeignLanguage.removeChild(oDOMHiddenIdInDdb);
		oDOMFormWordInForeignLanguage.removeChild(oDOMHiddenIdTopic);
	},false); 
	oDOMInputWordInForeignLanguage.focus();
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
			var oDOMDeleteTranslation = document.createElement('button'); // button delete translation
			oDOMDeleteTranslation.textContent = 'X';
			oDOMDeleteTranslation.className = 'deleteTranslation';
			oDOMDeleteTranslation.id = 'deleteTranslation' + idTranslation;
			oDOMDeleteTranslation.addEventListener('click', function () {
				var oDOMFormDeleteTranslation = document.createElement('form');
				oDOMFormDeleteTranslation.method='get';
				oDOMFormDeleteTranslation.action='memoryRepeater.php';
				var oDOMHiddenIdInDdb = document.createElement('input');
				oDOMHiddenIdInDdb.type = 'hidden';
				oDOMHiddenIdInDdb.name = 'idInDdb';
				oDOMHiddenIdInDdb.value = document.getElementById('wordInMyLanguage'+idTranslation).getAttribute('data-idInDdb');
				var oDOMHiddenIdTopic = document.createElement('input');
				oDOMHiddenIdTopic.type = 'hidden';
				oDOMHiddenIdTopic.name = 'idTopic';
				oDOMHiddenIdTopic.value = idTopic;
				var oDOMHiddenIsDelete = document.createElement('input');
				oDOMHiddenIsDelete.type = 'hidden';
				oDOMHiddenIsDelete.name = 'IsDelete';
				oDOMHiddenIsDelete.value = 1;
				oDOMcontainerTranslationMenu.appendChild(oDOMFormDeleteTranslation);
				oDOMFormDeleteTranslation.appendChild(oDOMHiddenIsDelete);
				oDOMFormDeleteTranslation.appendChild(oDOMHiddenIdInDdb);
				oDOMFormDeleteTranslation.appendChild(oDOMHiddenIdTopic);
				oDOMFormDeleteTranslation.submit();
			},false);
			oDOMcontainerTranslationMenu.appendChild(oDOMDeleteTranslation);
			
			var oDOMRecallApproved = document.createElement('button'); // button go to next recall
			oDOMRecallApproved.textContent = 'V';
			oDOMRecallApproved.className = 'recallApproved';
			oDOMRecallApproved.id = 'recallApproved' + idTranslation;
			oDOMRecallApproved.addEventListener('click', function () {
				var oDOMFormRecallApproved = document.createElement('form');
				oDOMFormRecallApproved.method='get';
				oDOMFormRecallApproved.action='memoryRepeater.php';
				var oDOMHiddenIdInDdb = document.createElement('input');
				oDOMHiddenIdInDdb.type = 'hidden';
				oDOMHiddenIdInDdb.name = 'idInDdb';
				oDOMHiddenIdInDdb.value = document.getElementById('wordInMyLanguage'+idTranslation).getAttribute('data-idInDdb');
				var oDOMHiddenIdTopic = document.createElement('input');
				oDOMHiddenIdTopic.type = 'hidden';
				oDOMHiddenIdTopic.name = 'idTopic';
				oDOMHiddenIdTopic.value = idTopic;
				var oDOMHiddenIsApproved = document.createElement('input');
				oDOMHiddenIsApproved.type = 'hidden';
				oDOMHiddenIsApproved.name = 'IsApproved';
				oDOMHiddenIsApproved.value = 1;
				oDOMcontainerTranslationMenu.appendChild(oDOMFormRecallApproved);
				oDOMFormRecallApproved.appendChild(oDOMHiddenIsApproved);
				oDOMFormRecallApproved.appendChild(oDOMHiddenIdInDdb);
				oDOMFormRecallApproved.appendChild(oDOMHiddenIdTopic);
				oDOMFormRecallApproved.submit();
			},false);
			oDOMcontainerTranslationMenu.appendChild(oDOMRecallApproved);
			
			var oDOMRecallIsToRepeat = document.createElement('button');
			oDOMRecallIsToRepeat.textContent = 'C';
			oDOMRecallIsToRepeat.className = 'recallIsToRepeat';
			oDOMRecallIsToRepeat.id = 'recallIsToRepeat' + idTranslation;
			oDOMRecallIsToRepeat.addEventListener('click', function () {
				var oDOMFormRecallIsToRepeat = document.createElement('form');
				oDOMFormRecallIsToRepeat.method='get';
				oDOMFormRecallIsToRepeat.action='memoryRepeater.php';
				var oDOMHiddenIdInDdb = document.createElement('input');
				oDOMHiddenIdInDdb.type = 'hidden';
				oDOMHiddenIdInDdb.name = 'idInDdb';
				oDOMHiddenIdInDdb.value = document.getElementById('wordInMyLanguage'+idTranslation).getAttribute('data-idInDdb');
				var oDOMHiddenIdTopic = document.createElement('input');
				oDOMHiddenIdTopic.type = 'hidden';
				oDOMHiddenIdTopic.name = 'idTopic';
				oDOMHiddenIdTopic.value = idTopic;
				var oDOMHiddenIsToRepeat = document.createElement('input');
				oDOMHiddenIsToRepeat.type = 'hidden';
				oDOMHiddenIsToRepeat.name = 'IsToRepeat';
				oDOMHiddenIsToRepeat.value = 1;
				oDOMcontainerTranslationMenu.appendChild(oDOMFormRecallIsToRepeat);
				oDOMFormRecallIsToRepeat.appendChild(oDOMHiddenIsToRepeat);
				oDOMFormRecallIsToRepeat.appendChild(oDOMHiddenIdInDdb);
				oDOMFormRecallIsToRepeat.appendChild(oDOMHiddenIdTopic);
				oDOMFormRecallIsToRepeat.submit();
			},false);
			oDOMcontainerTranslationMenu.appendChild(oDOMRecallIsToRepeat);			
		}
	}
	else { // so oDOMTranslationMenu.value = '-'
		oDOMcontainerTranslationMenu.style.display = 'none';
		oDOMcontainerPronunciationForeignWord.style.display = "inline";
		oDOMTranslationMenu.value = '+';
		oDOMTranslationMenu.textContent = '+';			
	}
}  


