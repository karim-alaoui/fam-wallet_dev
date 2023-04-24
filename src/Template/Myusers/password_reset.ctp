<section class="p-passwordReset">
    <div class="p-passwordReset__title__wrap">
        <h1 class="p-passwordReset__title">パスワードの再設定</h1>
    </div>
    <div class="p-passwordReset__contents">
        <p class="p-passwordReset__text">
            ご登録のメールアドレスを入力してください。パスワード再設定の案内をお送りします。
        </p>
        <?= $this->Form->create(); ?>
            <div class="p-passwordReset__form__input__wrap">
                <p class="p-passwordReset__form__label">メールアドレス</p>
                <?= $this->Form->control('email',['class'=>'p-passwordReset__form__input','required' => true]) ?>
            </div>
            <?= $this->Form->button('送信',['class' => 'p-passwordReset__btn c-btn--orange']) ?>
        <?= $this->Form->end(); ?>
    </div>
</section>
<?php echo $this->element('footer');