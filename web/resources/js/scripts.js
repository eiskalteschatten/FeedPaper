function openPopup(url, popupClass) {
    showAjaxLoader();

    $.get(url, function(data) {
        $('.js-popup-content').html(data);
        $('.js-popup-box').addClass(popupClass);
        $('.js-popup-box').attr('data-popup-class', popupClass);
        $('.js-popup').show();
        $('.js-popup').stop().animate({'opacity': 1}, 200);

        hideAjaxLoader();
    });
}

function closePopup() {
    $('.js-popup').stop().animate({'opacity': 0}, 100, function() {
        $(this).hide();
        $('.js-popup-content').html('');

        var popupClass = $('.js-popup-box').attr('data-popup-class');
        $('.js-popup-box').removeClass(popupClass);
    });
}

function showAjaxLoader() {
    $('.js-ajax-loader').show();
}

function hideAjaxLoader() {
    $('.js-ajax-loader').hide();
}