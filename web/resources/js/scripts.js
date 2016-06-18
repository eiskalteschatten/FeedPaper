window.onload = function() {
    $(window).keypress(function(e) {
        if (e.keyCode == 27) {
            closePopup();
        }
    });

    $('.js-post').click(function() {
        $('.js-post').removeClass('selected');
        selectPost($(this));
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


// Feed validation

var typingTimer;

function validateFeed(field) {
    var url = $(field).val();
    var validateUrl = $('.js-add-feed-form').attr('data-validate-feed-url');

    if (url != "") {
        $('.js-validating-feed').show();

        $.post(validateUrl, {'url': url}, function(data) {
            $('.js-validating-feed').hide();

            if (data === 'true') {
                $('#form_save').prop('disabled', false);
                $('.js-add-feed-form').attr('data-feed-validated', 'true');
            }
        });
    }
}

function validateFeedTimer(field) {
    clearTimeout(typingTimer);

    typingTimer = setTimeout(function() {
        validateFeed(field);
    }, 700);
}

// Refresh all feeds

function refreshAllFeeds() {
    var url = $('#refreshButton').attr('data-refresh-url');
    var buttonIcon = $('#refreshButtonIcon');
    buttonIcon.addClass('spin');

    $.post(url, function(data) {
        location.reload();
    }).always(function() {
        buttonIcon.removeClass('spin');
    });
}

// Posts

function selectPost(post) {
    post.addClass('selected');
}