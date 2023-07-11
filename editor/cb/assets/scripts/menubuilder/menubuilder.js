/*
MenuBuilder.js
*/
(function (jQuery) {

    jQuery.menubuilder = function (element, options) {
        
        var defaults = {
			data: {"menu":[
				{"name": "Home", "link": "", "sub": null},
				{"name": "Products", "link": null, "sub": [
					{"name": "Product One","link": "product1", "sub": null},
					{"name": "Product Two","link": "product2", "sub": null},
					{"name": "Product Three","link": "product3", "sub": null}
					]},
				{"name": "Support", "link": "support", "sub": null},        
				{"name": "About", "link": "about","sub": null}
				
			],
			onClick: function(){}
			}
        };

        this.settings = {};

        var $element = jQuery(element),
                    element = element;

        this.init = function () {
		
            this.settings = jQuery.extend({}, defaults, options);		
			
			$element.append('<ul class="menutree"></ul>')

			jQuery.each(this.settings.data.menu, function () {
		
				jQuery('.menutree').append(
					getMenuItem(this)
				);
				
			});

			jQuery('.menutree').addClass('sortable');	
			
			$('ul.sortable').sortable({
				connectWith: 'ul.sortable',
				placeholder: 'placeholder',
				deactivate: function(e){
				
				}
			}).disableSelection();
			
		};

		this.getMenu = function () {
			return JSON.parse('{"menu":' + JSON.stringify($element.data('menubuilder').parseMenu()) + '}');			
		};
		 
		this.parseMenu = function (){
			jsonObj = [];
			jQuery('.menutree').children().each(function(){
				item = {}
				item ["name"] = $(this).attr('data-name');
				item ["link"] = $(this).attr('data-link');
				item ["html"] = $(this).attr('data-html');
				item ["classname"] = $(this).attr('data-classname');
				item ["sub"] = [];
				jsonObj.push(item);
				var $ul = $(this).children('ul').first();
				if($ul.children('li').length>0){
					$element.data('menubuilder').parseMenuItem($ul, item ["sub"]);
				}
			});
			return jsonObj;
		}
		
		this.parseMenuItem = function ($ul,sub){
			$ul.children().each(function(){
				item = {}
				item ["name"] = $(this).attr('data-name');
				item ["link"] = $(this).attr('data-link');
				item ["html"] = $(this).attr('data-html');
				item ["classname"] = $(this).attr('data-classname');
				item ["sub"] = [];
				sub.push(item);
				var $ul = $(this).children('ul').first();
				if($ul.children('li').length>0){
					$element.data('menubuilder').parseMenuItem($ul, item ["sub"]);
				}
			});
		}
		
		this.init();

    };

    jQuery.fn.menubuilder = function (options) {
        return this.each(function () {

            if (undefined == jQuery(this).data('menubuilder')) {
                var plugin = new jQuery.menubuilder(this, options);
                jQuery(this).data('menubuilder', plugin);

            }

        });
    };
})(jQuery);

function getMenuItem (itemData) {
	var item = $('<li>');
	item.html('<span>' + itemData.name + '</span>');
	item.attr('data-name',itemData.name); 
	item.attr('data-link',itemData.link); 
	item.attr('data-classname',itemData.classname); 

	if(itemData.html) item.attr('data-html',itemData.html); // already encoded
	else item.attr('data-html','');

	var subList = $('<ul class="sortable">');
	item.append(subList);
	
	if (itemData.sub) {
		$.each(itemData.sub, function () {
			subList.append(getMenuItem(this));
		});
	}
	return item;
};

/*! jQuery UI Touch Punch 0.2.3 | Copyright 2011–2014, Dave Furfero | Dual licensed under the MIT or GPL Version 2 licenses. */
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('(7(4){4.w.8=\'H\'G p;c(!4.w.8){f}d 6=4.U.D.L,g=6.g,h=6.h,a;7 5(2,r){c(2.k.F.J>1){f}2.B();d 8=2.k.q[0],l=p.N(\'O\');l.S(r,i,i,V,1,8.W,8.X,8.Y,8.A,b,b,b,b,0,C);2.z.E(l)}6.m=7(2){d 3=e;c(a||!3.I(2.k.q[0])){f}a=i;3.j=b;5(2,\'K\');5(2,\'s\');5(2,\'M\')};6.n=7(2){c(!a){f}e.j=i;5(2,\'s\')};6.o=7(2){c(!a){f}5(2,\'P\');5(2,\'Q\');c(!e.j){5(2,\'R\')}a=b};6.g=7(){d 3=e;3.u.T({v:4.9(3,\'m\'),x:4.9(3,\'n\'),y:4.9(3,\'o\')});g.t(3)};6.h=7(){d 3=e;3.u.Z({v:4.9(3,\'m\'),x:4.9(3,\'n\'),y:4.9(3,\'o\')});h.t(3)}})(4);',62,62,'||event|self|jQuery|simulateMouseEvent|mouseProto|function|touch|proxy|touchHandled|false|if|var|this|return|_mouseInit|_mouseDestroy|true|_touchMoved|originalEvent|simulatedEvent|_touchStart|_touchMove|_touchEnd|document|changedTouches|simulatedType|mousemove|call|element|touchstart|support|touchmove|touchend|target|clientY|preventDefault|null|mouse|dispatchEvent|touches|in|ontouchend|_mouseCapture|length|mouseover|prototype|mousedown|createEvent|MouseEvents|mouseup|mouseout|click|initMouseEvent|bind|ui|window|screenX|screenY|clientX|unbind'.split('|'),0,{}));
	
