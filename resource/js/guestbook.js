$(document).ready(function () {
    (function ($) {
        "use strict";
        
        $(function () {
            $('#guestbookForm').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    message: {
                        required: true,
                        minlength: 20
                    }
                },
                messages: {
                    name: {
                        required: 'Укажите, как к вам обратиться?',
                        minlength: 'Ваше имя правда меньше 2х символов?'
                    },
                    email: {
                        required: 'Нет обратного E-Mail - нет ответа'
                    },
                    message: {
                        required: 'Напишите что-нибудь нам, пожалуйста',
                        minlength: 'Это все? Так мало?'
                    }
                },
                submitHandler: async function (form) {
                    let formData = new FormData(form);
                        formData.append('recaptcha', await grecaptcha.getToken());
    
                    $.ajax({
                        type: 'POST',
                        data: formData,
                        url: '/guestbook',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function () {
                            $('#guestbookForm :input').attr('disabled', 'disabled');
                            $('#guestbookForm').fadeTo('slow', 1, function () {
                                $(this).find(':input').attr('disabled', 'disabled');
                                $(this).find('label').css('cursor', 'default');
                                $('#success').fadeIn();
                                $('.modal').modal('hide');
                                $('#success').modal('show');
                            })
                        },
                        error: function () {
                            $('#guestbookForm').fadeTo('slow', 1, function () {
                                $('#error').fadeIn();
                                $('.modal').modal('hide');
                                $('#error').modal('show');
                            })
                        }
                    });
                }
            })
        })
        
    })(jQuery)
});
