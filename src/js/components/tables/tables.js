function refreshUsersTableStickyHeaderValues(){
    let usersCheckbox = jQuery(".users-table__checkbox-js");
    let usersAvatar = jQuery(".users-table__avatar-js");
    let usersName = jQuery(".users-table__name-js");

    // Check if checkbox exists in the users table.
    if (usersCheckbox.length) {
        // Both name and avatar exist
        if (usersAvatar.length && usersName.length) {
            usersAvatar.css( "left", usersCheckbox.outerWidth() );
            usersName.css( "left", (usersCheckbox.outerWidth() + usersAvatar.outerWidth()) );
        // Name exists
        } else if (!usersAvatar.length && usersName.length) {
            usersName.css( "left", usersCheckbox.outerWidth() );
        // Avatar exists
        } else if (usersAvatar.length && !usersName.length) {
            usersAvatar.css( "left", usersCheckbox.outerWidth() );
        }
    } else {
        // Both name and avatar exist
        if (usersAvatar.length && usersName.length) {
            usersName.css( "left", usersAvatar.outerWidth() );
        }
    }
}

jQuery(document).ready(function( $ ) {
    if ($(".users-table").length) {
        refreshUsersTableStickyHeaderValues();

        $(window).resize(function() {
            debounce(function() {
                refreshUsersTableStickyHeaderValues();
            }, 500, 'users-table')
        });
    }
});

jQuery(document).ready(function( $ ) {
    $(".form-control.search-input").keydown(function() {
        setTimeout(function() {
            if ($(".naiskoor-table__body tr.no-records-found").length) {
                $(".naiskoor-table__body tr.no-records-found td").text('Vasteid ei leitud.');
            }
        }, 1000);
    });
});

jQuery(document).ready(function( $ ) {
    if ($(".form-control.search-input").length) {
        $(".form-control.search-input").attr('placeholder', 'Otsi...')
    }
});

jQuery(document).ready(function( $ ) {
    $(".naiskoor-table th").click( function(e) {
        if ($(e.currentTarget).children().hasClass('sortable')) {
            $('naiskoor-table').ready(function () {
                refreshUsersTableStickyHeaderValues();
            });
        }
    });

    $(".dropdown-item input").on('click', function() {
        refreshUsersTableStickyHeaderValues();

        $(".naiskoor-table th").click( function(e) {
            if ($(e.currentTarget).children().hasClass('sortable')) {
                $('naiskoor-table').ready(function () {
                    refreshUsersTableStickyHeaderValues();
                });
            }
        });
    })
});
