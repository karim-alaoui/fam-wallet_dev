<div class="c-analytics">
    <section class="c-analytics__title__wrap">
        <h1 class="c-analytics__title">分析</h1>
    </section>
    <div class="c-analytics__box">
        <?= $this->Form->create() ?>
        <div class="c-analytics__select__wrap">
            <?= $this->Form->control('coupon_list' , ['type' => 'select', 'options' => $shop_list, 'multiple' => false , 'class' => 'c-analytics__select']) ?>
        </div>
        <?= $this->Form->button('店舗検索',['class' => 'p-couponEdit__form__btn c-btn--orange']) ?>
        <?= $this->Form->end() ?>
        <?= $this->Form->postlink('リセットする', ['action' => 'coupons', '?' => ['reset' => 1]], ['class' => 'c-analytics__form__reset']) ?>
        <?php if (!empty($start_date) && empty($end_date)): ?> <!-- controllerからtrueかfalseかで絞り込みされたかを判断する -->
          <p class="c-analytics__text"><?= $start_date ?> ~ 指定なし</p>
        <?php elseif (!empty($end_date) && empty($start_date)): ?>
          <p class="c-analytics__text">指定なし ~ <?= $end_date ?></p>
        <?php elseif (!empty($start_date) && !empty($end_date)): ?>
          <p class="c-analytics__text"><?= $start_date ?> ~ <?= $end_date ?></p>
        <?php endif; ?>
        <p class="c-analytics__link"><a href="/analytics/coupon_narrow_down">利用可能期間で絞り込む</a></p>
    </div>
    <div class="c-analytics__contents is-active" id="jsc-coupon-count"> <!-- 以下Couponsから各パラメータを引っ張ってくる -->
        <table class="c-analytics__table">
            <thead class="c-analytics__table__thead">
                <tr class="c-analytics__table__tr">
                    <th class="c-analytics__filter__active">タイトル</th>
                    <th class="c-analytics__filter__none">状態</th>
                    <th class="c-analytics__filter__active jsc-download-coupon" id="download">DL数</th>
                    <th class="c-analytics__filter__active jsc-usecount-coupon" id="usecount">使用回数</th>
                    <th class="c-analytics__filter__active jsc-last-updated-coupon" id="last_updated">最終更新日</th>
                    <th class="c-analytics__filter__active jsc-create-date-coupon" id="create_date">作成日</th>
                </tr>
            </thead>
            <tbody class="c-analytics__table__tbody" id="ajax_list">
              
            </tbody>
        </table>
        <div class="c-analytics__pagenation__wrap" id="diary-all-pager">
            
        </div>
    </div>
</div>
<?php echo $this->element('footer');
   echo $this->element('footer_menu');
?>
<?= $this->Html->script('//pagination.js.org/dist/2.1.5/pagination.min.js') ?>
<?= $this->Html->script('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.js') ?>
<?= $this->Html->script('//cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/locale/ja.js') ?>
<?= $this->Html->scriptStart([ 'block' => true ]) ?>
$(function(){
    $(window).on('load', function(){
        var csrf = $('input[name=_csrfToken]').val();
        $.ajax({
            url: '/analytics/coupon_api',
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
            $('#diary-all-pager').pagination({ // diary-all-pagerにページャーを埋め込む
                dataSource: json,
                pageSize: 10, // 1ページあたりの表示数
                prevText: '&lt;',
                nextText: '&gt;',
                ulClassName: 'c-pagination__list',
                // ページがめくられた時に呼ばれる
                callback: function(data, pagination) {
                    // dataの中に次に表示すべきデータが入っているので、html要素に変換
                    $('#ajax_list').html(template(data)); // ajax_listにコンテンツを埋め込む
                }
            });
        })
        .fail(function (XMLHttpRequest, textStatus, errorThrown){
            console.log("XMLHttpRequest : " + XMLHttpRequest.status);
            console.log("textStatus     : " + textStatus);
            console.log("errorThrown    : " + errorThrown.message);
        });
    });

    function template(data) {
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
});
<?= $this->Html->scriptEnd() ?>