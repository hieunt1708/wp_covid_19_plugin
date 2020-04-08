( function( $ ){
    $( document ).ready( function () {
        $('#widgets-right .jms-covid-country').select2();
        $( document ).on( 'change', '#widgets-right select.jms-covid-style', function( e ) {
            var style = $(this).val(),
                parent = $(this).closest('.widget-content');

            if (style === '5' || style === '4') {
                parent.find('select.jms-covid-country').parent().hide();
            }else{
                parent.find('select.jms-covid-country').parent().show();
            }

            if (style === '2'){
                parent.find('select.jms-covid-country').select2("destroy");
                parent.find('select.jms-covid-country').attr('multiple',true).select2();
            }else{
                parent.find('select.jms-covid-country').select2("destroy");
                parent.find('select.jms-covid-country').attr('multiple',false).select2();
            }
        });
        $('select.jms-covid-style').trigger('change');
        $(document).on('widget-updated widget-added', function(){
            $('#widgets-right .jms-covid-country').select2();
            $('select.jms-covid-style').trigger('change');
        });
    });
})(jQuery);