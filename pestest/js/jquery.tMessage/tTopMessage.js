/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


(function($){
    $.fn.extend({ 

        tTopMessage: function(options) {

            //Các giá trị mặc định
            var defaults = {
                load : false,
                type : 'success', /* success - loading - info - error - warning*/
                text : 'MCH Loading..',
                effect : 'slide', /*fade - slide*/
                delay : false, /* sec */
                parent : 'div#tTopMessage-parent',
                child : 'div#tTopMessage-child'
            };
            
            var init = function() {
                $(defaults.child).addClass('tTopMessage-' + defaults.type);
                
                switch(defaults.effect) {
                    case 'fade' :
                        if(defaults.load) {
                            $(defaults.parent).css('top','0px');
                            $('mch_loading').fadeIn('fast',function() {                              
                                $(this).html('<span>' + defaults.text + '</span>');
                                $(defaults.parent).fadeIn('fast');
                            });
                        } else {
                            $(defaults.parent).fadeOut('fast', function() {
                                $(defaults.child).fadeOut('fast',function() {
                                    $(this).html('');
                                });
                            });
                        }
                        break;
                    case 'slide':
                        if(defaults.load) {
                            $(defaults.parent).css('top','-40px');
                            $(defaults.parent).css('display','block');
                            $(defaults.child).fadeIn('fast',function() {
                                $(this).html('<span>' + defaults.text + '</span>');
                            });
                            $(defaults.parent).animate({
                                top : '0px'
                            },'normal');
                            
                            if(defaults.delay != false) {
                                $(defaults.parent).delay(defaults.delay).animate({
                                    top : '-40px'
                                },'normal',function() {
                                    $(defaults.parent).fadeOut('fast', function() {
                                        $(defaults.child).fadeOut('fast',function() {
                                            $(this).html('');
                                        });
                                    });
                                });
                            }
                            
                        } else {
                            $(defaults.parent).animate({
                                top : '-40px'
                            },'normal',function() {
                                $(defaults.parent).fadeOut('fast', function() {
                                    $(defaults.child).fadeOut('fast',function() {
                                        $(this).html('');
                                    });
                                });
                            });
                        }
                        break;
                }
                
            }
		
            return this.each(function() {
                if (options) { 
                    $.extend( defaults, options );
                }
                init();
            });
        }
    });
})(jQuery);
