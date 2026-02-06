(function ($) {
    'use strict';

    feather.replace();

    $('#df_audit_save').click(function () {
        if (confirm(js_df_audit.confirm_save)) {
            $('#df_audit_result_msg').empty().addClass('d-none');
            $('#df_audit_save').attr('disabled', 'disabled');
            $('#df_audit_loader').removeClass('d-none');
            $.ajax({
                url: js_df_audit.ajax_save_url,
                method: 'POST',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', js_df_audit.nonce);
                },
                data: $("#df_audit_form").serialize()
            }).done(function (data) {
                $('#df_audit_loader').addClass('d-none');
                $('#df_audit_result_msg').html(data.html).removeClass('d-none');
                $('#df_audit_save').removeAttr('disabled');

                if (data.success) {
                    // Update Progress bar
                    $('#audit_progress').attr('aria-valuenow', data.progress.valuenow).attr('style', data.progress.style).removeClass().addClass(data.progress.class).text(data.progress.text);
                    // Update distant id
                    if (data.distant_id === parseInt(data.distant_id, 10)) {
                        $('#audit_distant_id').val(data.distant_id);
                    }
                }
            });
        }
    });

    $('#df_audit_config_save').click(function () {
        $('#df_audit_result_msg').empty().addClass('d-none');
        $('#df_audit_config_save').attr('disabled', 'disabled');
        $('#df_audit_loader').removeClass('d-none');
        $.ajax({
            url: js_df_audit.ajax_config_url,
            method: 'POST',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nonce', js_df_audit.nonce);
            },
            data: $("#df_audit_config_form").serialize()
        }).done(function (data) {
            $('#df_audit_loader').addClass('d-none');
            $('#df_audit_result_msg').html(data.html).removeClass('d-none');
            $('#df_audit_config_save').removeAttr('disabled');

            if (data.success) {
                // refresh page
                setTimeout(function () {
                    window.location.reload(true);
                }, 3000);
            }
        });
    });

})(jQuery);
