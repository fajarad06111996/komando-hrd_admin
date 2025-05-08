/**
 * AdminLTE Demo Menu
 * ------------------
 * You should not use this file in production.
 * This file is for demo purposes only.
 */
(function ($) {
    'use strict'

    var nonavborderH, nonavborder, bdsmalltextH, bdsmalltext, navsmalltextH, navsmalltext, sidnavsmalltextH, sidnavsmalltext, footsmalltextH, footsmalltext, snavflatsH, snavflats, snavlegacysH, snavlegacys, snavcomH, snavcom, snavchindentH, snavchindent, autoexpandH, autoexpand, bsmalltextH, bsmalltext, navariant;
    var $sidebar   = $('.control-sidebar')
    var $container = $('<div />', {
        class: 'p-3 control-sidebar-content'
    })
    var $user_idx = $('input[name=user_idx]').val();
    var $base_url = $('input[name=base_url]').val();
    // console.log($user_idx);
    var $globalLink = $base_url+'home/cekRightbar';

    $sidebar.append($container)

    var navbar_dark_skins = [
        'navbar-primary',
        'navbar-secondary',
        'navbar-info',
        'navbar-success',
        'navbar-danger',
        'navbar-indigo',
        'navbar-purple',
        'navbar-pink',
        'navbar-navy',
        'navbar-lightblue',
        'navbar-teal',
        'navbar-cyan',
        'navbar-dark',
        'navbar-gray-dark',
        'navbar-gray',
    ]

    var navbar_light_skins = [
        'navbar-light',
        'navbar-warning',
        'navbar-white',
        'navbar-orange',
    ]

    $container.append(
        '<h5>Customize Style</h5><hr class="mb-2"/>'
    )

    var $no_border_checkbox = $('<input />', {
        type   : 'checkbox',
        value  : 1,
        checked: $('.main-header').hasClass('border-bottom-0'),
        'class': 'mr-1'
    })
    nonavborderH = $.ajax({
        type: 'ajax',
        method: 'get',
        async: true,
        url: $globalLink+'/'+$user_idx,
        dataType: 'json',
        success: function(response) {
            // console.log(response.nonavborder);
            if(response.nonavborder == 0){
                $no_border_checkbox.prop('checked', false);
                $('.main-header').removeClass('border-bottom-0')
            } else {
                $no_border_checkbox.prop('checked', true);
                $('.main-header').addClass('border-bottom-0')
            }
        },
        error: function(error) {
            console.log(error);
        }
    })
    $no_border_checkbox.on('click', function () {
        if ($(this).is(':checked')) {
            $('.main-header').addClass('border-bottom-0')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 1,
                    name: 'nonavborder'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        } else {
            $('.main-header').removeClass('border-bottom-0')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 0,
                    name: 'nonavborder'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    })
    var $no_border_container = $('<div />', {'class': 'mb-1'}).append($no_border_checkbox).append('<span>No Navbar border</span>')
    $container.append($no_border_container)

    var $text_sm_body_checkbox = $('<input />', {
        type   : 'checkbox',
        value  : 1,
        checked: $('body').hasClass('text-sm'),
        'class': 'mr-1'
    })
    bdsmalltextH = $.ajax({
        type: 'ajax',
        method: 'get',
        async: true,
        url: $globalLink+'/'+$user_idx,
        dataType: 'json',
        success: function(response) {
            // console.log(response.bdsmalltext);
            if(response.bdsmalltext == 0){
                $text_sm_body_checkbox.prop('checked', false);
                $('body').removeClass('text-sm')
                $('.searchFormOrder').removeClass('form-control-sm')
            } else {
                $text_sm_body_checkbox.prop('checked', true);
                $('body').addClass('text-sm')
                $('.searchFormOrder').addClass('form-control-sm')
            }
        },
        error: function(error) {
            console.log(error);
        }
    })
    $text_sm_body_checkbox.on('click', function () {
        if ($(this).is(':checked')) {
            $('body').addClass('text-sm')
            $('.searchFormOrder').addClass('form-control-sm')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 1,
                    name: 'bdsmalltext'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        } else {
            $('body').removeClass('text-sm')
            $('.searchFormOrder').removeClass('form-control-sm')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 0,
                    name: 'bdsmalltext'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        }
    })
    var $text_sm_body_container = $('<div />', {'class': 'mb-1'}).append($text_sm_body_checkbox).append('<span>Body small text</span>')
    $container.append($text_sm_body_container)

    var $text_sm_header_checkbox = $('<input />', {
        type   : 'checkbox',
        value  : 1,
        checked: $('.main-header').hasClass('text-sm'),
        'class': 'mr-1'
    })
    navsmalltextH = $.ajax({
        type: 'ajax',
        method: 'get',
        async: true,
        url: $globalLink+'/'+$user_idx,
        dataType: 'json',
        success: function(response) {
            // console.log(response.navsmalltext);
            if(response.navsmalltext == 0){
                $text_sm_header_checkbox.prop('checked', false);
                $('.main-header').removeClass('text-sm')
            } else {
                $text_sm_header_checkbox.prop('checked', true);
                $('.main-header').addClass('text-sm')
            }
        },
        error: function(error) {
            console.log(error);
        }
    })
    $text_sm_header_checkbox.on('click', function () {
        if ($(this).is(':checked')) {
            $('.main-header').addClass('text-sm')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 1,
                    name: 'navsmalltext'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        } else {
            $('.main-header').removeClass('text-sm')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 0,
                    name: 'navsmalltext'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        }
    })
    var $text_sm_header_container = $('<div />', {'class': 'mb-1'}).append($text_sm_header_checkbox).append('<span>Navbar small text</span>')
    $container.append($text_sm_header_container)

    var $text_sm_sidebar_checkbox = $('<input />', {
        type   : 'checkbox',
        value  : 1,
        checked: $('.nav-sidebar').hasClass('text-sm'),
        'class': 'mr-1'
    })
    sidnavsmalltextH = $.ajax({
        type: 'ajax',
        method: 'get',
        async: true,
        url: $globalLink+'/'+$user_idx,
        dataType: 'json',
        success: function(response) {
            // console.log(response.sidnavsmalltext);
            if(response.sidnavsmalltext == 0){
                $text_sm_sidebar_checkbox.prop('checked', false);
                $('.nav-sidebar').removeClass('text-sm')
            } else {
                $text_sm_sidebar_checkbox.prop('checked', true);
                $('.nav-sidebar').addClass('text-sm')
            }
        },
        error: function(error) {
            console.log(error);
        }
    })
    $text_sm_sidebar_checkbox.on('click', function () {
        if ($(this).is(':checked')) {
            $('.nav-sidebar').addClass('text-sm')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 1,
                    name: 'sidnavsmalltext'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        } else {
            $('.nav-sidebar').removeClass('text-sm')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 0,
                    name: 'sidnavsmalltext'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        }
    })
    var $text_sm_sidebar_container = $('<div />', {'class': 'mb-1'}).append($text_sm_sidebar_checkbox).append('<span>Sidebar nav small text</span>')
    $container.append($text_sm_sidebar_container)

    var $text_sm_footer_checkbox = $('<input />', {
        type   : 'checkbox',
        value  : 1,
        checked: $('.main-footer').hasClass('text-sm'),
        'class': 'mr-1'
    })
    footsmalltextH = $.ajax({
        type: 'ajax',
        method: 'get',
        async: true,
        url: $globalLink+'/'+$user_idx,
        dataType: 'json',
        success: function(response) {
            // console.log(response.footsmalltext);
            if(response.footsmalltext == 0){
                $text_sm_footer_checkbox.prop('checked', false);
                $('.main-footer').removeClass('text-sm')
            } else {
                $text_sm_footer_checkbox.prop('checked', true);
                $('.main-footer').addClass('text-sm')
            }
        },
        error: function(error) {
            console.log(error);
        }
    })
    $text_sm_footer_checkbox.on('click', function () {
        if ($(this).is(':checked')) {
            $('.main-footer').addClass('text-sm')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 1,
                    name: 'footsmalltext'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        } else {
            $('.main-footer').removeClass('text-sm')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 0,
                    name: 'footsmalltext'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        }
    })
    var $text_sm_footer_container = $('<div />', {'class': 'mb-1'}).append($text_sm_footer_checkbox).append('<span>Footer small text</span>')
    $container.append($text_sm_footer_container)

    var $flat_sidebar_checkbox = $('<input />', {
        type   : 'checkbox',
        value  : 1,
        checked: $('.nav-sidebar').hasClass('nav-flat'),
        'class': 'mr-1'
    })
    snavflatsH = $.ajax({
        type: 'ajax',
        method: 'get',
        async: true,
        url: $globalLink+'/'+$user_idx,
        dataType: 'json',
        success: function(response) {
            // console.log(response.snavflats);
            if(response.snavflats == 0){
                $flat_sidebar_checkbox.prop('checked', false);
                $('.nav-sidebar').removeClass('nav-flat')
            } else {
                $flat_sidebar_checkbox.prop('checked', true);
                $('.nav-sidebar').addClass('nav-flat')
            }
        },
        error: function(error) {
            console.log(error);
        }
    })
    $flat_sidebar_checkbox.on('click', function () {
        if ($(this).is(':checked')) {
            $('.nav-sidebar').addClass('nav-flat')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 1,
                    name: 'snavflats'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        } else {
            $('.nav-sidebar').removeClass('nav-flat')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 0,
                    name: 'snavflats'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        }
    })
    var $flat_sidebar_container = $('<div />', {'class': 'mb-1'}).append($flat_sidebar_checkbox).append('<span>Sidebar nav flat style</span>')
    $container.append($flat_sidebar_container)

    var $legacy_sidebar_checkbox = $('<input />', {
        type   : 'checkbox',
        value  : 1,
        checked: $('.nav-sidebar').hasClass('nav-legacy'),
        'class': 'mr-1'
    })
    snavlegacysH = $.ajax({
        type: 'ajax',
        method: 'get',
        async: true,
        url: $globalLink+'/'+$user_idx,
        dataType: 'json',
        success: function(response) {
            // console.log(response.snavlegacys);
            if(response.snavlegacys == 0){
                $legacy_sidebar_checkbox.prop('checked', false);
                $('.nav-sidebar').removeClass('nav-legacy')
            } else {
                $legacy_sidebar_checkbox.prop('checked', true);
                $('.nav-sidebar').addClass('nav-legacy')
            }
        },
        error: function(error) {
            console.log(error);
        }
    })
    $legacy_sidebar_checkbox.on('click', function () {
        if ($(this).is(':checked')) {
            $('.nav-sidebar').addClass('nav-legacy')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 1,
                    name: 'snavlegacys'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        } else {
            $('.nav-sidebar').removeClass('nav-legacy')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 0,
                    name: 'snavlegacys'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        }
    })
    var $legacy_sidebar_container = $('<div />', {'class': 'mb-1'}).append($legacy_sidebar_checkbox).append('<span>Sidebar nav legacy style</span>')
    $container.append($legacy_sidebar_container)

    var $compact_sidebar_checkbox = $('<input />', {
        type   : 'checkbox',
        value  : 1,
        checked: $('.nav-sidebar').hasClass('nav-compact'),
        'class': 'mr-1'
    })
    snavcomH = $.ajax({
        type: 'ajax',
        method: 'get',
        async: true,
        url: $globalLink+'/'+$user_idx,
        dataType: 'json',
        success: function(response) {
            // console.log(response.snavcom);
            if(response.snavcom == 0){
                $compact_sidebar_checkbox.prop('checked', false);
                $('.nav-sidebar').removeClass('nav-compact')
            } else {
                $compact_sidebar_checkbox.prop('checked', true);
                $('.nav-sidebar').addClass('nav-compact')
            }
        },
        error: function(error) {
            console.log(error);
        }
    })
    $compact_sidebar_checkbox.on('click', function () {
        if ($(this).is(':checked')) {
            $('.nav-sidebar').addClass('nav-compact')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 1,
                    name: 'snavcom'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        } else {
            $('.nav-sidebar').removeClass('nav-compact')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 0,
                    name: 'snavcom'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        }
    })
    var $compact_sidebar_container = $('<div />', {'class': 'mb-1'}).append($compact_sidebar_checkbox).append('<span>Sidebar nav compact</span>')
    $container.append($compact_sidebar_container)

    var $child_indent_sidebar_checkbox = $('<input />', {
        type   : 'checkbox',
        value  : 1,
        checked: $('.nav-sidebar').hasClass('nav-child-indent'),
        'class': 'mr-1'
    })
    snavchindentH = $.ajax({
        type: 'ajax',
        method: 'get',
        async: true,
        url: $globalLink+'/'+$user_idx,
        dataType: 'json',
        success: function(response) {
            // console.log(response.snavchindent);
            if(response.snavchindent == 0){
                $child_indent_sidebar_checkbox.prop('checked', false);
                $('.nav-sidebar').removeClass('nav-child-indent')
            } else {
                $child_indent_sidebar_checkbox.prop('checked', true);
                $('.nav-sidebar').addClass('nav-child-indent')
            }
        },
        error: function(error) {
            console.log(error);
        }
    })
    $child_indent_sidebar_checkbox.on('click', function () {
        if ($(this).is(':checked')) {
            $('.nav-sidebar').addClass('nav-child-indent')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 1,
                    name: 'snavchindent'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        } else {
            $('.nav-sidebar').removeClass('nav-child-indent')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 0,
                    name: 'snavchindent'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        }
    })
    var $child_indent_sidebar_container = $('<div />', {'class': 'mb-1'}).append($child_indent_sidebar_checkbox).append('<span>Sidebar nav child indent</span>')
    $container.append($child_indent_sidebar_container)

    var $no_expand_sidebar_checkbox = $('<input />', {
        type   : 'checkbox',
        value  : 1,
        checked: $('.main-sidebar').hasClass('sidebar-no-expand'),
        'class': 'mr-1'
    })
    autoexpandH = $.ajax({
        type: 'ajax',
        method: 'get',
        async: true,
        url: $globalLink+'/'+$user_idx,
        dataType: 'json',
        success: function(response) {
            // console.log(response.autoexpand);
            if(response.autoexpand == 0){
                $no_expand_sidebar_checkbox.prop('checked', false);
                $('.main-sidebar').removeClass('sidebar-no-expand')
            } else {
                $no_expand_sidebar_checkbox.prop('checked', true);
                $('.main-sidebar').addClass('sidebar-no-expand')
            }
        },
        error: function(error) {
            console.log(error);
        }
    })
    $no_expand_sidebar_checkbox.on('click', function () {
        if ($(this).is(':checked')) {
            $('.main-sidebar').addClass('sidebar-no-expand')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 1,
                    name: 'autoexpand'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        } else {
            $('.main-sidebar').removeClass('sidebar-no-expand')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 0,
                    name: 'autoexpand'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        }
    })
    var $no_expand_sidebar_container = $('<div />', {'class': 'mb-1'}).append($no_expand_sidebar_checkbox).append('<span>Main Sidebar disable hover/focus auto expand</span>')
    $container.append($no_expand_sidebar_container)

    var $text_sm_brand_checkbox = $('<input />', {
        type   : 'checkbox',
        value  : 1,
        checked: $('.brand-link').hasClass('text-sm'),
        'class': 'mr-1'
    })
    bsmalltextH = $.ajax({
        type: 'ajax',
        method: 'get',
        async: true,
        url: $globalLink+'/'+$user_idx,
        dataType: 'json',
        success: function(response) {
            // console.log(response.bsmalltext);
            if(response.bsmalltext == 0){
                $text_sm_brand_checkbox.prop('checked', false);
                $('.brand-link').removeClass('text-sm')
            } else {
                $text_sm_brand_checkbox.prop('checked', true);
                $('.brand-link').addClass('text-sm')
            }
        },
        error: function(error) {
            console.log(error);
        }
    })
    $text_sm_brand_checkbox.on('click', function () {
        if ($(this).is(':checked')) {
            $('.brand-link').addClass('text-sm')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 1,
                    name: 'bsmalltext'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        } else {
            $('.brand-link').removeClass('text-sm')
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 0,
                    name: 'bsmalltext'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        }
    })
    var $text_sm_brand_container = $('<div />', {'class': 'mb-4'}).append($text_sm_brand_checkbox).append('<span>Brand small text</span>')
    $container.append($text_sm_brand_container)

    $container.append('<h6>Navbar Variants</h6>')

    var $navbar_variants        = $('<div />', {
        'class': 'd-flex'
    })
    var navbar_all_colors       = navbar_dark_skins.concat(navbar_light_skins)
    var $navbar_variants_colors = createSkinBlock(navbar_all_colors, function (e) {
        var color           = $(this).data('color')
        var $main_header    = $('.main-header')
        $main_header.removeClass('navbar-dark').removeClass('navbar-light')
        navbar_all_colors.map(function (color) {
            $main_header.removeClass(color)
        })

        if (navbar_dark_skins.indexOf(color) > -1) {
            $main_header.addClass('navbar-dark')
            navariant = $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 'navbar-dark '+color,
                    name: 'navariant'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        } else {
            $main_header.addClass('navbar-light')
            navariant = $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: 'navbar-light '+color,
                    name: 'navariant'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        }

        $main_header.addClass(color)
    })
    $navbar_variants.append($navbar_variants_colors)
    // console.log($navbar_variants_colors);
    $container.append($navbar_variants)

    var sidebar_colors = [
        'bg-primary',
        'bg-warning',
        'bg-info',
        'bg-danger',
        'bg-success',
        'bg-indigo',
        'bg-lightblue',
        'bg-navy',
        'bg-purple',
        'bg-fuchsia',
        'bg-pink',
        'bg-maroon',
        'bg-orange',
        'bg-lime',
        'bg-teal',
        'bg-olive'
    ]

    var accent_colors = [
        'accent-primary',
        'accent-warning',
        'accent-info',
        'accent-danger',
        'accent-success',
        'accent-indigo',
        'accent-lightblue',
        'accent-navy',
        'accent-purple',
        'accent-fuchsia',
        'accent-pink',
        'accent-maroon',
        'accent-orange',
        'accent-lime',
        'accent-teal',
        'accent-olive'
    ]

    var sidebar_skins = [
        'sidebar-dark-primary',
        'sidebar-dark-warning',
        'sidebar-dark-info',
        'sidebar-dark-danger',
        'sidebar-dark-success',
        'sidebar-dark-indigo',
        'sidebar-dark-lightblue',
        'sidebar-dark-navy',
        'sidebar-dark-purple',
        'sidebar-dark-fuchsia',
        'sidebar-dark-pink',
        'sidebar-dark-maroon',
        'sidebar-dark-orange',
        'sidebar-dark-lime',
        'sidebar-dark-teal',
        'sidebar-dark-olive',
        'sidebar-light-primary',
        'sidebar-light-warning',
        'sidebar-light-info',
        'sidebar-light-danger',
        'sidebar-light-success',
        'sidebar-light-indigo',
        'sidebar-light-lightblue',
        'sidebar-light-navy',
        'sidebar-light-purple',
        'sidebar-light-fuchsia',
        'sidebar-light-pink',
        'sidebar-light-maroon',
        'sidebar-light-orange',
        'sidebar-light-lime',
        'sidebar-light-teal',
        'sidebar-light-olive'
    ]

    // $container.append('<h6>Accent Color Variants</h6>')
    // var $accent_variants = $('<div />', {
    //     'class': 'd-flex'
    // })
    // $container.append($accent_variants)
    // $container.append(createSkinBlock(accent_colors, function () {
    //     var color         = $(this).data('color')
    //     var accent_class = color
    //     var $body      = $('body')
    //     accent_colors.map(function (skin) {
    //         $body.removeClass(skin)
    //     })

    //     $body.addClass(accent_class)
    // }))

    $container.append('<h6>Dark Sidebar Variants</h6>')
    var $sidebar_variants_dark = $('<div />', {
        'class': 'd-flex'
    })
    $container.append($sidebar_variants_dark)
    $container.append(createSkinBlock(sidebar_colors, function () {
        var color         = $(this).data('color')
        var sidebar_class = 'sidebar-dark-' + color.replace('bg-', '')
        var $sidebar      = $('.main-sidebar')
        sidebar_skins.map(function (skin) {
            $sidebar.removeClass(skin)
        })
        console.log(color)
        $sidebar.addClass(sidebar_class)
        $.ajax({
            type: 'ajax',
            method: 'post',
            async: true,
            url: $globalLink+'/'+$user_idx,
            data: {
                value: sidebar_class,
                name: 'sidevariant'
            },
            dataType: 'json',
            success: function(response) {
                // console.log(response.nonavborder);
            },
            error: function(error) {
                console.log(error)
            }
        })
    }))

    $container.append('<h6>Light Sidebar Variants</h6>')
    var $sidebar_variants_light = $('<div />', {
        'class': 'd-flex'
    })
    $container.append($sidebar_variants_light)
    $container.append(createSkinBlock(sidebar_colors, function () {
        var color         = $(this).data('color')
        var sidebar_class = 'sidebar-light-' + color.replace('bg-', '')
        var $sidebar      = $('.main-sidebar')
        sidebar_skins.map(function (skin) {
            $sidebar.removeClass(skin)
        })

        $sidebar.addClass(sidebar_class)
        $.ajax({
            type: 'ajax',
            method: 'post',
            async: true,
            url: $globalLink+'/'+$user_idx,
            data: {
                value: sidebar_class,
                name: 'sidevariant'
            },
            dataType: 'json',
            success: function(response) {
                // console.log(response.nonavborder);
            },
            error: function(error) {
                console.log(error)
            }
        })
    }))

    var logo_skins = navbar_all_colors
    $container.append('<h6>Brand Logo Variants</h6>')
    var $logo_variants = $('<div />', {
        'class': 'd-flex'
    })
    $container.append($logo_variants)
    var $clear_btn = $('<a />', {
        href: 'javascript:void(0)'
    }).text('clear').on('click', function () {
        var $logo = $('.brand-link')
        logo_skins.map(function (skin) {
            $logo.removeClass(skin)
            $.ajax({
                type: 'ajax',
                method: 'post',
                async: true,
                url: $globalLink+'/'+$user_idx,
                data: {
                    value: '',
                    name: 'blogovariant'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.nonavborder);
                },
                error: function(error) {
                    console.log(error)
                }
            })
        })
    })
    $container.append(createSkinBlock(logo_skins, function () {
        var color = $(this).data('color')
        var $logo = $('.brand-link')
        logo_skins.map(function (skin) {
            $logo.removeClass(skin)
        })
        $logo.addClass(color)
        $.ajax({
            type: 'ajax',
            method: 'post',
            async: true,
            url: $globalLink+'/'+$user_idx,
            data: {
                value: color,
                name: 'blogovariant'
            },
            dataType: 'json',
            success: function(response) {
                // console.log(response.nonavborder);
            },
            error: function(error) {
                console.log(error)
            }
        })
    }).append($clear_btn))

    function createSkinBlock(colors, callback) {
        var $block = $('<div />', {
            'class': 'd-flex flex-wrap mb-3'
        })

        colors.map(function (color) {
            var $color = $('<div />', {
                'class': (typeof color === 'object' ? color.join(' ') : color).replace('navbar-', 'bg-').replace('accent-', 'bg-') + ' elevation-2'
            })

            $block.append($color)

            $color.data('color', color)

            $color.css({
                width       : '40px',
                height      : '20px',
                borderRadius: '25px',
                marginRight : 10,
                marginBottom: 10,
                opacity     : 0.8,
                cursor      : 'pointer'
            })

            $color.hover(function () {
                $(this).css({ opacity: 1 }).removeClass('elevation-2').addClass('elevation-4')
            }, function () {
                $(this).css({ opacity: 0.8 }).removeClass('elevation-4').addClass('elevation-2')
            })

            if (callback) {
                $color.on('click', callback)
            }
        })

        return $block
    }
    window.addEventListener("beforeunload", function() {
        nonavborderH.abort();
        bdsmalltextH.abort();
        navsmalltextH.abort();
        sidnavsmalltextH.abort();
        footsmalltextH.abort();
        snavflatsH.abort();
        snavlegacysH.abort();
        snavcomH.abort();
        snavchindentH.abort();
        autoexpandH.abort();
        bsmalltextH.abort();
        navariant.abort();
    });
})(jQuery)

