function toggleAllUsersCheckboxes(clickedCheckboxContext){
    let checkboxesToToggle = document.getElementsByClassName("sendEmailTo");

    for (let i = 0; i < checkboxesToToggle.length; i++) {
        checkboxesToToggle[i].checked = clickedCheckboxContext.checked;
    }
}
