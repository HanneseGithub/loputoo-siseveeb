jQuery(document).ready( function() {
    jQuery(".single-post-going__button").click( function(e) {
        e.preventDefault();

        event_id = jQuery(this).attr("data-event_id");
        nonce = jQuery(this).attr("data-nonce");
        event_participation = jQuery(this).attr("data-event_participation");

        jQuery.ajax({
            type : "post",
            url  : 'http://localhost:3000/wordpress/wp-admin/admin-ajax.php',
            data : {action: "event_participation", event_id: event_id, nonce: nonce, event_participation: event_participation },
            success: function(response) {
                if ( !jQuery(e.currentTarget).hasClass('active') ) {
                    jQuery(e.currentTarget).addClass('active');
                }

                if ( jQuery(e.currentTarget).siblings().hasClass('active') ) {
                    jQuery(e.currentTarget).siblings().removeClass('active');
                }
            }
        })
    })
 })