// Example of a toast
document.getElementById('loadToast').addEventListener('click', function(){
    tata.success('Parool muudetud', 'Parool muudetud edukalt', {
        position: 'br',
        duration: 5000,
        animate: 'slide'
    })
})

// Different toast functions that are called on successful or unscuccesful php events.
function notifyOnSuccessfulPasswordChange(){
    tata.success('Parool muudetud', 'Parool muudetud edukalt')
}