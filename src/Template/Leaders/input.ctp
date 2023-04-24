<div class="c-input">
    <section class="c-input__title__wrap">
        <h1 class="c-input__title">リーダー追加</h1>
    </section>
    <div class="c-input__contents">
        <?= $this->Form->create($user, ['class' => 'c-input__form']) ?>
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">名前</p>
                <?= $this->Form->control('username' , ['type' => 'text', 'class' => 'c-input__form__input' , 'required' => true, 'value' => $this->request->getData('username')]) ?>
            </div>
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">メールアドレス</p>
                <?= $this->Form->control('email' , ['class' => 'c-input__form__input' , 'required' => true, 'value' => $this->request->getData('email')]) ?>
            <?php if (!empty($email_check)): ?>
                <p class="c-usersForm-input__error"><?= $email_check ?></p>
            <?php endif; ?>
            </div>
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">パスワード</p>
                <?= $this->Form->control('password' , ['class' => 'c-input__form__input' , 'required' => true, 'value' => $this->request->getData('password')]) ?>
                <p class="c-input__form__note">半角英数字8文字以上</p>
            </div>
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">パスワード(確認)</p>
                <?= $this->Form->control('password_confirm' , ['class' => 'c-input__form__input' , 'required' => true , 'type' => 'password', 'value' => $this->request->getData('password_confirm')]) ?>
                <p class="c-input__form__note">半角英数字8文字以上</p>
            </div>
            <div class="p-couponInput__form__checkbox__wrap">
                <p class="c-input__form__label">所属店舗</p><p class="c-input__form__selectall jsc-selectAll">全て選択</p>
                <ul class="p-couponInput__form__checkbox__list">
                    <li class="p-couponInput__form__checkbox__listItem">
                        <label class="c-input__form__checkbox__label">
                                <?= $this->Form->control('shop_id' , [
                                    'type' => 'select',
                                    'multiple' => 'checkbox',
                                    'options' => $shop_list,
                                    'class' => 'c-input__form__checkbox__input' ,
                                  'value' => $this->request->getData('shop_id')
                                    ])
                                ?>
                        </label>
                    </li>
                </ul>
            </div>
      <?php if (!empty($shop_check)): ?>
        <p class="c-usersForm-input__error"><?= $shop_check ?></p>
      <?php endif; ?>
            <?= $this->Form->control('mode', ['type' => 'hidden', 'value' => 'confirm']) ?>
            <?= $this->Form->control('active', ['type' => 'hidden', 'value' => 1]) ?>
            <?= $this->Form->control('company_id', ['type' => 'hidden', 'value' => $auth['company_id']]) ?>
            <?= $this->Form->control('role_id', ['type' => 'hidden', 'value' => 2]); ?>
            <?= $this->Form->button('確認' , ['class' => 'c-input__form__btn c-btn--orange']) ?>
            <div class="c-input__form__btn c-btn--white"><a href="/leaders">戻る</a></div>
        <?= $this->Form->end() ?>
    </div>
</div>
<?php echo $this->element('footer');
