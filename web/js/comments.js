$(document).ready(function(){

    //var itemArr = items.split('&');

    $(".itemAddButton").click(function() {
        var items =  $('.itemsField').val();
        var itemId = $(".itemDescription").last().data('id') + 1;
        $(".items").append('<div class="item" data-id="'+itemId+'">' +
            '<input type="text" class="itemDescription form-control" data-id="'+itemId+'" placeholder="Item description">'
            +'<div><span class="close ItemDel" data-id="'+itemId+'" >&times</span></div>'+
            '</div>');
    });

    $('body').on('click', '.ItemDel', function(){
        var itemId = $(this).data('id');
        $(".item[data-id="+itemId+"]").remove();
    });

    $("body").on('click', '.addOrderButton', function(){
        var itemsStr = '';
        var items = $(".itemDescription").each(function(){
            itemsStr += $(this).val()+'&';
        });
        $('.itemsField').val(itemsStr);
    });
});

