jQuery(document).ready(function($) {
    // Toggle the checked state of all checkboxes
    $('#select-all-countries').on('click', function() {
        $('input[name="country_blocker_blocked_countries[]"]').prop('checked', this.checked);
    });
});
