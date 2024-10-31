/*!
 * jQuery Simple Rotator Div Plugin
 * Copyright (c) 2010-2013 ENE Catalin
 * Version: 3.0.3 (11-JUL-2013)
 * Dual licensed under the MIT and GPL licenses.
 * Requires: jQuery v1.7.1 or later
 */
(function($) {
	$.fn.simpleRotatorDiv = function( options ) {
		var interval;
		var config = $.extend({
            itemContainer: ".item",
            itemActiveContainer: ".item-active",
            
            navigationContainer: ".navigation",
            navigationItemContainer: ".item-navigation",
            navigationItemActiveContainer: ".item-navigation-active",
            navigationType: "default",
            
            speed: 1000,
            transition: 500,
            containerHeight: 0,
            autoplay: false
        }, options );
		
		config.itemActiveContainerWp = config.itemActiveContainer.replace('.', '');
		config.navigationItemContainerWp = config.navigationItemContainer.replace('.', '');
		config.navigationItemActiveContainerWp = config.navigationItemActiveContainer.replace('.', '');
		config.here = this;
		
		
		if (config.containerHeight > 0)
		{
			this.height( config.containerHeight );
		}
		
    	$( config.itemContainer, this ).first().addClass(config.itemActiveContainerWp).show();
		
		if ($( config.itemContainer, this ).length > 1)
	    {
			for (var i = 1; i <= $( config.itemContainer, this ).length; i++ )
			{
				if (config.navigationType == 'default')
				{
					$(config.navigationContainer).append('<span class="' + config.navigationItemContainerWp + '"></span>');
				}
				else if (config.navigationType == 'numeric')
				{
					$(config.navigationContainer).append('<span class="' + config.navigationItemContainerWp + '">' + i + '</span>');
				}
			}
			    	
	    	$( config.navigationItemContainer, config.navigationContainer ).first().addClass( config.navigationItemActiveContainerWp ); // navigation
	    	
	    	if ( config.autoplay === true )
	    	{
	    		autoplay();
	    	}
	    }
		
		$(config.navigationContainer).on('click', config.navigationItemContainer, function() {
			clearInterval(interval);
			var position = $(this).index();
			
			$( config.navigationItemContainer, config.navigationContainer ).removeClass( config.navigationItemActiveContainerWp );
			$(this).addClass(config.navigationItemActiveContainerWp);
			
			$( config.itemActiveContainer, config.here ).removeClass( config.itemActiveContainerWp ).hide();
	    	$( config.itemContainer, config.here ).eq(position).first().addClass( config.itemActiveContainerWp ).fadeIn(config.transition);
			
			autoplay();
		});
		
		function autoplay()
		{
			interval = setInterval(function(){
	    		if ( $( config.itemActiveContainer, config.here ).next().length > 0)
				{
	        		$( config.itemActiveContainer , config.here).hide().removeClass( config.itemActiveContainerWp ).next().addClass(config.itemActiveContainerWp).fadeIn(config.transition);
	        		
	        		$( config.navigationItemActiveContainer, config.navigationContainer ).removeClass( config.navigationItemActiveContainerWp ).next().addClass( config.navigationItemActiveContainerWp ); // navigation
				}
	    		else
				{
	    			$( config.itemActiveContainer, config.here ).removeClass( config.itemActiveContainerWp ).hide();
	    	    	$( config.itemContainer, config.here ).first().addClass( config.itemActiveContainerWp ).fadeIn(config.transition);
	    	    	
	        		$( config.navigationItemActiveContainer, config.navigationContainer ).removeClass( config.navigationItemActiveContainerWp ); // navigation
	    	    	$( config.navigationItemContainer, config.navigationContainer ).first().addClass( config.navigationItemActiveContainerWp ); // navigation
	        		
				}
	    	}, config.speed);
		}
	};
}(jQuery));