$(document).ready(function(){
    $('.commEditButton').on('click', function(){
       var commId = $(this).data('id');
        alert(commId);
       $('.commText').append('<input type="text" name="commEditField"');
    });
});
