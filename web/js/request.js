$(document).ready(function(){

    $(".requestConfirmButton").click(function () {
        var requestId = $(this).data('id');
        $(".modal-footer .modalConfirmReqButton").attr('href', $('.modalConfirmReqButton').attr('href')+'&requestId='+requestId);
    });

    $(".requestCancelButton").click(function () {
        var requestId = $(this).data('id');
        $(".modal-footer .modalCancelReqButton").attr('href', $('.modalCancelReqButton').attr('href')+'&requestId='+requestId);
    });

    $(".requestSendButton").click(function () {
        $(".requestFormSubmit").click();
    });
});
