/*---------------------------------------------
Template name :  Wcone
Version       :  1.0.0
Author        :  ThemeLooks
Author url    :  http://themelooks.com

NOTE:
------
Please DO NOT EDIT THIS JS, you may need to use "custom.js" file for writing your custom js.
We may release future updates so it will overwrite this file. it's better and safer to use "custom.js".

[Table of Content]
    01: SVG Image
    02: Top 50
----------------------------------------------*/

(function ($) {
    "use strict";

    let i, m, a, cartModal, admin,LocationFinder = {};
    
    i = {

        catSlug: "",
        taxonomy: "",
        options: {
            is_poup: false
        },
        currentPage: "",
        init: function () {

            let $this = this;

            a.sendMail();
            $this.conditionalTimeSelectBox();
            $this.conditionalTimeSelectBoxOnDeliveryDate();
            $this.holyDayCheck();

        },
         
        conditionalTimeSelectBox: function() {

            let that = this,
                getDate = '';

            // get delivery time Slot list on window load
            $.ajax({

                type: 'POST',
                url: wconeobj.ajaxurl,
                data: {
                    action: 'order_time_lists_action',
                    date: getDate
                },
                success: function (res) {
                    $('#rb_delivery_time').html(res);
                    that.holyDayCheck( getDate );
                }

            })
        },
        conditionalTimeSelectBoxOnDeliveryDate: function() {
            let that = this;
            // get delivery time Slot list On click delivery date 
            $( '#rb_delivery_date' ).on( 'change', function(e) {
                
                e.preventDefault();

                let getDate = $(this).val();

                if( getDate != '' && $('[name="rb_delivery_schedule_options"]').is(':checked') ) {
                    $('.fb-delivery-time-wrapper').fadeIn();
                }              

                // Ajax Call
                $.ajax({

                    type: 'POST',
                    url: wconeobj.ajaxurl,
                    data: {
                        action: 'order_time_lists_action',
                        date: getDate
                    },
                    success: function (res) {
                       
                        $('#rb_delivery_time').html(res);
                        that.holyDayCheck( getDate );
                    }

                })

            } )
        },
        checkoutPageTimeSlotTimeCheck: function( holyDay ) {

            if( wconeobj.is_checkout_delivery_option != 'yes' || wconeobj.is_checkout_delivery_time_switch != 'yes' ) {
                return;
            }

            let timeOption     = $('[name="rb_delivery_time"] option'),
                $checkModal    = $(document).find('.fb-show-availability-check-modal'),
                $availabilityStatus    = $(document).find('.d_availability_status'),
                $AbilityStatus = localStorage.getItem('fbAbilityStatus'),
                $infoMagAppend = $('.fb-delivery-time-wrapper'),
                $infoMag       = $('.fb-info-msg');

            // Time length check
            if(  timeOption.length > 0 && !holyDay ) {

                $checkModal.fadeIn('slow');
                $availabilityStatus.fadeIn('slow');
                $infoMag.remove();

            } else {

                let $msg;
                $infoMag.remove();
                $checkModal.fadeOut('slow');
                $availabilityStatus.fadeOut('slow');
                if( $('#rb_pickup_branch').val() != '' ) {
                    $msg = wconeobj.get_text.closing_time_msg;
                } else {
                    $msg = wconeobj.get_text.branch_select_msg;
                }

                $infoMagAppend.append('<div class="fb-info-msg"><p>'+$msg+'</p></div>');
                
            }


        },
        holyDayCheck: function( getData = '' ) {

            let $that = this;

            $.ajax({
                type: 'POST',
                url: wconeobj.ajaxurl,
                data: {
                    action: 'holy_day_check_action',
                    date: getData
                },
                success: function (res) {
                    $that.checkoutPageTimeSlotTimeCheck(res)
                }
            })
        },
        deliveryTimeSlotStatusEvent: function() {

            // Onload event
            let $that = this;

            // Onchange event
            $('[name="rb_delivery_time"]').on( 'change', function() {
                $that.deliveryTimeSlotStatus( $(this).val() );
            } )

        },
        deliveryTimeSlotStatus: function( $val ) {

            let $index   = $val.indexOf("no"),
                $availabilityChecker = $('.fb-checkout-availability-checker-wrapper');

            if( $index != -1 ) {
                $availabilityChecker.hide();
            } else {
                $availabilityChecker.show();
            }

        }
    }

    //
    m = {

        cartBackBtn: false,
        openModal: false,
        isCartModal: false,
        productId: "",
        isVerifiedOwner: "",

        init: function () {

            let $this = this;

            $this.fbClosePopup();
            $this.modalOpen();
            $this.placeOrder();
            $this.addCouponcode();
            $this.removeCouponcode();
            
            //
            a.quantityPlusMinusEvent();
        },
        
        modalOpen: function () {

            let $this = this;

            $(document).on('click', '.rb_order_cart_button', function (e) {

                e.preventDefault();

                $this.openModal = true;

                let $productId = $(this).data('pid');

                $this.productId = $productId;

                // Modal
                $this.modalTemplate();
                //
                $this.fbOpenPopUp();

            })

        },
        modalTemplate: function () {

            // Modal wrapper
            let modalTemp = wp.template('rb_modal_wrapper');
            let modal     = modalTemp();

            $('body').append(modal);
        },    
        fbMultiform: function () {

            let multiForm = $('.rb_multiform');
            if (multiForm.length) {
                let multiSelector = multiForm.find('.rb_form_selector_list .rb_single_form_selector input[type=radio]'),
                    forms = multiForm.find('.rb_single_form');

                multiSelector.on('click', function () {
                    let form = $(this).data('form');
                    forms.each(function () {
                        if ($(this).hasClass(form)) {
                            $(this).fadeIn().addClass('show')
                        } else {
                            $(this).hide().removeClass('show')
                        }
                    })
                })
            }

        },
        
        placeOrder: function () {
            let $this = this;

            $(document).on('submit', '#rb_place_order', function (e) {

                e.preventDefault();
                $this.createOrder();

            });

        },
        fbOpenPopUp: function () {

            let $target = $("#rb_popup_modal");

            if (!$target.length) {
                return false;
            }

            $target.fadeIn();
            $target.addClass('open');

            $(document.body).addClass('fbPopupModal-opened ');


        },
        fbClosePopup: function () {

            let $this = this;

            function removePopup() {
                
                let $target = $("#rb_popup_modal")

                $target.fadeTo(1000, 0.01, function () {
                    $(this).slideUp( 150, function () {
                        $(this).remove();
                        
                        // Reset the value
                        $this.productId = "";
                        $this.isVerifiedOwner = "";
                        $this.openModal = false;
                        $this.isCartModal = false;

                    });
                });

                $(document.body).removeClass('fbPopupModal-opened');
            }

            $(document).on('click', '.rb_close_modal_btn', function (e) {
                e.preventDefault()
                removePopup()
            });

            $(document).on('click', '#rb_popup_modal', function (e) {
                let isShow = e.target === e.currentTarget;

                if (isShow) {
                    removePopup()
                }
            });

            $(document).on('keydown', function (e) {
                if (e.key === 'Escape') {
                    removePopup()
                }
            });


        },
        cartCount: function () {
            $.ajax({

                type: "post",
                url: wconeobj.ajaxurl,
                data: {
                    action: "woo_get_cart_count"
                },
                success: function (res) {
                    $('.rb_cart_count').text(res);

                }

            })
        },
        addCouponcode: function () {

            $(document).on('click', '.rb_add_coupon', function () {

                let $t = $(this).parent().find('[name="coupon_code"]');

                $.ajax({
                    type: "post",
                    url: wconeobj.ajaxurl,
                    data: {
                        action: "woo_add_discount",
                        coupon_code: $t.val()
                    },
                    success: function (res) {

                        $.ajax({

                            post: 'post',
                            url: wconeobj.ajaxurl,
                            data: {
                                action: "woo_get_checkout_data"
                            },
                            success: function (res) {

                                let temp = wp.template('rb_billing_summary');
                                let t = temp(res.data);

                                $('.fb-billing-summary').html(t);

                            }

                        })

                    }

                })

            })

        },
        removeCouponcode: function () {

            let $t = this;

            $(document).on('click', '.rb_remove_coupon', function (e) {

                e.preventDefault();
                let $this = $(this),
                    $url = $this.data('url'),
                    $code = $this.data('coupon');

                $.post($url + '?remove_coupon=' + $code).done(function (data) {

                    $.ajax({

                        post: 'post',
                        url: wconeobj.ajaxurl,
                        data: {
                            action: "woo_get_checkout_data"
                        },
                        success: function (res) {

                            let temp = wp.template('rb_billing_summary');
                            let t = temp(res.data);

                            $('.fb-billing-summary').html(t);

                        }

                    })


                });

            })

        }

    }

    a = {

        init: function () {

            let $this = this;
            //SVG Image
            $this.SVGImage();
            // 
            $this.checkoutCoupon();
            //
            $this.checkoutPageScheduleType();
            $this.extraFeaturesCollapse();
                        
        },
        SVGImage: function () {

            $(window).on('load', function () {

                $('img.rb_svg').each(function () {
                    let $img = $(this);
                    let imgID = $img.attr('id');
                    let imgClass = $img.attr('class');
                    let imgURL = $img.attr('src');

                    $.get(imgURL, function (data) {
                        // Get the SVG tag, ignore the rest
                        let $svg = $(data).find('svg');

                        // Add replaced image's ID to the new SVG
                        if (typeof imgID !== 'undefined') {
                            $svg = $svg.attr('id', imgID);
                        }
                        // Add replaced image's classes to the new SVG
                        if (typeof imgClass !== 'undefined') {
                            $svg = $svg.attr('class', imgClass + ' replaced-svg');
                        }

                        // Remove any invalid XML tags as per http://validator.w3.org
                        $svg = $svg.removeAttr('xmlns:a');

                        // Check if the viewport is set, else we gonna set it if we can.
                        if (!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                            $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'));
                        }

                        // Replace image with new SVG
                        $img.replaceWith($svg);

                    }, 'xml');
                });

            });

        },
        checkoutCoupon: function () {
            let coupon2 = $(".checkout_coupon");
            coupon2.insertBefore('.fb-checkout-order-place-area');
        },
        lodingMarkup: function () {

            let html = '';
            html += '<div class="fb-loading">';
            html += '<div class="circle"></div>';
            html += '<div class="circle"></div>';
            html += '<div class="circle"></div>';
            html += '<div class="shadow"></div>';
            html += '<div class="shadow"></div>';
            html += '<div class="shadow"></div>';
            html += '<span>'+wconeobj.get_text.loading+'</span>';
            html += '</div>';

            return html;

        },
        lodingRemove: function () {

            $('.fb-loading').fadeOut('slow', function () {
                $(this).remove()
            });
        },
        quantityPlusMinusEvent: function () {

            /* Increase */
            $(document).on('click', '.rb_plus', function (e) {

                e.preventDefault();

                let $qty = $(this).parent().find('[name="rb_quantity"]');

                let currentVal = parseInt( $qty.val() );

                if (!isNaN(currentVal)) {
                    let q = currentVal + 1;

                    $qty.val(q);

                    let t = $(this).closest('.item-cart-qty').find('[data-quantity]');

                    t.attr( 'data-quantity', q );

                    miniCartQtyUpdate($(this));

                }

            });

            /* Decrease */
            $(document).on('click', '.rb_minus', function (e) {

                e.preventDefault();

                let $qty = $(this).parent().find('[name="rb_quantity"]');
                let currentVal = parseInt($qty.val());
                if (!isNaN(currentVal) && currentVal > 1) {
                    let q = currentVal - 1;
                    $qty.val(q);

                    let m = $(this).closest('.item-cart-qty').find('[data-quantity]');

                    m.attr( 'data-quantity', q );

                    miniCartQtyUpdate($(this));

                }
            });

        },
        
        extraFeaturesCollapse: function() {
            $(document).on('click','.fb-product-extra-group-title', function() {
                let $this = $(this); 
                $this.closest('.fb-wrap-selector').find('.rb_extra_group_wrap').slideToggle();
                $this.toggleClass('active');
                
            })
        },
        sendMail: function () {

            $('#invitemail').on('submit', function (e) {
                e.preventDefault();

                let $this = $(this),
                    $email = $this.find('[name="invite_mail"]').val();

                $.ajax({

                    type: "post",
                    url: wconeobj.ajaxurl,
                    data: {
                        action: "invitation_mail_action",
                        mail: $email
                    },
                    success: function (res) {

                        $this.append('<p class="invite-alert">' + res + '</p>');
                        $('.invite-alert').delay('3000').fadeOut('slow');

                    }

                })
                
            })

        },
        flyingCart: function () {

            $(document).on('click', '.ajax_add_to_cart', function () {

                let cart = $(".rb_cart_icon");
                let imgtodrag = $(this).closest('.rb_single_product_item, .rb_food_item, .rb_product_details_form').find(".rb_product_details_img img").eq(0);
                if (imgtodrag) {
                    let imgclone = imgtodrag.clone()
                        .offset({
                            top: imgtodrag.offset().top,
                            left: imgtodrag.offset().left
                        })
                        .css({
                            'opacity': '0.5',
                            'position': 'absolute',
                            'height': '100px',
                            'width': '100px',
                            'z-index': '100000000'
                        })
                        .appendTo($('body'))
                        .animate({
                            'top': cart.offset().top - 25,
                            'left': cart.offset().left + 10,
                            'width': 40,
                            'height': 40
                        }, 1000, 'easeInOutExpo');

                    setTimeout(function () {
                        cart.addClass('fb-shake-animation');
                        //
                        setTimeout(function () {
                            cart.removeClass('fb-shake-animation')
                        }, 1000)

                    }, 1500);

                    imgclone.animate({
                        'width': 0,
                        'height': 0
                    }, function () {
                        $(this).detach()

                    });
                }
            });
        },
        currency_symbol_position: function( price = '' ) {

            let currency_pos = wconeobj.currency_pos,
                $currency    = wconeobj.currency,
                $price;


            switch(currency_pos) {
              case 'right':
                $price = price+$currency;
                break;
              case 'left_space':
                $price = $currency+' '+price;
                break;
              case 'right_space':
                $price = price+' '+$currency;
                break;
              default:
                $price = $currency+price;
                break;
                // code block
            }

            return $price;

        },
        addThousandsSeparator: function (nStr) {
            nStr += '';
            let x = nStr.split('.');
            let x1 = x[0];
            let x2 = x.length > 1 ? wconeobj.wc_decimal_separator + x[1] : '';
            let rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + wconeobj.wc_thousand_separator + '$2');
            }
            return x1 + x2;
        },
        removeThousandsSeparator: function( selector ) {

            let $s = $("[data-"+selector+"]"),
                $v = $s.data(selector);
           
                if ( $v.toString().includes(',')  ) {

                   let $d = $v.replace(/,/g, "");

                    $s.attr('data-'+selector, $d);

                }
        },
        checkoutPageDateField: function() {
            $('.wcone-date-field').datepicker({ dateFormat: wconeobj.datepicker_format });
        },
        checkoutPageScheduleType: function() {

            let s = $('[name="rb_delivery_schedule_options"]'),
                t = $('.dp-date-wrapper'),
                deliveryDate = $('[name="rb_delivery_date"] option'),
                timeWrapper = $('.fb-delivery-time-wrapper');

            if( s.val() == 'todayDelivery' ) {
               t.hide() 
            }
            s.on( 'click', function() {

                let $this = $(this);

                if( $this.val() == 'scheduleDelivery' ) {
                    t.show()
                   
                   if( $('[name="rb_delivery_date"]').val() == '' ) {
                    timeWrapper.hide();
                   }                   

                } else {
                    t.hide()
                    deliveryDate.prop('selected', function() {
                    return this.defaultSelected;
                    });
                    $( '#rb_delivery_date' ).change();
                    timeWrapper.fadeIn();
                    
                }

            } )

        },
        orderStartButton:function() {
            let $selectedBranch = $('.rb_modal_location').find('[name="rb_pickup_branch"]').val();

            if( !wconeobj.is_multi_branch || $selectedBranch  ) {
                $('.rb_modal_content.fb-ability-checker-form-wrapper').find('.fb-availability-check-result').append('<div class="fb-availability-check-buton-order-start"><button class="rb_close_modal rb_btn_fill">'+wconeobj.get_text.start_order+'</button></div>').show();
            }
        },
        emptyOrderStartButton: function() {
            $('.fb-availability-check-buton-order-start').remove();
        }

    }

    cartModal = {

        is_cart: true,
        is_delivery_opt: false,
        is_order_payment: false,
        current_view: '',
        init: function() {
            this.onTriggerMiniCart()
            this.onTriggerCheckout()
            this.miniCartModalClose()
            this.onTriggerBack()
            this.onTriggerOrderPayment()
            this.checkoutError()
            this.addedToCartEvent()

            this.footerFixedCartOnTriggerCartOpen()
        },
        miniCartModalClose: function() {

            let $this = this;

            $(document.body).on( 'click', '.rb_close_mini_cart_modal', function() {
                $this.miniCartModalCloseCB()
            } )
            $(document.body).on( 'click', '.rb_cart_popup_modal', function(e) {
                if( e.currentTarget === e.target ) {
                   $this.miniCartModalCloseCB() 
                }
                
            } )

            $(document).on('keydown', function (e) {
                if (e.key === 'Escape') {
                    $this.miniCartModalCloseCB()
                }
            });
        },
        miniCartModalCloseCB: function () {
            $(document.body).removeClass('fbPopupModal-opened');
            $('.rb_cart_popup_modal').fadeOut();
            $('.step-cart').hide();
            $('.step-checkout').hide();
            $('.fb-shipping-billing-address').hide();
            $('.fb-checkout-review-order').hide();
            $('.rb_cart').removeClass('open')

        },
        onTriggerMiniCart: function() {
            let $that = this;
            $('.rb_cart_count_btn').on( 'click', function() {
                //
                $('body').addClass('fbPopupModal-opened');
                $('.rb_cart_popup_modal').fadeIn();
                $('.rb_cart').addClass('open');
                $('.step-cart').fadeIn();
                //
                $that.current_view = 'cart';

                $that.is_cart = true;
                $that.is_delivery_opt = false;
                $that.is_order_payment = false;
                //
                $that.conditionalShowHide();
            } )
        },
        footerFixedCartOnTriggerCartOpen: function() {

            let $miniCartBottom = $('.mini-cart-bottom-block');

            $('.rb_cart_btn_circle, .rb_cart_close').on( 'click', function(e) {
                $('.rb_order_details_main').slideToggle();
                $miniCartBottom.fadeToggle();
            } )
            //
            $('.rb_cart_close').on( 'click', function(e) {
                $miniCartBottom.fadeOut();
            } )

        },
        onTriggerCheckout: function () {
            let $that = this;
            $(document.body).on( 'click', '.rb_mini_cart_checkout_btn', function(e) {
                e.preventDefault();
                //
                $that.is_cart = false;
                $that.is_order_payment = false;
                $that.is_delivery_opt = true;
                $that.current_view = 'delivery';

                //
                $('.step-cart').hide();
                $('.step-checkout').fadeIn('500');
                // update checkout
                $(document.body).trigger( 'update_checkout' );

                //
                $that.conditionalShowHide();

                // trigger this if delivery option hide from checkout
                if( wconeobj.is_checkout_delivery_option != 'yes' ) {
                    $( '.fb-order-payment' ).click();
                }
                

            } );
        },
        onTriggerBack: function () {
            let $that = this;
            $(document.body).on( 'click', '.back-cart', function(e) {
                e.preventDefault();

                let $this = $(this),
                    $trigger = $this.data('back');

                if( $that.current_view == 'delivery' || wconeobj.is_checkout_delivery_option != 'yes' ) {

                    $that.is_cart = true;
                    $('.step-cart').fadeIn();
                    $('.step-checkout').hide();

                } else if( $that.current_view == 'order-payment' && wconeobj.is_checkout_delivery_option == 'yes' ) {

                    $that.is_delivery_opt = true;
                    $that.current_view = 'delivery';

                    $('.fb-shipping-billing-address').hide();
                    $('.fb-checkout-review-order').hide();

                    $('.rb_delivery').show();
                    
                }

                $that.conditionalShowHide();

            } );
        },
        onTriggerOrderPayment: function () {
            let $that = this;
            $(document.body).on( 'click', '.fb-order-payment', function(e) {
                e.preventDefault();
                
                $that.is_order_payment = true;
                $that.is_cart = false;
                $that.is_delivery_opt = false;

                $(document.body).trigger( 'update_checkout' );

                $that.current_view = 'order-payment';

                $('.fb-shipping-billing-address').fadeIn();
                $('.fb-checkout-review-order').fadeIn();

                $('[data-back]').attr( 'data-back', 'checkout' );

                $that.conditionalShowHide();
            } );
        },
        conditionalShowHide: function() {

            let $this = this,
                $checkoutBtn = $('.rb_mini_cart_checkout_btn'),
                $backCart    = $('.back-cart'),
                $deliveryOpt   = $('.rb_delivery'),
                $orderPayment   = $('.fb-order-payment');

            if( $this.is_cart == true ) {
                $backCart.hide()
                $deliveryOpt.hide()

                $checkoutBtn.show()
                $orderPayment.hide()

            } else if( $this.is_delivery_opt == true ) {

                $deliveryOpt.show()

                $backCart.show()
                $checkoutBtn.hide()
                $orderPayment.show()
                
            } else if( $this.is_order_payment == true ) {
                $deliveryOpt.hide()

                $checkoutBtn.hide()
                $orderPayment.hide()
            }

        },
        checkoutError: function() {
            $(document.body).on( 'checkout_error', function() {
                $('.woocommerce-NoticeGroup-checkout').insertBefore('#place_order')
            })
        },
        addedToCartEvent: function() {
            
            $(document.body).on( 'added_to_cart removed_from_cart', function() {
                $.ajax({
                    post: 'post',
                    url: wconeobj.ajaxurl,
                    data: {
                        action: "woo_update_fixed_cart_subtotal"
                    },
                    success: function (res) {
                        $('.fixed-cart-subtotal').html(res);
                    }
                })

            });

        }

    }

    // window on load Init 
    $( window ).on( 'load', function() {
        m.init(); i.init(); a.init(); cartModal.init();
    } )
 
    /**
     *  Custom admin scripts 
     */

    admin = {

        init: function () {

            let $this = this;

            // datepicker init for Date filter 
            $(document).find(".datepicker").datepicker({
                dateFormat: wconeobj.datepicker_format,
                 inline: true,
                onSelect: function(dateText, inst) { 
                    var date = $(this).datepicker('getDate'),
                        day  = date.getDate(),  
                        month = date.getMonth() + 1,              
                        year =  date.getFullYear();

                    $(this).data( 'getdate', month+ '/' + day + '/' + year );
                }
            });

            // Delivery Type
            $this.deliveryType();

        },
        
        deliveryType: function() {

            //
            if( wconeobj.delivery_options != 'all' )  {
                localStorage.setItem( "rb_delivery_type", wconeobj.delivery_options );
            }
           
            //
            let $getAddressArea = $(document).find('.fb-checkout-availability-checker-wrapper'),
                $hideAvailabilityChecker = $('.rb_modal_content').find('.hide-availability-checker'),
                $dTypePickup    = $('.delivery-type-pickup'),
                $dTypeDelivery  = $('.delivery-type-delivery'),
                $dTypeInRestaurant  = $('.delivery-type-in-restaurant'),
                $tableNumbers    = $('.table-numbers-list-wrapper'),
                $fbAbilityStatus = localStorage.getItem( "fbAbilityStatus" ),
                deliveryType     = localStorage.getItem( "rb_delivery_type" );

                //
                if(  deliveryType == 'Pickup' || deliveryType == 'pickup'  ) {
                    $getAddressArea.hide();
                    $hideAvailabilityChecker.hide();
                    $dTypePickup.attr( 'checked', true );
                    //
                    if( wconeobj.is_active_location && !wconeobj.is_checkout ) {
                        a.emptyOrderStartButton();
                        a.orderStartButton();
                    }

                } else if( deliveryType == 'In-Restaurant' ) {
                    $getAddressArea.hide();
                    $hideAvailabilityChecker.hide();
                    $dTypeInRestaurant.attr( 'checked', true );
                    $tableNumbers.show();
                    if( wconeobj.is_active_location && !wconeobj.is_checkout ) {
                        a.emptyOrderStartButton();
                        a.orderStartButton();
                    }
                } else {
                    $dTypeDelivery.attr( 'checked', true );
                }

            // On change event of delivery type
            $( "[name='rb_delivery_options']" ).on( "click", function(e) {

                let $getValue = $(this).val(),
                $fbAbilityStatus = localStorage.getItem( "fbAbilityStatus" );

                // Set delivery type in local storage
                localStorage.setItem( "rb_delivery_type", $getValue );

                if( $getValue == 'Pickup' ) {

                    $getAddressArea.hide();
                    $hideAvailabilityChecker.hide();
                    $('.fb-availability-check-result > p').hide();
                    $('.rb_modal_content').find('.fb-availability-check-buton').hide();
                    $tableNumbers.hide();
                    // if deactivate Modal Location Checker
                    if( wconeobj.is_active_location && !wconeobj.is_checkout ) {
                        a.emptyOrderStartButton();
                        a.orderStartButton();
                    }
                    
                } else if( $getValue == 'In-Restaurant' ) {
                    
                    $getAddressArea.hide();
                    $hideAvailabilityChecker.hide();
                    $('.fb-availability-check-result > p').hide();
                    $('.rb_modal_content').find('.fb-availability-check-buton').hide();
                    $tableNumbers.show();
                    // if deactivate Modal Location Checker
                    if( wconeobj.is_active_location && !wconeobj.is_checkout ) {
                        a.emptyOrderStartButton();
                        a.orderStartButton();
                    }

                } else {

                    $getAddressArea.show();
                    $hideAvailabilityChecker.show();
                    $tableNumbers.hide();
                    // 
                    if( wconeobj.is_active_location ) {
                        a.emptyOrderStartButton();
                        $('.fb-availability-check-buton').show();
                    }
                    
                }

                //

                let data = {
                  action: 'update_order_review_action',
                  security: wc_checkout_params.update_order_review_nonce
                };

                jQuery.post( wconeobj.ajaxurl, data, function( response )
                {

                  $('body').trigger( 'update_checkout' );

                });

            });

        },
        preloader: function() {
            $('.wcone-manager-data').html( '<div class="wcone-loader"></div>' );
        }
        
    } // 


    // Init admin object

    admin.init();

