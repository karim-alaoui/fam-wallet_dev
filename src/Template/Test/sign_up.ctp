<section class="p-signUp">
    <div class="p-signUp__title__wrap">
        <h1 class="p-signUp__title">新規会員登録</h1>
    </div>
    <div class="p-signUp__contents">
        <form class="p-signUp__form" action="">
            <div class="p-signUp__form__input__wrap">
                <p class="p-signUp__form__label">オーナー名</p>
                <input  class="p-signUp__form__input" type="text">
            </div>
            <div class="p-signUp__form__input__wrap">
                <p class="p-signUp__form__label">会社名</p>
                <input  class="p-signUp__form__input" type="text">
                <p class="p-signUp__form__note">会社名は後から変更することができません</p>
            </div>
            <div class="p-signUp__form__input__wrap">
                <p class="p-signUp__form__label">メールアドレス</p>
                <input  class="p-signUp__form__input" type="text">
            </div>
            <div class="p-signUp__form__input__wrap">
                <p class="p-signUp__form__label">パスワード</p>
                <input  class="p-signUp__form__input" type="password">
                <p class="p-signUp__form__note">半角英数字8文字以上</p>
            </div>
            <div class="p-signUp__form__input__wrap">
                <p class="p-signUp__form__label">パスワード(確認)</p>
                <input  class="p-signUp__form__input" type="password">
                <p class="p-signUp__form__note">半角英数字8文字以上</p>
            </div>
            <div class="p-signUp__form__checkbox__wrap">
                <label>
                    <input class="p-signUp__form__checkbox__input" type="checkbox">
                    <span class="p-signUp__form__checkbox__label"><a href="" target="_blank">利用規約・プライバシーポリシー</a>に同意します</span>
                </label>
            </div>
            <button class="p-signUp__form__btn c-btn--orange">確認</button>
        </form>
    </div>
</section>
<?php echo $this->element('footer');
