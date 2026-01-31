jQuery(function($){
  $(".hover-card").on("click", function(e){
    $(".hover-card").not(this).removeClass("active");
    $(this).toggleClass("active");
  });

  $(document).on("click", function(e){
    if(!$(e.target).closest(".hover-card").length){
      $(".hover-card").removeClass("active");
    }
  });
});