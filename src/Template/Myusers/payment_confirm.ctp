<div class="p-affiliaterAccount" id="payment">

    <section class="p-affiliaterAccount__title__wrap">
        <h1 class="p-affiliaterAccount__title">支払方法</h1>
    </section>
    <div class="p-affiliaterAccount__contents">
        <div id="card_errors" class="p-paymentEdit__alert">
        </div>

        <div class="p-affiliaterAccount__item__wrap">

            <?= $this->Form->create(null, ['id' => 'form',
                'url' => array('controller' => 'Myusers', 'action' => 'paymentConfirm'),
                ]); ?>
            <div class="p-leaderConfirm__item">
                <p class="p-leaderConfirm__item__label">カード会社</p>
                <?php echo $this->Html->para('p-leaderConfirm__item__text',
                    $this->request->getQuery('card.card_brand')
                ); ?>
            </div>

            <div class="p-leaderConfirm__item">
                <p class="p-leaderConfirm__item__label">カード番号</p>
                <?php echo $this->Html->para('p-leaderConfirm__item__text',
                    '**** **** **** '.$this->request->getQuery('card.card_last4')
                ); ?>
            </div>

            <div class="p-leaderConfirm__item">
                <p class="p-leaderConfirm__item__label">有効期限</p>
                <?php echo $this->Html->para('p-leaderConfirm__item__text',
                    $this->request->getQuery('card.card_exp_year').'年'.$this->request->getQuery('card.card_exp_month').'月'
                ); ?>
            </div>

            <div class="p-leaderConfirm__item">
                <p class="p-leaderConfirm__item__label">カード名義</p>
                <?php echo $this->Html->para('p-leaderConfirm__item__text',
                    $this->request->getQuery('card.card_name')
                ); ?>
            </div>

            <button class="p-stampEdit__form__btn c-btn--orange" type="submit">登録</button>
            <div class="p-stampComfirm__form__btn c-btn--white"><?= $this->Html->link('戻る',['action' => 'paymentEdit']) ?></div>

            <input type="hidden" name="stripeToken" value="<?php echo $this->request->getQuery('card.stripeToken') ?>"/>
            <?= $this->Form->end() ?>

        </div>

    </div>
</div>


<?php
echo $this->element('footer_login');


