<div class="p-shopInput">
    <section class="p-shopInput__title__wrap">
        <h1 class="p-shopInput__title">店舗追加</h1>
    </section>
    <div class="p-shopInput__contents">
        <?= $this->Form->create() ?>
            <fieldset>
                <div class="p-shopInput__form__input__wrap">
                    <p class="p-shopInput__form__label">店舗名</p>
                    <?= $this->Form->control('name', ['class' => 'p-shopInput__form__input' , 'required' => false]); ?>
                </div>
                <?= $this->Form->control('company_id', ['type' => 'hidden', 'value' => $auth['company_id']]); ?>
             </fieldset>
             <?= $this->Form->button('追加',['class' => 'p-shopInput__form__btn c-btn--orange']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
<?php echo $this->element('footer');
