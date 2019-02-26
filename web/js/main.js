/*-----------------------------------------------------------------------------------*/
/* 		Mian Js Start 
/*-----------------------------------------------------------------------------------*/
$(document).ready(function($) {
"use strict"
/*-----------------------------------------------------------------------------------*/
/*    STICKY NAVIGATION
/*-----------------------------------------------------------------------------------*/
$(".sticky").sticky({topSpacing:0});
/*-----------------------------------------------------------------------------------*/
/*  ISOTOPE PORTFOLIO
/*-----------------------------------------------------------------------------------*/
var $container = $('.portfolio-wrapper .items');
   $container.imagesLoaded(function () {
   $container.isotope({
   itemSelector: '.item',
   layoutMode: 'fitRows'
  });
});
$('.filter li a').click(function () {
   $('.filter li a').removeClass('active');
   $(this).addClass('active');
   var selector = $(this).attr('data-filter');
   $container.isotope({
   filter: selector
   });
   return false;
});
/*-----------------------------------------------------------------------------------*/
/* 	MENU
/*-----------------------------------------------------------------------------------*/
$().ownmenu();
/*-----------------------------------------------------------------------------------*/
/* 	FLEX SLIDER
/*-----------------------------------------------------------------------------------*/
$('.flex-banner').flexslider({
    animation: "fade",
	slideshow: true,                //Boolean: Animate slider automatically
    slideshowSpeed: 6000,           //Integer: Set the speed of the slideshow cycling, in milliseconds
    animationSpeed: 500,            //Integer: Set the speed of animations, in milliseconds
	pauseOnHover: true,
	autoPlay : true
});
/*-----------------------------------------------------------------------------------*/
/* 	WOW ANIMATION
/*-----------------------------------------------------------------------------------*/
var wow = new WOW({
    boxClass:     'wow',      // animated element css class (default is wow)
    animateClass: 'animated', // animation css class (default is animated)
    offset:       10,          // distance to the element when triggering the animation (default is 0)
    mobile:       false,       // trigger animations on mobile devices (default is true)
    live:         true,       // act on asynchronously loaded content (default is true)
    callback:     function(box) {
}});
wow.init();
/*-----------------------------------------------------------------------------------*/
/*    Parallax
/*-----------------------------------------------------------------------------------*/
jQuery.stellar({
    horizontalScrolling: false,
    scrollProperty: 'scroll',
    positionProperty: 'position'
});
/*-----------------------------------------------------------------------------------*/
/*    BANNER SLIDER
/*-----------------------------------------------------------------------------------*/
$(".testi").owlCarousel({ 
    autoPlay: 5000, //Set AutoPlay to 6 seconds 
    items : 1,
	singleItem	: true,
    navigation : true, // Show next and prev buttons
	pagination : true,
	animateOut: 'fadeOut',
	navigationText: ["<i class='fa fa-angle-up'></i>","<i class='fa fa-angle-down'></i>"]
});
/*-----------------------------------------------------------------------------------*/
/*    POPUP VIDEO
/*-----------------------------------------------------------------------------------*/
$('.popup-vedio').magnificPopup({
		type: 'inline',
		fixedContentPos: false,
		fixedBgPos: true,
		overflowY: 'auto',
		closeBtnInside: true,
		preloader: true,
		midClick: true,
		removalDelay: 300,
		mainClass: 'my-mfp-slide-bottom'
});
$('.gallery-pop').magnificPopup({
	delegate: 'a',
	type: 'image',
	tLoading: 'Loading image #%curr%...',
	mainClass: 'mfp-img-mobile',
	gallery: {
		enabled: true,
		navigateByImgClick: true,
		preload: [0,1] // Will preload 0 - before current, and 1 after the current image
	},
	image: {
		tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
		titleSrc: function(item) {
			return item.el.attr('title') + '';
	}}
});
});

