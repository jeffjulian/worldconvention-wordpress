jQuery(document).ready(function($) {
    'use strict';

    $('body').on('click', '.etn_event_upload_image_button', function(e) {

        e.preventDefault();
        var multiple = false;

        if ($(this).data('multiple')) {
            multiple = Boolean($(this).data('multiple'));
        }

        var button = $(this),
            custom_uploader = wp.media({
                title: 'Insert image',
                library: {

                    type: 'image'
                },
                button: {
                    text: 'Use this image' // button label text
                },
                multiple: multiple
            }).on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();

                $(button).removeClass('button').html('<img class="true_pre_image" src="' + attachment.url + '" style="max-width:95%;display:block;" />').next().val(attachment.id).next().show();


            })
            .open();
    });

    /*
     * Remove image event
     */
    $('body').on('click', '.essential_event_remove_image_button', function() {
        $(this).hide().prev().val('').prev().addClass('button').html('Upload image');
        return false;
    });

    // select2 for meta box
    $('.etn_es_event_select2').select2();




    // social icon
    var etn_selected_social_event_icon = null;
    $(' .social-repeater').on('click', '.etn-social-icon', function() {

        etn_selected_social_event_icon = $(this);

    });

    $('.etn-social-icon-list i').on("click", function() {
        var icon_class_selected = $(this).data('class');
        etn_selected_social_event_icon.val(icon_class_selected);
        $('.etn-search-event-mng-social').val(icon_class_selected);
        etn_selected_social_event_icon.siblings('i').removeClass().addClass(icon_class_selected);
    });


    $('.etn-search-event-mng-social').on('input', function() {
        var search_value = $(this).val().toUpperCase();

        let all_social_list = $(".etn-social-icon-list i");

        $.each(all_social_list, function(key, item) {

            var icon_label = $(item).data('value');

            if (icon_label.toUpperCase().indexOf(search_value) > -1) {
                $(item).show();
            } else {
                $(item).hide();
            }

        });
    });

    var etn_social_rep = $('.social-repeater').length;

    if (etn_social_rep) {
        $('.social-repeater').repeater({

            show: function() {
                $(this).slideDown();
            },

            hide: function(deleteElement) {

                $(this).slideUp(deleteElement);

            },

        });
    }

    // works only this page post_type=etn-schedule
    $('.etn_es_event_repeater_select2').select2();
    // event manager repeater
    var etn_event_schedule_repeater = $('.etn-event-manager-repeater-fld').length;
    if (etn_event_schedule_repeater) {
        $('.etn-event-manager-repeater-fld').repeater({
            show: function() {
                $(this).slideDown();
                $(this).find('.event-title').html("Schedule List " + $(this).parent().find('.etn-repeater-item').length);
                $(this).find('.select2').remove();
                $(this).find('.etn_es_event_repeater_select2').select2()
            },

            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            },
        });
    }

    // slide repeater
    $(document).on('click', '.etn-event-shedule-collapsible', function() {
        $(this).next('.etn-event-repeater-collapsible-content').slideToggle()
            .parents('.etn-repeater-item').siblings().find('.etn-event-repeater-collapsible-content').slideUp();

    });
    $('.etn-event-shedule-collapsible').first().trigger('click');
    // ./End slide repeater
    // ./end works only this page post_type=etn-schedule

    if ($("#etn_start_date").length) {

        $('#etn_start_date').datepicker({
            dateFormat: "yy-mm-dd",
            onSelect: function() {
                $(this).val();
            }
        });
    }

  

    if ($("#etn_end_date").length) {
        $('#etn_end_date').datepicker({
            dateFormat: "yy-mm-dd",
            onSelect: function() {
                $(this).val();
            }
        });
    }
    if ($("#etn_registration_deadline").length) {

        $('#etn_registration_deadline').datepicker({
            dateFormat: "yy-mm-dd",
            onSelect: function() {
                $(this).val();
            }
        });
    }

    // event schedule date repeater 

    $(document).on('focus', ".etn_schedule_event_date", function() {
        $(this).datepicker({
            dateFormat: "yy-mm-dd",
            onSelect: function() {
                $(this).val();
            }
        });
    });


    $('.etn-settings-tab .nav-tab').on('click', function(e) {
        e.preventDefault();
        var targetID = $(this).attr('href');
        window.location.hash = targetID;
        $(this).addClass('nav-tab-active').siblings().removeClass('nav-tab-active');
        $(targetID).addClass('active').siblings().removeClass('active');
    });

    var eventMnger = '#etn-general_options';
    if (window.location.hash) {
        eventMnger = window.location.hash;
    }

    $('.etn-settings-tab .nav-tab[href="' + eventMnger + '"]').trigger('click');

    // schedule tab

    $('.postbox .hndle').css('cursor', 'pointer');

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

   

    // dashboard menu active class pass
    var pgurl = window.location.href.substr(window.location.href.lastIndexOf("/")+1);
        $("#toplevel_page_etn-events-manager .wp-submenu-wrap li a").each(function(){
        if($(this).attr("href") == pgurl || $(this).attr("href") == '' )
        $(this).parent().addClass("current");
      })


});