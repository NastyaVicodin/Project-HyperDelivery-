$(document).ready(function(){
    var starId;
    var clickNum;
    var url = $('.rating').data('id');
    $('.ratingStar').mouseover(function() {
        starId = $(this).data('id');

        for(i=0; i<=starId; i++){
            $('.ratingStar[data-id='+i+']').attr('src', url+'/images/gold_star.png');
        }
        for(i=starId+1; i<=10; i++){
            $('.ratingStar[data-id='+i+']').attr('src', url+'/images/star.png');
        }
    });

    $('.ratingStar[data-id='+starId+']').mouseleave(function() {
        $(this).attr('src', url+'images/star.png');
    });

    $('.rating').mouseleave(function() {
        if(clickNum == null){
            $('.ratingStar').attr('src', url+'/images/star.png');
        }
        else{
            $('.ratingStar').attr('src', url+'/images/star.png');
            for(i=0; i<=clickNum; i++){
                $('.ratingStar[data-id='+i+']').attr('src', url+'/images/gold_star.png');
            }
        }
    });

    $('.ratingStar').on('click', function () {
        clickNum = $(this).data('id');
        $('#ratingField').val(starId);
    });
});
