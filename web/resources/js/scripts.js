function showSection(url) {
    showAjaxLoader();

    $.get(url, function(data) {
        $('.js-body-content').html(data);
        $('.js-sidebar').find('a').removeClass('active');
        hideAjaxLoader();
    });
}

function showAjaxLoader() {
    $('.js-ajax-loader').show();
}

function hideAjaxLoader() {
    $('.js-ajax-loader').hide();
}