<div class="c-input">
    <section class="c-input__title__wrap">
        <h1 class="c-input__title">アフィリエイター追加</h1>
    </section>
    <div class="c-input__contents">
        <?= $this->Form->create($user, ['class' => 'c-input__form']) ?>
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">名前</p>
                <?= $this->Form->control('username' , ['type' => 'text', 'class' => 'c-input__form__input' , 'required' => true]) ?>
            </div>
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">メールアドレス</p>
                <?= $this->Form->control('email' , ['class' => 'c-input__form__input' , 'required' => true]) ?>
            <?php if (!empty($email_check)): ?>
                <p class="c-usersForm-input__error"><?= $email_check ?></p>
            <?php endif; ?>
            </div>
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">パスワード</p>
                <?= $this->Form->control('password' , ['class' => 'c-input__form__input' , 'required' => true]) ?>
                <p class="c-input__form__note">半角英数字8文字以上</p>
            </div>
            
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">パスワード(確認)</p>
                <?= $this->Form->control('password_confirm' , ['class' => 'c-input__form__input' , 'type' => 'password' , 'required' => true]) ?>
                <p class="c-input__form__note">半角英数字8文字以上</p>
            </div>
            <?= $this->Form->control('mode', ['type' => 'hidden', 'value' => 'confirm']) ?>
            <?= $this->Form->control('active', ['type' => 'hidden', 'value' => 1]) ?>
            <?= $this->Form->control('company_id', ['type' => 'hidden', 'value' => $auth['company_id']]) ?>
            <?= $this->Form->control('role_id', ['type' => 'hidden', 'value' =>4]); ?>
            <?= $this->Form->button('確認',['class' => 'c-input__form__btn c-btn--orange']) ?>
            <div class="c-input__form__btn c-btn--white"><a href="/affiliaters">戻る</a></div>
        <?= $this->Form->end() ?>
    </div>
</div>
<?php echo $this->element('footer');