(function($){

    $.fn.floatBanner = function(options) {
        var settings = {
            panel : 'panel_float',
            position : 'position_float',
            content : 'content_float'
        }
        
        var initFloat = function(self) {
            var parent_float = self.parent();
            
            var panel = $('<div class="'+settings.panel+'" id="'+settings.panel+'"></div>');
			
            panel.append($('<div class="'+settings.position+'" id="'+settings.position+'"></div>'));
			
            var content = $('<div class="'+settings.content+'" id="'+settings.content+'"></div>');
            content.append(self);
			
            panel.append(content);
			
            parent_float.prepend(panel);
			
            $(window).scroll(function(){
                if  ($(window).scrollTop() > $("#"+settings.position).offset().top){
                    $("#"+settings.content).css("position", "fixed");
                    $("#"+settings.content).css("background", "rgba(0, 0, 0, 0.5)");
                    $("#"+settings.content).css("opacity", "0.5");
                    $("#"+settings.content).css("top", "0");
                }
                if  ($(window).scrollTop() <= $("#"+settings.position).offset().top){
                    $("#"+settings.content).css("position", "relative");
                    $("#"+settings.content).css("background", "#fff");
                    $("#"+settings.content).css("opacity", "1");
                    $("#"+settings.content).css("top", $(".position_button").offset);
                }
            });
            $("#"+settings.content).hover(function() {
                $("#"+settings.content).css("opacity", "1");
            }, function() {
                if($(this).css('position') != 'relative' && $(this).css('position') != 'static')
                    $("#"+settings.content).css("opacity", "0.5");
            });
        }
        
        return this.each(function() {
            if (options) { 
                $.extend(settings, options);
            }
			            
            initFloat($(this));
        });

    };
})(jQuery);