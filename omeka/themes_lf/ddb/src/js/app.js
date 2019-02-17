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
      menu: '#menu-container',
      // lockAnchors: false,
      // anchors: ['s0', 's1', 's2', 's3'],
      anchors: options.anchors,
      // navigation: true,
      // navigationPosition: 'right',
      // navigationTooltips: ['firstSlide', 'secondSlide'],
      // showActiveTooltip: false,
      slidesNavigation: true,
      // slidesNavPosition: 'bottom',

      // Scrolling
      // css3: true,
      // scrollingSpeed: 1500,
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
      fixedElements: '#header, #menu-container',
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

  function fpAfterResponsive(isResponsive) {
    // console.log('is responsive (< 768)? - ' +
    // 'responsive, width-jq, height-jq:',
    // isResponsive, $(window).width(), $(window).height());
    setTableCellHeight(isResponsive);
    if (!isResponsive) {
      // Fix fullpage.js setFitToSection after moving to desktop
      $.fn.fullpage.setFitToSection(true);
    }
  }

  // @TODO check if resize fires each time ...
  function fpAfterResize(width, height) {
    var isResponsive = ($(window).width() < 768)? true : false;
    // console.log('resize - ' +
    // 'width, width-jq, height, height-jq:',
    // width, $(window).width(), height, $(window).height());

    // Fix fullpage.js will reset height to pixel instead of 100% after fpAfterResponsive
    setTableCellHeight(isResponsive);

    setScrollElementMaxHeight();
    toggleScrollControls();
    setMediaProps();
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

    // console.log($(window).height(), menuProps.height, height);

    var mediaItemMaxHeight = height;

    // subtract individual media-item-caption height
    $('.media-item').each(function () {
      var $mediaItem = $(this);
      var $caption = $('.media-item-caption', $(this).parent('.media-item-container'));
      var captionHeight= $caption.height();
      if (captionHeight) {
        mediaItemMaxHeight -= captionHeight;
      }
      $mediaItem.css({ 'max-height': mediaItemMaxHeight + 'px' });
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
        'padding-right': '32px',
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

  function bindMenu() {
    $('#toggle-menu').click(function (e) {
      e.preventDefault();
      e.stopPropagation();
      var menuContainer = $('#menu-container');
      var menuControll = $(this);
      if (menuContainer.hasClass('active')) {
        menuControll.removeClass('active');
        menuContainer.removeClass('active');
      } else {
        menuControll.addClass('active');
        menuContainer.addClass('active');
      }
    });
  }

  function init() {
    $(function () {
      setMenuProps();
      setRGBColorInPalettes();
      setColors();
      initFullPage();
      bindMenu();
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
