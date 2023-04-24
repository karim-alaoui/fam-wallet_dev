'use strict';
$(function(){
    /**
     * モーダルを開く
     */
    $('.jsc-modal-trigger').on('click' , function(){
        $('.model-overlay').fadeIn();
    });
    /**
     * モーダルを開く
     */
    $('.jsc-modal-trigger-benefits').on('click' , function(){
        $('.jsc-delete').hide();
        $('.model-overlay-benefits').fadeIn();
    });
    /**
     * モーダルを閉じる (キャンセルボタン) 
     */ 
    $('.jsc-check-cancel').on('click' , function(){
        $('.model-overlay').fadeOut();
    });
    /**
     * モーダルを閉じる (決定ボタン)(仮)
     * フロント側かこっちかでデータをControllerに投げる実装を入れる。 
     */ 
    $('.jsc-check-decied').on('click' , function(){
        $('.model-overlay').fadeOut();
    });
    /**
     * プライバシーポリシー
     * is-activeで表示する内容を切り替える
     */
    $('.p-privacyPolicy__listItem').on('click' , function(){
        $(this).toggleClass('is-active');
        $(this).next('.jsc-privacyPolicy-count').toggleClass('is-active');
    });
    /**
     * トップ (暫定処理)
     * is-activeで表示する内容を切り替える
     */
    $('.p-userTop__listItem').on('click' , function(){
        if($(this).hasClass('jsc-coupon-count')){
            $('.jsc-stamp-count').removeClass('is-active');
            $('#jsc-stamp-count').removeClass('is-active');
            $('.jsc-coupon-count').addClass('is-active');
            $('#jsc-coupon-count').addClass('is-active');
        }　else　if($(this).hasClass('jsc-stamp-count')) {
            $('.jsc-coupon-count').removeClass('is-active');
            $('#jsc-coupon-count').removeClass('is-active');
            $('.jsc-stamp-count').addClass('is-active');
            $('#jsc-stamp-count').addClass('is-active');
        }
    });
    /**
     * クーポン一覧 (暫定処理)
     * is-activeで表示する内容を切り替える
     */
    $('.p-couponList__coupon__tab').on('click' , function(){
        if($(this).hasClass('jsc-coupon-public')){
            $('.jsc-coupon-private').removeClass('is-active');
            $('#jsc-coupon-private').removeClass('is-active');
            $('.jsc-coupon-public').addClass('is-active');
            $('#jsc-coupon-public').addClass('is-active');
        }　else　if($(this).hasClass('jsc-coupon-private')) {
            $('.jsc-coupon-public').removeClass('is-active');
            $('#jsc-coupon-public').removeClass('is-active');
            $('.jsc-coupon-private').addClass('is-active');
            $('#jsc-coupon-private').addClass('is-active');
        }
    });
    /**
     * スタンプカード一覧 (暫定処理)　一つにまとめたい
     * is-activeで表示する内容を切り替える
     */
    $('.p-stampList__stamp__tab').on('click' , function(){
        if($(this).hasClass('jsc-stampcard-public')){
            $('.jsc-stampcard-private').removeClass('is-active');
            $('#jsc-stampcard-private').removeClass('is-active');
            $('.jsc-stampcard-public').addClass('is-active');
            $('#jsc-stampcard-public').addClass('is-active');
        }　else　if($(this).hasClass('jsc-stampcard-private')) {
            $('.jsc-stampcard-public').removeClass('is-active');
            $('#jsc-stampcard-public').removeClass('is-active');
            $('.jsc-stampcard-private').addClass('is-active');
            $('#jsc-stampcard-private').addClass('is-active');
        }
    });
    /**
     * 参考クーポンモーダル (暫定処理)
     * is-activeで表示する内容を切り替える
     */
    $('.c-input__modal__tab').on('click' , function(){
        if($(this).hasClass('jsc-coupon-public')){
            $('.jsc-coupon-private').removeClass('is-active');
            $('#jsc-coupon-private').removeClass('is-active');
            $('.jsc-coupon-public').addClass('is-active');
            $('#jsc-coupon-public').addClass('is-active');
        }　else　if($(this).hasClass('jsc-coupon-private')) {
            $('.jsc-coupon-public').removeClass('is-active');
            $('#jsc-coupon-public').removeClass('is-active');
            $('.jsc-coupon-private').addClass('is-active');
            $('#jsc-coupon-private').addClass('is-active');
        }
    });
    /**
     * 分析画面のタブ (暫定処理)
     * is-activeで表示する内容を切り替える
     */
    $('.p-analytics__tab').on('click' , function(){
        if($(this).hasClass('jsc-coupon-count')){
            $('.jsc-stamp-count').removeClass('is-active');
            $('#jsc-stamp-count').removeClass('is-active');
            $('.jsc-coupon-count').addClass('is-active');
            $('#jsc-coupon-count').addClass('is-active');
        }　else　if($(this).hasClass('jsc-stamp-count')) {
            $('.jsc-coupon-count').removeClass('is-active');
            $('#jsc-coupon-count').removeClass('is-active');
            $('.jsc-stamp-count').addClass('is-active');
            $('#jsc-stamp-count').addClass('is-active');
        }
    });
    /**
     * 全て選択ボタン
     * 
     */
    $('.jsc-selectAll').on('click',function(){
        var shop = $('input[type="checkbox"]').length;
        var check = $('input[type="checkbox"]'+':checked').length;
        if(check >= 0){
            $('.jsc-selectAll').html('全て解除');
            $('input[type="checkbox"]').closest('label').addClass('is-check');
            $('input[type="checkbox"]').prop('checked',true);
        }
        if(check === shop){
            $('.jsc-selectAll').html('全て選択');
            $('input[type="checkbox"]').closest('label').removeClass('is-check');
            $('input[type="checkbox"]').prop('checked',false);
        }
    });
    /**
     * チェックされた時にチェックボックスのデザインを変更する
     * 
     */
    $('input[type="checkbox"]').on('click', function() {
        var prop = $(this).prop('checked');
    
        if(prop) {
          $(this).closest('label').addClass('is-check');
        } else {
          $(this).closest('label').removeClass('is-check');
          $('.jsc-selectAll').html('全て選択');
        }
    });
    /**
     * 各確認画面から戻った際にチェックされた要素のチェックボックスのデザインを変更する
     * 
     */
    $(window).on('load', function() {
        var check = $('input[type="checkbox"]'+':checked').length;
        var radio_check = $('input[type="radio"]'+':checked').length;
        if(check >= 0 && radio_check >= 0) {
          $('input[type="checkbox"]'+':checked').closest('label').addClass('is-check');
          $('input[type="radio"]'+':checked').closest('label').addClass('is-check');
        } 
    });
    /**
     * 色選択のラジオボタンのデザインを変更する
     * 
     */
    $('input[type="radio"]').on('click', function() {
        var prop = $(this).prop('checked');
        var name = $(this).attr('name');
    
        if(prop) {
            $('input[type="radio"]').not(name).closest('label').removeClass('is-check');
            $(this).closest('label').addClass('is-check');
        }
    });
    /**
     * edit時のラジオボタンのデザインを変更する
     * 
     */
    var type = $('.jsc-contract-field').val();
    $('p-stampEdit__form__state__radio__label').find('input[type="radio"]').prop('checked', function() {
        var prop = $('input[type="radio"]').prop('checked');
        if(prop) {
            $(this).closest('label').addClass('is-check');
        }
    });
    /**
     * 
     * テキストフォーカス処理
     * 
     */
    $('.jsc-input-text').blur(function() {
        //もしテキストボックスが空だったら
        if ($(this).val() == '') {
            $(this).css('border','1px solid #d56c6c');
        //テキストボックスに文字が入力されたら
        } else {
            $(this).css('border','1px solid #ddd');
        }
    });
    /**
     * 
     * テキストエリアのフォーカス処理
     * 
     */
    $('.jsc-textarea').blur(function() {
        //もしテキストボックスが空だったら
        if ($(this).val() == '') {
            $(this).css('border','1px solid #d56c6c');
        //テキストボックスに文字が入力されたら
        } else {
            $(this).css('border','1px solid #ddd');
        }
    });
    /**
     * 
     * 読み込み後のスタンプカードのスタンプを付与する値を変更する
     * 
     */
    $('.c-list__plus__btn').on('click',function() {
        var result = parseInt($('.p-stampConfirm__item__number').val());
        if(result >= 1 && result < 10){
            result = result + 1;
            $('.p-stampConfirm__item__number').val(result);
        }
    });
    $('.c-list__minus__btn').on('click',function() {
        var result = parseInt($('.p-stampConfirm__item__number').val());
        if(result > 1){
            result = result - 1;
            $('.p-stampConfirm__item__number').val(result);
        }
    });
    /**
     * 
     * お知らせの非表示
     * 
     */
    $('.c-notice__close').on('click',function() {
        $('.c-notice').fadeOut();
    });
    /**
     * 
     * QRコードのリンクコピー
     * 
     */
    $('#jsc-linkcopy').on('click', function(){
        var url = document.getElementById('jsc-linkcopy');
        execCopy(url.value);
        alert("コピーできました！");
    });
    function execCopy(string){
        // 空div 生成
        var tmp = document.createElement("div");
        // 選択用のタグ生成
        var pre = document.createElement('pre');
      
        // 親要素のCSSで user-select: none だとコピーできないので書き換える
        pre.style.webkitUserSelect = 'auto';
        pre.style.userSelect = 'auto';
      
        tmp.appendChild(pre).textContent = string;
      
        // 要素を画面外へ
        var s = tmp.style;
        s.position = 'fixed';
        s.right = '200%';
      
        document.body.appendChild(tmp);
        document.getSelection().selectAllChildren(tmp);
      
        var result = document.execCommand("copy");
      
        document.body.removeChild(tmp);
      
        return result;
    }
    /**
     * クーポン
     * ajax　DL数ソート処理
     * 
     */
    $('.jsc-download-coupon').on('click', function(){
        var csrf = $('input[name=_csrfToken]').val();
        var param = $(this).prop('id');
        $.ajax({
            url: '/analytics/coupon_api?param=' + param,
            type: 'POST',
            dataType: 'json',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', csrf);
            },
            timeout: 5000,
        })
        .done(function(result){
            var json = JSON.stringify(result);
            json = JSON.parse(json);
            $('#diary-all-pager').pagination({
                dataSource: json,
                pageSize: 10, // 1ページあたりの表示数
                prevText: '&lt;',
                nextText: '&gt;',
                ulClassName: 'c-pagination__list',
                callback: function(data) {
                    // dataの中に次に表示すべきデータが入っているので、html要素に変換
                    $('#ajax_list').html(template_coupon(data));
                }
            });
        })
        .fail(function (XMLHttpRequest, textStatus, errorThrown){
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);
        });
    });
    /**
     * クーポン
     * ajax　使用回数ソート処理
     * 
     */
    $('.jsc-usecount-coupon').on('click', function(){
        var csrf = $('input[name=_csrfToken]').val();
        var param = $(this).prop('id');
        $.ajax({
            url: '/analytics/coupon_api?param=' + param,
            type: 'POST',
            dataType: 'json',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', csrf);
            },
            timeout: 5000,
        })
        .done(function(result){
            var json = JSON.stringify(result);
            json = JSON.parse(json);
            $('#diary-all-pager').pagination({
                dataSource: json,
                pageSize: 10, // 1ページあたりの表示数
                prevText: '&lt;',
                nextText: '&gt;',
                ulClassName: 'c-pagination__list',
                callback: function(data) {
                    // dataの中に次に表示すべきデータが入っているので、html要素に変換
                    $('#ajax_list').html(template_coupon(data));
                }
            });
        })
        .fail(function (XMLHttpRequest, textStatus, errorThrown){
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);
        });
    });
    /**
     * クーポン
     * ajax　最終更新日ソート処理
     * 
     */
    $('.jsc-last-updated-coupon').on('click', function(){
        var csrf = $('input[name=_csrfToken]').val();
        var param = $(this).prop('id');
        $.ajax({
            url: '/analytics/coupon_api?param=' + param,
            type: 'POST',
            dataType: 'json',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', csrf);
            },
            timeout: 5000,
        })
        .done(function(result){
            var json = JSON.stringify(result);
            json = JSON.parse(json);
            $('#diary-all-pager').pagination({
                dataSource: json,
                pageSize: 10, // 1ページあたりの表示数
                prevText: '&lt;',
                nextText: '&gt;',
                ulClassName: 'c-pagination__list',
                callback: function(data) {
                    // dataの中に次に表示すべきデータが入っているので、html要素に変換
                    $('#ajax_list').html(template_coupon(data));
                }
            });
        })
        .fail(function (XMLHttpRequest, textStatus, errorThrown){
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);
        });
    });
    /**
     * クーポン
     * ajax　作成日ソート処理
     * 
     */
    $('.jsc-create-date-coupon').on('click', function(){
        var csrf = $('input[name=_csrfToken]').val();
        var param = $(this).prop('id');
        $.ajax({
            url: '/analytics/coupon_api?param=' + param,
            type: 'POST',
            dataType: 'json',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', csrf);
            },
            timeout: 5000,
        })
        .done(function(result){
            var json = JSON.stringify(result);
            json = JSON.parse(json);
            $('#diary-all-pager').pagination({
                dataSource: json,
                pageSize: 10, // 1ページあたりの表示数
                prevText: '&lt;',
                nextText: '&gt;',
                ulClassName: 'c-pagination__list',
                callback: function(data) {
                    // dataの中に次に表示すべきデータが入っているので、html要素に変換
                    $('#ajax_list').html(template_coupon(data));
                }
            });
        })
        .fail(function (XMLHttpRequest, textStatus, errorThrown){
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);
        });
    });
    /**
     * スタンプカード
     * ajax　DL数ソート処理
     * 
     */
    $('.jsc-download-stampcard').on('click', function(){
        var csrf = $('input[name=_csrfToken]').val();
        var param = $(this).prop('id');
        $.ajax({
            url: '/analytics/stampcard_api?param=' + param,
            type: 'POST',
            dataType: 'json',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', csrf);
            },
            timeout: 5000,
        })
        .done(function(result){
            var json = JSON.stringify(result);
            json = JSON.parse(json);
            $('#diary-all-pager').pagination({
                dataSource: json,
                pageSize: 10, // 1ページあたりの表示数
                prevText: '&lt;',
                nextText: '&gt;',
                ulClassName: 'c-pagination__list',
                callback: function(data) {
                    // dataの中に次に表示すべきデータが入っているので、html要素に変換
                    $('#ajax_list').html(template_stampcard(data));
                }
            });
        })
        .fail(function (XMLHttpRequest, textStatus, errorThrown){
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);
        });
    });
    /**
     * スタンプカード
     * ajax　最終更新日ソート処理
     * 
     */
    $('.jsc-last-updated-stampcard').on('click', function(){
        var csrf = $('input[name=_csrfToken]').val();
        var param = $(this).prop('id');
        $.ajax({
            url: '/analytics/stampcard_api?param=' + param,
            type: 'POST',
            dataType: 'json',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', csrf);
            },
            timeout: 5000,
        })
        .done(function(result){
            var json = JSON.stringify(result);
            json = JSON.parse(json);
            $('#diary-all-pager').pagination({
                dataSource: json,
                pageSize: 10, // 1ページあたりの表示数
                prevText: '&lt;',
                nextText: '&gt;',
                ulClassName: 'c-pagination__list',
                callback: function(data) {
                    // dataの中に次に表示すべきデータが入っているので、html要素に変換
                    $('#ajax_list').html(template_stampcard(data));
                }
            });
        })
        .fail(function (XMLHttpRequest, textStatus, errorThrown){
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);
        });
    });
    /**
     * スタンプカード
     * ajax　作成日ソート処理
     * 
     */
    $('.jsc-create-date-stampcard').on('click', function(){
        var csrf = $('input[name=_csrfToken]').val();
        var param = $(this).prop('id');
        $.ajax({
            url: '/analytics/stampcard_api?param=' + param,
            type: 'POST',
            dataType: 'json',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', csrf);
            },
            timeout: 5000,
        })
        .done(function(result){
            var json = JSON.stringify(result);
            json = JSON.parse(json);
            $('#diary-all-pager').pagination({
                dataSource: json,
                pageSize: 10, // 1ページあたりの表示数
                prevText: '&lt;',
                nextText: '&gt;',
                ulClassName: 'c-pagination__list',
                callback: function(data) {
                    // dataの中に次に表示すべきデータが入っているので、html要素に変換
                    $('#ajax_list').html(template_stampcard(data));
                }
            });
        })
        .fail(function (XMLHttpRequest, textStatus, errorThrown){
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);
        });
    });
    

    function template_coupon(data) {
        return data.map(function(val){
            var dl = 0;
            var count = 0;
            var beforeDate = moment(val.update_at,'YYYY/MM/DD');
            var update_at = beforeDate.format('YYYY/MM/DD');
            var beforeDate2 = moment(val.create_at,'YYYY/MM/DD');
            var create_at = beforeDate2.format('YYYY/MM/DD');

            // DL数
            $.each(val.child_coupons, function(i,dl_val){
                dl++;
            });
            // 使用回数
            $.each(val.child_coupons, function(limit,count_limit){
                count = count_limit.limit_count;
            });

            if(val.release_id == 1){
                return "<tr class='c-analytics__table__tr'>" +
                    "<td><a href='/coupons/"+val.id+"/edit'>"+val.title+"</a></td>"+
                    "<td><span class='c-analytics__table__tag is-active'>公開中</span></td>"+
                    "<td>"+ dl +"</td>"+
                    "<td>"+ count +"</td>"+
                    "<td>"+ update_at +"</td>"+
                    "<td>"+ create_at +"</td>"+
                "</tr>";
            } else {
                return "<tr class='c-analytics__table__tr'>" +
                    "<td><a href='/coupons/"+val.id+"/edit'>"+val.title+"</a></td>"+
                    "<td><span class='c-analytics__table__tag'>非公開</span></td>"+
                    "<td>"+ dl +"</td>"+
                    "<td>"+ count +"</td>"+
                    "<td>"+ update_at +"</td>"+
                    "<td>"+ create_at +"</td>"+
                "</tr>";
            }
        })
    }
    function template_stampcard(data) {
        return data.map(function(val){
            var dl = 0;
            var beforeDate = moment(val.update_at,'YYYY/MM/DD');
            var update_at = beforeDate.format('YYYY/MM/DD');
            var beforeDate2 = moment(val.create_at,'YYYY/MM/DD');
            var create_at = beforeDate2.format('YYYY/MM/DD');

            // DL数
            $.each(val.child_stampcards, function(i,dl_val){
                dl++;
            });

            if(val.release_id == 1){
                return "<tr class='c-analytics__table__tr'>" +
                    "<td><a href='/stampcards/"+val.id+"/edit'>"+val.title+"</a></td>"+
                    "<td><span class='c-analytics__table__tag is-active'>公開中</span></td>"+
                    "<td>"+ dl +"</td>"+
                    "<td>"+ update_at +"</td>"+
                    "<td>"+ create_at +"</td>"+
                "</tr>";
            } else {
                return "<tr class='c-analytics__table__tr'>" +
                    "<td><a href='/stampcards/"+val.id+"/edit'>"+val.title+"</a></td>"+
                    "<td><span class='c-analytics__table__tag'>非公開</span></td>"+
                    "<td>"+ dl +"</td>"+
                    "<td>"+ update_at +"</td>"+
                    "<td>"+ create_at +"</td>"+
                "</tr>";
            }
        })
    }
});