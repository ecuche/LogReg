$(document).ready(function () {


    $('#hide-show-password').css('cursor', 'pointer');
    $('#hide-show-password').click(function (e) { 
        e.preventDefault();
        if($(this).hasClass('bi-eye-slash-fill')){
            $(this).removeClass('bi-eye-slash-fill');
            $(this).addClass('bi-eye-fill');
            $(this).siblings('#password').attr('type', 'text');;
        }else if($(this).hasClass('bi-eye-fill')){
            $(this).removeClass('bi-eye-fill');
            $(this).addClass('bi-eye-slash-fill');
            $(this).siblings('#password').attr('type', 'password');;
        }
    });
    
});