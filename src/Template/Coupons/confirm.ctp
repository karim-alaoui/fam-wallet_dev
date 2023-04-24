
<section class="p-couponConfirm">
    <div class="p-couponConfirm__title__wrap">
        <h1 class="p-couponConfirm__title">クーポン発行</h1>
    </div>
    <div class="p-couponConfirm__contents">
        <?= $this->Form->create() ?>
            <div class="p-couponConfirm__item__wrap">
                <fieldset>
                    <div class="p-couponConfirm__item">
                        <p class="p-couponConfirm__item__label">クーポンタイトル</p>
                        <?= $this->Form->control('title', ['value' => $this->request->getData('title'), 'readonly' => true, 'class' => 'p-couponConfirm__item__text']) ?>
                    </div>
                    <div class="p-couponConfirm__item">
                        <p class="p-couponConfirm__item__label">内容</p>
                        <?= $this->Form->control('content', ['type' => 'textarea','rows' => 4,'value' => $this->request->getData('content'), 'readonly' => true, 'class' => 'p-couponConfirm__item__text']) ?>
                    </div>
                    <div class="p-couponConfirm__item">
                        <p class="p-couponConfirm__item__label">特典</p>
                        <?= $this->Form->control('reword', ['value' => $this->request->getData('reword'), 'readonly' => true, 'class' => 'p-couponConfirm__item__text']) ?>
                    </div>
                    <div class="p-couponConfirm__item">
                        <p class="p-couponConfirm__item__label">利用回数</p>
                        <?= $this->Form->control('limit', ['value' => $this->request->getData('limit'), 'readonly' => true, 'class' => 'p-couponConfirm__item__text']) ?>
                    </div>
                    <div class="p-couponConfirm__item">
                        <p class="p-couponConfirm__item__label">対象店舗</p>
                          <?php foreach ($shop_result_array as $shop_id) : ?>
                            <?= $this->Form->control('shop_id', ['type' => 'text' , 'value' => $shop_id, 'readonly' => true, 'class' => 'p-couponConfirm__item__text']) ?>
                            <br>
                          <?php endforeach; ?>
                    </div>
                    <div class="p-couponConfirm__item">
                        <p class="p-couponConfirm__item__label">有効期限</p>
                        <?= $this->Form->control('before_expiry_date', ['value' => $this->request->getData('before_expiry_date'), 'readonly' => true, 'class' => 'p-couponConfirm__item__shop__text']) ?>~
                        <?= $this->Form->control('after_expiry_date', ['value' => $this->request->getData('after_expiry_date'), 'readonly' => true, 'class' => 'p-couponConfirm__item__shop__text']) ?>
                    </div>
                    <div class="p-couponConfirm__item">
                        <p class="p-couponConfirm__item__label">色</p>
                        <?php if ($background_color == '224,224,224'): ?>
                          <div class="p-couponConfirm__item__colorTile u-bg__color--gray">
                        <?php elseif ($background_color == '51,51,51'): ?>
                          <div class="p-couponConfirm__item__colorTile u-bg__color--black">
                        <?php elseif ($background_color == '213,80,80'): ?>
                          <div class="p-couponConfirm__item__colorTile u-bg__color--red">
                        <?php elseif ($background_color == '62,122,211'): ?>
                          <div class="p-couponConfirm__item__colorTile u-bg__color--blue">
                        <?php elseif ($background_color == '14,198,116'): ?>
                          <div class="p-couponConfirm__item__colorTile u-bg__color--green">
                        <?php elseif ($background_color == '245,210,28'): ?>
                          <div class="p-couponConfirm__item__colorTile u-bg__color--yellow">
                        <?php elseif ($background_color == '236,161,37'): ?>
                          <div class="p-couponConfirm__item__colorTile u-bg__color--orange">
                        <?php elseif ($background_color == '105,58,202'): ?>
                          <div class="p-couponConfirm__item__colorTile u-bg__color--purple">
                        <?php elseif ($background_color == '233,156,157'): ?>
                          <div class="p-couponConfirm__item__colorTile u-bg__color--pink">
                        <?php elseif ($background_color == '112,82,63'): ?>
                          <div class="p-couponConfirm__item__colorTile u-bg__color--brown">
                        <?php endif; ?>
                            <span>A</span>
                          </div>
                    </div>
                    <div class="p-couponConfirm__item">
                        <p class="p-couponConfirm__item__label">通知メッセージ</p>
                        <?= $this->Form->control('relevant_text', ['value' => $this->request->getData('relevant_text'), 'readonly' => true, 'class' => 'p-couponConfirm__item__text']) ?>
                    </div>
                    <div class="p-couponConfirm__item">
                        <p class="p-couponConfirm__item__label">配信場所</p>
                            <?= $this->Form->control('address', ['value' => $this->request->getData('address'), 'readonly' => true, 'class' => 'p-couponConfirm__item__text']) ?>
                    </div>
                    <?php if($is_affiliate):?>
                        <div>
                            <div class="p-couponConfirm__item">
                                <p class="p-couponConfirm__item__label">アフィリエイト</p>
                                あり
                            </div>
                            <div class="p-couponConfirm__item">
                                <p class="p-couponConfirm__item__label">報酬方式</p>
                                <?= $this->Form->control('reword_type', ['value' => $this->request->getData('reword_type'), 'hidden' => true, 'class' => 'p-couponConfirm__item__text']) ?>
                                <p class="u-fs-14"><?= $this->request->getData('reword_type') == 1 ? '料率方式' : '金額固定方式'; ?></p>
                            </div>
                            <div class="p-couponConfirm__item">
                                <p class="p-couponConfirm__item__label">報酬</p>
                                <?= $this->Form->control('rate', ['value' => $this->request->getData('rate'), 'hidden' => true, 'class' => 'p-couponConfirm__item__text']) ?>
                                <p class="u-fs-14">
                                    <?= $this->request->getData('rate')?> <?=$this->request->getData('reword_type') == 1 ? '%' : 'P'; ?>
                                </p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="p-couponConfirm__item">
                            <p class="p-couponConfirm__item__label">アフィリエイト</p>
                            なし
                        </div>
                    <?php endif; ?>

                </fieldset>

                <?= $this->Form->control('company_id', ['type' => 'hidden', 'value' => $this->request->getData('company_id')]) ?>
                <?php if (!empty($lat) && !empty($lng)): ?>
                  <?= $this->Form->control('latitude' , ['type' => 'hidden' , 'value' => $lat]) ?>
                  <?= $this->Form->control('longitude' , ['type' => 'hidden' , 'value' => $lng ]) ?>
                <?php endif; ?>

                <?= $this->Form->control('background_color', ['type' => 'hidden', 'value' => $background_color]) ?>
                <?= $this->Form->control('foreground_color', ['type' => 'hidden', 'value' => $foreground_color]) ?>
                <?= $this->Form->control('reword', ['type' => 'hidden', 'value' => $this->request->getData('reword') ]) ?>
                <?= $this->Form->control('after_expiry_date', ['type' => 'hidden', 'value' => $this->request->getData('after_expiry_date')]) ?>
                <?= $this->Form->control('before_expiry_date', ['type' => 'hidden', 'value' => $this->request->getData('before_expiry_date')]) ?>
                <?php foreach ($this->request->getData('shop_id') as $shop_id): ?>
                  <?php $protect_value = htmlspecialchars($shop_id, ENT_QUOTES, 'UTF-8') ?>
                  <?= $this->Form->control("after_save_data[]", ['type' => 'hidden', 'value' => $protect_value]); ?>
                <?php endforeach; ?>
                <?= $this->Form->control('shop_id', ['type' => 'hidden', 'value' => json_encode($this->request->getData('shop_id'))]) ?>
                <?= $this->Form->control('is_affiliate', ['type' => 'hidden', 'value' => $is_affiliate]) ?>

                <?= $this->Form->control('white', ['type' => 'hidden', 'value' => $this->request->getData('white')]) ?>
                <?= $this->Form->control('black', ['type' => 'hidden', 'value' => $this->request->getData('black')]) ?>
                <?= $this->Form->control('red', ['type' => 'hidden', 'value' => $this->request->getData('red')]) ?>
                <?= $this->Form->control('blue', ['type' => 'hidden', 'value' => $this->request->getData('blue')]) ?>
                <?= $this->Form->control('green', ['type' => 'hidden', 'value' => $this->request->getData('green')]) ?>
                <?= $this->Form->control('yellow', ['type' => 'hidden', 'value' => $this->request->getData('yellow')]) ?>
                <?= $this->Form->control('orange', ['type' => 'hidden', 'value' => $this->request->getData('purple')]) ?>
                <?= $this->Form->control('pink', ['type' => 'hidden', 'value' => $this->request->getData('pink')]) ?>
                <?= $this->Form->control('brown', ['type' => 'hidden', 'value' => $this->request->getData('brown')]) ?>


                <?= $this->Form->button('公開' , ['class' => 'p-couponConfirm__btn c-btn--orange', 'name' => 'add']) ?>
                <?= $this->Form->button('戻る' , ['class' => 'p-couponComfirm__form__btn c-btn--white', 'name' => 'back']) ?>
              <p>戻る場合は"戻る"ボタンを押してください</p>
<!--                <button type="button" onclick="history.back()" class="p-couponComfirm__form__btn c-btn--white" >戻る</button>-->
            </div>
        <?= $this->Form->end() ?>
    </div>
</section>
<?php echo $this->element('footer');

