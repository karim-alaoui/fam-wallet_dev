<div class="c-footerMenu">
    <ul class="c-footerMenu__list">
        <li class="c-footerMenu__listItem">
            <a class="c-footerMenu__listItem__link"
            <?= $this->Html->image('icon/icon_coin.png', ['url' => ['controller' => 'AffiliaterPoints', 'action' => 'index'], 'class' => 'c-footerMenu__listItem__img--coupon']); ?>
            <?= $this->Html->Link('ポイント', ['controller' => 'AffiliaterPoints', 'action' => 'index'], ['class' => 'c-footerMenu__listItem__heading']); ?>
            </a>
        </li>
        <li class="c-footerMenu__listItem">
            <a class="c-footerMenu__listItem__link"
                <?= $this->Html->image('icon/icon_coupon.png', ['url' => ['controller' => 'AffiliaterCoupons', 'action' => 'index'], 'class' => 'c-footerMenu__listItem__img--coupon']); ?>
                <?= $this->Html->Link('クーポン', ['controller' => 'AffiliaterCoupons', 'action' => 'index'], ['class' => 'c-footerMenu__listItem__heading']); ?>
            </a>
        </li>
        <li class="c-footerMenu__listItem">
            <a class="c-footerMenu__listItem__link"
                <?= $this->Html->image('icon/icon_user.png', ['url' => ['controller' => 'Affiliaters', 'action' => 'detail'], 'class' => 'c-footerMenu__listItem__img--user']); ?>
                <?= $this->Html->Link('アカウント', ['controller' => 'Affiliaters', 'action' => 'detail'], ['class' => 'c-footerMenu__listItem__heading__user']); ?>
            </a>
        </li>
    </ul>
</div>
