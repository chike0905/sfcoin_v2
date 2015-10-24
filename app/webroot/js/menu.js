$(function(){
  $("#menu").click(function(){
    var display =  $('.menu').css("display");
    if(display == "none"){
      $(".menu").css("display", "block");
    } else if(display == "block"){
      $(".menu").css("display", "none");
    }
  });
});
