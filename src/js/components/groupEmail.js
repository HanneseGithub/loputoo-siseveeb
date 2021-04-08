function openEmailForm(){
    let arrayOfEmails = document.getElementsByClassName("sendEmailsTo")
    let groupEmailInputField = document.getElementById("sendGroupEmail")
    let emailsForInput = []

    // Push all checkboxed emails to the email form
    for (let i = 0; i < arrayOfEmails.length; i++) {
       if(arrayOfEmails[i].checked == true){
           emailsForInput.push(arrayOfEmails[i].value)
       }
    }
    console.log(emailsForInput)
    groupEmailInputField.value = emailsForInput

}