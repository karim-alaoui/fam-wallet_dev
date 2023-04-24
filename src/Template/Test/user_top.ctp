<section class="p-userTop__header">
    <h1 class="p-userTop__header__title">Get real</h1>
    <div class="p-userTop__header__btn__box">
        <div class="p-userTop__header__btn">
            <a href="#">
                <p class="p-userTop__header__btn__img"><?php echo $this->Html->image('icon/icon_coupon.png', ['alt' => 'クーポン']);?></p>
                <p class="p-userTop__header__btn__heading">クーポン</p>
            </a>
        </div>
        <div class="p-userTop__header__btn">
            <a href="#">
                <p class="p-userTop__header__btn__img"><?php echo $this->Html->image('icon/icon_stampcard.png', ['alt' => 'スタンプカード']);?></p>
                <p class="p-userTop__header__btn__heading">スタンプカード</p>
            </a>
        </div>
    </div>
</section>
<section class="p-userTop__notice">
    <h2 class="p-userTop__notice__heading">メンテナンスのお知らせ</h2>
    <div class="p-userTop__notice__close">
        <span></span>
        <span></span>
    </div>
    <p class="p-userTop__notice__text">
        2020年2月28日23:00〜2020年3月1日5:00の期間、システムメンテナンスのため、本システムはご利用いただけません。<br>
        ご不便をおかけいたしますが、ご理解いただきますようお願い申し上げます。
    </p>
</section>
<div class="p-userTop__contents">
    <p class="p-userTop__heading">人気</p>
    <ul class="p-userTop__list">
        <li class="p-userTop__listItem is-active">クーポン</li>
        <li class="p-userTop__listItem">スタンプ</li>
    </ul>
    <div class="p-userTop__content is-active">
        <div class="p-userTop__ticket">
            <div class="p-userTop__ticket__left">
                <p class="p-userTop__ticket__heading">大人気メニュー☆カラー＋トリートメント＋シールエ…</p>
                <p class="p-userTop__ticket__shop">ROLLEN原宿店,ROLLEN渋谷店,<br>ROLLEN HOMME…</p>
                <p class="p-userTop__ticket__price"><span>10<span>%</span></span>割引</p>
            </div>
            <div  class="p-userTop__ticket__right">
                <p class="p-userTop__ticket__text">使用回数</p>
                <p class="p-userTop__ticket__num">987</p>
            </div>
        </div>
    </div>
    <div class="p-userTop__content">
        <div class="p-userTop__card">
            <div class="p-userTop__card__top">
                <p class="p-userTop__card__img"><?php echo $this->Html->image('img_default.png', ['alt' => 'logo']);?></p>
                <div class="p-userTop__card__top__wrap">
                    <p class="p-userTop__card__shop">ROLLEN原宿店</p>
                    <p class="p-userTop__card__date">有効期限：2020.12.01</p>
                </div>
            </div>
            <div class="p-userTop__card__bottom">
                <p class="p-userTop__card__dl">201<span>DL</span></p>
            </div>
        </div>
    </div>
</div>
<?php
    echo $this->element('footer_login');
    echo $this->element('footer_menu');
