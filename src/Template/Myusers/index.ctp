<section class="p-userTop__header">
    <h1 class="p-userTop__header__title">Get real</h1>
    <div class="p-userTop__header__btn__box">
        <div class="p-userTop__header__btn">
            <a href=<?= $this->Url->build(['controller' => 'Coupons', 'action' => 'index']) ?>>
                <p class="p-userTop__header__btn__img"><?php echo $this->Html->image('icon/icon_coupon.png', ['alt' => 'クーポン']);?></p>
                <p class="p-userTop__header__btn__heading">クーポン</p>
            </a>
        </div>
        <div class="p-userTop__header__btn">
            <a href=<?= $this->Url->build(['controller' => 'Stampcards', 'action' => 'index']) ?>>
                <p class="p-userTop__header__btn__img"><?php echo $this->Html->image('icon/icon_stampcard.png', ['alt' => 'スタンプカード']);?></p>
                <p class="p-userTop__header__btn__heading">スタンプカード</p>
            </a>
        </div>
    </div>
</section>
<?php echo $this->element('payment_pending_notif'); ?>
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
    <p class="p-userTop__heading">人気</p>
    <ul class="p-userTop__list">
        <li class="p-userTop__listItem jsc-coupon-count is-active">クーポン</li>
        <li class="p-userTop__listItem jsc-stamp-count">スタンプ</li>
    </ul>
    <div class="p-userTop__content is-active" id='jsc-coupon-count'>
      <?php if (!empty($coupon_list)): ?>
        <?php foreach ($coupon_list as $coupon) : ?>
        <a href="<?= $this->Url->build(['controller' => 'Coupons', 'action' => 'edit', $coupon->id]) ?>">
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
                    <?= $child_count ?>
                    </p>
                </li>
            </ul>
        </a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div class="p-userTop__content" id='jsc-stamp-count'>
    <?php if (!empty($stampcard_list)): ?>
      <?php foreach ($stampcard_list as $stampcard): ?>
      <a href="<?= $this->Url->build(['controller' => 'Stampcards', 'action' => 'edit', $stampcard->id]) ?>">
        <ul class="p-userTop__card">
            <li class="p-userTop__card__top">
                <p class="p-userTop__card__img">
                <?php if (file_exists(WWW_ROOT.'img/shop_images/'.$stampcard->stampcard_shops[0]['shop_id'].'/'.$stampcard->stampcard_shops[0]->shop->image)): ?>
                  <?=  $this->Html->image('shop_images/'.$stampcard->stampcard_shops[0]->shop_id.'/'.$stampcard->stampcard_shops[0]->shop->image, ['alt' => 'logo']);?>
                <?php else: ?>
                  <?=  $this->Html->image('img_default.png', ['alt' => 'logo']);?>
                <?php endif; ?>
                </p>
                <div class="p-userTop__card__top__wrap">
                    <p class="p-userTop__card__shop">
                        <?= $stampcard->title ?>
                    </p>
                    <p class="p-userTop__card__date">
                        有効期限：<?= date('Y/m/d', strtotime($stampcard->after_expiry_date)) ?>
                    </p>
                </div>
            </li>
            <li class="p-userTop__card__bottom">
                <p class="p-userTop__card__dl">
                  <?php $i = 0; foreach ($stampcard->child_stampcards as $dl_val): ?>
                    <?php $i++ ?>
                  <?php endforeach; ?>
                  <?= $i ?> DL
                </p>
            </li>
        </ul>
      </a>
      <?php endforeach; ?>
    <?php endif; ?>
    </div>
</div>
<?php
    echo $this->element('footer_login');
    echo $this->element('footer_menu');
