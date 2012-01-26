var ma_accounts = {'sortable_original_order': null, 'ul_order': Array()};

jQuery(document).ready(function(){
    jQuery('#option-tabs').tabs();
    jQuery("#option-tabs").bind("tabsshow", function(event, ui) { 
        window.location.hash = ui.tab.hash;
    })
});

jQuery('#sortable').sortable({
    placeholder: 'ui-state-highlight ma_accounts_sortable_placeholder',
    update: function(browserEvent, item) {
        var new_order = jQuery(this).sortable('toArray');
        var temp_indexOf;
        var iterations=0;

        for (var element in new_order) {
            temp_indexOf = ma_accounts.sortable_original_order.indexOf(new_order[element]);
            jQuery('#sortable_trash div').children().eq(iterations).html(ma_accounts.ul_order[temp_indexOf]);
            iterations++;
        }

        ma_accounts.sortable_original_order = new_order;
        ma_accounts.ul_order = Array();
        for (var i=0; i<ma_accounts.sortable_original_order.length; i++) {
            ma_accounts.ul_order.push(jQuery('#sortable_trash div').children().eq(i).html());
        }
    }
});
jQuery('#sortable').disableSelection();
ma_accounts.sortable_original_order = jQuery('#sortable').sortable('toArray');
for (var i=0; i<ma_accounts.sortable_original_order.length; i++) {
    ma_accounts.ul_order.push(jQuery('#sortable_trash div').children().eq(i).html());
}

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

jQuery('#add_belt').dialog({
    autoOpen: false,
    width: 350,
    modal: true,
    resizable: false,
    draggable: false,
    buttons: {
        Add: function() {
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

jQuery('#add_program').dialog({
    autoOpen: false,
    width: 350,
    modal: true,
    resizable: false,
    draggable: false,
    buttons: {
        Add: function() {
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


jQuery('#delete_belt').dialog({
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
                 window.location = 'plugins.php?page=ma_accounts#belts_programs';
            }
        }
});

jQuery('#delete_program').dialog({
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
                 window.location = 'plugins.php?page=ma_accounts#belts_programs';
            }
        }
});
