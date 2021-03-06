(function ($) {
    'use strict';

    if ( typeof JMS_Covid === 'undefined' ) {
        return false;
    }
    $( function() {
        jms_covid19.ready();

        var eventsHandler;
        if(typeof Hammer !== 'undefined' ){
            eventsHandler = {
                haltEventListeners: ['touchstart', 'touchend', 'touchmove', 'touchleave', 'touchcancel']
                , init: function(options) {
                    var instance = options.instance
                        , initialScale = 1
                        , pannedX = 0
                        , pannedY = 0

                    // Init Hammer
                    // Listen only for pointer and touch events
                    this.hammer = Hammer(options.svgElement, {
                        inputClass: Hammer.SUPPORT_POINTER_EVENTS ? Hammer.PointerEventInput : Hammer.TouchInput
                    })

                    // Enable pinch
                    this.hammer.get('pinch').set({enable: true})

                    // Handle double tap
                    this.hammer.on('doubletap', function(ev){
                        instance.zoomIn()
                    })

                    // Handle pan
                    this.hammer.on('panstart panmove', function(ev){
                        // On pan start reset panned variables
                        if (ev.type === 'panstart') {
                            pannedX = 0
                            pannedY = 0
                        }

                        // Pan only the difference
                        instance.panBy({x: ev.deltaX - pannedX, y: ev.deltaY - pannedY})
                        pannedX = ev.deltaX
                        pannedY = ev.deltaY
                    })

                    // Handle pinch
                    this.hammer.on('pinchstart pinchmove', function(ev){
                        // On pinch start remember initial zoom
                        if (ev.type === 'pinchstart') {
                            initialScale = instance.getZoom()
                            instance.zoomAtPoint(initialScale * ev.scale, {x: ev.center.x, y: ev.center.y})
                        }

                        instance.zoomAtPoint(initialScale * ev.scale, {x: ev.center.x, y: ev.center.y})
                    })

                    // Prevent moving the page on some devices when panning over SVG
                    options.svgElement.addEventListener('touchmove', function(e){ e.preventDefault(); });
                }

                , destroy: function(){
                    this.hammer.destroy()
                }
            }
        }
        if(typeof svgPanZoom !== 'undefined' ){
            $('.jms-covid-svg-map').each(function(index) {
                var zoomMap = svgPanZoom(this, {
                    zoomEnabled: 1,
                    controlIconsEnabled: !1,
                    fit: 1,
                    center: 1,
                    customEventsHandler: eventsHandler
                });
                $(this).parent().find('.zoom-in').on('click',function (e) {  e.preventDefault(),zoomMap.zoomIn()})
                $(this).parent().find('.zoom-out').on('click',function (e) { e.preventDefault(),zoomMap.zoomOut()})
            });
        }

        $( 'body' ).on( 'init-tabs', '.cv-tab-wrapper', function() {
            var $tabs = $( this ).find( '.cv-nav-tabs' );
            $tabs.each(function() {
                $(this).find( '.cv-tab-panel' ).hide();
                $(this).find( 'li:first a' ).click();
            });
            $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
        })
        .on( 'click', '.cv-nav-tabs li a', function( e ) {
            e.preventDefault();
            var $tab          = $( this );
            var $tabs_wrapper = $tab.closest( '.cv-tab-wrapper' );
            var $tabs         = $tabs_wrapper.find( '.cv-nav-tabs' );

            $tabs.find( 'li' ).removeClass( 'active' );
            $tabs_wrapper.find( '.cv-tab-panel' ).hide();

            $tab.closest( 'li' ).addClass( 'active' );
            $tabs_wrapper.find( $tab.attr( 'href' ) ).show();

            $.fn.dataTable.tables( {visible: true, api: true} ).responsive.recalc().columns.adjust();
            setTimeout(function () {
                $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
            },100);
        } );

        $( '.cv-tab-wrapper' ).trigger( 'init-tabs' );
        if(typeof $.fn.dataTable !== 'undefined' ) {
            $('.table_countries_today').each(function () {
                var table_id = $(this).attr('id');
                if (table_id) {
                    $('#' + table_id).dataTable({
                        order: [
                            [1, 'desc']
                        ],
                        info: true,
                        paging: true,
                        lengthChange: true,
                        lengthMenu: [[15, 30, -1], [15, 30, "All"]],
                        responsive: true,
                    });
                }
            });

            $('.table_countries').each(function () {
                var table_id = $(this).attr('id');
                if (table_id) {
                    $('#' + table_id).dataTable({
                        scrollY: 230,
                        scrollCollapse: true,
                        order: [
                            [1, 'desc']
                        ],
                        info: false,
                        searching: false,
                        paging: false,
                        responsive: true,
                    });
                }
            });
        }
    });

    $(window).load(function() {
        jms_covid19.load()
    });

    var jms_covid19 = window.$jms_covid19 = {
        ready: function() {
            this.map()
        },
        load: function() {
            JMS_Covid.countries.sort(function(a, b) {
                return a.cases - b.cases;
            });
            for (var a = JMS_Covid.countries, t = 0; t < a.length; t++) {
                var e = $('.jms-covid-live-map [title="' + a[t].country + '"]'),
                    s = 0;
                if (e.length) {
                    var r = a[t].cases || 0,
                        n = a[t].deaths || 0,
                        o = a[t].recovered || 0;
                    console.log(a[t].country);
                    console.log(a[t].recovered);
                    if(JMS_Covid.country_comfirm_cl_type === '2'){
                        1 <= r && (s = 1);
                        for (var i = JMS_Covid.number_of_case, j = 0; j < i.length; j++) {
                            i[j] <= r && (s = j+1)
                        }
                        e.attr("data-level", s);
                    } else if ( JMS_Covid.country_comfirm_cl_type === '1') {
                        s = ( 1 / a.length ) * ( t + 1 );
                        e.css({
                            fill: JMS_Covid.country_confirm_cl,
                            fillOpacity: s,
                        })
                    }
                    e.attr("data-cases", r.format()), e.attr("data-deaths", n.format()), e.attr("data-recovered", o.format());
                }
            }
        },
        map: function() {
            $('.jms-covid-live-map').each(function(index) {
                var self = $(this),
                    d = self.find(".tooltip_nCoV"),
                    p = self.find("path"),
                    svg = self.find("svg"),
                    c = d.attr("data-confirmed"),
                    i = d.attr("data-deaths"),
                    l = d.attr("data-recovered");
                p.mouseenter(function() {
                    $(this).css({
                        stroke: JMS_Covid.country_cl_border_hover,
                    });
                    var a = $(this).attr("data-cases") ? $(this).attr("data-cases") : 0,
                        t = $(this).attr("data-deaths") ? $(this).attr("data-deaths") : 0,
                        e = $(this).attr("data-recovered") ? $(this).attr("data-recovered") : 0;
                    d.addClass("active"), d.html('<span class="country-name">' + $(this).attr("title") + '</span><br/><span class="confirmed-case">' + c + ": " + a + '</span><br/><span class="deaths-case">' + i + ": " + t + '</span><br/><span class="recovered-case">' + l + ": " + e + "</span>")
                }).mouseleave(function() {
                    $(this).css( 'stroke','');
                    d.removeClass("active")
                }), svg.on("mousemove", function(a) {
                    d.css({
                        left: a.offsetX + 40,
                        top: a.offsetY + 30
                    })
                })
            });
        }
    }

    Number.prototype.format = function(n, x) {
        var re = '(\\d)(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
        return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$1,');
    };
})(jQuery);
