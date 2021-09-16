jQuery(document).ready(function() {
    var max_fields = 10; //maximum input boxes allowed
    var wrapper = jQuery(".input_fields_wrap table tbody"); //Fields wrapper
    var add_button = jQuery(".add_field_button"); //Add button ID

    var x = 1; //initlal text box count
    jQuery(add_button).click(function(e) { //on add input button click
        e.preventDefault();
        if (x < max_fields) { //max input box allowed
            x++; //text box increment

            jQuery('#standard-row').clone().append('<td style=" padding: 12px 15px;"><div><a href="#" class="remove_field">Remove</a></div></td>').appendTo(wrapper);

            // jQuery(wrapper).append('<div> <input type="text" name="mytext[' + x + ']"/> <input type="date" name="mydate[' + x + ']"/> <select name="myselect[' + x + ']"><option>Please Select</option></select> <a href="#" class="remove_field">Remove</a> </div>'); // add input boxes.
        }
    });

    jQuery(wrapper).on("click", ".remove_field", function(e) { //user click on remove text
        e.preventDefault();
        console.log('remove');
        jQuery(this).parents('tr').remove();
        x--;
    });

    jQuery(wrapper).on("click", ".clear_field", function(e) { //user click on remove text
        e.preventDefault();
        console.log('remove');
        jQuery(this).parents('tr').find('input').val('');
        x--;
    })
});