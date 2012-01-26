jQuery(document).ready(function(){
    jQuery('#option-tabs').tabs();
    jQuery("#option-tabs").bind("tabsshow", function(event, ui) { 
        window.location.hash = ui.tab.hash;
    })
});

jQuery('#update_account').dialog({
    autoOpen: false,
    width: 350,
    modal: true,
    resizable: false,
    draggable: false,
    buttons: {
        "Edit": function() {
            var fail = false;
            for (var i=0; i < jQuery('#add_question input').length; i++) {
                if (jQuery('#add_question input').eq(i).val() == '') {
                    fail = true;
                }
            }
            if (!fail) {
                jQuery('#add_question').submit();
            } else {
                jQuery('#add_tips').css('color', 'red');
            }
        },
        Cancel: function() {
            jQuery(this).dialog('close');
        }
    }
});

jQuery('#update').dialog({
    autoOpen: false,
    height: 350,
    width: 350,
    modal: true,
    resizable: false,
    draggable: false,
    buttons: {
        "Update Question": function() {
            var fail = false;
            for (var i=0; i < jQuery('#update_question input').length; i++) {
                if (jQuery('#update_question input').eq(i).val() == '') {
                    fail = true;
                }
            }
            if (!fail) {
                jQuery('#update_question').submit();
            } else {
                jQuery('#edit_tips').css('color', 'red');
            }
        },
        Cancel: function() {
            window.location = 'plugins.php?page=quiz_manager';
        }
    }
});

jQuery('#delete').dialog({
        autoOpen: false,
        position: ['center', 100],
        height: 140,
        modal: true,
        resizable: false,
        draggable: false,
        buttons: {
            Delete: function() {
                jQuery('#delete_question').submit();
            },
            Close: function() {
                 window.location = 'plugins.php?page=quiz_manager';
            }
        }
});
