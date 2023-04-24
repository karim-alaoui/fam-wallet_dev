<div class="p-analytics">
    <section class="p-analytics__title__wrap">
        <h1 class="p-analytics__title">分析</h1>
    </section>
    <div class="p-analytics__tabs">
        <div class="p-analytics__tab jsc-coupon-count is-active">
            <p class="p-analytics__tab__text">クーポン</p>
        </div>
        <div class="p-analytics__tab jsc-stamp-count">
            <p class="p-analytics__tab__text">スタンプカード</p>
        </div>
    </div>
    <div class="p-analytics__box">
        <div class="p-analytics__select__wrap">
            <?= $this->Form->control('analytics_list' , ['type' => 'select' , 'options' => $shop_list , 'multiple' => false , 'class' => 'p-analytics__select']) ?>
        </div>
        <?php if($hoge): ?> <!-- controllerからtrueかfalseかで絞り込みされたかを判断する -->
        <p class="p-analytics__text">><?= date('Y/m/d', strtotime($analytics->before_expiry_date)) ?> ~ <?= date('Y/m/d', strtotime($analytics->after_expiry_date)) ?></p>
        <?php endif; ?>
        <p class="p-analytics__link"><a href="/analytics/narrowdown">期間を絞り込む</a></p>
    </div>
    <div class="p-analytics__contents is-active" id="jsc-coupon-count"> <!-- 以下Couponsから各パラメータを引っ張ってくる -->
        <table class="p-analytics__table">
            <thead class="p-analytics__table__thead">
                <tr class="p-analytics__table__tr">
                    <th>タイトル</th>
                    <th>状態</th>
                    <th>DL数</th>
                    <th>使用回数</th>
                    <th>最終更新日</th>
                    <th>作成日</th>
                </tr>
            </thead>
            <tbody class="p-analytics__table__tbody">
                <tr class="p-analytics__table__tr">
                    <td><a href="">大人気メニュー☆カラーfdfdsfdsdsjgfdofdsafdsatgrvjfd</a></td>
                    <td><span class="p-analytics__table__tag is-active">公開中</span></td>
                    <td>100</td>
                    <td>213</td>
                    <td>2019/11/13</td>
                    <td>2019/10/10</td>
                </tr>
                <tr class="p-analytics__table__tr">
                    <td><a href="">大人気メニュー☆カラー</a></td>
                    <td><span class="p-analytics__table__tag">非公開</span></td>
                    <td>100</td>
                    <td>213</td>
                    <td>2019/11/13</td>
                    <td>2019/10/10</td>
                </tr>
            </tbody>
        </table>
        <div class="p-analytics__pagenation__wrap">
            <ul class="c-pagination__list">
                <div class="p-analytics__pagenation__left"><?= $this->Paginator->prev('<') ?></div>
                <div class="p-analytics__pagenation__center"><?= $this->Paginator->numbers(['first' => 1, 'last' => 1, 'modulus' => '2']) ?></div>
                <div class="p-analytics__pagenation__right"><?= $this->Paginator->next('>') ?></div>
            </ul>
        </div>
    </div>
    <div class="p-analytics__contents" id="jsc-stamp-count"> <!-- 以下Stampcardsから各パラメータを引っ張ってくる -->
        <table class="p-analytics__table">
            <thead class="p-analytics__table__thead">
                <tr class="p-analytics__table__tr"> 
                    <th>タイトル</th>
                    <th>状態</th>
                    <th>DL数</th>
                    <th>使用回数</th>
                    <th>最終更新日</th>
                    <th>作成日</th>
                </tr>
            </thead>
            <tbody class="p-analytics__table__tbody">
                <tr class="p-analytics__table__tr">
                    <td><a href="">大人気メニュー☆カラー</a></td>
                    <td><span class="p-analytics__table__tag">非公開</span></td>
                    <td>100</td>
                    <td>213</td>
                    <td>2019/11/13</td>
                    <td>2019/10/10</td>
                </tr>
            </tbody>
        </table>
        <div class="p-analytics__pagenation__wrap">
            <ul class="c-pagination__list">
                <div class="p-analytics__pagenation__left"><?= $this->Paginator->prev('<') ?></div>
                <div class="p-analytics__pagenation__center"><?= $this->Paginator->numbers(['first' => 1, 'last' => 1, 'modulus' => '2']) ?></div>
                <div class="p-analytics__pagenation__right"><?= $this->Paginator->next('>') ?></div>
            </ul>
        </div>
    </div>
</div>
<?php echo $this->element('footer');
