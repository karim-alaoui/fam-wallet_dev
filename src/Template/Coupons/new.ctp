<?php
?>
<div class="c-input">
    <section class="c-input__title__wrap">
        <h1 class="c-input__title">クーポン発行</h1>
    </section>
    <div class="c-input__contents">
        <?= $this->Form->create($coupon_validate) ?>
        <fieldset>
            <div class="c-input__form__input__wrap">
                <div class="c-input__form__label__wrap">
                    <p class="c-input__form__label">クーポンタイトル</p>
                </div>
                <?= $this->Form->control('title' , ['required' => true , 'class' => 'c-input__form__input jsc-input-text', 'placeholder' => 'タイトル10文字']) ?>
            </div>
            <div class="c-input__form__input__wrap">
                <div class="c-input__form__label__wrap">
                    <p class="c-input__form__label">内容</p>
                </div>
                <?= $this->Form->control('content' , ['type' => 'textarea' , 'required' => true , 'class' => 'c-input__form__textarea jsc-textarea', 'placeholder' => '内容50文字']) ?>
            </div>
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">特典</p>
                <?= $this->Form->control('reword' , ['required' => true , 'class' => 'c-input__form__input  jsc-input-text' , 'placeholder' => '特典10文字 例：100円引、10%OFF、替玉無料']) ?>
            </div>
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">利用回数</p>
                <?= $this->Form->control('limit', ['type' => 'select' , 'options' => $count_list , 'multiple' => false , 'empty' => '選択してください' , 'required' => true , 'class' => 'c-input__form__input']) ?>
            </div>
            <div class="c-input__form__checkbox__wrap">
                <p class="c-input__form__label">所属店舗</p><p class="c-input__form__selectall jsc-selectAll">全て選択</p>
                <ul class="c-input__form__checkbox__list">
                    <li class="c-cinput__form__checkbox__listItem">
                        <label class="c-input__form__checkbox__label">
                            <?= $this->Form->control('shop_id', [
                                'type' => 'select',
                                'multiple' => 'checkbox',
                                'options' => $shop_list,
                                'class' => 'c-input__form__checkbox__input',
                                'value' => is_string($this->request->getData('shop_id'))
                                    ? json_decode($this->request->getData('shop_id'))
                                    : $this->request->getData('shop_id')
                            ]) ?>
                        </label>
                    </li>
                </ul>
            </div>
            <?php if (!empty($shop_error)): ?>
              <p class="c-usersForm-input__error"><?= $shop_error ?></p>
            <?php endif; ?>
            <p class="c-input__form__text">
                クーポンの店名には1店舗だけが表示されますが、詳細欄には全ての対象店舗名が表示されます。
            </p>
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">有効期限</p>
                <div style ="justify-content: normal !important;" class="c-input__form__select__wrap">
                    <div class="c-input__form__select">
                        <?= $this->Form->control('before_expiry_date',[
                            'required' => true,
                            'class' => 'datepicker',
                            'placeholder' => '選択してください',
                            'readonly' => true,
                            'value' => $this->request->getData('before_expiry_date')
                         ]);
                        ?>
                    </div>
                    <p class="c-input__form__select__text">~</p>
                    <div class="c-input__form__select">
                        <?= $this->Form->control('after_expiry_date',[
                            'required' => true,
                            'class' => 'datepicker date',
                            'placeholder' => '選択してください',
                            'readonly' => true,
                            'value' => $this->request->getData('after_expiry_date')
                         ]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">色</p>
                <div class="c-input__form__color__radio__wrap">
                    <label class="c-input__form__radio__label">
                        <?= $this->Form->control('white',[
                            'type' => 'radio',
                            'class' => 'c-input__form__color__radio',
                            'options' => '',
                            'value' => $this->request->getData('white')
                            ]);
                        ?>
                        <span class="c-input__form__color__radio__text u-bg__color--gray">A</span>
                    </label>
                    <label class="c-input__form__radio__label">
                        <?= $this->Form->control('black',[
                            'type' => 'radio',
                            'class' => 'c-input__form__color__radio',
                            'options' => '',
                            'value' => $this->request->getData('black')
                            ]);
                        ?>
                        <span class="c-input__form__color__radio__text u-bg__color--black">A</span>
                    </label>
                    <label class="c-input__form__radio__label">
                        <?= $this->Form->control('red',[
                            'type' => 'radio',
                            'class' => 'c-input__form__color__radio',
                            'options' => '',
                            'value' => $this->request->getData('red')
                            ]);
                        ?>
                        <span class="c-input__form__color__radio__text u-bg__color--red">A</span>
                    </label>
                    <label class="c-input__form__radio__label">
                        <?= $this->Form->control('blue',[
                            'type' => 'radio',
                            'class' => 'c-input__form__color__radio',
                            'options' => '',
                            'value' => $this->request->getData('blue')
                            ]);
                        ?>
                        <span class="c-input__form__color__radio__text u-bg__color--blue">A</span>
                    </label>
                    <label class="c-input__form__radio__label">
                        <?= $this->Form->control('green',[
                            'type' => 'radio',
                            'class' => 'c-input__form__color__radio',
                            'options' => '',
                            'value' => $this->request->getData('green')
                            ]);
                        ?>
                        <span class="c-input__form__color__radio__text u-bg__color--green">A</span>
                    </label>
                    <label class="c-input__form__radio__label">
                        <?= $this->Form->control('yellow',[
                            'type' => 'radio',
                            'class' => 'c-input__form__color__radio',
                            'options' => '',
                            'value' => $this->request->getData('yellow')
                            ]);
                        ?>
                        <span class="c-input__form__color__radio__text u-bg__color--yellow">A</span>
                    </label>
                    <label class="c-input__form__radio__label">
                        <?= $this->Form->control('orange',[
                            'type' => 'radio',
                            'class' => 'c-input__form__color__radio',
                            'options' => '',
                            'value' => $this->request->getData('orange')
                            ]);
                        ?>
                        <span class="c-input__form__color__radio__text u-bg__color--orange">A</span>
                    </label>
                    <label class="c-input__form__radio__label">
                        <?= $this->Form->control('purple',[
                            'type' => 'radio',
                            'class' => 'c-input__form__color__radio',
                            'options' => '',
                            'value' => $this->request->getData('purple')
                            ]);
                        ?>
                        <span class="c-input__form__color__radio__text u-bg__color--purple">A</span>
                    </label>
                    <label class="c-input__form__radio__label">
                        <?= $this->Form->control('pink',[
                            'type' => 'radio',
                            'class' => 'c-input__form__color__radio',
                            'options' => '',
                            'value' => $this->request->getData('pink')
                            ]);
                        ?>
                        <span class="c-input__form__color__radio__text u-bg__color--pink">A</span>
                    </label>
                    <label class="c-input__form__radio__label">
                        <?= $this->Form->control('brown',[
                            'type' => 'radio',
                            'class' => 'c-input__form__color__radio',
                            'options' => '',
                            'value' => $this->request->getData('brown')
                            ]);
                        ?>
                        <span class="c-input__form__color__radio__text u-bg__color--brown">A</span>
                    </label>
                </div>
            </div>
            <?php if (!empty($color_error)) : ?>
                <p class="c-usersForm-input__error"><?= $color_error ?></p>
            <?php endif; ?>
            <p class="c-input__form__helpLink jsc-modal-trigger">クーポンの参考画像を見る</p>
            <div class="c-input__form__input__wrap">
                <div class="c-input__form__label__wrap">
                    <p class="c-input__form__label">通知メッセージ</p>
                </div>
                <?= $this->Form->control('relevant_text' , ['type' => 'textarea' , 'class' => 'c-input__form__textarea jsc-textarea' , 'placeholder' => 'お持ちのクーポンを100m先にあるお店でご利用できます!']) ?>
            </div>
            <div class="c-input__form__input__wrap">
                <div class="c-input__form__label__wrap">
                    <p class="c-input__form__label">通知住所</p>
                </div>
                <?= $this->Form->control('address' , ['class' => 'c-input__form__input jsc-input-text' , 'placeholder' => '東京都〇〇区〇〇1-2-3']) ?>
                <?php if (!empty($address_error)) : ?>
                    <p class="c-usersForm-input__error"><?= $address_error ?></p>
                <?php endif; ?>
            </div>

            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">アフィリエイト</p>
                <div id="default_is_affiliate" style="display: none"><?= $this->request->getData('is_affiliate') ?></div>
                <div class="p-signUp__form__checkbox__wrap">
                    <label id="affiliater_is_use" class="p-signUp__form__checkbox__label">
                        <?= $this->Form->control('is_affiliate' , [
                            'class' => 'p-signUp__form__checkbox__input',
                            'type' => 'checkbox',
                            'label' => false,
                            'required' => false,
                            'error' => false,
                            'default' => $this->request->getData('is_affiliate')
                        ]) ?>
                        実施する
                    </label>
                    <?= $this->Form->error('consent') ?>
                </div>

                <div id="affiliater_rate_box">
                    <p class="c-input__form__label">報酬</p>
                    <div class="reword-box">
                        <div class="p-couponEdit__form__state__radio__label">
                            <div class="reword-rate-container" style="border-bottom: 1px solid #ddd;">
                                <label for="reword-type-1">
                                    <input type="radio" name="reword_type"
                                           value="1" checked id="reword-type-1"
                                           class="p-couponEdit__form__state__radio radio_ignore" disabled>
                                    料率方式
                                </label>
                                <div class="reword-rate-box">
                                    <p>支払い金額の<?= $this->Form
                                            ->control('rate', [
                                                'class' => 'c-input__form__input jsc-input-text',
                                                'placeholder' => 100,
                                                'disabled' => true,
                                                'id' => 'reword-rate-1',
                                                'error' => true,
                                                'value' => intval($this->request->getData('rate'))
                                            ]) ?>  %
                                    </p>
                                    <?php if(isset($coupon_entity) && $coupon_entity->getError('rate')): ?>
                                        <p  style="font-size: 10px;"
                                            class="c-usersForm-input__error">
                                            <?= $coupon_entity->getError('rate')['custom'] ?>
                                        </p>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </fieldset>
            <?= $this->Form->control('company_id', ['type' => 'hidden', 'value' => $auth['company_id']]) ?>
            <?= $this->Form->control('foreground_color' , ['type' => 'hidden' , 'value' => "null"])?>
            <?= $this->Form->control('background_color' , ['type' => 'hidden' , 'value' => "null"])?>
            <?= $this->Form->control('latitude' , ['type' => 'hidden' , 'value' => ""]) ?>
            <?= $this->Form->control('longitude' , ['type' => 'hidden' , 'value' => ""]) ?>
            <?= $this->Form->control('mode', ['type' => 'hidden', 'value' => 'confirm']); ?>
            <?= $this->Form->button('確認' , ['class' => 'c-input__form__btn c-btn--orange']) ?>
            <div class="c-input__form__btn c-btn--white"><a href="/coupons">戻る</a></div>
       <?= $this->Form->end() ?>
    </div>
    <div class="c-input__overlay model-overlay" style="display:none">
        <div class="c-input__modal">
            <div class="c-input__modal__close jsc-check-cancel">
                <span></span>
                <span></span>
            </div>
            <p class="c-input__modal__heading">クーポン参考画像</p>
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
                <?= $this->Html->image('coupon_iOS.jpg', ['class' => 'c-input__modal__img--coupon']); ?>
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
            <div class="c-input__modal__coupon__list" id="jsc-coupon-private">
                <?= $this->Html->image('coupon_Android.jpg', ['class' => 'c-input__modal__img--coupon']); ?>
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
<?= $this->Html->script('//code.jquery.com/ui/1.12.1/jquery-ui.js', [ 'block' => true ]) ?>
<?= $this->Html->scriptStart([ 'block' => true ]) ?>

<?= $this->Html->scriptEnd() ?>
<?php echo $this->element('footer'); ?>

<script>
    $( function() {
        $(".c-input__form__radio__label").on('click',function(){
            $(".c-input__form__color__radio__text").css({"border":""});
            $(".is-check").siblings('.c-input__form__color__radio__text').css({"border":"4px solid #ff54bc"});
        })
        $(".datepicker").datepicker({
            dateFormat: 'yy/mm/dd',
            monthNames: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
            showOtherMonths: true,
            selectOtherMonths: true,
            disableTouchKeyboard: true,
        });

        $('#affiliater_is_use').on('click', function() {
            var enable = $(this).hasClass('is-check');
            $('#affiliater_rate_box').css('display', enable ? 'block' : 'none');

            $('#affiliater_rate_box input').each(function(i, v) {
                if($(v).attr('id') === 'reword-rate-2') {
                    return true;
                }
                $(v).prop('disabled', !enable);
            });

            $('#affiliater_rate_box select').each(function(i, v) {
                $(v).prop('disabled', !enable);
            });
        });

        $('input[name="reword_type"]').click(function() {
            if($(this).attr('id') === 'reword-type-1') {
                $('#reword-rate-1').prop('disabled', false)
                $('#reword-rate-2').prop('disabled', true)
            } else {
                $('#reword-rate-2').prop('disabled', false)
                $('#reword-rate-1').prop('disabled', true)
            }
        })

        setTimeout(function() {
            if($('#affiliater_is_use').hasClass('is-check')) {
                $('#affiliater_is_use').trigger('click');
            }
        }, 1500)

    });
</script>
