
<section class="p-couponConfirm">
    <div class="p-couponConfirm__title__wrap">
        <h1 class="p-couponConfirm__title">クーポン</h1>
    </div>
    <div class="p-couponConfirm__contents">
        <?= $this->Form->create() ?>
            <div class="p-couponConfirm__item__wrap">
              <?php if(date('Y/m/d', strtotime($child_coupon->coupon->before_expiry_date)) > date('Y/m/d', strtotime('now'))): ?>
                <p class="c-confirm__tab__error">開始期限前のため利用できません</p>
              <?php elseif(date('Y/m/d', strtotime($child_coupon->coupon->after_expiry_date)) < date('Y/m/d', strtotime('now'))): ?>
                <p class="c-confirm__tab__error">期限切れのため利用できません</p>
              <?php elseif(!empty($state_flg)): ?>
                <div class="c-confirm__tab__error">使用済のクーポンです</div>
              <?php else: ?>
                <div class="c-confirm__tab">使用できるクーポンです</div>
              <?php endif; ?>
                <fieldset>
                    <div class="p-couponConfirm__item">
                        <p class='p-couponConfirm__item__text'>
                        <?= $child_coupon->coupon->title ?>
                        <dd><?= $child_coupon->coupon->content ?></dd>
                        </p>
                    </div>
                    <div class="p-couponConfirm__item">
                        <p class="p-couponConfirm__item__label">特典</p>
                        <p class="p-couponConfirm__item__text">
                        <?= $child_coupon->coupon->reword ?>
                    </div>
                    <div class="p-couponConfirm__item">
                        <p class="p-couponConfirm__item__label">対象店舗</p>
                        <?php foreach ($child_coupon->coupon->coupon_shops as $shop): ?>
                          <p class="p-couponConfirm__item__text">
                          <?= $shop->shop->name ?>
                        <?php endforeach; ?>
                    </div>
                    <div class="p-couponConfirm__item">
                        <p class="p-couponConfirm__item__label">有効期限</p>
                        <div class="p-couponConfirm__item__list">
                          <dd class="p-couponConfirm__item__text"><?= date('Y/m/d', strtotime($child_coupon->coupon->before_expiry_date)) ?></dd>
                          <p class="p-couponConfirm__item__tilde">~</p>
                          <dd class="p-couponConfirm__item__text"><?= date('Y/m/d', strtotime($child_coupon->coupon->after_expiry_date)) ?></dd>
                        </div>
                    </div>
                    <div class="p-couponConfirm__item">
                        <p class="p-couponConfirm__item__label">利用回数</p>
                        <p class="p-couponConfirm__item__text">
                        <?= $child_coupon->limit_count ?>/<?= $child_coupon->coupon->limit ?>
                        </p>
                    </div>
                </fieldset>
                <?php if(date('Y/m/d', strtotime($child_coupon->coupon->before_expiry_date)) <= date('Y/m/d', strtotime('now')) && date('Y/m/d', strtotime($child_coupon->coupon->after_expiry_date)) >= date('Y/m/d', strtotime('now')) && empty($state_flg)): ?>
                  <?= $this->Form->control('limit_count', ['type' => 'hidden', 'value' => 'true']) ?>
                  <p class="p-couponConfirm__btn jsc-modal-trigger c-btn--coupon__orange">このクーポンを使用する</p>
                <?php endif; ?>
                <div class='p-couponConfirm__btn c-btn--white'>
                    <?= $this->Html->link('戻る', ['action' => 'index']) ?>
                </div>
            </div>
    </div>
    <div class="p-couponConfirm__overlay model-overlay" style="display:none">
    <div class="p-couponConfirm__modal">
        <div class="p-couponConfirm__modal__content">
            <p class="p-couponConfirm__modal__heading">クーポンを使用しました！</p>
            <div class="p-couponConfirm__modal__btn__wrap">
                <?= $this->Form->button('OK' , ['class' => 'p-couponConfirm__modal__btn jsc-check-decied c-btn--orange']) ?>
            </div>
        </div>
    </div>
    <?= $this->Form->end() ?>
</section>
<?php echo $this->element('footer');

