/*
 * Variable defintions
 */
var ma_accounts = {'sortable_original_order': null, 'ul_order': Array()};

/*
 * Add tabs for navigation
 */
jQuery(document).ready(function(){
    jQuery('#option-tabs').tabs();
    jQuery("#option-tabs").bind("tabsshow", function(event, ui) { 
        window.location.hash = ui.tab.hash;
    })
});

/*
 * Add sortable content, used on Belts & Programs to make belts sortable.
 */
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

        jQuery('#update_belt_order #new_order').val(new_order);
        jQuery('#update_belt_order').submit();
    }
});
jQuery('#sortable').disableSelection();

ma_accounts.sortable_original_order = jQuery('#sortable').sortable('toArray');
for (var i=0; i<ma_accounts.sortable_original_order.length; i++) {
    ma_accounts.ul_order.push(jQuery('#sortable_trash div').children().eq(i).html());
}

/*
 * Begin adding dialogs for jQuery UI
 */
jQuery('#update_account').dialog({
    autoOpen: false,
    width: 350,
    modal: true,
    resizable: false,
    draggable: false,
    buttons: {
        Update: function() {
            jQuery('#edit_account').submit();
        },
        Cancel: function() {
            jQuery(this).dialog('close');
        }
    }
}); //End #update_account

jQuery('#add_belt').dialog({
    autoOpen: false,
    width: 350,
    modal: true,
    resizable: false,
    draggable: false,
    buttons: {
        Add: function() {
            if (jQuery('#add_belt_form #belt').val() !== '') {
                jQuery('#add_belt_form').submit();
            } else {
                jQuery('#add_belt_notification').css('display', 'block');
            }
        },
        Cancel: function() {
            jQuery(this).dialog('close');
        }
    }
}); //End #add_belt

jQuery('#add_program').dialog({
    autoOpen: false,
    width: 350,
    modal: true,
    resizable: false,
    draggable: false,
    buttons: {
        Add: function() {
            if (jQuery('#add_program_form #program').val() !== '') {
                jQuery('#add_program_form').submit();
            } else {
                jQuery('#add_program_notification').css('display', 'block');
            }
        },
        Cancel: function() {
            jQuery(this).dialog('close');
        }
    }
}); //End #add_program


jQuery('#delete_belt').dialog({
        autoOpen: false,
        position: ['center', 100],
        height: 140,
        modal: true,
        resizable: false,
        draggable: false,
        buttons: {
            Delete: function() {
                jQuery('#delete_belt_form').submit();
            },
            Close: function() {
                 window.location = 'plugins.php?page=ma_accounts#belts_programs';
            }
        }
}); //End #delete_belt

jQuery('#delete_program').dialog({
        autoOpen: false,
        position: ['center', 100],
        height: 140,
        modal: true,
        resizable: false,
        draggable: false,
        buttons: {
            Delete: function() {
                jQuery('#delete_program_form').submit();
            },
            Close: function() {
                 window.location = 'plugins.php?page=ma_accounts#belts_programs';
            }
        }
}); //End #delete_program
