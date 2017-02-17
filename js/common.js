 (function(d) {
   var config = {
     kitId: 'hkt7aak',
     scriptTimeout: 3000,
     async: true
   },
   h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
 })(document);

jQuery(document).ready(function ($) {
  $('.video-link').click(function(){
    $('#youtube').modal('toggle');
    $('body').attr('style', '');//prevents bug when closing and opening one at the same time
  });

$('input#gform_next_button_4_5').val('SUBMIT');
  content = $('h3.gf_progressbar_title').text().trim();  
  if(content=='Step 1 of 2'){
    console.log('step un');
    $('div.gf_progressbar').html('<h3>We are excited to show you the power of the DUnami Platform in a personalized demo for you and your team</h3><p>Please submit your contact information below and a Dunami team member will be in touch shortly</p>');
  }

  $( "input#gform_next_button_4_5" ).click(function() {
    setTimeout(function(){
      content = $('h3.gf_progressbar_title').text().trim();  
        if(content=='Step 2 of 2'){
          console.log('step du');
          $('div.gf_progressbar').html('<h3>Your infomration has been received!<br />A Dunami team member will be in touch shortly.</h3><p>Please consider completing the additional questions below to allow our team to better understand your needs as it relates to the Dunami Platform</p>');          
      //content here for step 2
      }
      else{
        $('div.gf_progressbar').html('<h3>We are excited to show you the power of the DUnami Platform in a personalized demo for you and your team</h3><p>Please submit your contact information below and a Dunami team member will be in touch shortly</p>');
      }
            },1000);
  });

//animation for 
    var myVar;
    function myAnimate() {
        myVar = setInterval(slideit, 500);
    }            
    function slideit() {
        offset = $('.indicator.active').offset();
        if(offset === undefined){
          // console.log('no sliders');
          clearInterval(myVar2);
          return 0;
        }
        else{
          $('.triangle').css({'left':offset.left + (($('li.indicator.active').outerWidth()/2)-65)});
          // console.log(offset.left + ' ' + (($('li.indicator.active').outerWidth()))+' ');
        }      
        
    }
      myAnimate();


//animation for 
    var myVar2;
    function myAnimate2() {
        myVar = setInterval(slideit2, 500);
    }            
    function slideit2() {
        offset = $('li.success-indicator.active').offset();
        if(offset === undefined){
          // console.log('no success stories');
          clearInterval(myVar2);
          return 0;
        }
        
        $('.success-triangle').css({'left':offset.left + (($('li.success-indicator.active').outerWidth()/2)-65)});
    }
    
     myAnimate2();
    



$(window).scroll(function() {
    if ($(this).scrollTop() > 1){  
        $('header.site-header').addClass("theme-change");
      }
      else{
        $('header.site-header').removeClass("theme-change");
      }
});
    $("ul.menu-mobile").before('<div id="primary-menu-toggle" class="menu-toggle primary"><a class="toggle-switch show" href="#"><span>Show Menu</span></a></div>');
    $("#primary-menu-toggle .show").click(function () {
        if ($(".menu-mobile").is(":hidden")) {
            $(".menu-mobile").slideDown(500);
            $(this).attr("class", "toggle-switch hide").attr("title", "Hide Menu");
            $("#primary-menu-toggle span").replaceWith("<span>Hide Menu</span>")
        } else {
            $(".menu-mobile").hide(500);
            $(this).attr("class", "toggle-switch show").attr("title", "Show Menu");
            $("#primary-menu-toggle span").replaceWith("<span>Show Menu</span>")
        }
    });
    
    jQuery(".scroll, .gototop a").click(function (e) {
        e.preventDefault();
        jQuery("html,body").animate({
            scrollTop: jQuery(this.hash).offset().top
        }, 500)
    })
})