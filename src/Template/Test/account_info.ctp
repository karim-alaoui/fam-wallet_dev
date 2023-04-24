<div class="p-accountInfo">
    <section class="p-accountInfo__title__wrap">
        <h1 class="p-accountInfo__title">アカウント情報</h1>
    </section>
    <div class="p-accountInfo__contents">
        <form class="p-accountInfo__form" action="">
            <div class="p-accountInfo__form__input__wrap">
                <p class="p-accountInfo__form__label">名前</p>
                <input  class="p-accountInfo__form__input" type="text">
            </div>
            <div class="p-accountInfo__form__input__wrap">
                <p class="p-accountInfo__form__label">メールアドレス</p>
                <input class="p-accountInfo__form__input" type="email">
            </div>
            <button class="p-accountInfo__form__btn c-btn--orange">変更</button>
        </form>
    </div>
    <ul class="p-accountInfo__list">
        <li class="p-accountInfo__listItem">
            <p class="p-accountInfo__listItem__label">パスワード</p>
            <p class="p-accountInfo__listItem__link"><a href="">パスワードを変更する</a></p>
        </li>
        <li class="p-accountInfo__listItem">
            <p class="p-accountInfo__listItem__label">権限</p>
            <p class="p-accountInfo__listItem__text">メンバー</p>
        </li>
        <li class="p-accountInfo__listItem">
            <p class="p-accountInfo__listItem__label">所属店舗</p>
            <p class="p-accountInfo__listItem__text">ROLLEN原宿店</p>
            <p class="p-accountInfo__listItem__text">ROLLEN渋谷宮益</p>
        </li>
    </ul>
    <div class="p-accountInfo__delete__wrap">
        <p class="p-accountInfo__delete">アカウントを削除する</p>
    </div>
</div>
<div class="p-accountInfo__overlay" style="display:none">
    <div class="p-accountInfo__modal">
        <p class="p-accountInfo__modal__heading">このユーザーを本当に削除しますか？</p>
        <div class="p-accountInfo__modal__btn__wrap">
            <button class="p-accountInfo__modal__btn c-btn--gray">キャンセル</button>
            <button class="p-accountInfo__modal__btn c-btn--red">削除</button>
        </div>
    </div>
</div>
<?php echo $this->element('footer');
