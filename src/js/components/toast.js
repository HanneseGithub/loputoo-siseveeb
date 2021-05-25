// Various toast functions that are called on successful or unsuccessful php events.
// https://www.cssscript.com/toast-prompt-tata/

function notifyOnSuccess($messageTitle, $toastMessage) {
    tata.success($messageTitle, $toastMessage);
}

function notifyOnError($messageTitle, $toastMessage) {
    tata.error($messageTitle, $toastMessage);
}

function notifyOnSuccessfulUsersTableInfoChange(){
    tata.success('Andmed muudetud!', 'Liikme andmed edukalt muudetud!')
}

// User edits his information
function notifyOnUserinfoBeingUpdated(){
    tata.success('Andmed muudetud', 'Andmed muudetud edukalt')
}
function notifyOnUserinfoNotBeingUpdated(){
    tata.error('Esines probleem', 'Kasutaja andmed ei muutunud!')
}
function notifyIfUserWantsToChangeEmailButCant(){
    tata.info('E-maili ei muudetud!', 'See e-mail on juba andmebaasis')
}
function notifyManagerThatUserinfoWasUpdated(){
    tata.success('Andmed muudetud', 'Kasutaja andmed muudeti edukalt')
}
function notifyManagerThatUserinfoWasAFailure(){
    tata.error('Andmed Pole muudetud', 'Kasutaja andmeid ei muudetud edukalt')
}

// Posts are deleted
function notifyOnMusicBeingTrashed(songName){
    let songDeletedHeader = songName + ' on kustutatud';
    tata.success( songDeletedHeader, 'Laul on edukalt eemaldatud')
}
function notifyOnDocumentBeingTrashed(documentName){
    let documentHeader = documentName + ' on kustutatud';
    tata.success( documentHeader, 'Dokument on prügikastis')
}
function notifyOnSuccessfulEmailSent(){
    tata.success( 'Email saadetud', 'E-mail on saadetud')
}
function notifyOnSuccessfulMultipleEmailSent(){
    tata.success( 'Emailid saadetud', 'E-mailid on saadetud')
}