jQuery(function($){
	jQuery.extend(jQuery.fn.disableTextSelect = function() {
		return this.each(function(){
			if(jQuery.browser.mozilla){//Firefox
				jQuery(this).css('MozUserSelect','none');
			}else if(jQuery.browser.msie){//IE
				jQuery(this).bind('selectstart',function(){return false;});
			}else{//Opera, etc.
				jQuery(this).mousedown(function(){return false;});
			}
		});
	});
	jQuery('.noSelect').disableTextSelect();//No text selection on elements with a class of 'noSelect'
});
