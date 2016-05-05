jQuery(window).trigger('resize').trigger('scroll');

// ---------------------------------------------- //
// Global Read-Only Variables (DO NOT CHANGE!)
// ---------------------------------------------- //
var doc = document.documentElement; doc.setAttribute('data-useragent', navigator.userAgent);

var fullwidth = 1200;
var iOS = check_iOS();
var _event = (iOS) ? 'click, mousemove':'click';

;(function ($) {
"use strict";

/* Parallax */
$(window).stellar();

var jPM = $.jPanelMenu({
    menu: '#site-navigation',
    trigger: '.mobile-menu a',
    animated: true
});

var jRes = jRespond([
    {
        label: 'small',
        enter: 0,
        exit: 768
    },{
        label: 'medium',
        enter: 768,
        exit: 980
    },{
        label: 'large',
        enter: 980,
        exit: 10000
    }
]);

jRes.addFunc({
    breakpoint: 'small',
    enter: function() {
        jPM.on();
        // Add class accordion
        $('#jPanelMenu-menu').attr('id', 'lee_menu_mobile'); // change jPanelMenu-menu => lee_menu_mobile
        $('#lee_menu_mobile').addClass('lee_menu_accordion');
        $('#lee_menu_mobile .nav-dropdown').attr('class', 'nav-dropdown-mobile');
        $('#lee_menu_mobile .nav-column-links').addClass('nav-dropdown-mobile');
        $('#lee_menu_mobile').find('hr.hr_lee-megamenu').remove();
        $('.lee_menu_accordion li').each(function(k, v){
          if($(this).hasClass('menu-item-has-children')){
            $(this).addClass('li_accordion');
            if($(this).hasClass('current-menu-ancestor') || $(this).hasClass('current-menu-parent')){
              $(this).addClass('active');
              $(this).prepend('<a href="javascript:void(0);" class="accordion"><span class="icon fa fa-minus-square-o"></span></a>');
            }else{
              $(this).prepend('<a href="javascript:void(0);" class="accordion"><span class="icon fa fa-plus-square-o"></span></a>').find('>.nav-dropdown-mobile').hide();
            }
          }
        });
        $('.mobile-menu a').click(function(e){
          e.preventDefault();
          $('#lee_menu_mobile').addClass('menu-show');
        });
    },
    exit: function() {
      $('#lee_menu_mobile').attr('id', 'jPanelMenu-menu');
      $('#jPanelMenu-menu').removeClass('lee_menu_accordion');
      $('#jPanelMenu-menu .nav-dropdown-mobile').attr('class', 'nav-dropdown');
      $('#lee_menu_mobile .nav-column-links').removeClass('nav-dropdown-mobile');
      $('.accordion').remove();
      $('.lee_menu_accordion li').each(function(k, v){
        if($(this).hasClass('menu-item-has-children')){
          $(this).removeClass('li_accordion');
        }
      });
      jPM.off();
    }
});

// Accordion menu
$('body').on('click', '.lee_menu_accordion .li_accordion > a.accordion', function(e) {
  e.preventDefault();
  var ths = $(this).parent();
  var cha = $(ths).parent();
  if(!$(ths).hasClass('active')) {
    var c = $(cha).children('li.active');
    $(c).removeClass('active').children('.nav-dropdown-mobile').css({height:'auto'}).slideUp(300);
    $(ths).children('.nav-dropdown-mobile').slideDown(300).parent().addClass('active');
    $(c).find('> a.accordion > span').removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
    $(this).find('span').removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
  } else {
    $(ths).find('>.nav-dropdown-mobile').slideUp(300).parent().removeClass('active');
    $(this).find('span').removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
  }
  return false;
});

// Accordion products Categories
if($('.lee_accordion').length > 0){
  $('.lee_accordion li').each(function(k, v){
    if($(this).hasClass('cat-parent')){
      $(this).addClass('li_accordion');
      if($(this).hasClass('current-cat') || $(this).hasClass('current-cat-parent')){
        $(this).addClass('active');
        $(this).prepend('<a href="javascript:void(0);" class="accordion"><span class="icon fa fa-minus-square-o"></span></a>');
      }else{
        $(this).prepend('<a href="javascript:void(0);" class="accordion"><span class="icon fa fa-plus-square-o"></span></a>').find('>.children').hide();
      }
    }
  });
};

$('body').on('click', '.lee_accordion .li_accordion > a.accordion', function(e) {
  var ths = $(this).parent();
  var cha = $(ths).parent();
  var ul = $(ths).children('.children');
  if(!$(ths).hasClass('active')) {
    var c = $(cha).children('li.active');
    $(c).removeClass('active').children('.children').slideUp(300);
    $(ths).addClass('active').children('.children').slideDown(300);
    $(c).find('>a.accordion>span').removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
    $(this).find('span').removeClass('fa-plus-square-o').addClass('fa-minus-square-o');
  } else {
    $(ths).removeClass('active').children('.children').slideUp(300);
    $(this).find('span').removeClass('fa-minus-square-o').addClass('fa-plus-square-o');
  }
  return false;
});

/* GRID LIST SWITCH */
$(".productGrid").click(function(){
  var product_per_row = $('.category-page .products').attr('data-product-per-row');
  $(".productGrid").addClass("active");
  $(".productList").removeClass("active");
  $.cookie('gridcookie','grid', {path: '/'});
  $("ul.products").fadeOut(300,function(){
      $(this).addClass('grid large-block-grid-'+product_per_row).removeClass('list large-block-grid-1').fadeIn(300);
  });

  return false;
});


$(".productList").click(function(){
  var product_per_row = $(".category-page .products").attr('data-product-per-row');
  $(".products").attr('class','products list small-block-grid-2 large-block-grid-1');
  $(".productList").addClass("active");
  $(".productGrid").removeClass("active");
  $.cookie('gridcookie','list', {path: '/'});
  $("ul.products").fadeOut(300,function(){
      $(this).addClass('list large-block-grid-1').removeClass('grid large-block-grid-'+product_per_row).fadeIn(300);
  });
  return false;
});

if ($.cookie('gridcookie')){
  $("ul.products").addClass($.cookie('gridcookie'));
}

if ($.cookie('gridcookie') == 'grid') {
  $(".filter-tabs .productGrid").addClass('active');
  $(".filter-tabs .productList").removeClass('active');
}

if($.cookie('gridcookie') == 'list') {
  $(".filter-tabs .productList").addClass('active');
  $(".filter-tabs .productGrid").removeClass('active');
}

$(".filter-tabs li").click(function(event){
  event.preventDefault();
});



$('body').on('click', '.quick-view', function(){
   $(this).parent().parent().after('<div class="please-wait dark"><i></i><i></i><i></i><i></i></div>');
   var product_id = $(this).attr('data-prod');
   var data = { action: 'jck_quickview', product: product_id};
    $.post(ajaxurl, data, function(response) {
     $.magnificPopup.open({
        mainClass: 'my-mfp-zoom-in',
        items: {
          src: '<div class="product-lightbox">'+response+'</div>',
          type: 'inline'
        }
      });
     $('.please-wait,.color-overlay').remove();

     setTimeout(function() {
        $('.main-image-slider-1').owlCarousel({
            navigation : true,
            slideSpeed : 300,
            pagination: false,
            paginationSpeed : 500,
            autoPlay : true,
            stopOnHover : false,
            itemsCustom : [
            [0, 1],
            [450, 1],
            [600, 1],
            [700, 1],
            [1000, 1],
            [1200, 1],
            [1400, 1],
            [1600, 1]
            ],
            navigationText: ["", ""]
        });
        $('.product-lightbox form').wc_variation_form();
        $('.product-lightbox form select').change();
      }, 600);
    });
    

    e.preventDefault();
});

jRes.addFunc({
    breakpoint: ['large','medium'],
    enter: function() {

        $('.nav-top-link').parent().hoverIntent(
            function () {
                 var max_width = '1200';
                 if(max_width > $(window).width()) {max_width = $(window).width()}
                 $(this).find('.nav-dropdown').css('max-width',max_width);
                 $(this).addClass('active');
                 var dropdown_width = $(this).find('.nav-dropdown').outerWidth();
                 var col_width =  $(this).find('.nav-dropdown > ul > li.menu-parent-item').width();
                 var cols = ($(this).find('.nav-dropdown > ul > li.menu-parent-item').length);
                 cols += ($(this).find('.nav-dropdown > ul > li.image-column').length);
                 var col_must_width = cols*col_width;
                 if($('.wide-nav').hasClass('nav-center')){
                  $(this).find('.nav-dropdown').css('margin-left','-70px');
                }

                 if(col_must_width >= dropdown_width){
                    $(this).find('.nav-dropdown').width(col_must_width);
                    $(this).find('.nav-dropdown').addClass('no-arrow');
                    //$(this).find('.nav-dropdown').css('left','auto');
                    $(this).find('ul:after').remove();
                 }
            },
            function () {
                  $(this).removeClass('active');
            }
        );

         $('.menu-item-language-current').hoverIntent(
            function () {
                 $(this).find('.sub-menu').fadeIn(50);

            },
            function () {
                 $(this).find('.sub-menu').fadeOut(50);
            }
        );
        

         $('.search-dropdown').hoverIntent(
            function () {
                 if($('.wide-nav').hasClass('nav-center')){
                    $(this).find('.nav-dropdown').css('margin-left','-85px');
                  }
                 $(this).find('.nav-dropdown').fadeIn(50);
                 $(this).addClass('active');
                 $(this).find('input').focus();

            },
            function () {
                 $(this).find('.nav-dropdown').fadeOut(50);
                 $(this).removeClass('active');
                 $(this).find('input').blur();
            }
        );

        $('.category-tree').hoverIntent(
          function(){
            $(this).find('.nav-dropdown').fadeIn(50);
            $(this).addClass('active');
          },
          function(){
            $(this).find('nav-dropdown').fadeOut(50);
            $(this).removeClass('active');
          }
        );

        $('.category-tree .nav-dropdown ul > li').click(function(){
          var selected = jQuery.trim($(this).text());
          var maxLengh = 8;
          console.log($(this).html());
          $('.category-tree > .category-inner span').html(selected).text(function(i, text){
            if(text.length > maxLengh){
              return text.substr(0, maxLengh) + '...';
            }
          });
        });


         $('.cart-link').parent().parent().hoverIntent(
            function () {
                 $(this).find('.nav-dropdown').fadeIn(50);
                 $(this).addClass('active');

            },
            function () {
                 $(this).find('.nav-dropdown').fadeOut(50);
                 $(this).removeClass('active');
            }
          );
    },
    exit: function() {
    }
});


/* Product Gallery Popup */
 $('.product-images-slider').magnificPopup({
    delegate: 'a',
    type: 'image',
    tLoading: '<div class="please-wait dark"><i></i><i></i><i></i><i></i></div>',
    removalDelay: 300,
    closeOnContentClick: true,
    gallery: {
        enabled: true,
        navigateByImgClick: false,
        preload: [0,1]
    },
    image: {
        verticalFit: false,
        tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
    },
    callbacks: {
      beforeOpen: function() {
       this.st.mainClass = 'has-product-video';
      },
      open: function () {
          var magnificPopup = $.magnificPopup.instance;
          var productVideo = $('.product-video-popup').attr('href');

          if(productVideo){
            magnificPopup.items.push({
                src: productVideo,
                type: 'iframe'
            });

            magnificPopup.updateItemHTML();
          }
          
      },
  }
});

/* Product Video Popup link*/
  $("a.product-video-popup").click(function(e){
       $('.product-images-slider').find('.first a').click();
       setTimeout(function(){
          var magnificPopup = $.magnificPopup.instance;
          magnificPopup.prev();
        }, 10);
       e.preventDefault();
  });

$('.product-lightbox-btn').click(function(e){
  $('.product-images-slider').find('.owl-item.active a').click();
  e.preventDefault();
});


$("*[id^='attachment'] a, .entry-content a[href$='.jpg'], .entry-content a[href$='.jpeg']").magnificPopup({
  type: 'image',
  tLoading: '<div class="please-wait dark"><i></i><i></i><i></i><i></i></div>',
  closeOnContentClick: true,
  mainClass: 'my-mfp-zoom-in',
  image: {
    verticalFit: false
  }
});


$(".gallery a[href$='.jpg'],.gallery a[href$='.jpeg'],.featured-item a[href$='.jpeg'],.featured-item a[href$='.gif'],.featured-item a[href$='.jpg'], .page-featured-item .slider > a, .page-featured-item .page-inner a > img, .gallery a[href$='.png'], .gallery a[href$='.jpeg'], .gallery a[href$='.gif']").parent().magnificPopup({
  delegate: 'a',
  type: 'image',
  tLoading: '<div class="please-wait dark"><i></i><i></i><i></i><i></i></div>',
  mainClass: 'my-mfp-zoom-in',
  gallery: {
    enabled: true,
    navigateByImgClick: true,
    preload: [0,1] 
  },
  image: {
    tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
  }
});

$('#main-content').waypoint(function() {
  $('#top-link').toggleClass('active');
},{offset:'-100%'});

// **********************************************************************// 
// ! Fixed header
// **********************************************************************// 
    
$(window).scroll(function(){
    if (!$('body').find('fixNav-enabled')) {return false; }
    var fixedHeader = $('.fixed-header-area');
    var scrollTop = $(this).scrollTop();
    var headerHeight = $('.header-wrapper').height() + 50;
    
    if(scrollTop > headerHeight){
        if(!fixedHeader.hasClass('fixed-already')) {
            fixedHeader.stop().addClass('fixed-already');
        }
    }else{
        if(fixedHeader.hasClass('fixed-already')) {
            fixedHeader.stop().removeClass('fixed-already');
        }
    }
});


// **********************************************************************// 
// ! Header slider overlap for Transparent
// **********************************************************************//


$(window).resize(function() {
  var headerWrapper = $('.header-wrapper');
  if(headerWrapper.hasClass('header-transparent')) {
    var headerHeight = headerWrapper.height();
    var revSlider = $('.rev_slider_wrapper').first();
    revSlider.css({
      'marginTop' : - headerHeight
    });
  }
});

$('#top-link').click(function(e) {
    $.scrollTo(0,300);
    e.preventDefault();
}); // top link


$('.scroll-to').each(function(){
    var link = $(this).data('link');
    var end = $(this).offset().top;
    var title = $(this).data('title');

    if($(this).data('bullet','true')){
      $('.scroll-to-bullets').append('<a href="'+link+'"><strong>'+title+'</strong><span></span></a><br/>');
    }

    $('a[href="'+link+'"]').click(function(){
        $.scrollTo(end,500);
    });

    $(this).waypoint(function() {
      $('.scroll-to-bullets a').removeClass('active');
      $('.scroll-to-bullets').find('a[href="'+link+'"]').toggleClass('active');
    },{offset:'0'});
});


/***** Progress Bar *****/
  if (jQuery().waypoint) {
      $('.progress-bar').waypoint(function() {
        var meter = $(this).find('.bar-meter');
        var number = $(this).find('.bar-number');
        $(meter).css('width', 0);
        $(meter).delay(250).animate({
          width : $(meter).attr('data-meter') + '%',

        }, 1400);
        $(number).delay(1400).show();
        setTimeout(function(){
          $(number).css('opacity',1);
        }, 1400);
      },
      {
        offset : '85%',
        triggerOnce : true
      });

    }


// For demo

     $('.show-theme-options').click(function(){
        $(this).parent().toggleClass('open');
        $(window).resize();
        //$(window).scroll();

        return false;
      });

     $('.ss-button').click(function(){
      //location.reload();
      $(window).resize();
     })
      

    $('.wide-button').click(function(){
      $('body').removeClass('boxed');
      $(this).addClass('active');
      $('.config-options').find('.ss-content .boxed-button').removeClass('active');
      $.cookie('layout', null, { path: '/' });
    });

    $('.boxed-button').click(function(){
      $('body').addClass('boxed');
      $(this).addClass('active');
      $('.config-options').find('.ss-content .wide-button').removeClass('active');
      $.cookie('layout' , 'boxed' , {path: '/'});
    });

    if (($.cookie('layout') != null) && ($.cookie('layout') == 'boxed')){
      $('body').addClass('boxed');
      $('.boxed-button').addClass('active');
      $('.wide-button').removeClass('active');
    } 

    $('.ss-color').click(function(){
      	var datastyle = $(this).attr('data-style');
      	$('head').append("<link rel='stylesheet' href='"+datastyle+"'  type='text/css' />");
      	if (($.cookie('data-style') != null) && ($.cookie('data-style') != datastyle)){
        	$.cookie('data-style', null, { path: '/' });
      	}
      	$.cookie('data-style',datastyle,{path: '/'});
    });

    if ($.cookie('data-style') != null){
        $('head').append("<link rel='stylesheet' href='"+$.cookie('data-style')+"'  type='text/css' />");
    };

    $('.ss-image').click(function(){
        var pattern = $(this).attr('data-pattern');
        $('html').css({"background-image": "url('"+pattern+"')", "background-attachment": "fixed"});
        $('body').css("background-color", "transparent");
        if (($.cookie('data-bg') != null) && ($.cookie('data-bg') != pattern)){
          $.cookie('data-bg', null, { path: '/' });
        }
        $.cookie('data-bg',pattern,{path: '/'});
    });
    
	if ($.cookie('data-bg') != null){
		$('html').css({"background-image": "url('"+$.cookie('data-bg')+"')", "background-attachment": "fixed"});
		$('body').css("background-color", "transparent");
	};
   
// End For demo
$('.widget_nav_menu .menu-parent-item').hoverIntent(
    function () {
        $(this).find('ul').slideDown();
    },
    function () {
       $(this).find('ul').slideUp();
    }
);
  
$('.collapses .collapses-title a').click(function(e) {
  var g = $(this).parents('.collapses-group');
  var t = $(this).parents('.collapses');
  if(!$(t).hasClass('active')) {
    var c = $(g).find('.collapses.active');
    $(c).removeClass('active').find('.collapses-inner').slideUp(200);
    $(t).addClass('active').find('.collapses-inner').slideDown(200);
  } else {
    $(t).removeClass('active').find('.collapses-inner').slideUp(200);
  }
  return false;
});

$('body').on(_event, '.lee-tabs-content ul.lee-tabs li a', function(e){
	e.preventDefault();
	if(!$(this).parent().hasClass('active')){
		var _root = $(this).parents('.lee-tabs-content');
		var currentTab = $(this).attr('data-id');
		var show = $(this).attr('data-show');
		$(_root).find('ul li').removeClass('active');
		$(this).parent().addClass('active');
		
		if($(currentTab).find('.product-item').length > 0 && !show){
			//$(this).attr('data-show', '1');
			if($(_root).find('.lee-panels .lee-panel.first .wow').length > 0){
				//$(_root).find('li.lee-tab.first a').attr('data-show', '1');
				$(_root).find('.lee-panels .lee-panel.first .wow').each(function(){
					$(this).removeClass('wow');
					$(this).removeClass('animated');
					//$(this).removeClass('fadeInUp');
					//$(this).removeAttr('data-wow-duration');
					//$(this).removeAttr('data-wow-delay');
					//$(this).removeAttr('style');
				});
			};
			
			$(currentTab).find('.product-item').each(function(){
				$(this).removeClass('wow');
				$(this).removeClass('animated');
			});
			
			new WOW({boxClass:'product-item'}).init();
		}/*else{
			$(currentTab).find('.product-item').each(function(){
				$(this).removeClass('wow');
				$(this).removeClass('animated');
				$(this).removeClass('fadeInUp');
				$(this).removeAttr('data-wow-duration');
				$(this).removeAttr('data-wow-delay');
				$(this).removeAttr('style');
			});
		}*/
		
		$(_root).find('div.lee-panel').removeClass('active').hide();
		$(currentTab).addClass('active').show();
	}
  	return false;
});


// Countdown
$.countdown.regionalOptions[''] = {
    labels: [lee_countdown_l10n.years, lee_countdown_l10n.months, lee_countdown_l10n.weeks, lee_countdown_l10n.days , lee_countdown_l10n.hours, lee_countdown_l10n.minutes, lee_countdown_l10n.seconds],
    labels1: [lee_countdown_l10n.year, lee_countdown_l10n.month, lee_countdown_l10n.week, lee_countdown_l10n.day, lee_countdown_l10n.hour, lee_countdown_l10n.minute, lee_countdown_l10n.second],
    compactLabels: ['y', 'm', 'w', 'd'],
    whichLabels: null,
    digits: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
    timeSeparator: ':', isRTL: true};
  $.countdown.setDefaults($.countdown.regionalOptions['']);
  $('.countdown').each(function() {
    var count = $(this);
    var austDay =  new Date(count.data('countdown'));
    $(this).countdown({
      until: austDay,
      format: 'dHMS'
    });
  });

if(! /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {

  $('.yith-wcwl-wishlistexistsbrowse.show').each(function(){
      var tip_message = $(this).find('a').text();
      $(this).find('a').attr('data-tip',tip_message).addClass('tip-top');
  });

  $('.yith-wcwl-add-button.show').each(function(){
      var tip_message = $(this).find('a.add_to_wishlist').text();
      $(this).find('a.add_to_wishlist').attr('data-tip',tip_message).addClass('tip-top');
  });

  $('.tip,.tip-bottom').tipr();
  $('#main-content .tip-top, .footer .tip-top, .absolute-footer .tip-top, .featured-box .tip-top, .quick-view .tip-top').tipr({mode:"top"});
  $('#top-bar .tip-top, #header-outer-wrap .tip-top').tipr({mode:"bottom"});
}

$('.bery_banner .center').vAlign();
$( window ).resize(function() {
  $('.bery_banner .center').vAlign();
});

$('.col_hover_focus').hover(function(){
  $(this).parent().find('.columns > *').css('opacity','0.5');
}, function() {
  $(this).parent().find('.columns > *').css('opacity','1');
});


$('.add-to-cart-grid.product_type_simple').click(function(){
  jQuery('.mini-cart').addClass('active cart-active');
  jQuery('.mini-cart').hover(function(){jQuery('.cart-active').removeClass('cart-active');});
  setTimeout(function(){jQuery('.cart-active').removeClass('active')}, 5000);
});

$('.row ~ br').remove();
$('.columns ~ br').remove();
$('.columns ~ p').remove();
$('#megaMenu').wrap('<li/>');
$('select.ninja-forms-field,select.addon-select').wrap('<div class="custom select-wrapper"/>');
$(window).resize();

/* Carousel */
$('.lee-slider').each(function(k, v){
  var cols = $(this).attr('data-columns');
  var cols_small = $(this).attr('data-columns-small');
  var cols_tablet = $(this).attr('data-columns-tablet');
  $(this).owlCarousel({
    navigation : true,
    slideSpeed : 300,
    pagination: false,
    paginationSpeed : 400,
    autoPlay : false,
    stopOnHover : true,
    itemsCustom : [
      [0, cols_small],
      [450, cols_tablet],
      [600, cols_tablet],
      [700, cols_tablet],
      [1000, cols],
      [1200, cols],
      [1400, cols],
      [1600, cols]
    ],
    navigationText: ["", ""]
  });

});

/* Resize carousel */
setInterval(function(){
  var owldata = $(".owl-carousel").data('owlCarousel');
  if (typeof owldata !== typeof undefined && owldata !== false){
    owldata.updateVars();
  }
},1500);

/* Limit product title charactor*/
$('.product-title a').each(function(k, v){
  var selected = jQuery.trim($(this).text());
  var maxLengh = 20;
  console.log($(this).html());
  $(this).html(selected).text(function(i, text){
    if(text.length > maxLengh){
      return text.substr(0, maxLengh) + '...';
    }
  });
});


$('.main-images').owlCarousel({
    items:1,
    lazyLoad: false,
    rewindNav: false,
    addClassActive: true,
    autoHeight : true,
    navigation: false,
    pagination: false,
    autoPlay : false,
    itemsCustom: [1600, 1],
    afterMove: function(args) {
        var owlMain = $(".main-images").data('owlCarousel');
        var owlThumbs = $(".product-thumbnails").data('owlCarousel');
        $('.active-thumbnail').removeClass('active-thumbnail')
        $(".product-thumbnails").find('.owl-item').eq(owlMain.currentItem).addClass('active-thumbnail');
        if(typeof owlThumbs != 'undefined') {
          owlThumbs.goTo(owlMain.currentItem-1);
        }
    }
});


$('.main-images a').click(function(e){
    e.preventDefault();
})

$('.product-thumbnails').owlCarousel({
    items : 3,
    transitionStyle:"fade",
    navigation: false,
    pagination: false,
    autoPlay : false,
    autoHeight : true,
    navigationText: ["",""],
    addClassActive: true,
    itemsCustom: [[0, 2], [479,2], [619,3], [768,4], [1200, 4], [1600, 4]],
}); 

$('.product-thumbnails .owl-item a').click(function(e) {
  e.preventDefault();
});

$('.product-thumbnails .owl-item').click(function(e) {
    var owlMain = $(".main-images").data('owlCarousel');
    var owlThumbs = $(".product-thumbnails").data('owlCarousel');
    owlMain.goTo($(e.currentTarget).index());
    e.preventDefault();
});

/* Language switch */
$('.language-filter select').change(function(){
  window.location = $(this).val();
});


// **********************************************************************// 
// ! Promo popup
// **********************************************************************//

var et_popup_closed = $.cookie('leetheme_popup_closed');
$('.leetheme-popup').magnificPopup({
    items: {
      src: '#leetheme-popup',
      type: 'inline'
    },
    removalDelay: 300, //delay removal by X to allow out-animation
    callbacks: {
        beforeOpen: function() {
            this.st.mainClass = 'my-mfp-slide-bottom';
        },
        beforeClose: function() {
        if($('#showagain:checked').val() == 'do-not-show')
            $.cookie('leetheme_popup_closed', 'do-not-show', { expires: 1, path: '/' } );
        },
    }
  // (optionally) other options
});

if(et_popup_closed != 'do-not-show' && $('.leetheme-popup').length > 0 && $('body').hasClass('open-popup')) {
    $('.leetheme-popup').magnificPopup('open');
}

$('body').delegate().on('click', '.product-interactions .btn-compare',function(){
  var $button = $(this).parents('.product-interactions');
  $button.find('.compare-button .compare.button').trigger('click');
  return false;
});

$('body').delegate().on('click', '.product-interactions .btn-wishlist',function(){
  var $button = $(this).parents('.product-interactions');
  $button.find('.add_to_wishlist').trigger('click');
  return false;
});


/* PRODUCT ZOOM */
if(!/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ) {
    var $easyzoom = $('.product-zoom .easyzoom').easyZoom({ loadingNotice: '' });
}

/* AJAX PRODUCT */
$('body').on('click', '.load-more-btn', function(){
    var infinite_id = $(this).attr('data-infinite');
    var _type = $('.shortcode_'+infinite_id).attr('data-product-type');
    var _page = parseInt($('.shortcode_'+infinite_id).attr('data-next-page'));
    var _post_per_page = parseInt($('.shortcode_'+infinite_id).attr('data-post-per-page'));
    var _is_deals = $('.shortcode_'+infinite_id).attr('data-is-deals');
    var _max_pages = parseInt($('.shortcode_'+infinite_id).attr('data-max-pages'));
      $.ajax({
          url : ajaxurl,
          type: 'post',
          data: {
              action: 'moreProduct',
              page: _page,
              type: _type,
              post_per_page: _post_per_page,
              is_deals: _is_deals
          },
          beforeSend: function(){
            $('.load-more-btn.'+infinite_id).before('<div id="ajax-loading"></div>');
          },
          success: function(res){
              $('.shortcode_'+infinite_id).append(res).fadeIn(1000);
              $('.shortcode_'+infinite_id).attr('data-next-page', _page + 1);
              if (_page == _max_pages){
                $('.load-more-btn.'+infinite_id).addClass('end-product');
                $('.load-more-btn.'+infinite_id).html('ALL PRODUCT LOAD').removeClass('load-more-btn');
              }
              $('#ajax-loading').remove();
              $('.tip,.tip-bottom').tipr();
              $('.products-infinite .tip-top').tipr({mode:"top"});
          }
          
      });
});

$('body').find('div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)').addClass('buttons_added').append('<input type="button" value="+" class="plus" />').prepend('<input type="button" value="-" class="minus" />');
// Target quantity inputs on product pages
$('body').find('input.qty:not(.product-quantity input.qty)').each(function() {
    var min = parseFloat($(this).attr('min'));
    if (min && min > 0 && parseFloat($(this).val()) < min) {
        $(this).val(min);
    }
});
$('body').on('click', '.plus, .minus', function() {
    // Get values
    var $qty = $(this).closest('.quantity').find('.qty'),
        currentVal = parseFloat($qty.val()),
        max = parseFloat($qty.attr('max')),
        min = parseFloat($qty.attr('min')),
        step = $qty.attr('step');
    // Format values
    if (!currentVal || currentVal === '' || currentVal === 'NaN') currentVal = 0;
    if (max === '' || max === 'NaN') max = '';
    if (min === '' || min === 'NaN') min = 0;
    if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') step = 1;
    // Change the value
    if ($(this).is('.plus')) {
        if (max && (max == currentVal || currentVal > max)) {
            $qty.val(max);
        } else {
            $qty.val(currentVal + parseFloat(step));
        }
    } else {
        if (min && (min == currentVal || currentVal < min)) {
            $qty.val(min);
        } else if (currentVal > 0) {
            $qty.val(currentVal - parseFloat(step));
        }
    }
    // Trigger change event
    $qty.trigger('change');
});

}(jQuery));


function check_iOS() {
  var iDevices = [
    'iPad Simulator',
    'iPhone Simulator',
    'iPod Simulator',
    'iPad',
    'iPhone',
    'iPod'
  ];
  while (iDevices.length) {
    if (navigator.platform === iDevices.pop()){ return true; }
  }
  return false;
}

