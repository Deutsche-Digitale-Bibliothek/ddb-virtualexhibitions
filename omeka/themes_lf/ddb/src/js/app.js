(function ($) {

  'use strict';

  var menuProps;

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

      licenseKey: 'OPEN-SOURCE-GPLV3-LICENSE',

      // Navigation
      menu: '#menu',
      // lockAnchors: false,
      anchors: ['s0', 's2', 's3'],
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
      sectionsColor: ['#fcc', '#ccf', '#ffc'],
      paddingTop: '38px',
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

  function fpAfterLoad(origin, destination, direction) {
    // console.log('loaded');
  }

  function fpAfterRender() {

  }

  function fpAfterResize(width, height) {
    // console.log('resize - height is wrong somehow', width, height);
    setScrollElementMaxHeight();
    toggleScrollControlls();
    setMediaItemProps();
  }

  function fpAfterResponsive(isResponsive) {

    // console.log('responsive', isResponsive);
    setTableCellHeight(isResponsive);
    if (isResponsive) {
      // $('.fp-tableCell').removeAttr('style');
      $('.fp-tableCell').attr('style', 'height: 100%');
    } else {
      $('.fp-tableCell').attr('style', 'height: ' + $('.fp-tableCell').parent('.section').height() + 'px');
    }
  }

  function fpAfterSlideLoad(section, origin, destination, direction) {

  }

  function fpOnSlideLeave(section, origin, destination, direction) {

  }

  function setTableCellHeight(isResponsive) {
    if (isResponsive) {
      $('.fp-tableCell').removeAttr('style');
    } else {
      $('.fp-tableCell').attr('style', 'height: ' + $('.fp-tableCell').parent('.section').height() + 'px');
    }
  }

  function setScrollElementMaxHeight() {
    var scrollElement = $('.scroll-element');
    // console.log($(window).width());
    if ($(window).width() < 768) {
      scrollElement.css({
        'max-height': '100%',
        'padding-right': '0'
      });
    } else {
      scrollElement.css({
        // 'max-height': scrollElement.parents('.scroll-container').height() + 'px', // parent is misscalleced by fullpage.js
        'max-height': scrollElement.parents('.scroll-container').height() + 'px',
        'padding-right': (scrollElement[0].offsetWidth - scrollElement[0].clientWidth) + 'px'
      });
    }
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

  function bindSCrollControlls() {
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

  function toggleScrollControlls() {
    // we could also use .text-content instead of .scroll-element
    $('.scroll-container').each(function (index) {
      var scrollContainer = $(this);
      if ($('.scroll-element', scrollContainer).height() < scrollContainer.height()) {
        $('.scroll-controllers', scrollContainer).addClass('d-none');
      } else {
        $('.scroll-controllers', scrollContainer).removeClass('d-none');
      }
    });
  }

  function setMediaItemProps(height) {
    if (typeof height === 'undefined') {
      height = ($(window).height() - menuProps.height) + 'px';
    } else {
      height = (height - menuProps.height) + 'px';
    }
    $('.media-item').css({
      'max-height': height
    });
  }

  function setMenuProps() {
    menuProps = {
      height: $('#menu').height()
    };
  }

  function init() {
    $(function () {
      setMenuProps();
      initFullPage();
      setScrollElementMaxHeight();
      bindSCrollControlls();
      toggleScrollControlls();
      setMediaItemProps();
    });
  }

  init();

})(jQuery);
