$(function() {

    $('#side-menu').metisMenu();

    //画像preview
    $('.txt input[type=file]').change(function() {
        var file = $(this).prop('files')[0];

        if (! file.type.match('image.*')) {
            $('span').html('');
            return;
        }

        var reader = new FileReader();
        reader.onload = function() {
            var img_src = $('<img>').attr('src', reader.result);
            $('.txt span').html(img_src);
        }

        reader.readAsDataURL(file);
    });
    $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    var url = window.location;
    var element = $('ul.nav a').filter(function() {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }).addClass('active').parent().parent().addClass('in').parent();
    if (element.is('li')) {
        element.addClass('active');
    }
});
