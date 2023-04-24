<div class="p-shopEdit">
    <section class="p-shopEdit__title__wrap">
        <h1 class="p-shopEdit__title">店舗情報編集</h1>
    </section>
    <div class="p-shopEdit__contents">
        <?= $this->Form->create() ?>
                <fieldset>
                    <div class="p-shopEdit__form__file__wrap">
                        <label class="p-shopEdit__form__file__cycle">
                            <p class="p-shopEdit__form__file__img"><?php echo $this->Html->image('img_default.png', ['alt' => 'SHOP IMAGE']);?></p>
                            <div class="p-shopEdit__form__file__label">

                            </div>
                            <?= $this->Form->control('image' , ['type' => 'file']) ?>
                        </label>
                    </div>
                    <div class="p-shopEdit__form__text__wrap">
                        <p class="p-shopEdit__form__text">正方形以外の画像を入れた場合、クーポン・スタンプカード発行時に画像がずれる場合があります。</p>
                    </div>
                    <div class="p-shopEdit__form__input__container">
                        <div class="p-shopEdit__form__input__wrap">
                            <p class="p-shopEdit__form__label">店舗名</p>
                            <?= $this->Form->control('shopname' , ['class' => 'p-shopEdit__form__input' , 'required' => false]) ?>
                        </div>
                        <div class="p-shopEdit__form__input__wrap">
                            <p class="p-shopEdit__form__label">紹介文</p>
                            <?= $this->Form->control('introdaction' , ['class' => 'p-shopEdit__form__textarea' , 'type' => 'textarea','required' => false]) ?>
                        </div>
                        <div class="p-shopEdit__form__input__wrap">
                            <p class="p-shopEdit__form__label">住所</p>
                            <?= $this->Form->control('address' , ['class' => 'p-shopEdit__form__input' , 'required' => false]) ?>
                        </div>
                        <div class="p-shopEdit__form__input__wrap">
                            <p class="p-shopEdit__form__label">電話番号</p>
                            <?= $this->Form->control('tel' , ['class' => 'p-shopEdit__form__input' , 'required' => false]) ?>
                        </div>
                        <div class="p-shopEdit__form__input__wrap">
                            <p class="p-shopEdit__form__label">ホームページ</p>
                            <?= $this->Form->control('homepage' , ['class' => 'p-shopEdit__form__input' , 'required' => false]) ?>
                        </div>
                        <div class="p-shopEdit__form__input__wrap">
                            <p class="p-shopEdit__form__label">LINE</p>
                            <?= $this->Form->control('line' , ['class' => 'p-shopEdit__form__input' , 'required' => false]) ?>
                        </div>
                        <div class="p-shopEdit__form__input__wrap">
                            <p class="p-shopEdit__form__label">Twitter</p>
                            <?= $this->Form->control('twitter' , ['class' => 'p-shopEdit__form__input' , 'required' => false]) ?>
                        </div>
                        <div class="p-shopEdit__form__input__wrap">
                            <p class="p-shopEdit__form__label">Facebook</p>
                            <?= $this->Form->control('facebook' , ['class' => 'p-shopEdit__form__input' , 'required' => false]) ?>
                        </div>
                        <div class="p-shopEdit__form__input__wrap">
                            <p class="p-shopEdit__form__label">Instagram</p>
                            <?= $this->Form->control('instagram' , ['class' => 'p-shopEdit__form__input' , 'required' => false]) ?>
                        </div>
                    </div>
                </fieldset>
                <?= $this->Form->button('保存',['class' => 'p-shopEdit__form__btn c-btn--orange']) ?>
        <?= $this->Form->end() ?>
    </div>
    <div class="p-shopEdit__delete__wrap">
        <p class="p-shopEdit__delete jsc-modal-trigger">この店舗を削除する</p>
    </div>
</div>
<div class="p-shopEdit__overlay model-overlay" style="display:none">
    <div class="p-shopEdit__modal">
        <div class="p-shopEdit__modal__content" style="">
            <p class="p-shopEdit__modal__heading">この店舗を本当に削除しますか？</p>
            <p class="p-shopEdit__modal__text">店舗を削除しても作成したクーポンは残ります</p>
            <div class="p-shopEdit__modal__btn__wrap">
                <?= $this->Form->button('キャンセル' , ['class' => 'p-shopEdit__modal__btn jsc-check-cancel c-btn--gray']) ?>
                <?= $this->Form->button('削除' , ['class' => 'p-shopEdit__modal__btn jsc-check-decied c-btn--red']) ?>
            </div>
        </div>
        <div class="p-shopEdit__modal__content" style="display:none">
            <div class="p-shopEdit__modal__close">
                <span></span>
                <span></span>
            </div>
            <p class="p-shopEdit__modal__heading">この店舗に所属しているユーザーがいるので削除できません</p>
            <p class="p-shopEdit__modal__text">店舗に所属しているユーザーをすべて解除してから削除してください。</p>
        </div>
    </div>
</div>
<?php echo $this->element('footer');
