// Various toast functions that are called on successful or unsuccessful php events.
// https://www.cssscript.com/toast-prompt-tata/

function notifyOnSuccessfulUsersTableInfoChange(){
    tata.success('Andmed muudetud!', 'Liikme andmed edukalt muudetud!')
}

// Password toasts
function notifyOnSuccessfulPasswordChange(){
    tata.success('Parool muudetud', 'Parool muudetud edukalt')
}
function notifyIfPasswordsDidNotMatch(){
    tata.info('Parooli ei muudetud!', 'Paroolid ei klappinud')
}

function notifyOnBadPasswords(){
    tata.error('Vale parool', 'Parool oli vale!')
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