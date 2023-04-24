<div class="p-leaderEdit">
    <section class="p-leaderEdit__title__wrap">
        <h1 class="p-leaderEdit__title">リーダー詳細・編集</h1>
    </section>

    <div class="p-leaderEdit__account">
    <!-- username,emailを変更できるのは該当ユーザのみ-->
    <?= $this->Form->create($myuser) ?>
      <?php if ($auth['id'] == $myuser['id']) : ?>
        <p class="p-leaderEdit__account__name">
        <?= $this->Form->control('username'); ?></p>
        <p class="p-leaderEdit__account__email">
        <?= $this->Form->control('email'); ?></p>
      <?php else : ?>
        <p class="p-leaderEdit__account__name">
        <?= $myuser['username'] ?></p>
        <p class="p-leaderEdit__account__email">
        <?= $myuser['email'] ?></p>
      <?php endif; ?>
    </div>
    <div class="p-leaderEdit__contents">
            <div class="p-leaderEdit__form__input__wrap">
                <p class="p-leaderEdit__form__label">権限</p>
                <div class="p-leaderEdit__form__select__wrap">
                <?= $this->Form->control('role_id', [
                     'type' => 'select',
                     'options' => $roles,
                     'multiple' => false,
                     'class' => 'p-leaderEdit__form__select'
                ]) ?>
                </div>
            </div>
            <div class="p-leaderEdit__form__checkbox__wrap">
                <p class="c-input__form__label">所属店舗</p><p class="c-input__form__selectall jsc-selectAll">全て選択</p>
                <ul class="p-leaderEdit__form__checkbox__list">
                    <li class="p-leaderEdit__form__checkbox__listItem">
                        <label class="p-leaderEdit__form__checkbox__label">
                                <?= $this->Form->control('shop_id', [
                                    'type' => 'select',
                                    'multiple' => 'checkbox',
                                    'options' => $shop_list,
                                    'class' => 'p-leaderInput__form__checkbox__input',
                                    'value' => $myuser_shops
                                    ])
                                ?>
                        </label>
                    </li>
                </ul>
            </div>
            <?= $this->Form->button('変更', ['class' => 'p-leaderEdit__form__btn c-btn--orange']) ?>
            <div class="p-leaderEdit__form__btn c-btn--white"><a href="/leaders">戻る</a></div>
        <?= $this->Form->end() ?>
    </div>
    <div class="p-leaderEdit__delete__wrap">
        <p class="p-leaderEdit__delete jsc-modal-trigger">このアカウントを削除する</p>
    </div>
</div>
<div class="p-leaderEdit__overlay model-overlay" style="display:none">
    <div class="p-leaderEdit__modal">
        <p class="p-leaderEdit__modal__heading">このユーザーを本当に削除しますか？</p>
        <div class="p-leaderEdit__modal__btn__wrap">
            <?= $this->Form->button('キャンセル', ['class' => 'p-leaderEdit__modal__btn jsc-check-cancel c-btn--gray']) ?>
            <?= $this->Form->postButton('削除', ['controller' => 'Myusers', 'action' => 'delete', $myuser->id], ['class' => 'p-leaderEdit__modal__btn jsc-check-decied c-btn--red']) ?>
        </div>
    </div>
</div>
<?php echo $this->element('footer');
