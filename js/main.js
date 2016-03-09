$curBookId=0;

$("#searchForm").submit(function(event){
    $("#offerContainer #bookID").val($curBookId);
});

$( ".listing" ).click(function() {
    $list = this;
  $(".secondaryInfo, .secondaryInfoHeader", this ).slideToggle({
    "duration": 400
  });
});

$( ".listing .tab span" ).click(function() {
    $curBookId = $(this).data("book-id");
    $("#offerContainer #bookID").val($curBookId);
    $("#offerContainer").slideToggle(true);
});

$( "#offerSubmit" ).click(function() {
    $("#offerContainer").slideToggle(false);
});