jQuery(document).ready(function ($) {

    $('.repertoire-play-button').click(function (e) {
        var elm = e.currentTarget;
        var audio = document.getElementById('repertoireAudio');
      
        var source = document.getElementById('repertoireAudioSource');
        source.src = elm.getAttribute('data-value');
      
        audio.load();
        audio.play(); 
    });
});