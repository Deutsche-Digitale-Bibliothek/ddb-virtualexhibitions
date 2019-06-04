(function ($, options, litfassColorPalettes) {

  'use strict';

  var menuProps;
  var headerHeight = 43;
  var menuScroll;
  var zoomHintShown = false;
  var slideHeightOffset = 43;

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
      slidesNavigation: false,
      // slidesNavPosition: 'bottom',

      // Scrolling
      // css3: true,
      // scrollingSpeed: 1500,
      autoScrolling: false,
      fitToSection: true,
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
    // console.log(current.anchor, $('#menu .active').data('menuanchor'));
    setVisitedSectionsInHeaderSectionBar(current.index);
    scrollMenu(current.anchor);
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
    setTableCellHeight();
    setSlideControls();

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
    scrollMenu($('#menu .active').data('menuanchor'));
  }

  function fpAfterSlideLoad(section, origin, destination, direction) {
    // console.log('fpAfterSlideLoad', section, origin, destination, direction);
    // console.log($(destination.item), $(destination.item).data('slideno'));
    var parent = $(destination.item).parents('.section-slides');
    $('.currentSlide', parent).html($(destination.item).data('slideno'));
  }

  function fpOnSlideLeave(section, origin, destination, direction) {
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
    // console.log('setTableCellHeight');
    if (isResponsive) {
      $('.fp-tableCell').css('height', '100%');
    } else {
      $('.fp-tableCell').each(function() {
        var tableCell = $(this);
        // console.log(tableCell.parent('.slide'));
        if (tableCell.parent('.slide').length > 0) {
          // console.log(tableCell.parents('.section').height(), (tableCell.parents('.section').height() - headerHeight - slideHeightOffset));
          tableCell.css('height', function () {
            return (tableCell.parents('.section').height() - slideHeightOffset) + 'px';
          });
        } else {
          tableCell.css('height', function () {
            return (tableCell.parents('.section').height()) + 'px';
          });
        }
      });
      // $('.fp-tableCell').css('height', function () {
      //   return ($(this).parent('.section').height() - headerHeight) + 'px';
      // });
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
    // console.log('setMediaProps');

    var mediaItemMaxHeight = height;

    // subtract individual media-item-caption height
    $('.media-item').each(function () {
      var $mediaItem = $(this);
      var $caption = $('.media-item-caption', $(this).parent('.media-item-container'));
      var captionHeight= $caption.height();
      if (captionHeight) {
        mediaItemMaxHeight -= captionHeight;
      }
      if ($mediaItem.parents('.slide').length) {
        mediaItemMaxHeight -= slideHeightOffset;
        // console.log($mediaItem);
      }
      $mediaItem.css({ 'max-height': mediaItemMaxHeight + 'px' });
    });

    $('.media-item-3d-thumb').each(function () {
      var $mediaItem = $(this);
      var $caption = $('.media-item-caption', $(this).parent('.media-item-container'));
      var captionHeight= $caption.height();
      if (captionHeight) {
        mediaItemMaxHeight -= captionHeight;
      }
      if ($mediaItem.parents('.slide').length) {
        mediaItemMaxHeight -= slideHeightOffset;
        // console.log($mediaItem);
      }
      $mediaItem.css({ 'max-height': mediaItemMaxHeight + 'px' });
    });

    $('.media-audio-image').each(function () {
      var $mediaItem = $(this);
      var $caption = $('.media-item-caption', $(this).parents('.media-item-container'));
      var captionHeight= $caption.height();
      if (captionHeight) {
        mediaItemMaxHeight -= captionHeight;
      }
      if ($mediaItem.parents('.slide').length) {
        mediaItemMaxHeight -= slideHeightOffset;
        // console.log($mediaItem);
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
      if (typeof litfassColorPalettes[palette] !== 'undefined' &&
        typeof litfassColorPalettes[palette][color] !== 'undefined' &&
        section.hasClass('slide'))
      {
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
      $(this).find('g').toggleClass('active');
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

  function bindEmptyClick() {
    $(document).click(function(e) {
      if($(e.target).closest('#menu-container').length <= 0 &&
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
    var panzoomInstance;
    var caller = $(this);
    // console.log(caller);
    var container = $('<div id="zoom-container" class="zoom-container"></div>');
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
      zoomHintShown = true;
      zoomHint.on('click', function() {
        $(this).remove();
      });
    } else {
      container.append(closer, spinner);
    }
    $('body').append(container);

    $('<img src="' + caller.data('zoom') + '" alt="' + caller.attr('alt') + '">')
      .on('load', function() {
        spinner.remove();
        var image = $(this);
        container.append(image);
        var initZoomPos = getInitZoomPos(caller);
        var minZoom = getMinZoom(caller);
        panzoomInstance = panzoom(image[0], {
          smoothScroll: false,
          maxZoom: 1,
          minZoom: minZoom,
          onTouch: function(e) {
            var discardClasses = ['zoom-hint-container', 'zoom-close'];
            for (var i = 0; i < e.path.length; i++) {
              if (discardClasses.indexOf(e.path[i]['className']) !== -1) {
                // if false, tells the library not to
                // preventDefault and not to stopPropageation:
                return false;
              }
            }
            return true;
          },
          onDoubleClick: function(e) {
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

    // toggle cursor - does not work in chrome:
    // container.on('mousedown', function (e) {
    //   $(this).addClass('active');
    // });
    // container.on('mouseup', function (e) {
    //   $(this).removeClass('active');
    // });

    container.trigger('click').trigger('focus');
    closer.on('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      panzoomInstance.dispose();
      $(this).off('click');
      container.remove();
    });
    $(document).on('keydown.zoom', function(e) {
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
    $('.control-zoom').bind('click', function() {
      $('.content-media .media-item', $(this).parents('.container-media')).trigger('click');
    });
  }

  function generate3D() {
    var caller = $(this);
    var url = caller.data('3durl');
    var container = $('<div id="zoom-container" class="zoom-container"></div>');
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

    closer.on('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      $(this).off('click');
      container.remove();
    });
  }

  function bind3D() {
    $('img.media-item-3d-thumb').bind('click', generate3D);
    $('img.item-3d-thumb-icon').bind('click', function() {
      $(this).siblings('img.media-item-3d-thumb').trigger('click');
    });
    $('.control-zoom').bind('click', function() {
      $('.content-media .media-item-3d-thumb', $(this).parents('.container-media')).trigger('click');
    });
  }

  function setSlideControls() {
    $('.section-slides').each(function() {
      var controllWrapper = $('<div class="slideControlWrapper"></div>');
      $('.fp-prev', this).appendTo(controllWrapper);
      $(this).append(controllWrapper);
      var numSliders = $('.slide', this).length;
      var slidesInfo = $('<div class="slidesInfo"><span class="currentSlide">1</span> / ' + numSliders + '</div>');
      slidesInfo.appendTo(controllWrapper);
      $('.fp-next', this).appendTo(controllWrapper);
    });
  }

  function init() {
    $(function() {
      setMenuProps();
      setRGBColorInPalettes();
      setColors();
      initScrollMenu();
      initFullPage();
      bindMenu();
      setScrollElementMaxHeight();
      bindSCrollControls();
      toggleScrollControls();
      setMediaProps();
      bindMediaInfo();
      bindTitlePageNextLink();
      bindZoom();
      bind3D();
      bindEmptyClick();
    });
  }

  init();

})(jQuery, window.litfassOptions, window.litfassColorPalettes);
