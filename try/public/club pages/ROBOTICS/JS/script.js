$   (".icon").click(function(){
    $("ul").slideToggle('medium', function(){
        if($(this).is(':visible'))
        $(this).css('display','flex')
    })
})

$(document).ready(function(){
    $("#showtextlink").click(function(event){
        event.preventDefault(); // Prevent the default action of the link
        $("#textcontainer").slideToggle(); // Show the text container
    });
});
