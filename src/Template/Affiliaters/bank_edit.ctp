<div class="c-input">
    <section class="c-input__title__wrap">
        <h1 class="c-input__title">銀行口座</h1>
    </section>
    <div class="c-input__contents">
        <?= $this->Form->create($bank, ['class' => 'c-input__form', 'type' => 'post']) ?>
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">口座名義人（カタカナ）</p>
                <?= $this->Form->control('account_holder_name' , ['class' => 'c-input__form__input' , 'required' => true]) ?>
            </div>
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">金融機関</p>
                <?= $this->Form->control('bank_name' , ['class' => 'c-input__form__input' , 'required' => true]) ?>
            </div>
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">支店名</p>
                <?= $this->Form->control('branch' , ['class' => 'c-input__form__input' , 'required' => true]) ?>
            </div>
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">預金種別</p>
                <?= $this->Form->control('deposit_type' , ['type' => 'select', 'options' => $depositTypeList, 'multiple' => false , 'class' => 'c-input__form__input']) ?>
            </div>
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">口座番号</p>
                <?= $this->Form->control('account_number' , ['class' => 'c-input__form__input' , 'required' => true]) ?>
            </div>

            <?= $this->Form->control('myuser_id', ['type' => 'hidden', 'value' => $user['id']]); ?>
            <?= $this->Form->button('確認',['class' => 'c-input__form__btn c-btn--orange']) ?>
            <div class="c-input__form__btn c-btn--white"><a href="/affiliater/detail">戻る</a></div>

        <?= $this->Form->end() ?>
    </div>
</div>
