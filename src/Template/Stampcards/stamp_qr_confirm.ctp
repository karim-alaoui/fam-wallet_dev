
<section class="p-stamp_Qr_Confirm">
    <div class="p-stampConfirm__title__wrap">
        <h1 class="p-stampConfirm__title">スタンプカード</h1>
    </div>
    <div class="p-stampConfirm__contents">
        <?= $this->Form->create() ?>
            <div class="p-stampConfirm__item__wrap">
                <?php if(date('Y/m/d', strtotime($child_stampcard->stampcard->before_expiry_date)) > (date('Y/m/d', strtotime('now')))): ?>
                  <p class="c-confirm__tab__error">開始期限前のため利用できません</p>
                <?php elseif(date('Y/m/d', strtotime($child_stampcard->stampcard->after_expiry_date)) < (date('Y/m/d', strtotime('now')))): ?>
                  <p class="c-confirm__tab__error">期限切れのため利用できません</p>
                <?php elseif (!empty($state_flg)): ?>
                  <p class="c-confirm__tab__error">特典を使用済のため利用できません</p>
                <?php elseif (!empty($change_flg)): ?>
                  <p class="c-confirm__tab">特典を引き換えられます</p>
                <?php else: ?>
                  <p class="c-confirm__tab">利用できるスタンプカードです</p>
                <?php endif; ?>
                <fieldset>
                    <div class="p-stampConfirm__item">
                        <p class="p-stampConfirm__item__title"><?= $child_stampcard->stampcard->title ?></p>
                        <p class="p-stampConfirm__item__text"><?= $child_stampcard->stampcard->content ?></p>
                    </div>
                    <div class="p-stampConfirm__item">
                        <p class="p-stampConfirm__item__label">特典</p>
                         <p class="p-stampConfirm__item__text"><?= $child_stampcard->stampcard->stampcard_rewords[0]['reword'] ?></p>
                    </div>
                    <div class="p-stampConfirm__item">
                        <p class="p-stampConfirm__item__label">対象店舗</p>
                          <?php foreach ($child_stampcard->stampcard->stampcard_shops as $shop): ?>
                            <p class="p-stampConfirm__item__text"><?=  $shop->shop->name ?></p>
                          <?php endforeach; ?>
                    </div>
                    <div class="p-stampConfirm__item">
                        <p class="p-stampConfirm__item__label">有効期限</p>
                        <div class="p-stampConfirm__item__list">
                            <dd class="p-stampConfirm__item__text"><?= date('Y/m/d', strtotime($child_stampcard->stampcard->before_expiry_date)) ?></dd>
                            <p class="p-stampConfirm__item__tilde">~</p>
                            <dd class="p-stampConfirm__item__text"><?= date('Y/m/d', strtotime($child_stampcard->stampcard->after_expiry_date)) ?></dd>
                        </div>
                    </div>
                    <div class="p-stampConfirm__currentNumber">
                        <p class="p-stamConfirm__currentNumber__label">現在のスタンプ数</p>
                        <div class="p-stamConfirm__currentNumber__list">
                            <p class="p-stampConfirm__item__stampNumber"><?= $child_stampcard->limit_count ?></p>
                            <p class="p-stampConfirm__item__stampNumber__text">/</p>
                            <p class="p-stampConfirm__item__stampNumber__denominator">
                              <?= $child_stampcard->stampcard->max_limit ?>
                            </p>
                        </div>
                    </div>
                    <?php if ($child_stampcard->stampcard->max_limit != $child_stampcard->limit_count): ?>
                      <div class="c-confirm__item">
                          <p class="p-stampConfirm__item__label">付与するスタンプ数</p>
                          <div class="p-stampConfirm__item__contents">
                            <div class="c-list__plus__btn">
                                <span class="c-list__add__btn__plus">
                                <span class="c-list__add__btn__plus">
                            </div>
                            <?= $this->Form->control('limit_count', ['type' => 'number', 'value' => '1', 'size' => '2', 'readonly' => true, 'class' => 'p-stampConfirm__item__number']) ?>
                            <div class="c-list__minus__btn">
                                <span class="c-list__add__btn__minus">
                            </div>
                          </div>
                      </div>
                    <?php endif; ?>
                </fieldset>
                <?php if (empty($state_flg) && $child_stampcard->stampcard->max_limit != $child_stampcard->limit_count): ?>
                  <?= $this->Form->button('確認', ['class' => 'p-stampConfirm__btn c-btn--orange']) ?>
                <?php endif; ?>
                <?php if(empty($state_flg) && $child_stampcard->stampcard->max_limit == $child_stampcard->limit_count): ?>
                  <div class="p-stampConfirm__btn c-btn--orange">
                    <p class="jsc-modal-trigger">特典を引き換える</p>
                  </div>
                <?php endif; ?>
                <div class='p-stampConfirm__btn c-btn--white'>
                    <?= $this->Html->link('戻る', ['action' => 'index']) ?>
                </div>
            </div>
    </div>
    <div class="p-stampConfirm__overlay model-overlay" style="display:none">
        <div class="p-stampConfirm__modal">
            <div class="p-stampConfirm__modal__content jsc-delete" style="">
                <p class="p-stampConfirm__modal__heading">今すぐ特典を引き換えしますか？</p>
                <p class="p-stampConfirm__modal__text">あとで引き換えることも可能です。</p>
                <div class="p-stampConfirm__modal__btn__wrap">
                    <button class="c-btn--modal--white--orange jsc-check-cancel">引き換えない</button>
                    <p class="p-stampConfirm__modal__btn jsc-modal-trigger-benefits c-btn--modal--orange">引き換える</p>
                </div>
            </div>
            <div class="p-stampConfirm__modal__content model-overlay-benefits" style="display:none">
                <p class="p-stampConfirm__modal__benefits__heading">特典を引き換えました！</p>
                <div class="p-stampConfirm__modal__btn__wrap">
                    <?= $this->Form->button('OK' , ['name' => 'change', 'value' => 1,'class' => 'p-stampConfirm__modal__btn jsc-check-decied c-btn--orange']) ?>
                </div>
            </div>
        </div>
    </div>
   
    <?= $this->Form->end() ?>
</section>
<?php echo $this->element('footer');

