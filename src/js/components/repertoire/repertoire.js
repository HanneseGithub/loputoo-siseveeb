jQuery(document).ready(function ($) {

    $('.repertoire-play-button').click(function (e) {
        let elm = e.currentTarget;
        
        let audio = $('#repertoireAudio');
        console.log(audio);
        let source = $('#repertoireAudioSource');
        source.src = $(elm).data('value');
        console.log($(elm).data('value'));
        console.log(source);
        audio[0].pause();
        audio[0].load(); //call this to just preload the audio without playing
        audio[0].play(); //call this to play the song right away
    });
});