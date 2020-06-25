/*!
 * DDB Litfaß Theme App
 *
 * Copyright Viktor Grandgeorg, Deutsche Digitale Bibliothek
 * Released under Apache License Version 2.0
 * https://www.apache.org/licenses/LICENSE-2.0
 *
 */
(function ($, options, litfassColorPalettes) {

  'use strict';

  var menuProps;
  var headerHeight = 43;
  var menuScroll;
  var zoomHintShown = false;
  var slideHeightOffset = 45;

  // Creates 'Implied Consent' EU Cookie Law Banner v:2.4
  // Conceived by Robert Kent, James Bavington & Tom Foyster

  var dropCookie = true; // false disables the Cookie, allowing you to style the banner
  var cookieDuration = 14; // Number of days before the cookie expires, and the banner reappears
  var cookieName = 'complianceCookie'; // Name of our cookie
  var cookieValue = 'on'; // Value of cookie
  var vimeoVideos = {};
  var vimeoBgVideos = {};

  var testmode = false;

  // for iPad
  var lastVerticalDiretion = null;
  var lastScrollTop = null;
  var currentVerticalDiretion = null;
  var rebuildCued = false;

  // var pubsub = (function () {

  //   var events = {};

  //   function subscribe(eventName, fn) {
  //     events[eventName] = events[eventName] || [];
  //     events[eventName].push(fn);
  //   }

  //   function unsubscribe(eventName, fn) {
  //     if (events[eventName]) {
  //       for (var i = events[eventName].length; i >= 0; i--) {
  //         if (events[eventName][i] === fn) {
  //           events[eventName].splice(i, 1);
  //         }
  //       }
  //     }
  //   }

  //   function emit(eventName, data) {
  //     if (events[eventName]) {
  //       events[eventName].forEach(function (fn) {
  //         fn(data);
  //       });
  //     }
  //   }

  //   return Object.freeze({
  //     subscribe: subscribe,
  //     unsubscribe: unsubscribe,
  //     emit: emit
  //   });

  // })();

  function initFullPage() {
    var responsiveWidth = 768;
    var fitToSectionDelay = 500;
    var fitToSection = true;

    // Do not mess around with iPad timings!
    if (options.is_ipad) {
      responsiveWidth = 0;
      fitToSectionDelay = 450;
      fitToSection = true;
    }

    $('#fullpage').fullpage({

      licenseKey: '8B69A3A7-6EE14CEB-AB786AF1-5C9D1AB6',

      // Navigation
      menu: '#menu-container',
      // lockAnchors: false,
      // anchors: ['s0', 's1', 's2', 's3'],
      anchors: options.anchors,
      // navigation: true,
      // navigationPosition: 'right',
      // navigationTooltips: ['firstSlide', 'secondSlide'],
      // showActiveTooltip: false,
      slidesNavigation: false,
      // slidesNavPosition: 'bottom',

      // Scrolling
      // css3: true,
      scrollingSpeed: 500,
      autoScrolling: false,
      fitToSection: fitToSection,
      fitToSectionDelay: fitToSectionDelay,
      // scrollBar: false,
      // easing: 'easeInOutCubic',
      // easingcss3: 'ease',
      // loopBottom: false,
      // loopTop: false,
      // loopHorizontal: true,
      // continuousVertical: false,
      // continuousHorizontal: false,
      // scrollHorizontally: false,
      // interlockedSlides: false,
      // dragAndMove: false,
      // offsetSections: false,
      // resetSliders: true,
      // fadingEffect: false,
      // normalScrollElements: '#element1, .element2',
      // scrollOverflow: false,
      // scrollOverflowReset: false,
      // scrollOverflowOptions: null,
      // touchSensitivity: 15,
      // normalScrollElementTouchThreshold: 5,
      // bigSectionsDestination: null,

      // Accessibility
      // keyboardScrolling: true,
      // animateAnchor: true,
      // recordHistory: true,

      // Design
      // controlArrows: true,
      // verticalCentered: true,
      // sectionsColor: ['#fcc', '#006', '#ccf', '#ffc'],
      sectionsColor: options.sectionsColor,
      paddingTop: headerHeight + 'px',
      // paddingBottom: '10px',
      fixedElements: '#header, #menu-container',
      responsiveWidth: responsiveWidth, // 768,

      // responsiveHeight: 0,
      // responsiveSlides: false,
      // parallax: false,
      // parallaxOptions: {type: 'reveal', percentage: 62, property: 'translate'},

      // Custom selectors
      // sectionSelector: '.section',
      // slideSelector: '.slide',

      // lazyLoading: true,

      isIpad: options.is_ipad,

      // events
      onLeave: fpOnLeave,
      afterLoad: fpAfterLoad,
      afterRender: fpAfterRender,
      afterResize: fpAfterResize,
      afterResponsive: fpAfterResponsive,
      afterSlideLoad: fpAfterSlideLoad,
      onSlideLeave: fpOnSlideLeave


    });
    // $.fn.fullpage.setAllowScrolling(false);
  }

  function fpOnLeave(origin, destination, direction) {
    // This callback is fired once the user leaves a section,
    // in the transition to the new section.
    // Returning false will cancel the move before it takes place.

    // console.log(origin, destination, direction);

    var $origin = $(origin.item);

    playVimeoBgVideo(destination);
    pauseVimeoBgVideo(origin);

    if ($origin.hasClass('team') && $origin.hasClass('section')) {
      containerScrollReset($('.scroll-element', $origin));
    }
    if ($origin.hasClass('imprint') && $origin.hasClass('section')) {
      containerScrollReset($('.scroll-element', $origin));
    }

    // if (direction !== lastVerticalDiretion) {
    //   console.log('We have a new direction. It\'s now ' +
    //     direction + '. Used to be ' + lastVerticalDiretion);
    // }

  }

  function bindWindowScroll() {
    $(window).scroll(function () {
      var st = $(this).scrollTop();
      if (st > lastScrollTop) {
        currentVerticalDiretion = 'down';
      } else {
        currentVerticalDiretion = 'up';
      }
      lastScrollTop = st;
    });
  }

  function bindWindowResize () {
    // For iPad ...
    $(window).resize(function() {
      if (currentVerticalDiretion !== lastVerticalDiretion) {
        // console.log('We have a new direction. It\'s now ' +
        // currentVerticalDiretion + '. Used to be ' + lastVerticalDiretion);
        dipatchNewDirection();
        lastVerticalDiretion = currentVerticalDiretion;
      }
    });
  }

  function handleIpad () {
    if (options.is_ipad) {
      bindWindowScroll();
      bindWindowResize();
      // $('#header').css('background-color', '#c30');
      // $('#header .header_title').css('color', 'white').html('... Development Test aktiv, bitte später validieren! ...');
    }
  }

  // function bindCustomWindowResize () {
  //   // fpAfterResize will not fire on small window changes, so use this one ...
  //   $(window).resize(function() {});
  // }

  function dipatchNewDirection() {

    var handleScroll = (function (currentTop) {

      var checkTop = currentTop;
      var checkTop2 = currentTop;
      var isScrolling = true;

      function checkScrolling () {
        if (isScrolling) {
          setTimeout(function() {
            var newTop = $(window).scrollTop();
            // console.log('check', newTop, checkTop);
            if (newTop !== checkTop) {
              checkTop = newTop;
              checkScrolling();
            } else {
              isScrolling = false;
              // double check ...
              if (newTop !== checkTop2) {
                checkTop2 = newTop;
                checkScrolling();
              } else {
                isScrolling = false;
              }
            }
          }, 240);
        }
      }

      function getIsScrolling () {
        return isScrolling;
      }

      return Object.freeze({
        getIsScrolling: getIsScrolling,
        checkScrolling: checkScrolling
      });

    })($(window).scrollTop());

    function tryRebuild() {
      rebuildCued = true;
      setTimeout(function() {
        if (handleScroll.getIsScrolling()) {
          // console.log('scrolling ...');
          tryRebuild();
        } else {
          rebuildCued = false;
          // console.log('rebuilding ...');
          // console.log('outer index', $.fn.fullpage.getActiveSection()['index']);
          setTimeout(function() {
            $.fn.fullpage.reBuild(true);
          }, 860);
        }
      }, 240);
    }

    if (!rebuildCued) {
      handleScroll.checkScrolling();
      tryRebuild();
    }

  }

  function fpAfterLoad(from, current, direction) {
    // Fires every time a section is loaded i.e.
    // Callback fired once the sections have been loaded,
    // after the scrolling has ended.

    // console.log('loaded', from, current, direction);

    // if (direction !== lastVerticalDiretion) {
    //   console.log('We have a new direction. It\'s now ' +
    //   direction + '. Used to be ' + lastVerticalDiretion);
    // }

    setVisitedSectionsInHeaderSectionBar(current.index);
    scrollMenu(current.anchor);

    // We have to focus on navigation after navigating with keyboard (enter),
    // otherwhise focus will be outside for fullapge ...
    $('#jump-to-navigation').focus();


  }

  function fpAfterRender() {
    // fires only once on page load

    // we could put functioncalls from init() here e.g.
    // setScrollElementMaxHeight();
    // bindScrollControls();
    // toggleScrollControls();
    // setMediaProps();
    // bindMediaInfo();
    // bindTitlePageNextLink();

    // console.log('renderd');
    setTableCellHeight();
    setSlideControls();

  }

  function fpAfterResponsive(isResponsive) {
    // console.log('is responsive? ' + isResponsive);

    setTableCellHeight(isResponsive);
    if (!isResponsive || options.is_ipad) {
      $.fn.fullpage.setFitToSection(true);
      // $.fn.fullpage.fitToSection();
    }
    // else {
    //   $('#header').css('background', '#6c6');
    // }
  }

  function fpAfterResize(width, height) {
    // Fires only if 20% of window height changes!
    // Make sure to PATCH resizeActions(line 2642 cons.) in fullpage.js
    // to change this behaviour

    // $('#header').css('background', '#060');

    // console.log('resize:' + width + 'x' + height);

    customAfterResize();
  }

  function customAfterResize() {
    var isResponsive = ($(window).width() < 768) ? true : false;
    if (options.is_ipad) {
      isResponsive = false;
    }
    var anchor = $('#menu .active').data('menuanchor');
    setTableCellHeight(isResponsive);
    setScrollElementMaxHeight();
    setMediaProps();
    toggleScrollControls();
    scrollMenu(anchor);
  }

  function fpAfterSlideLoad(section, origin, destination, direction) {
    // Fires each time a slide is entered, but not from vertical to slide #0
    // console.log('fpAfterSlideLoad', section, origin, destination, direction);

    // if (options.is_ipad || true) {
    //   $.fn.fullpage.setFitToSection(true);
    //   $.fn.fullpage.silentMoveTo(section.anchor, destination.index );
    //   $.fn.fullpage.fitToSection();
    // }

    var parent = $(destination.item).parents('.section-slides');
    $('.currentSlide', parent).html($(destination.item).data('slideno'));
  }

  function fpOnSlideLeave(section, origin, destination, direction) {
    // Fires each time a slide is left, but not from vertical to slide #0
    // console.log('fpOnSlideLeave', destination);
  }

  function initScrollMenu() {
    new SimpleBar($('#menu-scrollable')[0], {
      autoHide: true
    });
    menuScroll = $('#menu-scrollable .simplebar-content');
  }

  function scrollMenu(anchor) {
    var pos = $('#menuanchor-' + anchor).position();
    var scrollRange = menuScroll.scrollTop() + pos.top;
    var maxScroll = $('#menu-scrollable .simplebar-wrapper').height() -
      $('#menu-scrollable .simplebar-content').height();
    if (scrollRange > maxScroll) {
      scrollRange = maxScroll;
    }
    menuScroll.scrollTop(scrollRange);
  }

  function setVisitedSectionsInHeaderSectionBar(index) {
    // console.log(index);
    $('#header-section-bar > div').each(function (i) {
      var section = $(this);
      // console.log(index, section.data('headeranchor'));
      if (index >= section.data('headeranchor')) {
        section.addClass('visited');
      } else {
        section.removeClass('visited');
      }
    });
  }

  function setTableCellHeight(isResponsive) {
    if (isResponsive && !options.is_ipad) {
      $('.fp-tableCell').css({
        'height': '100%',
        // make sure tableCell does not collapse if content is short:
        'min-height': ($(window).height() - headerHeight) + 'px'
      });
    }
  }

  function setScrollElementMaxHeight() {
    $('.scroll-element').each(function (index) {
      var scrollElement = $(this);
      var tableCell = scrollElement.parents('.fp-tableCell');
      var paddingTop = parseInt(scrollElement.css('paddingTop'));
      var paddingRight = scrollElement[0].offsetWidth - scrollElement[0].clientWidth;
      if ($(window).width() < 768) {
        scrollElement.css({
          'max-height': '100%',
          // let there be a minimum padding, if detection goes wrong ...
          'padding-right': '18px'
        });
      } else {
        paddingRight = (paddingRight <= 18) ? 18 : paddingRight;
        scrollElement.css({
          'max-height': (tableCell.height() - paddingTop) + 'px',
          'padding-right': paddingRight + 'px'
        });
      }
    });
  }

  function containerScrollDown(direction, element, scrollPos) {
    if (element.hasClass('active')) {
      var step = (direction === 'down') ? 5 : -5;
      if (
        direction === 'up' && element.scrollTop() !== 0 ||
        direction === 'down'
      ) {
        element.animate(
          { scrollTop: (element.scrollTop() + step) },
          10,
          function () {
            if (element.hasClass('active')) {
              containerScrollDown(direction, element);
            }
          }
        );
      }
    }
  }

  function containerScrollReset(element) {
    element.scrollTop(0);
  }

  function bindMetaScrollControlls() {
    // mouse events
    $('.meta-scroll-arrow-down').bind('mousedown', function (event) {
      event.preventDefault();
      var scrollElement = $('.media-meta-scroll', $(this).parents('.tile'));
      scrollElement.addClass('active');
      containerScrollDown('down', scrollElement);

    });
    $('.meta-scroll-arrow-up').bind('mousedown', function (event) {
      event.preventDefault();
      var scrollElement = $('.media-meta-scroll', $(this).parents('.tile'));
      scrollElement.addClass('active');
      containerScrollDown('up', scrollElement);
    });
    $(window).bind('mouseup', function () {
      if ($('.media-meta-scroll').hasClass('active')) {
        $('.media-meta-scroll').removeClass('active');
      }
    });
    // touch events
    $('.meta-scroll-arrow-down').bind('touchstart', function (event) {
      event.preventDefault();
      var scrollElement = $('.media-meta-scroll', $(this).parents('.tile'));
      scrollElement.addClass('active');
      containerScrollDown('down', scrollElement);

    });
    $('.meta-scroll-arrow-up').bind('touchstart', function (event) {
      event.preventDefault();
      var scrollElement = $('.media-meta-scroll', $(this).parents('.tile'));
      scrollElement.addClass('active');
      containerScrollDown('up', scrollElement);
    });
    $(window).bind('touchend', function () {
      if ($('.media-meta-scroll').hasClass('active')) {
        $('.media-meta-scroll').removeClass('active');
      }
    });
  }

  function bindScrollControls() {
    // mouse events
    $('.scroll-arrow-down').bind('mousedown', function (event) {
      event.preventDefault();
      var scrollElement = $('.scroll-element', $(this).parents('.scroll-container'));
      scrollElement.addClass('active');
      containerScrollDown('down', scrollElement);

    });
    $('.scroll-arrow-up').bind('mousedown', function (event) {
      event.preventDefault();
      var scrollElement = $('.scroll-element', $(this).parents('.scroll-container'));
      scrollElement.addClass('active');
      containerScrollDown('up', scrollElement);
    });
    $(window).bind('mouseup', function () {
      if ($('.scroll-element').hasClass('active')) {
        $('.scroll-element').removeClass('active');
      }
    });
    // touch events
    $('.scroll-arrow-down').bind('touchstart', function (event) {
      event.preventDefault();
      var scrollElement = $('.scroll-element', $(this).parents('.scroll-container'));
      scrollElement.addClass('active');
      containerScrollDown('down', scrollElement);

    });
    $('.scroll-arrow-up').bind('touchstart', function (event) {
      event.preventDefault();
      var scrollElement = $('.scroll-element', $(this).parents('.scroll-container'));
      scrollElement.addClass('active');
      containerScrollDown('up', scrollElement);
    });
    $(window).bind('touchend', function () {
      if ($('.scroll-element').hasClass('active')) {
        $('.scroll-element').removeClass('active');
      }
    });
    $(window).bind('touchcancel', function () {
      if ($('.scroll-element').hasClass('active')) {
        $('.scroll-element').removeClass('active');
      }
    });
  }

  function toggleScrollControls() {
    // we could also use .text-content instead of .scroll-element
    $('.scroll-container').each(function (index) {
      var scrollContainer = $(this);
      if ($('.scroll-element', scrollContainer).prop('scrollHeight') < scrollContainer.height()) {
        $('.scroll-controls', scrollContainer).addClass('d-none');
      } else {
        $('.scroll-controls', scrollContainer).removeClass('d-none');
      }
    });
  }

  function setMediaProps(height) {

    if (typeof height === 'undefined') {
      height = ($(window).height() - menuProps.height);
    } else {
      height = (height - menuProps.height);
    }

    // console.log($(window).height(), menuProps.height, height);
    // console.log('setMediaProps');


    // subtract individual media-item-caption height
    $('.media-item').each(function () {
      var $mediaItem = $(this);
      var mediaItemMaxHeight = height;
      var $caption = $('.media-item-caption', $(this).parent('.media-item-container'));
      var captionHeight = $caption.height() + 20; // 20 for top & bottom margin
      captionHeight += 40; // nicer layout on skyscraper sized elements ...
      if (captionHeight) {
        mediaItemMaxHeight -= captionHeight;
      }
      if ($mediaItem.parents('.slide').length) {
        mediaItemMaxHeight -= slideHeightOffset;
      }
      $mediaItem.css({
        'max-height': mediaItemMaxHeight + 'px'
      });
    });

    $('.media-item-3d-thumb').each(function () {
      var $mediaItem = $(this);
      var mediaItemMaxHeight = height;
      var $caption = $('.media-item-caption', $(this).parents('.media-item-container'));
      var captionHeight = $caption.height() + 10; // 10 for top margin
      if (captionHeight) {
        mediaItemMaxHeight -= captionHeight;
      }
      if ($mediaItem.parents('.slide').length) {
        mediaItemMaxHeight -= slideHeightOffset;
      }
      $mediaItem.css({
        'max-height': mediaItemMaxHeight + 'px'
      });
    });

    $('.media-audio-image').each(function () {
      var $mediaItem = $(this);
      var mediaItemMaxHeight = height;
      var $caption = $('.media-item-caption', $(this).parents('.media-item-container'));
      var captionHeight = $caption.height() + 10; // 10 for top margin
      if (captionHeight) {
        mediaItemMaxHeight -= captionHeight;
      }
      if ($mediaItem.parents('.slide').length) {
        mediaItemMaxHeight -= slideHeightOffset;
      }
      $mediaItem.css({
        'max-height': mediaItemMaxHeight + 'px'
      });
    });


    // make media meta scroll
    // this will not work, we would have to wait for image load events
    // https://stackoverflow.com/questions/3877027/jquery-callback-on-image-load-even-when-the-image-is-cached
    // and also if we do, image height can change on window resize event ...

    // $('.section-text-media .media-meta').each(function () {
    //   var mediaMeta = $(this);
    //   var maxHeight = (mediaMeta.height() / 2) + (mediaMeta.prev('.media-item-container').height() / 2);
    //   var marginTop = mediaMeta.height() - maxHeight;
    //   $('.media-meta-scroll', mediaMeta).css({
    //     'max-height': maxHeight + 'px',
    //     'margin-top': marginTop + 'px'
    //   });

    // });


    var mediaMetaScroll = $('.media-meta-scroll');
    if ($(window).width() < 768) {
      mediaMetaScroll.css({
        'max-height': '100%',
        'padding-right': '0'
      });
    } else {
      mediaMetaScroll.css({
        'max-height': (height - 64), // 64 for the padding top and bottom from parent .media-meta
        // 'padding-right': (mediaMetaScroll[0].offsetWidth - mediaMetaScroll[0].clientWidth) + 'px'
        // 'padding-right': '17px',
        'padding-right': '34px',
      });
    }
  }

  function setMenuProps() {
    menuProps = {
      // this is to early as menu css will not be loaded in this state:
      // height: $('#menu').height()
      height: headerHeight
    };
  }

  function setColors() {
    $('.tile').each(function (i) {
      var section = $(this);
      var palette = section.data('colorPalette');
      var color = section.data('colorSection');
      var fader = $('.fader', section);
      if (typeof litfassColorPalettes[palette] !== 'undefined' &&
        typeof litfassColorPalettes[palette][color] !== 'undefined' &&
        fader.length > 0) {
        fader.css({
          background: 'linear-gradient(to bottom, rgba(' +
            litfassColorPalettes[palette][color]['rgb']['r'] + ', ' +
            litfassColorPalettes[palette][color]['rgb']['g'] + ', ' +
            litfassColorPalettes[palette][color]['rgb']['b'] + ', 0) 0, ' +
            litfassColorPalettes[palette][color]['hex'] + ' 75%, ' +
            litfassColorPalettes[palette][color]['hex'] + ' 100%)'
        });
      }
      if (typeof litfassColorPalettes[palette] !== 'undefined' &&
        typeof litfassColorPalettes[palette][color] !== 'undefined' &&
        section.hasClass('slide')) {
        section.css({
          'background-color': 'rgb(' +
            litfassColorPalettes[palette][color]['rgb']['r'] + ', ' +
            litfassColorPalettes[palette][color]['rgb']['g'] + ', ' +
            litfassColorPalettes[palette][color]['rgb']['b'] + ')'
        });
      }
    });
  }

  function setRGBColorInPalettes() {
    for (var palette in litfassColorPalettes) {
      // if (litfassColorPalettes.hasOwnProperty(palette)) {
      for (var color in litfassColorPalettes[palette]) {
        // if (litfassColorPalettes[palette].hasOwnProperty(color)) {
        litfassColorPalettes[palette][color]['rgb'] = hexToRgb(litfassColorPalettes[palette][color]['hex']);
        // }
      }
      // }
    }
  }

  function hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
      r: parseInt(result[1], 16),
      g: parseInt(result[2], 16),
      b: parseInt(result[3], 16)
    } : null;
  }

  function bindMediaInfo() {
    $('.icon-info').bind('click keydown', function (event) {
      if (event.which === 13 || event.which === 1) {
        // console.log(event.which);
        var iconInfo = $(this);
        var controlInfo = iconInfo.parent('.control-info');
        var metaScrollControls = controlInfo.siblings('.meta-scroll-controls');
        var mediaMeta = $('.media-meta', iconInfo.parents('.tile'));
        var mediaMetaScroll = $('.media-meta-scroll', mediaMeta);
        var mediaMetaScrollContent = $('.media-meta-scroll-content', mediaMetaScroll);
        var mediaCol = iconInfo.parents('.col-media');
        var textCol = $('.col-text', iconInfo.parents('.row'));
        event.preventDefault();
        if (iconInfo.hasClass('active')) {
          iconInfo.removeClass('active');
          controlInfo.removeClass('active');
          mediaMeta.addClass('d-none');
          textCol.removeClass('hidden');
          mediaCol.removeClass('active');
          metaScrollControls.addClass('d-none');
        } else {
          iconInfo.addClass('active');
          controlInfo.addClass('active');
          mediaMeta.removeClass('d-none');
          textCol.addClass('hidden');
          mediaCol.addClass('active');
          setTimeout(function () {
            if (mediaMetaScrollContent.height() > mediaMetaScroll.height()) {
              metaScrollControls.removeClass('d-none');
            }
          }, 200);
        }
      }
    });
  }

  function bindTitlePageNextLink(params) {
    $('.title-page-next-link').bind('click', function (event) {
      event.preventDefault();
      // $.fn.fullpage.moveTo(2);
      $.fn.fullpage.moveSectionDown();
    });
  }

  function bindMenu() {
    var menuContainer = $('#menu-container');
    var menuControl = $('#toggle-menu');
    var icon = menuControl.find('g');

    function openMenu() {
      menuControl.addClass('active');
      menuContainer.addClass('active');
      icon.toggleClass('active');
    }

    function closeMenu() {
      menuControl.removeClass('active');
      menuContainer.removeClass('active');
      icon.toggleClass('active');
    }

    function toggleMenu() {
      // icon.toggleClass('active');
      if (menuContainer.hasClass('active')) {
        closeMenu();
      } else {
        openMenu();
      }
    }

    $('#toggle-menu').click(function (e) {
      e.preventDefault();
      e.stopPropagation();
      toggleMenu();
    });

    // open when browsing with keyboard tab navigation
    $('#jump-to-navigation-control').on('keydown', function(e) {
      if(e.which == 13) {
        e.preventDefault();
        e.stopPropagation();
        // icon.toggleClass('active');
        if (menuContainer.hasClass('active')) {
          closeMenu();
        } else {
          openMenu();
          $('#menu li.active a').focus();
        }
      } else if (e.which === 9) {
        e.preventDefault();
        // do not stop propagation here as fullpage needs to know
        $('.section.active').focus();
        // console.log('focus');
      }
    });

    // close on escape (key=27) and on Enter (key=13)
    menuContainer.on('keydown', function(e) {
      if (e.which == 27 && menuContainer.hasClass('active')) {
        e.preventDefault();
        e.stopPropagation();
        closeMenu();
        $('#jump-to-navigation-control').focus();
      } else if (e.which == 13 && menuContainer.hasClass('active')) {
        closeMenu();
        // Jump to navigation-control, if active menu item was selected as
        // fullpage will not do anything (will not fire fpAfterLoad())
        if ($(e.target).parent().hasClass('active')) {
          $('#jump-to-navigation-control').focus();
        }
      }
    });



  }

  function bindEmptyClick() {
    $(document).click(function (e) {
      if ($(e.target).closest('#menu-container').length <= 0 &&
        $('#menu-container').hasClass('active')
      ) {
        $('#toggle-menu').trigger('click');
      }
    });
  }

  function getMinZoom(caller) {
    var minZoom = 0.2;
    var calcMinZoom = minZoom;
    var zoomImgWidth = caller.data('zoom-img-width');
    var zoomImgHeight = caller.data('zoom-img-height');
    var windowWidth = $(window).width();
    var windowHeight = $(window).height();
    if (zoomImgWidth > windowWidth) {
      calcMinZoom = windowWidth / zoomImgWidth;
      if (calcMinZoom >= 1) {
        return 1;
      }
      minZoom = calcMinZoom;
    }
    if (zoomImgHeight > windowHeight) {
      calcMinZoom = windowHeight / zoomImgHeight;
      if (calcMinZoom < minZoom) {
        minZoom = calcMinZoom;
      }
    }
    return minZoom;
  }

  function getInitZoomPos(caller) {
    var zoomImgWidth = caller.data('zoom-img-width');
    var zoomImgHeight = caller.data('zoom-img-height');
    var windowWidth = $(window).width();
    var windowHeight = $(window).height();
    var initX, initY;
    initX = ((zoomImgWidth / 2) - (windowWidth / 2)) * -1;
    initY = ((zoomImgHeight / 2) - (windowHeight / 2)) * -1;
    return [initX, initY];
  }

  function getCustomZoom(caller, minZoom) {
    var
      imgWidth = caller.data('zoom-img-width'),
      imgHeight = caller.data('zoom-img-height'),
      zoomDetail = caller.data('zoomdetail'),
      zoom = 1;
    if (typeof zoomDetail === 'undefined' || zoomDetail === '') {
      return false;
    }
    var
      windowWidth = $(window).width(),
      halfWindowWidth = windowWidth / 2,
      windowHeight = $(window).height(),
      halfWindowHeight = windowHeight / 2,
      imgWidthOneP = imgWidth / 100,
      imgHeightOneP = imgHeight / 100,
      realW = imgWidthOneP * zoomDetail.w,
      realH = imgHeightOneP * zoomDetail.h,
      zoomW = windowWidth / realW,
      zoomH = windowHeight / realH;

    if (zoomW < zoom) {
      if (zoomW > minZoom) {
        zoom = zoomW;
      } else {
        zoom = minZoom;
      }
    }

    if (zoomH < zoom && zoomH > minZoom && zoomH < 1) {
      zoom = zoomH;
    }

    // var zoomPosX = (zoom * imgWidthOneP * zoomDetail.startX) + (zoom * realW / 2) - halfWindowWidth;
    // var zoomPosY = (zoom * imgHeightOneP * zoomDetail.startY) + (zoom * realH / 2) - halfWindowHeight;
    // console.log({'zoomW': zoomW, 'imgWidth': imgWidth, 'w': zoomDetail.w, 'zoom': zoom, 'zoomPosX': zoomPosX});

    return {
      'zoom': zoom,
      'zoomPosX': (zoom * imgWidthOneP * zoomDetail.startX) + (zoom * realW / 2) - halfWindowWidth,
      'zoomPosY': (zoom * imgHeightOneP * zoomDetail.startY) + (zoom * realH / 2) - halfWindowHeight
    };
  }


  function generateZoomableImage() {
    if ($('#zoom-container').length > 0) {
      return;
    }
    var panzoomInstance;
    var caller = $(this);
    var container = $('<div id="zoom-container" class="zoom-container" tabindex="0"></div>');
    var spinner = $(
      '<div class="zoom-spinner-container">' +
      '<div class="spinner-border zoom-spinner text-white" role="status">' +
      '<span class="sr-only">Lade ...</span>' +
      '</div>' +
      '</div>'
    );
    if (!zoomHintShown) {
      var zoomHint = $('<div class="zoom-hint-container"></div>');
      var zoomHintHelper = $('.zoom-hint-helper').clone();
      zoomHintHelper.removeClass('zoom-hint-helper');
      zoomHint.append(zoomHintHelper);
    }
    var closer = $(
      '<div class="zoom-close">' +
      '<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="18px" height="18px" viewBox="0 0 18 18">' +
      '<g>' +
      '<line x1="1" y1="18" x2="18" y2="1" stroke="#FFFFFF" stroke-width="2"></line>' +
      '<line x1="1" y1="1" x2="18" y2="18" stroke="#FFFFFF" stroke-width="2"></line>' +
      '</g>' +
      '</svg>' +
      '</div>'
    );
    if (!zoomHintShown) {
      container.append(closer, spinner, zoomHint);
      // zoomHintShown = true;
      zoomHint.on('click', function () {
        $(this).remove();
      });
    } else {
      container.append(closer, spinner);
    }
    $('body').append(container);

    $('<img src="' + caller.data('zoom') + '" alt="' + caller.attr('alt') + '" class="zoom-image">')
      .on('load', function () {
        spinner.remove();
        var image = $(this);
        container.append(image);
        var initZoomPos = getInitZoomPos(caller);
        var minZoom = getMinZoom(caller);
        panzoomInstance = panzoom(image[0], {
          smoothScroll: false,
          maxZoom: 1,
          minZoom: minZoom,
          onTouch: function (e) {
            var discardClasses = ['zoom-hint-container', 'zoom-close'];
            // for (var i = 0; i < e.path.length; i++) {
            //   if (discardClasses.indexOf(e.path[i]['className']) !== -1) {
            //     // if false, tells the library not to
            //     // preventDefault and not to stopPropageation:
            //     return false;
            //   }
            // }
            // return false;
            // e.preventDefault();
            if (e.path) {
              for (var i = 0; i < e.path.length; i++) {
                if (discardClasses.indexOf(e.path[i]['className']) !== -1) {
                  return false;
                }
              }
            } else if (e.composedPath) {
              var path = e.composedPath();
              for (var j = 0; j < path.length; j++) {
                if (discardClasses.indexOf(path[j]['className']) !== -1) {
                  return false;
                }
              }
            }
            return true;
          },
          onDoubleClick: function (e) {
            e.preventDefault();
            e.stopPropagation();
            panzoomInstance.dispose();
            container.remove();
          }
        });
        panzoomInstance.moveTo(
          initZoomPos[0],
          initZoomPos[1]
        );
        var customZoom = getCustomZoom(caller, minZoom);
        if (customZoom !== false) {
          if (customZoom.zoom !== 1) {
            panzoomInstance.zoomTo(
              ($(window).width() / 2),
              ($(window).height() / 2),
              customZoom.zoom
            );
            panzoomInstance.moveTo(
              (customZoom.zoomPosX * -1),
              (customZoom.zoomPosY * -1)
            );
          } else {
            panzoomInstance.moveTo(
              (customZoom.zoomPosX * -1),
              (customZoom.zoomPosY * -1)
            );
          }
        }
        // window.testzoom = panzoomInstance;
      });

    if (!zoomHintShown) {
      zoomHintShown = true;
      var zoomHintClose = $('.zoom-hint-close', zoomHint);
      zoomHintClose.focus();
      zoomHintClose.on('keydown', function (e) {
        e.preventDefault();
        if (e.which === 13) {
          $(this).off('keydown');
          container.trigger('click').trigger('focus');
          zoomHint.remove();
        }
      });
    } else {
      $('#zoom-container').focus();
      // container.trigger('click'); // .trigger('focus');
      // container.focus();
      // console.log(document.activeElement);
    }
    // toggle cursor - does not work in chrome:
    // container.on('mousedown', function (e) {
    //   $(this).addClass('active');
    // });
    // container.on('mouseup', function (e) {
    //   $(this).removeClass('active');
    // });

    // container.trigger('click').trigger('focus');
    closer.on('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      panzoomInstance.dispose();
      $(this).off('click');
      container.remove();
    });
    $(document).on('keydown.zoom', function (e) {
      if (e.keyCode === 27 || e.key === 'Escape') {
        e.preventDefault();
        e.stopPropagation();
        $(document).off('.zoom');
        panzoomInstance.dispose();
        container.remove();
      }
    });
  }

  function bindZoom() {
    $('img.media-item').bind('click', generateZoomableImage);
    $('.control-zoom').bind('click keydown', function (event) {
      if (event.which === 13 || event.which === 1) {
        $('.content-media .media-item', $(this).parents('.container-media')).trigger('click');
      }
    });
    $('.zoomer').bind('click', generateZoomableImage);
  }

  function generate3D() {
    var caller = $(this);
    var url = caller.data('3durl');
    var container = $('<div id="zoom-container" class="zoom-container" tabindex="0"></div>');
    var object3D = $(
      '<x3d class="x3d" showLog="false" showStat="false">' +
      '<scene>' +
      '<inline url="' + caller.data('3durl') + '"> </inline>' +
      '</scene>' +
      '</x3d>');
    var closer = $(
      '<div class="zoom-close">' +
      '<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="18px" height="18px" viewBox="0 0 18 18">' +
      '<g>' +
      '<line x1="1" y1="18" x2="18" y2="1" stroke="#FFFFFF" stroke-width="2"></line>' +
      '<line x1="1" y1="1" x2="18" y2="18" stroke="#FFFFFF" stroke-width="2"></line>' +
      '</g>' +
      '</svg>' +
      '</div>'
    );
    container.append(object3D, closer);
    $('body').append(container);
    x3dom.reload();
    var x3domCanvas = $('.x3dom-canvas', container);
    x3domCanvas.focus();

    x3domCanvas.on('keydown', function (e) {
      if (e.which === 9) {
        e.preventDefault();
        e.stopPropagation();
      }
    });

    // close on escape
    container.on('keydown', function (e) {
      if (e.which === 27) {
        e.preventDefault();
        e.stopPropagation();
        closer.off('click');
        x3domCanvas.off('keydown');
        $(this).remove();
      }
    });

    closer.on('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).off('click');
      x3domCanvas.off('keydown');
      container.remove();
    });
  }

  function bind3D() {
    $('img.media-item-3d-thumb').bind('click', generate3D);
    $('img.item-3d-thumb-icon').bind('click', function () {
      $(this).siblings('img.media-item-3d-thumb').trigger('click');
    });
    $('.control-zoom').bind('click keydown', function (event) {
      if (event.which === 13 || event.which === 1) {
        $('.content-media .media-item-3d-thumb', $(this).parents('.container-media')).trigger('click');
      }
    });
  }

  function setSlideControls() {
    $('.section-slides').each(function () {
      var sectionSlide = $(this);
      var controllWrapper = $('<div class="slideControlWrapper"></div>');
      $('.fp-prev', this).appendTo(controllWrapper);
      $(this).append(controllWrapper);
      var numSliders = $('.slide', this).length;
      var slidesInfo = $('<div class="slidesInfo"><span class="currentSlide">1</span> / ' + numSliders + '</div>');
      slidesInfo.appendTo(controllWrapper);
      var mobileSlideControl = $('<div class="mobileSlideControl"></div>');
      mobileSlideControl.appendTo(controllWrapper);
      mobileSlideControl.on('click', function () {
        if (sectionSlide.hasClass('mobile-open')) {
          sectionSlide.removeClass('mobile-open');
          $.fn.fullpage.silentMoveTo(sectionSlide.data('anchor'));
        } else {
          sectionSlide.addClass('mobile-open');
        }
        if ($(this).hasClass('open')) {
          $(this).removeClass('open');
        } else {
          $(this).addClass('open');
        }
      });
      $('.fp-next', this).appendTo(controllWrapper);
    });
  }

  function createCookieDiv() {
    var div = $('<div id="cookie-law" class="cookie-law"><p>Um unser Internetangebot für Sie optimal gestalten und fortlaufend verbessern zu können, verwenden wir Cookies. Durch die weitere Nutzung unseres Angebots erklären Sie sich hiermit einverstanden. Wenn Sie mehr über Cookies erfahren möchten, klicken Sie bitte auf unsere <a href="https://www.deutsche-digitale-bibliothek.de/content/datenschutzerklaerung" rel="noopener" target="_blank">Datenschutzerklärung</a>. Eine Widerrufsmöglichkeit gibt es <a href="https://www.deutsche-digitale-bibliothek.de/content/datenschutzerklaerung#cookies" rel="noopener" target="_blank">hier</a>.</p></div>');
    var button = $('<button id="close-cookie-notice" type="button" class="close-cookie-notice close btn btn-secondary" aria-label="Schließen" aria-controls="cookie-law"><span aria-hidden="true">&times;</span></button>');
    div.append(button);
    $('body').prepend(div);
    button.on('click', function () {
      dispathCookie();
    });
  }

  function createCookie(name, value, days) {
    var expires = '';
    if (days) {
      var date = new Date();
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      expires = '; expires=' + date.toGMTString();
    }
    if (dropCookie) {
      document.cookie = name + '=' + value + expires + '; path=/';
    }
  }

  function checkCookie(name) {
    var nameEQ = name + '=';
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') c = c.substring(1, c.length);
      if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
  }

  function eraseCookie(name) {
    createCookie(name, '', -1);
  }

  function dispathCookie() {
    // Create the cookie only if the user click on "Close"
    createCookie(cookieName, cookieValue, cookieDuration); // Create the cookie
    // then close the window/
    var element = document.getElementById('cookie-law');
    element.parentNode.removeChild(element);
  }

  function bindCookieNotice() {
    $(window).on('load', function () {
      if (checkCookie(cookieName) != cookieValue) {
        createCookieDiv();
      }
    });
  }

  function bindHeaderLogo() {
    $('#nav_logo_small').on('click', function () {
      $.fn.fullpage.moveTo('s0');
    });
  }

  function bindVideoControl() {
    $('.icon-video-audio').on('click', function () {
      var controlElement = $(this);
      var tile = controlElement.parents('.tile');
      var video = $('.litfass-bg-video', tile);
      if (controlElement.hasClass('active')) {
        controlElement.removeClass('active');
        video[0].muted = true;
      } else {
        controlElement.addClass('active');
        video[0].muted = false;
      }
    });
  }

  function bindVideoVimeoControl() {
    $('.icon-video-audio-vimeo').on('click', function () {
      var controlElement = $(this);
      var tile = controlElement.parents('.tile');
      var video = $('.litfass-bg-vimeo-video', tile);
      var id = video.attr('id');
      if (controlElement.hasClass('active')) {
        controlElement.removeClass('active');
        vimeoBgVideos[id].setVolume(0);
      } else {
        controlElement.addClass('active');
        vimeoBgVideos[id].setVolume(1);
      }
    });
  }

  function bindVideoClipping() {
    $('.litfass-bg-video').each(function (index) {
      var video = this;
      var offsetStart = $(video).data('video-offset-start');
      var offsetStop = $(video).data('video-offset-stop');
      if (typeof offsetStart !== 'undefined' ||
        typeof offsetStop !== 'undefined') {

        video.ontimeupdate = function () {
          // if (typeof offsetStart !== 'undefined' &&
          //   video.currentTime < offsetStart) {

          //   video.currentTime = offsetStart;
          // } else
          if (typeof offsetStop !== 'undefined' &&
            video.currentTime > offsetStop) {

            if (typeof offsetStart === 'undefined') {
              offsetStart = 0;
            }
            video.currentTime = offsetStart;
            video.play();
          }
        };
      }
    });
  }

  // function openFullscreen() {
  //   var doc = window.document;
  //   var element = doc.documentElement;

  //   if (element.requestFullscreen) {
  //     element.requestFullscreen();
  //   } else if (element.mozRequestFullScreen) {
  //     element.mozRequestFullScreen();
  //   } else if (element.webkitRequestFullscreen) {
  //     element.webkitRequestFullscreen();
  //   } else if (element.msRequestFullscreen) {
  //     element.msRequestFullscreen();
  //   }
  // }

  // function closeFullscreen() {
  //   if (document.exitFullscreen) {
  //     document.exitFullscreen();
  //   } else if (document.mozCancelFullScreen) {
  //     document.mozCancelFullScreen();
  //   } else if (document.webkitExitFullscreen) {
  //     document.webkitExitFullscreen();
  //   } else if (document.msExitFullscreen) {
  //     document.msExitFullscreen();
  //   }
  // }

  function toggleFullScreen() {

    var doc = window.document;
    var docEl = doc.documentElement;

    var requestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen;
    var cancelFullScreen = doc.exitFullscreen || doc.mozCancelFullScreen || doc.webkitExitFullscreen || doc.msExitFullscreen;

    if (!doc.fullscreenElement && !doc.mozFullScreenElement && !doc.webkitFullscreenElement && !doc.msFullscreenElement) {
      requestFullScreen.call(docEl);
    } else {
      cancelFullScreen.call(doc);
    }
  }

  function bindFullscreen() {
    var controlButton = $('#toggle-fullsize');
    window.addEventListener('fullscreenchange', function (event) {
      event.stopPropagation();
      if (controlButton.hasClass('active')) {
        controlButton.removeClass('active');
      } else {
        controlButton.addClass('active');
      }
    }, true);
    window.addEventListener('mozfullscreenchange', function (event) {
      event.stopPropagation();
      if (controlButton.hasClass('active')) {
        controlButton.removeClass('active');
      } else {
        controlButton.addClass('active');
      }
    }, true);
    window.addEventListener('webkitfullscreenchange', function (event) {
      event.stopPropagation();
      if (controlButton.hasClass('active')) {
        controlButton.removeClass('active');
      } else {
        controlButton.addClass('active');
      }
    }, true);
    window.addEventListener('MSFullscreenChange', function (event) {
      event.stopPropagation();
      if (controlButton.hasClass('active')) {
        controlButton.removeClass('active');
      } else {
        controlButton.addClass('active');
      }
    }, true);
    // window.addEventListener('webkitendfullscreen', function (event) {
    //   event.stopPropagation();
    //   if (controlButton.hasClass('active')) {
    //     controlButton.removeClass('active');
    //   } else {
    //     controlButton.addClass('active');
    //   }
    // }, true);


    controlButton.on('click', function (e) {
      e.preventDefault();
      // var control = $(this);
      var activeSection = $.fn.fullpage.getActiveSection();
      var activeSlide = $.fn.fullpage.getActiveSlide();
      if (activeSlide && activeSlide.item) {
        activeSlide = ($(activeSlide.item).data('slideno') - 1);
      } else {
        activeSlide = 0;
      }
      toggleFullScreen();
      setTimeout(function () {
        $.fn.fullpage.moveTo(activeSection.anchor, activeSlide);
      }, 400);


      // control.toggleClass('active');

      // if (controlButton.hasClass('active')) {
      //   controlButton.removeClass('active');
      // } else {
      //   controlButton.addClass('active');
      // }


      // if (control.hasClass('active')) {
      //   // if (screenfull.enabled) {
      //   // screenfull.request();
      //   // closeFullscreen();
      //   toggleFullScreen();
      // control.removeClass('active');
      //   setTimeout(function() {
      //     $.fn.fullpage.moveTo(activeSection.anchor, activeSlide);
      //   }, 400);
      //   // }
      // } else {
      //   // if (screenfull.enabled) {
      //   // screenfull.exit();
      //   // openFullscreen();
      //   toggleFullScreen();
      // control.addClass('active');
      //   setTimeout(function() {
      //     $.fn.fullpage.moveTo(activeSection.anchor, activeSlide);
      //   }, 400);
      //   // }
      // }
    });
  }

  function setVimeoVideos() {
    $('.litfass-vimeo-video').each(function () {
      var videoContainer = $(this);
      var data = {
        id: videoContainer.data('ddb-vimeo-id'),
        width: videoContainer.width(),
        color: 'ef0053',
        dnt: true,
      };
      var id = videoContainer.attr('id');
      vimeoVideos[id] = new Vimeo.Player(id, data);
      // vimeoVideos[id].setColor('#ef0053');
    });
  }

  function setVimeoBgVideos() {
    $('.litfass-bg-vimeo-video').each(function () {
      var videoContainer = $(this);
      var data = {
        id: videoContainer.data('ddb-vimeo-id'),
        width: videoContainer.width(),
        color: 'ef0053',
        // background: true,
        controls: false,
        autoplay: false,
        dnt: true,
        loop: true,
        muted: true

      };
      var id = videoContainer.attr('id');
      vimeoBgVideos[id] = new Vimeo.Player(id, data);
      // vimeoBgVideos[id].setColor('#ef0053');
      // vimeoBgVideos[id].setVolume(0);
      // vimeoBgVideos[id].setLoop(true);
      // vimeoBgVideos[id].getPaused()
      //   .then(function(paused) {
      //     if (!paused) {
      //       vimeoBgVideos[id].pause();
      //     }
      //   });
    });
  }

  function pauseVimeoBgVideo(section) {
    var item = $(section.item);
    var id = $('.litfass-bg-vimeo-video', item).attr('id');
    if (typeof id !== 'undefined') {
      for (var prop in vimeoBgVideos) {
        if (prop === id) {
          vimeoBgVideos[prop].pause();
        }
      }
    }
  }

  function playVimeoBgVideo(section) {
    var item = $(section.item);
    var id = $('.litfass-bg-vimeo-video', item).attr('id');
    if (typeof id !== 'undefined') {
      for (var prop in vimeoBgVideos) {
        if (prop === id) {
          vimeoBgVideos[prop].play();
        }
      }
    }
  }

  function initTestmode() {
    if (testmode) {
      $('#header').css('background-color', '#c30');
      $('#header .header_title').css('color', 'white').html('... Development Test aktiv, bitte später validieren! ...');
    }
  }

  function handleMac() {
    if('platform' in navigator) {
      var mac = /(Mac|iPhone|iPod|iPad)/i.test(navigator.platform);
      if (mac) {
        $('body').addClass('mac');
      }
    }
  }

  function revealFullpage() {
    $('#fullpage').css('opacity', 1);
  }

  function init() {
    $(function () {
      setMenuProps();
      setRGBColorInPalettes();
      setColors();
      initScrollMenu();
      initFullPage();
      bindMenu();
      setScrollElementMaxHeight();
      bindScrollControls();
      setMediaProps();
      toggleScrollControls();
      setVimeoVideos();
      setVimeoBgVideos();
      bindMediaInfo();
      bindTitlePageNextLink();
      bindZoom();
      bind3D();
      bindVideoControl();
      bindVideoVimeoControl();
      bindVideoClipping();
      bindEmptyClick();
      bindHeaderLogo();
      bindFullscreen();
      bindCookieNotice();
      handleIpad();
      handleMac();
      initTestmode();
      revealFullpage();
      bindMetaScrollControlls();
      // bindCustomWindowResize();
    });
  }

  init();

})(jQuery, window.litfassOptions, window.litfassColorPalettes);
