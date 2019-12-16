$(document).ready(function () {
    (function ($) {
        "use strict";
        
        $(function () {
            $('#quizForm').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    comment: {
                        required: true,
                        minlength: 10
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
                        minlength: 'Попробуйте подробней..'
                    }
                },
                submitHandler: async function (form) {
                    let formData = new FormData(form);
                        formData.append('recaptcha', await grecaptcha.getToken());
    
                    $.ajax({
                        type: 'POST',
                        data: formData,
                        url: '/form/forma-zayavki',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function () {
                            $('#quizForm :input').attr('disabled', 'disabled');
                            $('#quizForm').fadeTo('slow', 1, function () {
                                $(this).find(':input').attr('disabled', 'disabled');
                                $(this).find('label').css('cursor', 'default');
                                $('#success-quiz').fadeIn();
                                $('.modal').modal('hide');
                                $('#success-quiz').modal('show');
                            })
                        },
                        error: function () {
                            $('#quizForm').fadeTo('slow', 1, function () {
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