/*-----------------------------------------------------------------------------------*/
/*    BANNER SLIDER
/*-----------------------------------------------------------------------------------*/
$(".bnr-slider").owlCarousel({ 
    autoPlay: 5000, //Set AutoPlay to 6 seconds 
    items : 1,
	singleItem	: true,
    navigation : true, // Show next and prev buttons
	pagination : true,
	autoplay:true,
	autoplayTimeout:5000,
	autoplayHoverPause:false,
	animateOut: 'fadeOut',
	navigationText: ["<i class='fa fa-angle-up'></i>","<i class='fa fa-angle-down'></i>"]
});
/*-----------------------------------------------------------------------------------*/
/*    CONTACT FORM
/*-----------------------------------------------------------------------------------*/
function checkmail(input){
  var pattern1=/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
  	if(pattern1.test(input)){ return true; }else{ return false; }}     
    function proceed(){
    	var name = document.getElementById("name");
		var email = document.getElementById("email");
		var company = document.getElementById("company");
		var web = document.getElementById("website");
		var msg = document.getElementById("message");
		var errors = "";
		  if(name.value == ""){ 
		  	name.className = 'error';
		  return false;}    
		  else if(email.value == ""){
		  email.className = 'error';
		  return false;}
		    else if(checkmail(email.value)==false){
		        alert('Please provide a valid email address.');
		        return false;}
		    else if(company.value == ""){
		        company.className = 'error';
		        return false;}
		   else if(web.value == ""){
		        web.className = 'error';
		        return false;}
		   else if(msg.value == ""){
		        msg.className = 'error';
		        return false;}
		   else 
		  {
    	$.ajax({
			type: "POST",
			url: "php/submit.php",
			data: $("#contact_form").serialize(),
			success: function(msg){
			//alert(msg);
            if(msg){
                $('#contact_form').fadeOut(1000);
                $('#contact_message').fadeIn(1000);
                document.getElementById("contact_message");
            return true;
        }}
    });
}};
/*-----------------------------------------------------------------------------------*/
/*    BANNER FORM
/*-----------------------------------------------------------------------------------*/
function bannerProceed(){
  var name = document.getElementById("name");
  var email = document.getElementById("email");
  var phone = document.getElementById("phone");
  var msgform = document.getElementById("msgform");
  var errors = "";
    if(name.value == ""){ 
     name.className = 'error';
    return false;}    
    else if(email.value == ""){
    email.className = 'error';
    return false;}
      else if(checkmail(email.value)==false){
          alert('Please provide a valid email address.');
          return false;}
      else if(phone.value == ""){
    phone.className = 'error';
          return false;}
     else if(msgform.value == ""){
    msgform.className = 'error';
          return false;}
     else 
    {
     $.ajax({
   type: "POST",
   url: "php/banner-form.php",
   data: $("#banner_form").serialize(),
   success: function(msgform){
   //alert(msg);
            if(msgform){
                $('#banner_form').fadeOut(1000);
                $('#banner_message').fadeIn(1000);
                document.getElementById("banner_message");
            return true;
        }}
    });
}};


/*-----------------------------------------------------------------------------------*/
/*    Feature Slider
/*-----------------------------------------------------------------------------------*/
$('.gallery-slide-3').owlCarousel({
    loop:true,
	autoPlay:6000, //Set AutoPlay to 6 seconds 
    items:3,
    margin:40,	
	navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
    responsiveClass:true,
	loop:true,
    responsive:{
        0:{
            items:1,
            nav:true
        },
        600:{
            items:2,
            nav:false
        },
        1000:{
            items:3,
            nav:true,
            loop:false
        }}
});
/*-----------------------------------------------------------------------------------*/
/*    Feature Slider
/*-----------------------------------------------------------------------------------*/
$('.gallery-slide').owlCarousel({
    loop:true,
	autoPlay:6000, //Set AutoPlay to 6 seconds 
    items:5,
    margin:5,	
	navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
    responsiveClass:true,
	loop:true,
    responsive:{
        0:{
            items:1,
            nav:true
        },
        600:{
            items:2,
            nav:false
        },
        1000:{
            items:5,
            nav:true,
            loop:false
        }}
});
/*-----------------------------------------------------------------------------------*/
/*    Feature Slider
/*-----------------------------------------------------------------------------------*/
$('.parthner-slide').owlCarousel({
    loop:true,
	autoPlay:6000, //Set AutoPlay to 6 seconds 
    items:5,
    margin:30,	
	navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
    responsiveClass:true,
	loop:true,
    responsive:{
        0:{
            items:1,
            nav:true
        },
        600:{
            items:2,
            nav:false
        },
        1000:{
            items:5,
            nav:true,
            loop:false
        }}
});