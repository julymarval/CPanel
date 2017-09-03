$(document).ready(function(){
    /*
      // Add smooth scrolling to all links in navbar + footer link
      $(".navbar a, footer a[href='#myPage']").on('click', function(event) {
    
        // Prevent default anchor click behavior
        event.preventDefault();
    
        // Store hash
        var hash = this.hash;
        console.log(hash);
        if(hash[0]=='#'){
          // Using jQuery's animate() method to add smooth page scroll
          // The optional number (900) specifies the number of milliseconds it takes to scroll to the specified area
          $('html, body').animate({
            scrollTop: $(hash).offset().top
          }, 900, function(){
          
            // Add hash (#) to URL when done scrolling (default click behavior)
            window.location.hash = hash;
          });
        }
      });
      */
      // Slide in elements on scroll
      $(window).scroll(function() {
        $(".slideanim").each(function(){
          var pos = $(this).offset().top;
    
          var winTop = $(window).scrollTop();
            if (pos < winTop + 600) {
              $(this).addClass("slide");
            }
        });
      });
      $('#list').click(function(event){
        event.preventDefault();
        $('#products .item').addClass('list-group-item');
      });
      $('#grid').click(function(event){
        event.preventDefault();
        $('#products .item').removeClass('list-group-item');
        $('#products .item').addClass('grid-group-item');
      });
  
      $('.show1').click(function(){
        $('.showresult').html('<h2>El cuartel del Sargento</h2><h4> Rubén Baca </h4>');
       });
       $('.show2').click(function(){
        $('.showresult').html('<h2>Dame tu mano</h2><h4> Diana Montiel </h4>');
       });
       $('.show3').click(function(){
        $('.showresult').html('<h2>Grandes momentos del recuerdo</h2><h4> Arturo Salazar</h4>');
       });
  
       var maxLength = 600;
       $(".show-read-more").each(function(){
         var myStr = $(this).text();
         if($.trim(myStr).length > maxLength){
           var newStr = myStr.substring(0, maxLength);
           var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
           $(this).empty().html(newStr);
           $(this).append(' <a href="javascript:void(0);" class="read-more">read more...</a>');
           $(this).append('<span class="more-text">' + removedStr + '</span>');
         }
       });
       $(".read-more").click(function(){
         $(this).siblings(".more-text").contents().unwrap();
         $(this).remove();
       });
  
    })