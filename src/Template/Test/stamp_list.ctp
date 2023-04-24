<div class="p-stampList">
    <section class="p-stampList__title__wrap">
        <h1 class="p-stampList__title">スタンプカード管理</h1>
    </section>
    <div class="p-stampList__contents">
        <div class="p-stampList__btn">
            <p class="p-stampList__text">メニュー</p>
            <div class="p-stampList__btn__wrap">
                <a href="">
                    <p class="p-stampList__btn__text p-stampList__btn__img--stamp">新規発行</p>
                </a>
            </div>
            <div class="p-stampList__btn__wrap">
                <a href="">
                    <p class="p-stampList__btn__text p-stampList__btn__img--analytics">分析</p>
                </a>
            </div>
        </div>
        <div class="p-stampList__input__wrap">
            <p class="p-stampList__label">スタンプカード一覧</p>
            <div class="p-stampList__select__wrap">
                <select class="p-stampList__select">
                    <option>全所属店舗</option>
                </select>
            </div>
        </div>
        <div class="p-stampList__stamp__wrap">
            <div class="p-stampList__stamp__tabs">
                <div class="p-stampList__stamp__tab is-active">
                    <p class="p-stampList__stamp__tab__text">公開中<span>1201</span></p>
                </div>
                <div class="p-stampList__stamp__tab">
                    <p class="p-stampList__stamp__tab__text">非公開<span>15</span></p>
                </div>
            </div>
            <div class="p-stampList__stamp__list__wrap">
                <div class="p-stampList__stamp__list__text__wrap">
                    <p class="p-stampList__stamp__list__text">
                        発行されているスタンプカードがありません。<br>
                        ページ上部の新規発行ボタンよりスタンプカードを作成してください。
                    </p>
                </div>
                <div class="p-stampList__stamp__list is-active">
                    <div class="p-stamp__card">
                        <div class="p-stamp__card__top">
                            <p class="p-stamp__card__img"><?php echo $this->Html->image('img_default.png', ['alt' => 'logo']);?></p>
                            <div class="p-stamp__card__top__wrap">
                                <p class="p-stamp__card__shop">ROLLEN原宿店</p>
                                <p class="p-stamp__card__date">有効期限：2020.12.01</p>
                            </div>
                        </div>
                        <div class="p-stamp__card__bottom">
                            <p class="p-stamp__card__dl">201<span>DL</span></p>
                        </div>
                    </div>
                    <div class="p-stamp__card">
                        <div class="p-stamp__card__top">
                            <p class="p-stamp__card__img"><?php echo $this->Html->image('img_default.png', ['alt' => 'logo']);?></p>
                            <div class="p-stamp__card__top__wrap">
                                <p class="p-stamp__card__shop">ROLLEN原宿店</p>
                                <p class="p-stamp__card__date">有効期限：2020.12.01</p>
                            </div>
                        </div>
                        <div class="p-stamp__card__bottom">
                            <p class="p-stamp__card__dl">201<span>DL</span></p>
                        </div>
                    </div>
                </div>
                <div class="p-stampList__stamp__list">
                    <div class="p-stamp__card">
                        <div class="p-stamp__card__top">
                            <p class="p-stamp__card__img"><?php echo $this->Html->image('img_default.png', ['alt' => 'logo']);?></p>
                            <div class="p-stamp__card__top__wrap">
                                <p class="p-stamp__card__shop">ROLLEN原宿店</p>
                                <p class="p-stamp__card__date">有効期限：2020.12.01</p>
                            </div>
                        </div>
                        <div class="p-stamp__card__bottom">
                            <p class="p-stamp__card__dl">201<span>DL</span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-stampList__stamp__pagenation__wrap">
                <div class="p-stampList__stamp__pagenation">
                    <div class="p-stampList__stamp__pagenation__left"><</div>
                    <div class="p-stampList__stamp__pagenation__center">1/5</div>
                    <div class="p-stampList__stamp__pagenation__right">></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    echo $this->element('footer_login');
    echo $this->element('footer_menu');
