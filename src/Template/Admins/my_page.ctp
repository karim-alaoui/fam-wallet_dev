<div class="p-affiliaterAccount">
    <section class="p-affiliaterAccount__title__wrap">
        <h1 class="p-affiliaterAccount__title">アカウント情報</h1>
    </section>
    <div class="p-affiliaterAccount__contents">

        <div class="p-affiliaterAccount__item__wrap">
            <div class="p-affiliaterAccount__item">
                <p class="p-affiliaterAccount__item__label">名前</p>
                <?= $this->Html->para('p-affiliaterAccount__item__text' , $myuser['username']) ?>
            </div>
            <div class="p-affiliaterAccount__item">
                <?= $this->Form->create(); ?>
                    <p class="p-affiliaterAccount__item__label">メールアドレス</p>
                    <?= $this->Form->control('email' , [
                        'required' => true ,
                        'class' => 'p-affiliaterAccount__form__input c-input__form__input jsc-input-text',
                        'value' => $myuser['email']
                    ]) ?>
                <?= $this->Form->control('id', ['type' => 'hidden', 'value' => $myuser['id']]) ?>
                <?= $this->Form->button('変更', ['class' => 'p-accountInfo__form__btn c-btn--orange']) ?>
                <?= $this->Form->end()?>

            </div>
            <div class="p-affiliaterAccount__item">
                <p class="p-affiliaterAccount__item__label">パスワード</p>
                <?= $this->Html->link('パスワードを変更する', ['controller' => 'Myusers', 'action' => 'request_reset_password'],['class' => 'p-affiliaterAccount__link']) ?>
            </div>
            <div class="p-affiliaterAccount__item">
                <p class="p-affiliaterAccount__item__label">振込先口座</p>
                <?php if(!empty($account)) : ?>
                    <p class="p-affiliaterAccount__item__text">登録済</p>
                    <?= $this->Html->link('振込先口座を変更する',
                        'https://dashboard.stripe.com/settings/payouts',
                        ['class' => 'p-affiliaterAccount__link', 'target' => '_blank'])
                    ?>
                <?php else : ?>
                    <?= $this->Html->link('振込先口座を登録する', ['controller' => 'Admins', 'action' => 'account_edit'],['class' => 'p-affiliaterAccount__link']) ?>
                <?php endif ?>
            </div>
        </div>
        <p class="pt-15 mb-15 p-affiliaterAccount__link logOut ">
            <?= $this->Html->link('ログアウト', ['controller' => 'Myusers', 'action' => 'logout']); ?>
        </p>
    </div>
</div>


<?php
echo $this->element('footer_login');
echo $this->element('footer_menu_to_admin');


