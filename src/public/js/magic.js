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
        var cat =  $(this).children(".card-name").text();
        var src = $('.card img').attr('src');
        console.log(src);
        var cat2 =  $(this).children(".card-schedule").text();
        var cat3 =  $(this).children(".card-description").text();
        
        $('.showresult').html('<h2>'+cat+'</h2> <img src="'+src+'" width="400" height="280"> <h3>'+cat2+'</h3><h4>'+cat3+'</h4>');
       });
            
    })