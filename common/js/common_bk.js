$(function () {
    //ページ読み込み時のチェックボックス取得 index の検索リスト
    $(".panels input").each(function() {
        var val = $(this).val();
        if($(this).is(":checked")){
            $(this).parent().addClass("c_on");
            $('#chklist').append("<li data-trg='"+val+"'>"+val+"<p class='close'>✖</p></li>");
        }else{
            $(this).parent().removeClass("c_on");
            $('#chklist li').each(function() {
                var val2 = $(this).data('trg');
                if(val == val2) $(this).remove();
            });
        }
    });
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //      お気に入り
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Cookie制御
    $.cookie.json = true;
    var idParam =$.cookie("EL-ID");
    var titleParam =$.cookie("EL-TITLE");
    if(!idParam || !titleParam){
        idParam =[];
        titleParam =[];
    }else{
        //起動時に選択案件リストを更新する
        $(idParam).each(function(i,val) {
            var kak = titleParam[i].substr(0,1);
            if(kak == "【")kak = "";else kak=" pad";
            $('<li><label class="c_on'+kak+'"><input type="checkbox" name="entry[]" value="'+val+'" checked>'+titleParam[i]+'</label></li>').appendTo(".bg_checkbox2").hide().slideDown(100);
        });
    }
    $('.jq').click(function () {
        var id = $(this).data("id");
        var title = $(this).data("title");
        if(!$.cookie("EL-ID")) {
            idParam.push(id);
            titleParam.push(title);
            console.log("false cookie:"+idParam);
            //お気に入りに登録
            $('.jq').addClass('active');
            //選択中案件リストに追加
            var kak = title.substr(0,1);
            if(kak == "【")kak = "";else kak=" pad";
            $('<li><label class="c_on'+kak+'"><input type="checkbox" name="entry[]" value="'+id+'" checked>'+title+'</label></li>').appendTo(".bg_checkbox2").hide().slideDown(500);
        }else{
            //Cookie判別。重複は削除
            idParam = $.cookie("EL-ID");
            titleParam = $.cookie("EL-TITLE");
            var chkid = jQuery.inArray( id, idParam );
            var chkti = jQuery.inArray( title, titleParam);
            if ( chkid == -1 ){
                //お気に入りに登録
                $('.jq').addClass('active');
                idParam.push(id);
                titleParam.push(title);
                //選択中案件リストに追加
                var kak = title.substr(0,1);
                if(kak == "【")kak = "";else kak=" pad";
                $('<li><label class="c_on'+kak+'"><input type="checkbox" name="entry[]" value="'+id+'" checked>'+title+'</label></li>').appendTo(".bg_checkbox2").hide().slideDown(500);
            } else {
                //お気に入りを解除
                $('.jq').removeClass('active');
                idParam.splice(chkid, 1);
                titleParam.splice(chkti, 1);
                //選択中案件リストから削除
                $(".bg_checkbox2 input").on().each(function() {
                    var val = $(this).val();
                    if(val == id)$(this).parent().parent().slideUp("500", function() { $(this).remove(); } );
                });
            }
            console.log(idParam);
        }
        //Cookieセット
        $.cookie('EL-ID', idParam, { expires: 30,path: '/'});
        $.cookie('EL-TITLE', titleParam, { expires: 30,path: '/' });
    });
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //      その他
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //画像ホバー
    $('a img,form :image,.pagetop img').hover(function(){
        $(this).attr('src', $(this).attr('src').replace('_off', '_on'));
    }, function(){
        if (!$(this).hasClass('active')) {
            $(this).attr('src', $(this).attr('src').replace('_on', '_off'));
        }
    }).each(function(){
        if(String($(this).attr("src")).match(/_off\.(.*)$/)){
            var img = new Image();
            img.src = String($(this).attr("src")).replace(/_off\.(.*)$/,"_on.$1");
        }else if(String($(this).attr("src")).match(/_on\.(.*)$/)){
            var img = new Image();
            img.src = String($(this).attr("src")).replace(/_on\.(.*)$/,"_off.$1");
        }
    });
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //      検索リスト
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //タブ
    $(".tabs a").on('click', function(e) {
        e.preventDefault();
        var target = $(this).attr('href');
        if (! $(target).length) return false;
        $('.tab', $(this).closest('.tabs')).removeClass('active');
        $(this).closest('.tab').addClass('active');
        $('.panel', $(target).closest('.panels')).removeClass('active');
        $(target).addClass('active');
    });
    //チェックボックスの装飾
    $(".bg_checkbox input").change(function(){
        var val = $(this).val();
        if($(this).is(":checked")){
            $(this).parent().addClass("c_on");
            $('#chklist').append("<li data-trg='"+val+"'>"+val+"<p class='close'>✖</p></li>");
        }else{
            $(this).parent().removeClass("c_on");
            $('#chklist li').each(function() {
                var val2 = $(this).data('trg');
                if(val == val2) $(this).remove();
            });
        }
    });
    //searchbox li
    $('#chklist').on('click','.close',function() {
        $(this).parent().remove();
        val2 = $(this).parent().data('trg');
        //リストのチェックを外す
        $(".bg_checkbox input").each(function() {
            val = $(this).val();
            if(val == val2){
                $(this).attr({'checked':false});
                $(this).parent().removeClass("c_on");
            }
        });
    });
    //選択中の案件チェックボックス
    $(".bg_checkbox2").on('click','input', function(e) {
        if($(this).is(":checked")){
            $(this).parent().addClass("c_on");
        }else{
            $(this).parent().removeClass("c_on");
        }
    });

    //ファイルの添付
    $(".file").on('change','input' , function(){
        tx = $(this).parent().parent().find("p:last");
        tx.html("添付されました！");
        tx.addClass("red");
        console.log(tx);
    });

    //フォームAJAX
    $('#entry,.entry2').on('click', function() {
        var trg = $(this).parent().parent()[0];
        var formData = new FormData( trg );
        var cnt = $(this).parent().prev().find('li').length;
        var iz = $(this).attr("id");
        if(iz == undefined) cnt = 1;
        if(cnt) {
            $.ajax({
                url: '/common/api.php',
                method: 'post',
                data: formData,
                processData: false,
                contentType: false
            }).done(function (res) {
                if (res == "error") {
                    alert("正しいメールアドレスを入力してください")
                } else if (res == 2 || res == 22) {
                    alert("エントリーを受け付けました。ご応募ありがとうございました");
                    $.cookie("EL-ID", null, {expires: -1, path: '/'});
                    $.cookie("EL-TITLE", null, {expires: -1, path: '/'});
                    window.location.href = '/';
                } else {
                    alert("エラー：登録が完了できません");
                }
                console.log(res);
            });
        }else{
            alert("案件を選択してください。")
        }
        return false;
    });

//end
});

