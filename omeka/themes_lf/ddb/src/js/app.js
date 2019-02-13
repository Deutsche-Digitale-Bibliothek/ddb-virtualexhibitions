(function ($, options, litfassColorPalettes) {

  'use strict';

  var menuProps;
  var headerHeight = 38;

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
    $('#fullpage').fullpage({

      licenseKey: '8B69A3A7-6EE14CEB-AB786AF1-5C9D1AB6',

      // Navigation
      menu: '#menu',
      // lockAnchors: false,
      // anchors: ['s0', 's1', 's2', 's3'],
      anchors: options.anchors,
      // navigation: false,
      // navigationPosition: 'right',
      // navigationTooltips: ['firstSlide', 'secondSlide'],
      // showActiveTooltip: false,
      slidesNavigation: true,
      // slidesNavPosition: 'bottom',

      // Scrolling
      // css3: true,
      // scrollingSpeed: 700,
      autoScrolling: false,
      // fitToSection: true,
      fitToSectionDelay: 500,
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
      fixedElements: '#header, #menu',
      responsiveWidth: 768,
      // responsiveHeight: 0,
      // responsiveSlides: false,
      // parallax: false,
      // parallaxOptions: {type: 'reveal', percentage: 62, property: 'translate'},

      // Custom selectors
      // sectionSelector: '.section',
      // slideSelector: '.slide',

      // lazyLoading: true,

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
    // console.log(origin, destination, direction);
  }

  function fpAfterLoad(from, current, direction) {
    // fires every time a section is loaded
    // console.log('loaded', from, current, direction);
    setVisitedSectionsInHeaderSectionBar(current.index);
  }

  function fpAfterRender() {
    // fires only once on page load

    // we could put functioncalls from init() here e.g.
    // setScrollElementMaxHeight();
    // bindSCrollControls();
    // toggleScrollControls();
    // setMediaProps();
    // bindMediaInfo();
    // bindTitlePageNextLink();

    // console.log('renderd');
  }

  function fpAfterResize(width, height) {
    // console.log('resize - height is wrong somehow', width, height);
    setScrollElementMaxHeight();
    toggleScrollControls();
    setMediaProps();
  }

  function fpAfterResponsive(isResponsive) {
    setTableCellHeight(isResponsive);
  }

  function fpAfterSlideLoad(section, origin, destination, direction) {

  }

  function fpOnSlideLeave(section, origin, destination, direction) {

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
    if (isResponsive) {
      $('.fp-tableCell').css('height', '100%');
    } else {
      $('.fp-tableCell').css('height', function () {
        return ($(this).parent('.section').height() - headerHeight) + 'px';
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
          'padding-right': '17px'
        });
      } else {
        paddingRight = (paddingRight <= 17) ? 17 : paddingRight;
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
          {
            scrollTop: (element.scrollTop() + step)
          },
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

  function bindSCrollControls() {
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
      // if ($('.scroll-element', scrollContainer).height() < scrollContainer.height()) {
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

    // subtract individual media-item-caption height
    $('.media-item').each(function () {
      var $mediaItem = $(this);
      var $caption = $('.media-item-caption', $(this).parent('.media-item-container'));
      var captionHeight= $caption.height();
      if (captionHeight) {
        height -= captionHeight;
      }
      $mediaItem.css({ 'max-height': height + 'px' });
    });

    var mediaMetaScroll = $('.media-meta-scroll');
    if ($(window).width() < 768) {
      mediaMetaScroll.css({
        'max-height': '100%',
        'padding-right': '0'
      });
    } else {
      mediaMetaScroll.css({
        'max-height': height,
        // 'padding-right': (mediaMetaScroll[0].offsetWidth - mediaMetaScroll[0].clientWidth) + 'px'
        // 'padding-right': '17px',
        'padding-right': '32px',
      });
    }
  }

  function setMenuProps() {
    menuProps = {
      height: $('#menu').height()
    };
  }

  function setColors() {
    $('.section').each(function (i) {
      var section = $(this);
      var palette = section.data('colorPalette');
      var color = section.data('colorSection');
      var fader = $('.fader', section);
      if (typeof litfassColorPalettes[palette] !== 'undefined' &&
          typeof litfassColorPalettes[palette][color] !== 'undefined' &&
          fader.length > 0)
      {
        fader.css({
          background: 'linear-gradient(to bottom, rgba(' +
          litfassColorPalettes[palette][color]['rgb']['r'] + ', ' +
          litfassColorPalettes[palette][color]['rgb']['g'] + ', ' +
          litfassColorPalettes[palette][color]['rgb']['b'] + ', 0) 0, ' +
          litfassColorPalettes[palette][color]['hex'] + ' 75%, ' +
          litfassColorPalettes[palette][color]['hex'] + ' 100%)'
        });
      }
    });
  }

  function setRGBColorInPalettes() {
    for (var palette in litfassColorPalettes) {
      if (litfassColorPalettes.hasOwnProperty(palette)) {
        for (var color in litfassColorPalettes[palette]) {
          if (litfassColorPalettes[palette].hasOwnProperty(color)) {
            litfassColorPalettes[palette][color]['rgb'] = hexToRgb(litfassColorPalettes[palette][color]['hex']);
          }
        }
      }
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
    $('.icon-info').bind('click', function (event) {
      var iconInfo = $(this);
      var controlInfo = iconInfo.parent('.control-info');
      var mediaMeta = $('.media-meta', iconInfo.parents('.container-media'));
      // var mediaItem = $('.media-item', iconInfo.parents('.container-media'));
      var mediaCol = iconInfo.parents('.col-media');
      var textCol = $('.col-text', iconInfo.parents('.row'));
      event.preventDefault();
      if (iconInfo.hasClass('active')) {
        iconInfo.removeClass('active');
        controlInfo.removeClass('active');
        mediaMeta.addClass('d-none');
        // mediaItem.removeClass('d-none');
        textCol.removeClass('hidden');
        mediaCol.removeClass('active');
      } else {
        iconInfo.addClass('active');
        controlInfo.addClass('active');
        mediaMeta.removeClass('d-none');
        // mediaItem.addClass('d-none');
        textCol.addClass('hidden');
        mediaCol.addClass('active');
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

  function init() {
    $(function () {
      setMenuProps();
      setRGBColorInPalettes();
      setColors();
      initFullPage();
      setScrollElementMaxHeight();
      bindSCrollControls();
      toggleScrollControls();
      setMediaProps();
      bindMediaInfo();
      bindTitlePageNextLink();
    });
  }

  init();

})(jQuery, litfassOptions, litfassColorPalettes);
