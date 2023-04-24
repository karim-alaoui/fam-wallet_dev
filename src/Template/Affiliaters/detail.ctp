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

                <!-- アフィリエイターの場合 -->
                <?php if($myuser['role_id'] == \App\Model\Entity\Myuser::ROLE_AFFILIATER) : ?>
                    <?php if($bank) : ?>
                        <p class="p-affiliaterAccount__item__text">登録済</p>
                        <?= $this->Html->link('振込先口座を変更する', ['controller' => 'Affiliaters', 'action' => 'bank_edit'],['class' => 'p-affiliaterAccount__link']) ?>
                    <?php else : ?>
                        <?= $this->Html->link('振込先口座を登録する', ['controller' => 'Affiliaters', 'action' => 'bank_edit'],['class' => 'p-affiliaterAccount__link']) ?>
                    <?php endif ?>

                <!-- アフィリエイター以外の場合 -->
                <?php else : ?>
                    <?php if(!empty($account)) : ?>
                        <p class="p-affiliaterAccount__item__text">登録済</p>
                        <?= $this->Html->link('振込先口座を変更する',
                            'https://dashboard.stripe.com/settings/payouts',
                            ['class' => 'p-affiliaterAccount__link', 'target' => '_blank'])
                        ?>
                    <?php else : ?>
                        <?= $this->Html->link('振込先口座を登録する', ['controller' => 'Affiliaters', 'action' => 'account_edit'],['class' => 'p-affiliaterAccount__link']) ?>
                    <?php endif ?>
                <?php endif ?>
            </div>
            <div class="p-affiliaterAccount__item">
                <p class="p-affiliaterAccount__item__label">保有ポイント</p>
                <div class="flex align-items-center mb-15">
                    <div>
                        <?= $this->Html->image('icon/icon_coin.png', ['style' => 'width:20px;']);?>
                    </div>
                    <p class="u-fs-24 ml-5 color--orange font-weight-500"><?= $point; ?></p>
                </div>

                <div class="flex align-items-center mb-10">
                    <div class="mr-10 c-btn--white"><a href="/affiliater/point">履歴を見る</a></div>
                    <div class="c-btn--white"><a href="/affiliater/point">換金申請</a></div>
                </div>
            </div>
        </div>
        <p class="pt-15 mb-15 p-affiliaterAccount__link logOut ">
            <?= $this->Html->link('ログアウト', ['controller' => 'Myusers', 'action' => 'logout']); ?>
        </p>
        <p class="p-singIn__form__device__link">
            <?= $this->Html->link('対応端末・利用方法 はこちら', ['controller' => 'Myusers', 'action' => 'enabled_device']) ?></p>
        </p>
    </div>
</div>


<?php
echo $this->element('footer_login');
echo $this->element('footer_menu_to_affiliater');


