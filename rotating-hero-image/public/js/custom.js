

$(function(){

  var slideIndex = 0;

  var myIndex = 0;
  var dotindex = 0;

  if(wsxhi.autoplay == 'Yes'){
    carousel();
  }

  function carousel() {
      var i;
      var slides = $(".mySlides");   
      for (i = 0; i < slides.length; i++) {
        slides[i].style.display =  "none ";  
      }
      myIndex++;
      if (myIndex > slides.length) {myIndex = 1}    
      slides[myIndex-1].style.display =  "block ";  
      

      var dots = $(".wsxDot span");   
      for (i = 0; i < dots.length; i++) {
        dots[i].className = "dot";
      }
      dotindex++;
      if (dotindex > dots.length) {dotindex = 1}    
      dots[dotindex-1].className = "dot active";


      setTimeout(carousel, 3000); 

  }

 $('.arrow').on('click', function (e){
    e.preventDefault();
    var g = parseInt($(this).attr('data-move')); 
    showSlides(slideIndex += g);  
  });
  
  
  $(".wsxDot span").first().addClass("active");
  $('.dot').on('click', function (e){
    $(".wsxDot span").removeClass("active");
    var slides = $(".mySlides");    
    var g = parseInt($(this).attr('data-dot'));   
    slides.hide();
    $(this).addClass("active");
    $("#currentslide"+g).show();
  });
   
  function showSlides(n) {
      
    //var i;
    var slides = $(".mySlides"); 
    var dots = $(".wsxDot span");    
    
    if (n > slides.length-1) {      
      slideIndex = 0      
    }
    if (n < 0) {      
      slideIndex = slides.length-1
    }   
        
    slides.hide();     
    $(".wsxDot span").removeClass("active"); 
    slides.eq(slideIndex).show().addClass("active"); 
    dots.eq(slideIndex).show().addClass("active"); 
    
  }
  
  
});