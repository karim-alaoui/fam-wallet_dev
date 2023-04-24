<div class="p-leaderEdit">
    <section class="p-leaderEdit__title__wrap">
        <h1 class="p-leaderEdit__title">リーダー詳細・編集</h1>
    </section>

    <div class="p-leaderEdit__account">
        <p class="p-leaderEdit__account__name">ちゃりぴー</p>
        <p class="p-leaderEdit__account__email">chapily@rollen.com</p>
    </div>
    <div class="p-leaderEdit__contents">
        <form class="p-leaderEdit__form" action="">
            <div class="p-leaderEdit__form__input__wrap">
                <p class="p-leaderEdit__form__label">権限</p>
                <div class="p-leaderEdit__form__select__wrap">
                    <select class="p-leaderEdit__form__select">
                        <option>リーダー</option>
                        <option>メンバー</option>
                    </select>
                </div>
            </div>
            <div class="p-leaderEdit__form__checkbox__wrap">
                <p class="p-leaderEdit__form__label">所属店舗</p>
                <ul class="p-leaderEdit__form__checkbox__list">
                    <li class="p-leaderEdit__form__checkbox__listItem">
                        <label class="p-leaderEdit__form__checkbox__item">
                            <input class="p-leaderEdit__form__checkbox__input" type="checkbox">
                            <span class="p-leaderEdit__form__checkbox__label">ROLLEN原宿店</span>
                        </label>
                    </li>
                    <li class="p-leaderEdit__form__checkbox__listItem">
                        <label class="p-leaderEdit__form__checkbox__item">
                            <input class="p-leaderEdit__form__checkbox__input" type="checkbox">
                            <span class="p-leaderEdit__form__checkbox__label">ROLLEN原宿店</span>
                        </label>
                    </li>
                </ul>
            </div>
            <button class="p-leaderEdit__form__btn c-btn--orange">変更</button>
        </form>
    </div>
    <div class="p-leaderEdit__delete__wrap">
        <p class="p-leaderEdit__delete">このアカウントを削除する</p>
    </div>
</div>
<div class="p-leaderEdit__overlay" style="display:none">
    <div class="p-leaderEdit__modal">
        <p class="p-leaderEdit__modal__heading">このユーザーを本当に削除しますか？</p>
        <div class="p-leaderEdit__modal__btn__wrap">
            <button class="p-leaderEdit__modal__btn c-btn--gray">キャンセル</button>
            <button class="p-leaderEdit__modal__btn c-btn--red">削除</button>
        </div>
    </div>
</div>
<?php echo $this->element('footer');
