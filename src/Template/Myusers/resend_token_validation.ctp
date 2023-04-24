<section class="p-resendTokenValidation">
    <div class="p-resendTokenValidation__title__wrap">
        <h1 class="p-resendTokenValidation__title">認証メール再送信</h1>
    </div>
    <div class="p-resendTokenValidation__contents">
        <p class="p-resendTokenValidation__text">
            ご登録したメールアドレスを入力してください。認証メールの案内を再度お送りします。
        </p>
        <?= $this->Form->create($user); ?>
            <div class="p-resendTokenValidation__form__input__wrap">
                <p class="p-resendTokenValidation__form__label">メールアドレス</p>
                <?= $this->Form->control('email',['class'=>'p-resendTokenValidation__form__input','required' => true]) ?>
            </div>
            <?= $this->Form->button('送信',['class' => 'p-resendTokenValidation__btn c-btn--orange']) ?>
        <?= $this->Form->end(); ?>
    </div>
</section>
<?php echo $this->element('footer');