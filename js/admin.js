(function ($) {
    'use strict';
    $(function () {
        jQuery('.ui.dropdown.multi').each(function () {
            var values = jQuery(this).parent().find('.selected_vals').eq(0).val();
            if (values)
                jQuery(this).dropdown("set selected", values.split(','));
            else
                jQuery(this).dropdown();
        })
        jQuery('.color-field').wpColorPicker();

        jQuery('input[name="jms_covid_params[country_comfirm_color_type]"]').on('change', function () {
            if (this.value == '1') {
                jQuery('.form_color_opacity').show();
                jQuery('.form_color_nnumber_case').hide();
            } else if (this.value == '2') {
                jQuery('.form_color_nnumber_case').show();
                jQuery('.form_color_opacity').hide();
            }
        });

        function assign_page_oncheck() {
            if (jQuery("input[name='jms_covid_params[country_comfirm_color_type]']:checked").val()) {
                jQuery("input[name='jms_covid_params[country_comfirm_color_type]']:checked").trigger("change");
            }else{
                jQuery('input[name="jms_covid_params[country_comfirm_color_type]"]:first').prop("checked", true).trigger("change");
            }
        }

        assign_page_oncheck();
    });
})(jQuery);