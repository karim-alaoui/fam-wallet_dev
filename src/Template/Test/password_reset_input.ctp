<section class="p-passwordResetInput">
    <div class="p-passwordResetInput__title__wrap">
        <h1 class="p-passwordResetInput__title">パスワードの再設定</h1>
    </div>
    <div class="p-passwordResetInput__contents">
        <p class="p-passwordResetInput__text">
            新しいパスワードを入力し、保存ボタンを押してください。
        </p>
        <form class="p-passwordResetInput__form" action="">
            <div class="p-passwordResetInput__form__input__wrap">
                <p class="p-passwordResetInput__form__label">新しいパスワード</p>
                <input  class="p-passwordResetInput__form__input" type="password">
                <p class="p-passwordResetInput__form__note">半角英数字8文字以上</p>
            </div>
            <div class="p-passwordResetInput__form__input__wrap">
                <p class="p-passwordResetInput__form__label">新しいパスワード（確認）</p>
                <input  class="p-passwordResetInput__form__input" type="password">
                <p class="p-passwordResetInput__form__note">半角英数字8文字以上</p>
            </div>
            <button class="p-passwordResetInput__btn c-btn--orange">保存</button>
        </form>
    </div>
</section>
<?php echo $this->element('footer');
