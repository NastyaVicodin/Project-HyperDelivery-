$(document).ready(function(){

    var itemArr = $(".itemsField").val().split('&');
    for(var i=0; i < itemArr.length; i++){
        if(i==0){
            $('.items .item[data-id="1"] input').val(itemArr[i]);
        }else{
        $(".items").append('<div class="item" data-id="'+(i+1)+'">' +
            '<input type="text" class="itemDescription form-control" data-id="'+(i+1)+'" placeholder="Item description" value="'+itemArr[i]+'">'
            +'<div><span class="close ItemDel" data-id="'+(i+1)+'" >&times</span></div>'+
            '</div>');
        }
    }

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
