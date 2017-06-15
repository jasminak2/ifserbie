    // NAVIGATION
    jQuery(document).ready(function(){
  
        jQuery('.mobile-nav .menu-trigger').click(function(){
        	jQuery('.mobile-nav > .menu').slideToggle(400, function(){
                jQuery(this).toggleClass("nav-expanded").css('display', '');
            });
    
        });
    
        
    
    });   // ...document.ready






