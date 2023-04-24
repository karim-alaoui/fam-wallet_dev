<div class="p-couponList">
    <section class="p-couponList__title__wrap">
        <h1 class="p-couponList__title">クーポン管理</h1>
    </section>
    <?php echo $this->element('payment_pending_notif'); ?>
    <div class="p-couponList__contents">
        <div class="p-couponList__btn">
          <?php if ($auth['role_id'] == 1 || $auth['role_id'] == 2): ?>
            <div class="p-couponList__btn__wrap">
                <?= $this->Html->link(
                    $this->Html->para('p-couponList__btn__text p-couponList__btn__img--coupon' , '新規発行'),
                    '/coupons/new',
                    ['escape' => false]) ?>
            </div>
            <div class="p-couponList__btn__wrap">
                <?= $this->Html->link(
                        $this->Html->para('p-couponList__btn__text p-couponList__btn__img--analytics' , '分析'),
                        '/analytics/coupons',
                        ['escape' => false]) ?>
            </div>
          <?php endif; ?>
        </div>
        <div class="p-couponList__input__wrap">
            <p class="p-couponList__label">クーポン一覧</p>
            <div class="p-couponList__select__wrap">
                <?= $this->Form->create() ?>
                <?= $this->Form->control('shop' , ['type' => 'select', 'options' => $shop_list, 'multiple' => false , 'class' => 'p-couponList__select']) ?>
            </div>
            <?= $this->Form->button('店舗検索',['class' => 'p-couponEdit__form__btn c-btn--orange']) ?>
            <?= $this->form->end() ?>
        </div>
        <div class="p-couponList__coupon__wrap">
            <div class="p-couponList__coupon__tabs">
                <div class="p-couponList__coupon__tab jsc-coupon-public is-active">
                    <p class="p-couponList__coupon__tab__text">公開中<span><?= $release_record ?></span></p>
                </div>
                <div class="p-couponList__coupon__tab jsc-coupon-private">
                    <p class="p-couponList__coupon__tab__text">非公開<span><?= $private_record ?></span></p>
                </div>
            </div>
            <div class="p-couponList__coupon__list__wrap">
                <div class="p-couponList__coupon__list__text__wrap">
                    <p class="p-couponList__coupon__list__text">
                        発行されているクーポンがありません。<br>
                        ページ上部の新規発行ボタンよりクーポンを作成してください。
                    </p>
                </div>
                <div class="p-couponList__coupon__list jsc-coupon-public is-active">
                  <?php foreach ($result_record as $coupon): ?>
                  <?php if ($coupon->release_id == 1): ?>
                    <a href="<?= $this->Url->build(['controller' => 'Coupons', 'action' => 'edit', $coupon->id]) ?>">
                        <ul class="p-couponList__coupon">
                            <li class="p-couponList__coupon__content">
                                <p class='p-couponList__coupon__heading'><?= $coupon->title ?></p>
                                <?php foreach ($coupon->coupon_shops as $shop_name): ?>
                                <p class='p-couponList__coupon__shop'><?= $shop_name->shop->name ?></p>
                                <?php endforeach; ?>
                                <p class='p-couponList__coupon__price'><?= $coupon->reword ?></p>
                            </li>
                            <li  class="p-couponList__coupon__use">
                                <p class="p-couponList__coupon__text">使用回数</p>
                                <p class="p-couponList__coupon__num">
                                <?php $child_count = 0; foreach ($coupon->child_coupons as $child_coupon): ?>
                                  <?php $child_count  += $child_coupon->limit_count ?>
                                <?php endforeach; ?>

                                <?php foreach($coupon->affiliater_child_coupons as $child_coupon):?>
                                  <?php $child_count += $child_coupon->used_count;?>
                                <?php endforeach;?>

                                <?= $child_count ?>
                                </p>
                            </li>
                        </ul>
                    </a>
                  <?php endif; ?>
                  <?php endforeach; ?>
                </div>
                <div class="p-couponList__coupon__list jsc-coupon-private">
                  <?php foreach ($result_record as $coupon): ?>
                  <?php if ($coupon->release_id == 2): ?>
                    <a href="<?= $this->Url->build(['controller' => 'Coupons', 'action' => 'edit', $coupon->id]) ?>">
                        <ul class="p-couponList__coupon">
                            <div class="p-couponList__coupon__content">
                                <p class='p-couponList__coupon__heading'><?= $coupon->title ?>
                                <?php foreach ($coupon->coupon_shops as $shop_name): ?>
                                <p class='p-couponList__coupon__shop'><?= $shop_name->shop->name ?>
                                <?php endforeach; ?>
                                <p class='p-couponList__coupon__price'><?= $coupon->reword ?>
                            </div>
                            <li class="p-couponList__coupon__use">
                                <p class="p-couponList__coupon__text">使用回数</p>
                                <p class="p-couponList__coupon__num">
                                <?php $child_count = 0; foreach ($coupon->child_coupons as $child_coupon): ?>
                                  <?php $child_count  += $child_coupon->limit_count ?>
                                <?php endforeach; ?>
                                <?= $child_count ?>
                            </li>
                        </ul>
                    </a>
                  <?php endif; ?>
                  <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    echo $this->element('footer_login');
    echo $this->element('footer_menu');
