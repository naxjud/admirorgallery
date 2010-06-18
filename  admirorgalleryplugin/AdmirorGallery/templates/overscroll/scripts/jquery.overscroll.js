/*
 * Copyright (C) 2009 Jonathan Azoff <jon@azoffdesign.com>
 *
 * This script is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation; either version 2, or (at your option) any
 * later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 */
 
 // changelog 1.0.1: Fixed bug with interactive elements and made scrolling smoother (thanks Paul and Aktar) 
 
 // OverScroll v1.0.1 - A jQuery plugin that turns any DOM element with overflow and at least one child into a scrollable (iphone-like) interface.  
 // Usage: 	call $("selector").overscroll() on any element to turn it into a scroller. 
 //			**See http://azoffdesign.com/plugins/js/overscroll for an example.
 // Arguments: None. Currently, the plugin does not take any arguments.
 // Returns: A jQuery object that represents the scrollable container(s)
 // Notes:	In order to get the most out of this plugin, make sure to only apply it to parent elements 
 //			that are smaller than the collective width and/or height then their children. This way,
 //			you can see the actual scroll effect as you pan the element.
 // Dependencies: You MUST have two cursors to get the hand to show up, open, and close during the panning 
 //				  process. You can put the cursors wherever you want, just make sure to reference them in 
 //				  the code below. I have provided cursors that I used in the aforementioned exapmle 
 //				  at http://azoffdesign.com/demos/scripts/overscroll/jquery.overscroll.zip for your 
 //				  convenience.

(function($) {

	// cursor locations
	var open_hand = "url(data:image/x-icon;base64,AAACAAEAICAQAAgAAQDoAgAAFgAAACgAAAAgAAAAQAAAAAEABAAAAAAAgAIAAAAAAAAAAAAAEAAAABAAAAAAAAAAAACAAACAAAAAgIAAgAAAAIAAgACAgAAAgICAAMDAwAAAAP8AAP8AAAD//wD/AAAA/wD/AP//AAD///8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA////AAAAAAAAAAAAAAAAAP///wAAAAAAAAAAAAAAAAD///8AAAAAAAAAAAAAAAAA////AAAAAAAAAAAAAAAAAP///wAAAAAAAAAAAAAAAAD///8AAAAAAAAAAAAAAAAP////AAAAAAAAAAAAAAAA//////AAAAAAAAAAAAAAD//////wAAAAAAAAAAAAAA///////wAAAAAAAAAAAAD///////8AAAAAAAAAAAAP/w//////AAAAAAAAAAAAD/AP//////AAAAAAAAAAAAAAD////w/wAAAAAAAAAAAAAP8P8P8P8AAAAAAAAAAAAAD/D/D/APAAAAAAAAAAAAAP8A/w/wAAAAAAAAAAAAAAD/AP8P8AAAAAAAAAAAAAAAAAD/AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD////////////////////////////////////////////////////////////////4B///+Af///gH///4B///+Af///gH///wB///4AP//8AD///AAf//gAH//wAB//8AAP//kAD//+AA///gAP//wAX//8AH///kD////n///w==), default";
	var closed_hand = "url(data:image/x-icon;base64,AAACAAEAICAQAAgABgDoAgAAFgAAACgAAAAgAAAAQAAAAAEABAAAAAAAgAIAAAAAAAAAAAAAEAAAABAAAAAAAAAAAACAAACAAAAAgIAAgAAAAIAAgACAgAAAgICAAMDAwAAAAP8AAP8AAAD//wD/AAAA/wD/AP//AAD///8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA////AAAAAAAAAAAAAAAAAP///wAAAAAAAAAAAAAAAAD///8AAAAAAAAAAAAAAAAA////AAAAAAAAAAAAAAAAAP///wAAAAAAAAAAAAAAAAD///8AAAAAAAAAAAAAAAAP////AAAAAAAAAAAAAAAA//////AAAAAAAAAAAAAAD//////wAAAAAAAAAAAAAA///////wAAAAAAAAAAAAAAD/////8AAAAAAAAAAAAAAA//////AAAAAAAAAAAAAAD/////DwAAAAAAAAAAAAAA/w/w/wAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD////////////////////////////////////////////////////////////////4B///+Af///gH///4B///+Af///gH///wB///4AP//8AD///AAf//4AH///AB///gAf//4AP///JP/////////////////////////////w==), default";
	
	// the overscroll handler
	jQuery.fn.overscroll = function()
	{
		this.each(function(){
						   
			var lastPos = {};
			var capPos = {};
			var capTime = false;
			var capInterval = false;
				
			$(this)
				.css({"cursor":open_hand, "overflow":"hidden"})
				.select(startDrag)
				.mousedown(startDrag)
				.mouseup(endDrag)
				.mouseleave(endDrag);

			// begins the drag and sets the timer
			function startDrag(e){
				var tag = e.target.tagName.toLowerCase();
				if ( (/input|textarea|select|a/).test(tag) ) return true; // check for interactive elements (thanks Paul!)
				var elm = jQuery(this).css("cursor", closed_hand).stop(true, true).mousemove(doDrag);
				lastPos.x = e.pageX;
				lastPos.y = e.pageY;
				capInterval = setInterval(capturePosition, 50);
				return false;
			}
			
			// scrolls the element while dragging
			function doDrag(e){
				this.scrollLeft -= e.pageX - lastPos.x;
				this.scrollTop  -= e.pageY - lastPos.y;
				lastPos.x = e.pageX;
				lastPos.y = e.pageY;
				return true;
			}
			
			// ends the drag, unbinds the drag handler, and applies the slide
			function endDrag(e){
				$(this).css("cursor", open_hand).unbind("mousemove",doDrag);
				clearInterval(capInterval);
				if(capTime) applySlide(this, e);
				return true;
			}
			
			//applies the slide after the drag ends
			function applySlide(domElement, e)
			{
				var elm = jQuery(domElement).stop(true, true);
				var dt = timer() - capTime;
				var vx = (e.pageX - capPos.x) / dt;
				var vy = (e.pageY - capPos.y) / dt;
				var ax = (vx > 0) ? -0.005 : 0.005;
				var ay = (vy > 0) ? -0.005 : 0.005;
				var dx = -(vx*vx) / 2 / ax;
				var dy = -(vy*vy) / 2 / ay;
				var v = Math.sqrt(vx*vx + vy*vy); 
				var a = Math.sqrt(ax*ax + ay*ay); 

				elm.animate({
					scrollTop: (domElement.scrollTop - dy), 
					scrollLeft: (domElement.scrollLeft - dx)
				},
				
				// easing added to scroll, big thanks to aktar!
				{ queue:false, duration:800, easing:"cubicEaseOut" }, 
				
				Math.abs(v/a));
				
				capTime = false;
			}
			
			// captures the current position and time
			function capturePosition()
			{
				capPos.x = lastPos.x;
				capPos.y = lastPos.y;
				capTime  = timer();
			}
			
			// returns current time in milliseconds
			function timer()
			{
				return (new Date()).getMilliseconds();
			}
			
		});
		
		return this;
	}
	
})(jQuery);
