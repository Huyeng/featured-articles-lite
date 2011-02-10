/**
 * FeaturedArticles - article rotator
 *
 * This script is part of a Wordpress plugin that is available here: http://www.php-help.ro/mootools-12-javascript-examples/wordpress-featured-content-plugin/
 * Plugin is completely free and very flexible, allowing new themes to be developed for it and plenty of options directly from WP admin.
 * The script is developed using MooTools 1.2 (http://www.mootools.net).
 *
 * @version		2.0
 * @license		MIT-style license
 * @author		Constantin Boiangiu <constantin [at] php-help.ro>
 * @copyright	Author
 */
 (function(){var B=document.id;this.FeaturedArticles=new Class({Implements:[Options],options:{container:null,slides:null,slideDuration:null,effectDuration:1000,fadeDist:null,fadePosition:null,stopSlideOnClick:true,autoSlide:true,mouseWheelNav:true},initialize:function(A){this.setOptions(A);if(!B(this.options.container)||!this.options.slides){return}this.currentKey=0;this.container=B(this.options.container);this.start()},start:function(){this.prepareSlides();this.injectNavigation();if(this.options.autoSlide){this.period=this.startSlides.bind(this).periodical(this.options.slideDuration||5000);this.container.addEvents({mouseenter:function(A){if($defined(this.period)){$clear(this.period)}}.bind(this),mouseleave:function(A){if($defined(this.period)){$clear(this.period)}this.period=this.startSlides.bind(this).periodical(this.options.slideDuration||5000)}.bind(this)})}if(!this.options.mouseWheelNav){return}this.container.addEvent("mousewheel",function(A){if($defined(this.period)){$clear(this.period)}if(A.wheel==1){var D=this.currentKey-1<0?this.slides.length-1:this.currentKey-1}else{if(A.wheel==-1){var D=this.currentKey+1>this.slides.length-1?0:this.currentKey+1}}A.preventDefault();this.goToSlide(D,A.wheel);if(!this.options.stopSlideOnClick&&this.options.autoSlide){this.period=this.startSlides.bind(this).periodical(this.options.slideDuration||5000)}}.bind(this))},prepareSlides:function(){this.slides=this.container.getElements(this.options.slides);this.container.getElements(".FA_image").each(function(K){var A=K.get("width");var I=K.get("height");K.removeProperties("width","height");var M=K.getSize();if(M.x<=A&&M.y<=I){return}var L=M.x/M.y;if(L<1){var N=I;var J=(A*L).toInt()}else{var J=A;var N=(I/L).toInt()}K.set({width:J,height:N});K.getParent().setStyles({"margin-top":(I-N)/2,"margin-left":(A-J)/2})});this.slides.set("morph",{wait:false,duration:this.options.effectDuration||800,transition:"cubic:out"});this.totalSlides=this.slides.length;this.slides.setStyles({position:"absolute",top:0,left:0,opacity:0});this.slides[this.currentKey].setStyle("opacity",1)},injectNavigation:function(){if(this.slides.length<2){return}this.navLinks=$$(".FA_navigation a");this.navLinks.each(function(G,H){var C=G.getParent().getElement("span");if(C){C.set("morph",{duration:300,wait:false})}G.addEvents({click:function(E){new Event(E).stop();if($defined(this.period)){$clear(this.period)}this.goToSlide(H);if(!this.options.stopSlideOnClick&&this.options.autoSlide){this.period=this.startSlides.bind(this).periodical(this.options.slideDuration||5000)}}.bind(this),mouseenter:function(){if(C){C.setStyles({display:"block",top:-25,opacity:0}).morph({opacity:1,top:-20})}},mouseleave:function(){if(C){C.setStyles({display:"none",opacity:0,top:-25})}}});if(H==this.currentKey){this.navLinks[H].addClass("active")}}.bind(this));var D=$$(".FA_back");var A=$$(".FA_next");D.addEvent("click",function(C){new Event(C).stop();if($defined(this.period)){$clear(this.period)}var F=this.currentKey-1>=0?this.currentKey-1:this.slides.length-1;this.goToSlide(F,1);if(!this.options.stopSlideOnClick&&this.options.autoSlide){this.period=this.startSlides.bind(this).periodical(this.options.slideDuration||5000)}}.bind(this));A.addEvent("click",function(C){new Event(C).stop();if($defined(this.period)){$clear(this.period)}var F=this.currentKey+1<this.slides.length?this.currentKey+1:0;this.goToSlide(F);if(!this.options.stopSlideOnClick&&this.options.autoSlide){this.period=this.startSlides.bind(this).periodical(this.options.slideDuration||5000)}}.bind(this))},startSlides:function(){var A=this.currentKey+1>=this.totalSlides?0:this.currentKey+1;this.goToSlide(A)},goToSlide:function(F,E){if(F==this.currentKey){return}if(!$defined(this.slides[F])){return}if(!$defined(E)){var E=-1}var A=this.options.fadePosition=="left"?"left":"top";switch(A){case"top":this.slides[this.currentKey].morph({opacity:0,top:[0,E*this.options.fadeDist]});this.slides[F].setStyle(A,-this.options.fadeDist).morph({opacity:1,top:[-E*this.options.fadeDist,0]});break;case"left":this.slides[this.currentKey].morph({opacity:0,left:[0,E*this.options.fadeDist]});this.slides[F].setStyle(A,-this.options.fadeDist).morph({opacity:1,left:[-E*this.options.fadeDist,0]});break}if($defined(this.navLinks[this.currentKey])){this.navLinks[this.currentKey].removeClass("active");this.navLinks[F].addClass("active")}this.currentKey=F}})})();window.addEvent("load",function(){var B=document.id;$$(FA_settings.container).each(function(A){FA_settings.container=A;new FeaturedArticles(FA_settings)})});