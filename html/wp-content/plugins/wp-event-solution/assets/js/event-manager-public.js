jQuery(document).ready(function($) {
    'use strict';

    // countdown
    if ($('.etn-event-count-down').length) {

        let etn_event_start_date = '';

        etn_event_start_date = $('.etn-event-count-down').data('start-date');
        var countDownDate = new Date(etn_event_start_date).getTime();

        let etn_timer_x = setInterval(function() {

            var now = new Date().getTime();

            var distance = countDownDate - now;

            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            $('.etn-event-count-down .etn-days .text').html(days);
            $('.etn-event-count-down .etn-hours .text').html(hours);
            $('.etn-event-count-down .etn-minutes .text').html(minutes);
            $('.etn-event-count-down .etn-seconds .text').html(seconds);
            if (distance < 0) {
                clearInterval(etn_timer_x);
                $('.etn-event-count-down').html('EXPIRED');

            }
        }, 1000);
    }


    //cart attendee 

    $(".etn-extra-attendee-form").on('blur change click', function() {
        $('.wc-proceed-to-checkout').css({ 'cursor': "default", 'pointer-events': 'none' });
        $.ajax({
            url: etn_localize_event.rest_root + 'etn-events/v1/cart/attendee',
            type: 'GET',
            data: $('.woocommerce-cart-form').serialize(),
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-WP-Nonce', etn_localize_event.nonce);
            },
            success: function(data) {
                console.log(data);
                $('.wc-proceed-to-checkout').css({ 'cursor': "default", 'pointer-events': 'auto' });
            },

        });
    });

    // another
    $('#etn_product_qty').on('change', function() {
        var left_qty = parseInt($('#etn_product_qty').attr("data-left_ticket"));
        var qty = parseInt($('#etn_product_qty').val());
        var price = $('#etn_product_price').val();
        if (qty <= left_qty) {
            var total_price = qty * price;
            $('#etn_form_price').html(total_price);
        }
    });

    $('.schedule-tab').on('click', openScheduleTab);

    function openScheduleTab() {
        var title = $(this).data('title');
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(title).style.display = "block";
    }

    $('.schedule-tab-shortcode').on('click', openScheduleTabShortCode);

    function openScheduleTabShortCode() {
        var title = $(this).data('title');
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent-shortcode");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks-shortcode");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        let single_title = "shortcode_" + title;
        document.getElementById(single_title).style.display = "block";
    }

    $('.attr-nav-pills>li>a').first().trigger('click');

   
    //   custom tabs
    $(document).on('click', '.etn-tab-a', function (event) {
        event.preventDefault();
        $(this).parent().parent().parent().find(".etn-tab").removeClass('tab-active');
        // $(this).parents(".etn-food-block-tab-item").find(".etn-tab").removeClass('tab-active');
        $(".etn-tab[data-id='" + $(this).attr('data-id') + "']").addClass("tab-active");
        $(".etn-tab-a").removeClass('etn-active');
        $(this).parent().find(".etn-tab-a").addClass('etn-active');
    });

});


