function refreshUsersTableStickyHeaderValues(){
    let usersCheckbox = jQuery(".users-table__checkbox-js");
    let usersAvatar = jQuery(".users-table__avatar-js");
    let usersName = jQuery(".users-table__name-js");

    if (usersCheckbox.length) {
        usersAvatar.css( "left", usersCheckbox.outerWidth() );
        usersName.css( "left", (usersCheckbox.outerWidth() + usersAvatar.outerWidth() ) );
    } else {
        usersName.css( "left", usersAvatar.outerWidth() );
    }
}

jQuery(document).ready(function( $ ) {
    if ($(".users-table").length) {
        refreshUsersTableStickyHeaderValues();

        $(window).resize(function() {
            debounce(function() {
                refreshUsersTableStickyHeaderValues()
            }, 500, 'users-table')
        });
    }
});

jQuery(document).ready(function( $ ) {
    if ($(".form-control.search-input").length) {
        $(".form-control.search-input").attr('placeholder', 'Otsi...')
    }
});
