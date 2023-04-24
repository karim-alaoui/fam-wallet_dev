<?php
if(isset($childCoupon)) {
    $coupon = $childCoupon->affiliater_coupon->coupon;
}
?>
<?php if(isset($childCoupon)):?>
<?php $info = $this->AffiliaterCoupons->infoMessage($coupon);?>
<div id="qr-detail" class="p-couponEdit">
  <section class="p-couponEdit__title__wrap">
    <h1 class="p-couponEdit__title">クーポン</h1>
  </section>

    <?= $this->Form->create();?>
  <div class="info-box flex align-items-center justify-content-center m-15 <?= $info['class']?>">
    <p class="u-fs-12"><?= $info['text']; ?></p>
  </div>

  <div class="p-couponEdit__contents">
    <div class="p-couponEdit__item__wrap">
      <div>
        <p class="u-fs-14 font-weight-600"><?= $coupon->title; ?></p>
        <p class="p-couponEdit__item__text"><?= $coupon->content ?></p>
      </div>
    </div>
  </div>

  <div class="p-couponEdit__contents">
    <div class="p-couponEdit__item__wrap">
      <div class="p-couponEdit__item">
        <p class="p-couponEdit__item__label">特典</p>
        <p class="p-couponEdit__item__text"><?= $coupon->reword ?></p>
      </div>

      <div class="p-couponEdit__item">
        <p class="p-couponEdit__item__label">対象店舗</p>
          <?php foreach ($coupon->coupon_shops as $coupon_shops) : ?>
            <p class="p-couponEdit__item__text">
                <?= $coupon_shops->shop->name ?>
            </p>
          <?php endforeach; ?>
      </div>

      <div class="p-couponEdit__item">
        <p class="p-couponEdit__item__heading">有効期限</p>
        <p class="p-couponEdit__item__text"><?= date('Y/m/d', strtotime($coupon->before_expiry_date)) ?> ~ <?= date('Y/m/d', strtotime($coupon->after_expiry_date)) ?></p>
      </div>
    </div>

      <div class="p-couponEdit__item flex justify-content-between">
        <p class="p-couponEdit__item__label">利用回数</p>
        <p class="">
            <?php $child_count = 0; foreach ($coupon->affiliater_child_coupons as $child_coupon): ?>
                <?php $child_count  += $child_coupon->used_count ?>
            <?php endforeach; ?>
          <span class="u-fs-24"><?= $child_count ?></span>/<span><?= $coupon->limit ?></span>
        </p>
      </div>

    <?php if($childCoupon->affiliater_coupon->type == 1):?>
    <div class="c-input__form__input__wrap">
      <div class="c-input__form__label__wrap">
        <p class="c-input__form__label">支払金額</p>
      </div>
        <?= $this->Form->control('price' , ['type' => 'number' , 'class' => 'c-input__form__input jsc-input-text', 'required']) ?>
      <p class="u-fs-10 color--gray">この金額の<?= $childCoupon->affiliater_coupon->rate ?>%がアフィリエイト報酬になります。</p>
    </div>
    <?php endif;?>
  </div>

  <div class="p-signUp__form__checkbox__wrap ml-10 mr-10">
    <?php if($info['class'] === 'info-orange'):?>
        <?= $this->Form->button('このクーポンを使用する' , ['class' => 'c-input__form__btn c-btn--orange']) ?>
    <?php endif;?>
    <div class="p-couponEdit__form__btn c-btn--white"><a href="/">戻る</a></div>
  </div>

  <?= $this->Form->end();?>

</div>
<?php else :?>
    <section class="p-couponEdit__title__wrap">
        <h1 class="p-couponEdit__title">クーポンが見つかりません。</h1>
    </section>

    <div class="p-signUp__form__checkbox__wrap ml-10 mr-10">
        <div class="p-couponEdit__form__btn c-btn--white"><a href="/">戻る</a></div>
    </div>
<?php endif;?>
<?php
/**
 * @var \App\View\AppView $this
 */
echo $this->element('footer_login');
?>
