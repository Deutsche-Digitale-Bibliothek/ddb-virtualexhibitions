$(function() {
    Tooltip = function(element){
        this.init(element);
    }
    
    $.extend(Tooltip.prototype,{
        
        hint: null,
        tooltip: null,
        
        opened: false,
        lock:false,
        
        hoverTime: 0,
        hoverTimeout:300,
    
        init: function(element){
            var currObjInstance = this;
            this.hint = element.attr("data-content");
            this.tooltip = element.siblings("div.tooltip");
            element.removeAttr("title");
            this.tooltip.html(this.hint).text();
            if(this.tooltip.hasClass('hasArrow')){
              var arrow = $(document.createElement('div'));
              arrow.addClass('arrow');
              arrow.appendTo(this.tooltip);
            }
            this.tooltip.hide();
            this.tooltip.removeClass("off");
            element.hover(function(){
                var d = new Date();
                currObjInstance.hoverTime = d.getTime();
                currObjInstance.open();
            });
            this.tooltip.mouseenter(function(){
                currObjInstance.lock = true;
            });
            this.tooltip.mouseleave(function(){
                currObjInstance.close();
            });
            element.mouseleave(function(){
                setTimeout(function(){
                    var currentD = new Date();
                    if(!currObjInstance.lock && currObjInstance.hoverTime+currObjInstance.hoverTimeout-100<currentD.getTime())
                        currObjInstance.close();
                },currObjInstance.hoverTimeout);
            });
        },
        open: function(){
            var currObjInstance = this;
            if (!this.opened) {
                this.opened = true;
                this.tooltip.fadeIn('fast');
            }
        },
        close: function(){
            this.tooltip.fadeOut('fast');
            this.opened = false;
            this.lock = false;
        }
    });
    
    $("span.contextual-help").each(function(){
        new Tooltip($(this));
    });
});