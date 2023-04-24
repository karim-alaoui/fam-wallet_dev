<div class="p-couponEdit">
    <section class="p-couponEdit__title__wrap">
        <h1 class="p-couponEdit__title">クーポン詳細</h1>
    </section>

  <div class="p-couponEdit__btn">
    <p class="p-couponEdit__btn__heading">クーポンをシェアする</p>
    <div class="p-couponEdit__btn__wrap">
      <button class="p-couponEdit__btn__text p-couponEdit__btn__img--share" id="jsc-linkcopy" value="<?= $qrUrl ?>">リンクをコピー</button>
    </div>

  </div>
    <div class="p-couponEdit__status__wrap">
        <div class="p-couponEdit__status">
            <p class="p-couponEdit__status__name">DL数</p>
            <p class="p-couponEdit__status__count">
            <?php $i = 0; foreach ($coupon->child_coupons as $child_coupon): ?>
              <?php $i++ ?>
            <?php endforeach; ?>
            <?= $i ?>
            </p>
        </div>
        <div class="p-couponEdit__status">
            <p class="p-couponEdit__status__name">使用回数</p>
            <p class="p-couponEdit__status__count">
            <?php $count = 0; foreach ($coupon->child_coupons as $child_coupon): ?>
              <?php $count += $child_coupon->limit_count ?>
            <?php endforeach; ?>
            <?= $count ?>
            </p>
        </div>
    </div>
    <?php $coupon = $affiliaterCoupon->coupon;?>
    <div class="p-couponEdit__contents">
        <div class="p-couponEdit__item__wrap">
            <div class="p-couponEdit__item">
                <p class="p-couponEdit__item__label">クーポンタイトル</p>
                <p class="p-couponEdit__item__text"><?= $coupon->title ?></p>
            </div>
            <div class="p-couponEdit__item">
                <p class="p-couponEdit__item__label">内容</p>
                <p class="p-couponEdit__item__text"><?= $coupon->content ?></p>
            </div>
            <div class="p-couponEdit__item">
                <p class="p-couponEdit__item__label">特典</p>
                <p class="p-couponEdit__item__text"><?= $coupon->reword ?>
            </div>
            <div class="p-couponEdit__item">
                <p class="p-couponEdit__item__label">利用回数</p>
                <p class="p-couponEdit__item__text"><?= $coupon->limit ?>
            </div>
            <div class="p-couponEdit__item">
                <p class="p-couponEdit__item__label">対象店舗</p>
                <?php foreach ($coupon->coupon_shops as $coupon_shops) : ?>
                <p class="p-couponEdit__item__text">
                    <?= $coupon_shops->shop->name ?>
                </p>
                <?php endforeach; ?>
            </div>
            <div class="p-stampEdit__item">
                <p class="p-couponEdit__item__heading">有効期限</p>
                <p class="p-couponEdit__item__text"><?= date('Y/m/d', strtotime($coupon->before_expiry_date)) ?> ~ <?= date('Y/m/d', strtotime($coupon->after_expiry_date)) ?></p>
            </div>
           
            <div class="p-couponEdit__item">
                <p class="p-couponEdit__item__label">色</p>
                    <?php if ($coupon->background_color == '255,255,255'): ?>
                      <div class="p-couponConfirm__item__colorTile u-bg__color--gray">
                    <?php elseif ($coupon->background_color == '51,51,51'): ?>
                      <div class="p-couponConfirm__item__colorTile u-bg__color--black">
                    <?php elseif ($coupon->background_color == '213,80,80'): ?>
                      <div class="p-couponConfirm__item__colorTile u-bg__color--red">
                    <?php elseif ($coupon->background_color == '62,122,211'): ?>
                      <div class="p-couponConfirm__item__colorTile u-bg__color--blue">
                    <?php elseif ($coupon->background_color == '14,198,116'): ?>
                      <div class="p-couponConfirm__item__colorTile u-bg__color--green">
                    <?php elseif ($coupon->background_color == '245,210,28'): ?>
                      <div class="p-couponConfirm__item__colorTile u-bg__color--yellow">
                    <?php elseif ($coupon->background_color == '236,161,37'): ?>
                      <div class="p-couponConfirm__item__colorTile u-bg__color--orange">
                    <?php elseif ($coupon->background_color == '105,58,202'): ?>
                      <div class="p-couponConfirm__item__colorTile u-bg__color--purple">
                    <?php elseif ($coupon->background_color == '233,156,157'): ?>
                      <div class="p-couponConfirm__item__colorTile u-bg__color--pink">
                    <?php elseif ($coupon->background_color == '112,82,63'): ?>
                      <div class="p-couponConfirm__item__colorTile u-bg__color--brown">
                    <?php endif; ?>
                    <span>A</span>
                </div>
            </div>
        </div>
    </div>

         <p class="p-couponEdit__heading">アフィリエイト</p>
         <div>
             <div class="p-couponEdit__contents">
                 <dl class="p-couponEdit__item">
                     <dt class="p-couponEdit__item__heading">報酬方式</dt>
                     <p class="p-couponEdit__item__text">
                         <?= $this->AffiliaterCoupons->getRewordTypeString($affiliaterCoupon->type); ?>
                     </p>
                 </dl>
                 <dl class="p-couponEdit__item">
                     <dt class="p-couponEdit__item__heading">報酬</dt>
                     <p class="p-couponEdit__item__text">
                         <?= $this->AffiliaterCoupons->getRateString($affiliaterCoupon->type, $affiliaterCoupon->rate); ?>
                     </p>
                 </dl>
             </div>
         </div>

                        <div class="p-couponEdit__contents">
                          <div class="c-input__form__btn c-btn--white"><a href="/affiliater-coupons">戻る</a></div>
                        </div>
    <div class="p-couponEdit__delete__wrap">
        <!--<p class="p-couponEdit__delete jsc-modal-trigger">このクーポンを削除する</p>-->
    </div>
</div>
<div class="p-couponEdit__overlay model-overlay" style="display:none">
    <div class="p-couponEdit__modal">
        <p class="p-couponEdit__modal__heading">本当に削除しますか？</p>
        <p class="p-couponEdit__modal__text">クーポンを削除すると分析など紐づく情報全てが削除されます</p>
        <div class="p-couponEdit__modal__btn__wrap">
            <button class="p-couponEdit__modal__btn jsc-check-cancel c-btn--gray">キャンセル</button>
            <?= $this->Form->postButton('削除', ['controller' => 'AffiliaterCoupons', 'action' => 'delete', $affiliaterCoupon->id], ['class' => 'p-couponEdit__modal__btn jsc-check-decied c-btn--red']) ?>
        </div>
    </div>
    <div class="p-couponEdit__modal" style="display:none">
        <div class="p-couponEdit__modal__close">
            <span></span>
            <span></span>
        </div>
        <p class="p-couponEdit__modal__heading">QRコードを取得できませんでした</p>
        <p class="p-couponEdit__modal__text">しばらくしてからもう一度お試しください</p>
        <?= $this->Form->button('リトライ',['class' => 'p-couponEdit__modal__btn--retry jsc-check-decied c-btn--orange']) ?>
    </div>
</div>
<?php
/**
 * @var \App\View\AppView $this
 */

echo $this->element('footer');
