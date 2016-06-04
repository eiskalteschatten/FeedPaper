window.onload = function() {
    $(window).keypress(function(e) {
        if (e.keyCode == 27) {
            closePopup();
        }
    });
}

function openPopup(url, popupClass) {
    $('.js-popup-box').addClass(popupClass);
    $('.js-popup-box').attr('data-popup-class', popupClass);
    $('.js-popup').show();
    $('.js-popup').stop().animate({'opacity': 1}, 200);

    $.get(url, function(data) {
        $('.js-popup-content').html(data);
        $('.js-popup-box').addClass('done-loading');
    }).fail(function() {
        $('.js-popup-box').addClass('done-loading');
        var error = $('.js-popup').attr('data-error');
        $('.js-popup-content').html(error);
    });
}

function closePopup() {
    $('.js-popup').stop().animate({'opacity': 0}, 100, function() {
        $(this).hide();
        $('.js-popup-content').html('');

        var popupClass = $('.js-popup-box').attr('data-popup-class');
        $('.js-popup-box').removeClass(popupClass);
        $('.js-popup-box').attr('data-popup-class', '');

        $('.js-popup-box').removeClass('done-loading');
    });
}