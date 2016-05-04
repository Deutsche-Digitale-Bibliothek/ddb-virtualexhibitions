/*
* rwdImageMaps jQuery plugin v1.5
*
* Allows image maps to be used in a responsive design by recalculating the area
* coordinates to match the actual image size on load and window.resize.
* 
* Uses now data-mediawidth and data-mediaheight attribs to determine basic w and h.
* There is no need to double loed the image to determine its w and h now.
* This was added by Grandgeorg Websolutions
*
* Copyright (c) 2014 Matt Stow and addition by Viktot Grandgeorg
*
* https://github.com/stowball/jQuery-rwdImageMaps
*
* Licensed under the MIT license
*/
!function ($) {
    $.fn.rwdImageMaps = function (newWidth, newHeight) {
        var $img = this,
            rwdImageMap = function() {
            $img.each(function() {

                // console.log('called rwdImageMap');

                if (typeof($(this).attr('usemap')) === 'undefined') {
                    return;
                }
                
                var that = this,
                    $that = $(that);

                var attrW = 'width',
                    attrH = 'height',
                    w = that.dataset.mediawidth,
                    h = that.dataset.mediaheight;

                    if (!w) {
                        w = $that.attr(attrW);
                    }
                    if (!h) {
                        h = $that.attr(attrH);
                    }

                
                if (!w || !h) {
                    var temp = new Image();
                    temp.src = $that.attr('src');
                    if (!w) {
                        w = temp.width;
                    }
                    if (!h) {
                        h = temp.height;
                    }
                }
                
                var wPercent = $that.width()/100,
                    hPercent = $that.height()/100,
                    map = $that.attr('usemap').replace('#', ''),
                    c = 'coords';

                if ($that.width() == 0 || $that.height() == 0) {
                    wPercent = newWidth/100;
                    hPercent = newHeight/100;
                }
                // console.log(w + ' --- ' + $that.width() + '***');

                $('map[name="' + map + '"]').find('area').each(function() {
                    var $this = $(this);
                    if (!$this.data(c)) {
                        $this.data(c, $this.attr(c));
                    }
                    
                    var coords = $this.data(c).split(','),
                        coordsPercent = new Array(coords.length);
                    
                    for (var i = 0; i < coordsPercent.length; ++i) {
                        if (i % 2 === 0) {
                            coordsPercent[i] = parseInt(((coords[i]/w)*100)*wPercent);
                        } else {
                            coordsPercent[i] = parseInt(((coords[i]/h)*100)*hPercent);
                        }
                    }
                    $this.attr(c, coordsPercent.toString());
                });

            });
        };
        rwdImageMap();
        // $(window).resize(rwdImageMap);
        return this;
    }
}(window.jQuery);