/**
 * Category Dropdown
 * 
 */

    function collapse() {
        $(document.body).on('click', '[data-toggle="collapse"]', function (e) {
            var target = '#' + $(this).data('target');
            $(this).toggleClass('collapsed');
            // $(target).toggleClass('open');
            $(target).slideToggle();
            
            e.preventDefault();
        })
    }
    collapse();

    // Mini Cart Qty Update

    function miniCartQtyUpdate(t) {

        let $selector = t.closest( '.rb_quantity' ).find('.rb_input_text'),
            $qty = $selector.val(),
            $pid = $selector.data('product_id'),
            $cartContainer = $('.mini-cart-content-inner');

            console.log( $qty );

        $.ajax({

            type: 'POST',
            url: wconeobj.ajaxurl,
            data:{
                action: 'woo_mini_cart_qty_update',
                item_qty: $qty,
                item_id: $pid
            },
            beforeSend: function() {
                $cartContainer.css('opacity', '0.5');
            },
            success: function( res ) {
                $(document.body).trigger('wc_fragment_refresh');
            },
            complete: function() {
                $cartContainer.css('opacity', '1');
            }
        })

    }

    $('.grid-layout-cart-mobile-bar-cart-up').on( 'click', function() {
        $(this).closest('.rb_grid_layout_cart_content').toggleClass('cart-toggle');
        
    } )


    /**********************
     * Found variation
     * *******************/ 

    $( document ).on( 'found_variation.wc-variation-form', function(e) {

        let y = $(e.target).find( '.variations select' );

       // get Chosen Attributes
        var data   = {};
        y.each( function() {
            var attribute_name = $( this ).data( 'attribute_name' ) || $( this ).attr( 'name' );
            var value          = $( this ).val() || '';
            data[ attribute_name ] = value;
        });

        // matching variations
        var matching_variations = findMatchingVariations( $(e.target).data('product_variations'), data ),
            variation           = matching_variations.shift(),
            $displayPrice = variation.display_price,
            $displayPriceSelector = $('[data-item-price]');

            $('.product-extra-options:checked').prop('checked', false);
            $('.rb_total_product_extra_price').html( a.currency_symbol_position(0) );
            $displayPriceSelector.attr( 'data-item-price', $displayPrice );
            $displayPriceSelector.html('').html( a.currency_symbol_position( a.addThousandsSeparator( $displayPrice.toFixed( wconeobj.price_decimals ) ) ) );
    } )
    
    // Find Matching Variations 
    function findMatchingVariations( variations, attributes ) {

        var matching = [];
        for ( var i = 0; i < variations.length; i++ ) {
            var variation = variations[i];

            if ( isMatch( variation.attributes, attributes ) ) {
                matching.push( variation );
            }
        }
        return matching;

    }
    // is Match variation attributes
    function isMatch( variation_attributes, attributes ) {
        var match = true;
        for ( var attr_name in variation_attributes ) {
            if ( variation_attributes.hasOwnProperty( attr_name ) ) {
                var val1 = variation_attributes[ attr_name ];
                var val2 = attributes[ attr_name ];
                if ( val1 !== undefined && val2 !== undefined && val1.length !== 0 && val2.length !== 0 && val1 !== val2 ) {
                    match = false;
                }
            }
        }
        return match;
    };




}(jQuery))