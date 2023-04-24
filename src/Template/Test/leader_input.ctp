<div class="c-input">
    <section class="c-input__title__wrap">
        <h1 class="c-input__title">リーダー追加</h1>
    </section>
    <div class="c-input__contents">
        <form class="c-input__form" action="">
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">名前</p>
                <input  class="c-input__form__input" type="text">
            </div>
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">メールアドレス</p>
                <input class="c-input__form__input" type="email">
            </div>
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">パスワード</p>
                <input  class="c-input__form__input" type="password">
            </div>
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">パスワード(確認)</p>
                <input  class="c-input__form__input" type="password">
            </div>
            <div class="c-input__form__checkbox__wrap">
                <p class="c-input__form__label">所属店舗</p>
                <p class="c-input__form__selectall jsc-selectAll">全て選択</p>
                <ul class="c-input__form__checkbox__list">
                    <li class="c-input__form__checkbox__listItem">
                        <label class="c-input__form__checkbox__item">
                            <input class="c-input__form__checkbox__input" type="checkbox">
                            <span class="c-input__form__checkbox__label">ROLLEN原宿店</span>
                        </label>
                    </li>
                    <li class="c-input__form__checkbox__listItem">
                        <label class="c-input__form__checkbox__item">
                            <input class="c-input__form__checkbox__input" type="checkbox">
                            <span class="c-input__form__checkbox__label">ROLLEN原宿店</span>
                        </label>
                    </li>
                </ul>
            </div>
            <button class="c-input__form__btn c-btn--orange">確認</button>
        </form>
    </div>
</div>
<?php echo $this->element('footer');
