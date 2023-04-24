<div class="p-memberEdit">
    <section class="p-memberEdit__title__wrap">
        <h1 class="p-memberEdit__title">メンバー詳細・編集</h1>
    </section>

    <div class="p-memberEdit__account">
        <p class="p-memberEdit__account__name">ちゃりぴー</p>
        <p class="p-memberEdit__account__email">chapily@rollen.com</p>
    </div>
    <div class="p-memberEdit__contents">
        <form class="p-memberEdit__form" action="">
            <div class="p-memberEdit__form__input__wrap">
                <p class="p-memberEdit__form__label">権限</p>
                <div class="p-memberEdit__form__select__wrap">
                    <select class="p-memberEdit__form__select">
                        <option>リーダー</option>
                        <option selected>メンバー</option>
                    </select>
                </div>
            </div>
            <div class="p-memberEdit__form__checkbox__wrap">
                <p class="p-memberEdit__form__label">所属店舗</p>
                <ul class="p-memberEdit__form__checkbox__list">
                    <li class="p-memberEdit__form__checkbox__listItem">
                        <label class="p-memberEdit__form__checkbox__item">
                            <input class="p-memberEdit__form__checkbox__input" type="checkbox">
                            <span class="p-memberEdit__form__checkbox__label">ROLLEN原宿店</span>
                        </label>
                    </li>
                    <li class="p-memberEdit__form__checkbox__listItem">
                        <label class="p-memberEdit__form__checkbox__item">
                            <input class="p-memberEdit__form__checkbox__input" type="checkbox">
                            <span class="p-memberEdit__form__checkbox__label">ROLLEN原宿店</span>
                        </label>
                    </li>
                </ul>
            </div>
            <button class="p-memberEdit__form__btn c-btn--orange">変更</button>
        </form>
    </div>
    <div class="p-memberEdit__delete__wrap">
        <p class="p-memberEdit__delete">このアカウントを削除する</p>
    </div>
</div>
<div class="p-memberEdit__overlay" style="display:none">
    <div class="p-memberEdit__modal">
        <p class="p-memberEdit__modal__heading">このユーザーを本当に削除しますか？</p>
        <div class="p-memberEdit__modal__btn__wrap">
            <button class="p-memberEdit__modal__btn c-btn--gray">キャンセル</button>
            <button class="p-memberEdit__modal__btn c-btn--red">削除</button>
        </div>
    </div>
</div>
<?php echo $this->element('footer');
