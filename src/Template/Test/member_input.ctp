<div class="p-memberInput">
    <section class="p-memberInput__title__wrap">
        <h1 class="p-memberInput__title">メンバー追加</h1>
    </section>
    <div class="p-memberInput__contents">
        <form class="p-memberInput__form" action="">
            <div class="p-memberInput__form__input__wrap">
                <p class="p-memberInput__form__label">名前</p>
                <input  class="p-memberInput__form__input" type="text">
            </div>
            <div class="p-memberInput__form__input__wrap">
                <p class="p-memberInput__form__label">メールアドレス</p>
                <input class="p-memberInput__form__input" type="email">
            </div>
            <div class="p-memberInput__form__input__wrap">
                <p class="p-memberInput__form__label">パスワード</p>
                <input  class="p-memberInput__form__input" type="password">
            </div>
            <div class="p-memberInput__form__input__wrap">
                <p class="p-memberInput__form__label">パスワード(確認)</p>
                <input  class="p-memberInput__form__input" type="password">
            </div>
            <div class="p-memberInput__form__checkbox__wrap">
                <p class="p-memberInput__form__label">所属店舗</p>
                <ul class="p-memberInput__form__checkbox__list">
                    <li class="p-memberInput__form__checkbox__listItem">
                        <label class="p-memberInput__form__checkbox__item">
                            <input class="p-memberInput__form__checkbox__input" type="checkbox">
                            <span class="p-memberInput__form__checkbox__label">ROLLEN原宿店</span>
                        </label>
                    </li>
                    <li class="p-memberInput__form__checkbox__listItem">
                        <label class="p-memberInput__form__checkbox__item">
                            <input class="p-memberInput__form__checkbox__input" type="checkbox">
                            <span class="p-memberInput__form__checkbox__label">ROLLEN原宿店</span>
                        </label>
                    </li>
                </ul>
            </div>
            <button class="p-memberInput__form__btn c-btn--orange">追加</button>
        </form>
    </div>
</div>
<?php echo $this->element('footer');
