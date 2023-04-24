<div class="c-input">
    <section class="c-input__title__wrap">
        <h1 class="c-input__title">スタンプカード発行</h1>
    </section>
    <div class="c-input__contents">
        <?= $this->Form->create($stampcard_validate) ?>
            <fieldset>
                <div class="c-input__form__input__wrap">
                    <div class="c-input__form__label__wrap">
                        <p class="c-input__form__label">スタンプカードタイトル</p>
                    </div>
                    <?= $this->Form->control('title', ['required' => true, 'class' => 'c-input__form__input  jsc-input-text', 'placeholder' => 'タイトル20文字']) ?>
                </div>
                <div class="c-input__form__input__wrap">
                    <div class="c-input__form__label__wrap">
                        <p class="c-input__form__label">内容</p>
                    </div>
                    <?= $this->Form->control('content', ['type' => 'textarea', 'required' => true, 'class' => 'c-input__form__textarea jsc-textarea', 'placeholder' => '内容60文字']) ?>
                </div>
                <div class="c-input__form__input__wrap">
                    <p class="c-input__form__label">ゴール設定</p>
                    <div class="c-input__form__state__radio__wrap">
                        <label class="c-input__form__state__radio__label">
                            <span class="c-input__form__label">スタンプ数</span>
                        </label>
                        <div class="c-input__from__state__select">
                          <?= $this->Form->control('max_limit', [
                            'type' => 'select',
                            'multipre' => false,
                            'options' => $end_stamp_value,
                            'empty' => '選択してください',
                          ]) ?>
                        </div>
                        <label class="c-input__form__state__radio__label u-mt-12">
                            <label class="c-input__form__state__radio__label">
                                <span class="c-input__form__label">特典内容</span>
                            </label>
                            <?= $this->Form->contorl('reword', ['required' => true, 'class' => 'c-input__form__state__input  jsc-input-text', 'type' => 'text', 'placeholder' => '特典10文字 例：100円引、10%OFF、替玉無料']) ?>
                        </label>
                    </div>
                </div>
                <div class="c-input__form__checkbox__wrap">
                    <p class="c-input__form__label">対象店舗</p><p class="c-input__form__selectall jsc-selectAll">全て選択</p>
                    <ul class="c-input__form__checkbox__list">
                        <li class="c-input__form__checkbox__listItem">
                            <label class="c-input__form__checkbox__label">
                                <?= $this->Form->control('shop_id', [
                                    'type' => 'select',
                                    'multiple' => 'checkbox',
                                    'options' => $shop_list,
                                    'class' => 'c-input__form__checkbox__input',
                                    ]) ?>
                            </label>
                        </li>
                    </ul>
                </div>
                <?php if (!empty($shop_error)): ?>
                    <p class="c-usersForm-input__error"><?= $shop_error ?></p>
                <?php endif; ?>
                <p class="c-input__form__text">
                    スタンプカードの店名には1店舗だけが表示されますが、詳細欄には全ての対象店舗名が表示されます。
                </p>
                <div class="c-input__form__input__wrap">
                    <p class="c-input__form__label">有効期限</p>
                    <div class="c-input__form__select__wrap">
                        <div class="c-input__form__select">
                            <?= $this->Form->control('before_expiry_date', [
                                'type' => 'text',
                                'required' => false,
                                'class' => 'datepicker',
                                'placeholder' => '選択してください',
                                'readonly' => true
                                ]);
                            ?>
                        </div>
                        <p class="c-input__form__select__text">~</p>
                        <div class="c-input__form__select">
                            <?= $this->Form->control('after_expiry_date',[
                                'type' => 'text',
                                'required' => false,
                                'class' => 'datepicker',
                                'placeholder' => '選択してください',
                                'readonly' => true
                                ]);
                            ?>
                        </div>
                    </div>
                </div>
                <div class="c-input__form__input__wrap">
                    <p class="c-input__form__label">色</p>
                    <div class="c-input__form__color__radio__wrap">
                        <label class="c-input__form__radio__label">
                            <?= $this->Form->control('white', [
                                'type' => 'radio',
                                'class' => 'c-input__form__color__radio',
                                'options' => ''
                                ]);
                            ?>
                            <span class="c-input__form__color__radio__text u-bg__color--gray">A</span>
                        </label>
                        <label class="c-input__form__radio__label">
                            <?= $this->Form->control('black', [
                                'type' => 'radio',
                                'class' => 'c-input__form__color__radio',
                                'options' => ''
                                ]);
                            ?>
                            <span class="c-input__form__color__radio__text u-bg__color--black">A</span>
                        </label>
                        <label class="c-input__form__radio__label">
                            <?= $this->Form->control('red', [
                                'type' => 'radio',
                                'class' => 'c-input__form__color__radio',
                                'options' => ''
                                ]);
                            ?>
                            <span class="c-input__form__color__radio__text u-bg__color--red">A</span>
                        </label>
                        <label class="c-input__form__radio__label">
                            <?= $this->Form->control('blue', [
                                'type' => 'radio',
                                'class' => 'c-input__form__color__radio',
                                'options' => ''
                                ]);
                            ?>
                            <span class="c-input__form__color__radio__text u-bg__color--blue">A</span>
                        </label>
                        <label class="c-input__form__radio__label">
                            <?= $this->Form->control('green', [
                                'type' => 'radio',
                                'class' => 'c-input__form__color__radio',
                                'options' => ''
                                ]);
                            ?>
                            <span class="c-input__form__color__radio__text u-bg__color--green">A</span>
                        </label>
                        <label class="c-input__form__radio__label">
                            <?= $this->Form->control('yellow', [
                                'type' => 'radio',
                                'class' => 'c-input__form__color__radio',
                                'options' => ''
                                ]);
                            ?>
                            <span class="c-input__form__color__radio__text u-bg__color--yellow">A</span>
                        </label>
                        <label class="c-input__form__radio__label">
                            <?= $this->Form->control('orange', [
                                'type' => 'radio',
                                'class' => 'c-input__form__color__radio',
                                'options' => ''
                                ]);
                            ?>
                            <span class="c-input__form__color__radio__text u-bg__color--orange">A</span>
                        </label>
                        <label class="c-input__form__radio__label">
                            <?= $this->Form->control('purple', [
                                'type' => 'radio',
                                'class' => 'c-input__form__color__radio',
                                'options' => ''
                                ]);
                            ?>
                            <span class="c-input__form__color__radio__text u-bg__color--purple">A</span>
                        </label>
                        <label class="c-input__form__radio__label">
                            <?= $this->Form->control('pink', [
                                'type' => 'radio',
                                'class' => 'c-input__form__color__radio',
                                'options' => ''
                                ]);
                            ?>
                            <span class="c-input__form__color__radio__text u-bg__color--pink">A</span>
                        </label>
                        <label class="c-input__form__radio__label">
                            <?= $this->Form->control('brown', [
                                'type' => 'radio',
                                'class' => 'c-input__form__color__radio',
                                'options' => ''
                                ]);
                            ?>
                            <span class="c-input__form__color__radio__text u-bg__color--brown">A</span>
                        </label>
                    </div>
                </div>
                <?php if (!empty($color_error)) : ?>
                    <p class="c-usersForm-input__error"><?= $color_error ?></p>
                <?php endif; ?>
                <p class="c-input__form__helpLink jsc-modal-trigger">スタンプカードの参考画像を見る</p>
                <div class="c-input__form__input__wrap">
                    <div class="c-input__form__label__wrap">
                        <p class="c-input__form__label">通知メッセージ</p>
                    </div>
                    <?= $this->Form->control('relevant_text', ['type' => 'textarea', 'class' => 'c-input__form__textarea jsc-textarea', 'placeholder' => 'お持ちのスタンプカードを100m先にあるお店でご利用できます!']) ?>
                </div>
                <div class="c-input__form__input__wrap">
                    <div class="c-input__form__label__wrap">
                        <p class="c-input__form__label">通知住所</p>
                    </div>
                    <?= $this->Form->control('address', ['class' => 'c-input__form__input  jsc-input-text', 'placeholder' => '東京都〇〇区〇〇1-2-3']) ?>
                  <?php if (!empty($address_error)) : ?>
                    <p class="c-usersForm-input__error"><?= $address_error ?></p>
                  <?php endif; ?>
                </div>
            </fieldset>
            <?= $this->Form->control('company_id', ['type' => 'hidden', 'value' => $auth['company_id']]) ?>
            <?= $this->Form->control('limit_count', ['type' => 'hidden', 'value' => 0]) ?>
            <?= $this->Form->control('longitude', ['type' => 'hidden', 'value' => '']) ?>
            <?= $this->Form->control('latitude', ['type' => 'hidden', 'value' => '']) ?>
            <?= $this->Form->control('foreground_color' , ['type' => 'hidden' , 'value' => 'null']) ?>
            <?= $this->Form->control('background_color', ['type' => 'hidden', 'value' => 'null']) ?>
            <?= $this->Form->control('mode', ['type' => 'hidden', 'value' => 'confirm']); ?>
            <?= $this->Form->button('確認', ['class' => 'c-input__form__btn c-btn--orange']) ?>
            <div class="c-input__form__btn c-btn--white"><a href="/stampcards">戻る</a></div>
        <?= $this->Form->end() ?>
    </div>
    <div class="c-input__overlay model-overlay" style="display:none">
        <div class="c-input__modal">
            <div class="c-input__modal__close jsc-check-cancel">
                <span></span>
                <span></span>
            </div>
            <p class="c-input__modal__heading">スタンプカード参考画像</p>
            <ul class="c-input__modal__tabs">
                <div class="c-input__modal__tab jsc-coupon-public is-active">
                    <li class="c-input__listItem">iOS</li>
                </div>
                <div class="c-input__modal__tab jsc-coupon-private">
                    <li class="c-input__listItem">Android</li>
                </div>
            </ul>
            <div class="c-input__modal__coupon__list is-active" id="jsc-coupon-public">
                <!-- クーポンの画像 -->
                <?= $this->Html->image('stampcard_ios.jpg', ['class' => 'c-input__modal__img--coupon']); ?>
                <p class="c-input__modal__label__shopImage">店舗画像</p>
                <p class="c-input__modal__text">対象店舗の店舗画像が表示されます。複数店舗を選択している場合は、一番上の店舗画像が表示されます。</p>
                <p class="c-input__modal__label__shopName">店舗名</p>
                <p class="c-input__modal__text">対象店舗で選択した店舗名が表示されます。複数店舗を選択している場合は、一番上の店舗名のみ表示されますが、詳細欄には全ての対象店舗名が表示されます。</p>
                <p class="c-input__modal__label__benefits">特典</p>
                <p class="c-input__modal__text">特典や割引率が表示されます。</p>
                <p class="c-input__modal__label__couponTitle">スタンプカードタイトル</p>
                <p class="c-input__modal__text"></p>
                <p class="c-input__modal__label__expirationDate">有効期限</p>
                <p class="c-input__modal__text"></p>
                <p class="c-input__modal__label__qr">QRコード</p>
                <p class="c-input__modal__text"></p>
                <p class="c-input__modal__label__detail">詳細情報</p>
                <p class="c-input__modal__text">上記以外の情報を見ることができます。</p>
            </div>
            <div class="c-input__modal__coupon__list" id="jsc-coupon-private">
                <?= $this->Html->image('stampcard_Android.jpg', ['class' => 'c-input__modal__img--coupon']); ?>
                <p class="c-input__modal__label__shopImage">店舗画像</p>
                <p class="c-input__modal__text">対象店舗の店舗画像が表示されます。複数店舗を選択している場合は、一番上の店舗画像が表示されます。</p>
                <p class="c-input__modal__label__shopName">店舗名</p>
                <p class="c-input__modal__text">対象店舗で選択した店舗名が表示されます。複数店舗を選択している場合は、一番上の店舗名のみ表示されますが、詳細欄には全ての対象店舗名が表示されます。</p>
                <p class="c-input__modal__label__benefits">特典</p>
                <p class="c-input__modal__text">特典や割引率が表示されます。</p>
                <p class="c-input__modal__label__couponTitle">クーポンタイトル</p>
                <p class="c-input__modal__text"></p>
                <p class="c-input__modal__label__expirationDate">有効期限</p>
                <p class="c-input__modal__text"></p>
                <p class="c-input__modal__label__qr">QRコード</p>
                <p class="c-input__modal__text"></p>
                <p class="c-input__modal__label__detail">詳細情報</p>
                <p class="c-input__modal__text">上記以外の情報を見ることができます。</p>
            </div>
        </div>
    </div>
</div>
<?= $this->Html->css('//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', [ 'block' => true ]) ?>
<?= $this->Html->script('//code.jquery.com/jquery-1.12.4.js', [ 'block' => true ]) ?>
<?= $this->Html->script('//code.jquery.com/ui/1.12.1/jquery-ui.js', ['block' => true ]) ?>
<?= $this->Html->scriptStart([ 'block' => true ]) ?>
  $( function() {
    $(".datepicker").datepicker({
      dateFormat: 'yy/mm/dd',
      monthNames: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
      showOtherMonths: true,
      selectOtherMonths: true,
      disableTouchKeyboard: true,
    });
  });
<?= $this->Html->scriptEnd() ?>
<?php echo $this->element('footer');
