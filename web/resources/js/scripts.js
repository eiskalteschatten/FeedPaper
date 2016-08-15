$(document).ready(function() {
    $(window).keypress(function(e) {
        switch(e.keyCode) {
            case 27: // Esc
                closePopup();
                break;
            case 38: // Arrow key up
                break;
            case 40: // Arrow key down
                break;
        }
    });

    $('.js-post').click(function() {
        $('.js-post').removeClass('selected');
        selectPost($(this));
    });

    $('[data-toggle="tooltip"]').tooltip();

    $('.js-folder-filter-link').click(function() {
        showFilterItem($(this), 'data-folder');
    });

    $('.js-feed-filter-link').click(function() {
        showFilterItem($(this), 'data-feed');
    });

    $('#todayFilterLink').trigger('click');
});


// Popups

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

var markAsReadTimer;

function selectPost(post) {
    clearTimeout(markAsReadTimer);
    post.addClass('selected');

    var url = $('.js-posts').attr('data-single-post-url');
    var id = post.attr('data-id');

    var vars = {
        'id': id
    };

    $.post(url, vars, function(data) {
        $('.js-post-title').text(data.title);
        $('.js-post-title').attr('href', data.url);
        $('.js-post-date').text(data.date);
        $('.js-post-body').html(data.content);
        $('.js-post-link').attr('href', data.url);
        $('.js-post-feed').text(data.feed);

        if (data.author != '') {
            $('.js-post-author').text(data.author);
            $('.js-post-author').addClass('shown');
        }
        else {
            $('.js-post-author').removeClass('shown');
            $('.js-post-author').text('');
        }

        $('.js-post-content-placeholder').hide();
        $('.js-post-content-column').show();

        if (!post.hasClass('read')) {
            $('.js-mark-as-read').addClass('show');
            $('.js-mark-as-unread').removeClass('show');

            markAsReadTimer = setTimeout(function() {
                markPostAsRead();
            }, 1000);
        }
        else {
            $('.js-mark-as-unread').addClass('show');
            $('.js-mark-as-read').removeClass('show');
        }
    });
}

function markPostAsRead() {
    var post = $('.js-post.selected');
    var id = post.attr('data-id');

    var url = $('.js-posts').attr('data-mark-single-read-url');

    var vars = {
        'id': id
    };

    $.post(url, vars, function() {
        post.addClass('read');
        $('.js-mark-as-unread').addClass('show');
        $('.js-mark-as-read').removeClass('show');
    });
}

function markPostAsUnread() {
    var post = $('.js-post.selected');
    var id = post.attr('data-id');

    var url = $('.js-posts').attr('data-mark-single-unread-url');

    var vars = {
        'id': id
    };

    $.post(url, vars, function() {
        post.removeClass('read');
        $('.js-mark-as-read').addClass('show');
        $('.js-mark-as-unread').removeClass('show');
    });
}

function markAllAsRead(url) {
    var toMarkAsRead = [];

    $('.js-post:not(.hidden)').each(function() {
        toMarkAsRead.push($(this).attr('data-id'));
    });

    var vars = {
        'ids': toMarkAsRead
    };

    $.post(url, vars, function() {
        $('.js-post:not(.hidden)').addClass('read');
        $('.js-mark-as-unread').addClass('show');
        $('.js-mark-as-read').removeClass('show');
    });
}


// Post filters

function showFilterAllUnread() {
    changeActiveSidebarLink($('#allUnreadFilterLink'));

    hidePostContent();
    hideAllPosts();
    $('.js-post:not(.read)').removeClass('hidden');
}

function showFilterToday() {
    changeActiveSidebarLink($('#todayFilterLink'));

    var todayUnix = new Date().getTime().toString();
    var today = todayUnix.substr(0, 5);

    hidePostContent();
    hideAllPosts();
    $('.js-post[data-post-date=' + today + ']').removeClass('hidden');
}

function showFilterAllFeeds() {
    hidePostContent();
    changeActiveSidebarLink($('#allFeedsFilterLink'));
    $('.js-post').removeClass('hidden');
}

function showFilterItem(filterLink, attribute) {
    changeActiveSidebarLink(filterLink);

    var itemId = filterLink.attr('data-id');

    hidePostContent();
    hideAllPosts();
    $('.js-post[' + attribute + '=' + itemId + ']').removeClass('hidden');
}

function hideAllPosts() {
    $('.js-post').addClass('hidden');
    $('.js-post').removeClass('selected');
}

function changeActiveSidebarLink(activeLink) {
    $('.js-full-link').removeClass('active');
    activeLink.addClass('active');
}

function hidePostContent() {
    $('.js-post-content-placeholder').show();
    $('.js-post-content-column').hide();
}