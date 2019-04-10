$(function() {
    var settings = {

        animation: 700, // Animation speed
        buttons: {
            cancel: {
                action: function() { Apprise('close'); },
                className: 'gray', 
                id: 'confirm',
                text: 'Cancel',
            },
            confirm: {
                action: function() { window.location = $(this).attr('href'); },
                className: 'red',
                id: 'confirm',
                text: 'Delete',
            }
        },
        input: false,
        override: false,
    };
});