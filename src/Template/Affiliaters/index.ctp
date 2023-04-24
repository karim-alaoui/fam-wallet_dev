<section class="p-userTop__header">
    <h1 class="p-userTop__header__title">Get real</h1>
    <div class="p-userTop__header__btn__box">
        <div class="p-userTop__header__btn">
            <a href=<?= $this->Url->build(['controller' => 'AffiliaterPoints', 'action' => 'index']) ?>>
                <p class="p-userTop__header__btn__heading">あなたの総保有ポイント</p>
                <p class="flex align-items-center justify-content-center">
                    <?php echo $this->Html->image('icon/icon_coin.png', ['alt' => 'コイン', 'style' => 'width:20px;']);?>
                    <span class="affiliater__color__orange font-weight-500"><?= $point ?></span>
                </p>
            </a>
        </div>
    </div>
</section>
<section class="c-notice">
    <h2 class="c-notice__heading">メンテナンスのお知らせ</h2>
    <div class="c-notice__close">
        <span></span>
        <span></span>
    </div>
    <p class="c-notice__text"> 2020年2月28日23:00〜2020年3月1日5:00の期間、システムメンテナンスのため、本システムはご利用いただけません。<br>
        ご不便をおかけいたしますが、ご理解いただきますようお願い申し上げます。</p>
</section>
<div class="p-userTop__contents">
    <?php if(!$coupon_list):?>
    <p class="u-fs-12">表示できるクーポンが存在しません</p>
    <?php endif;?>
    <?php if($coupon_list):?>
    <p class="p-userTop__heading">よく使われているクーポン</p>
    <div class="p-userTop__content is-active" id='jsc-coupon-count'>
        <?php foreach ($coupon_list as $coupon) : ?>
            <a href="<?= $this->Url->build(['controller' => 'AffiliaterCoupons', 'action' => 'edit', $coupon->affiliater_coupons[0]->id]) ?>">
                <ul class="p-userTop__ticket">
                    <li class="p-userTop__ticket__content">
                        <p class="p-userTop__ticket__heading">
                            <?= $coupon->title ?>
                        </p>
                        <?php foreach ($coupon->coupon_shops as $shop): ?>
                            <p class="p-userTop__ticket__shop">
                                <?= $shop->shop->name ?>
                            </p>
                        <?php endforeach; ?>
                        <p class="p-userTop__ticket__price">
                            <?= $coupon->reword ?>
                        </p>
                        <p class="p-userTop__ticket__price__text">

                        </p>
                    </li>
                    <li class="p-userTop__ticket__use">
                        <p class="p-userTop__ticket__text">使用回数</p>
                        <p class="p-userTop__ticket__num">
                            <?php $child_count = 0; foreach ($coupon->child_coupons as $child_coupon): ?>
                                <?php $child_count += $child_coupon->limit_count ?>
                            <?php endforeach; ?>
                            <?php foreach ($coupon->affiliater_child_coupons as $child_coupon): ?>
                                <?php $child_count += $child_coupon->used_count ?>
                            <?php endforeach;?>
                            <?= $child_count ?>
                        </p>
                    </li>
                </ul>
            </a>
        <?php endforeach; ?>
    </div>
    <?php endif;?>
</div>
<?php
    echo $this->element('footer_login');
    echo $this->element('footer_menu_to_affiliater');
