jQuery(document).ready( function() {
    jQuery(".single-post-going__button").click( function(e) {
        e.preventDefault();

        event_id = jQuery(this).attr("data-event_id");
        nonce = jQuery(this).attr("data-nonce");
        event_participation = jQuery(this).attr("data-event_participation");

        jQuery.ajax({
            type : "post",
            url  : 'http://localhost/wordpress/wp-admin/admin-ajax.php',
            data : {action: "event_participation", event_id: event_id, nonce: nonce, event_participation: event_participation },
            success: function(response) {
                alert(response);
                jQuery(".single-post-going__button").hide();
            }
        })
    })
 })