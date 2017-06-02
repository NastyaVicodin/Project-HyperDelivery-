$(document).ready(function(){
    var sortMethod;
    $(".sortMethodSelect").change(function() {
        sortMethod = $(".sortMethodSelect").val();
        var date = $("#dateField").val();
        var time = getTime($("#timeHHField").val(), $("#timeMMField").val());
        var price = $('#priceField').val();
        var search = $('#searchField').val();
        $(location).attr('href', '?orderBy='+sortMethod+
            ((date != '' && date !='дд.мм.гггг') ? '&date='+date.replace('.', '-') : '')+
            (time != '::00' ? '&time='+time : '')+
            (price != '' ? '&price='+price : '')+
            (search != '' ? '&search='+search : '') );

    });

    $('#timeHHField').keyup(function(){
       if($(this).val() > 24){
           $(this).val('23');
       }
       if($(this).val() < 0){
           $(this).val('00');
       }
    });
    $('#timeMMField').keyup(function(){
        if($(this).val() > 60){
            $(this).val('59');
        }
        if($(this).val() < 0){
            $(this).val('00');
        }
    });
    $("#filterSubmitButton").on('click', function() {
        var date = $("#dateField").val();
        var time = getTime($("#timeHHField").val(), $("#timeMMField").val());
        var price = $('#priceField').val();
        var search = $('#searchField').val();
        $(location).attr('href', '?orderBy='+sortMethod+
            ((date != '' && date !='дд.мм.гггг') ? '&date='+date.replace('.', '-') : '')+
            (time != '::00' ? '&time='+time : '')+
            (price != '' ? '&price='+price : '')+
            (search != '' ? '&search='+search : '')

        );
    });

    function getTime(HH, MM){
        return HH+':'+MM+':00';
    }

});