<div class="c-footerMenu">
    <ul class="c-footerMenu__list">
        <li class="c-footerMenu__listItem">
            <a class="c-footerMenu__listItem__link"
                <?= $this->Html->image('icon/icon_coupon.png', ['url' => ['controller' => 'Coupons', 'action' => 'index'], 'class' => 'c-footerMenu__listItem__img--coupon']); ?>
                <?= $this->Html->Link('クーポン', ['controller' => 'Coupons', 'action' => 'index'], ['class' => 'c-footerMenu__listItem__heading']); ?>
            </a>
        </li>
        <li class="c-footerMenu__listItem">
            <a class="c-footerMenu__listItem__link" 
                <?= $this->Html->image('icon/icon_stampcard.png', ['url' => ['controller' => 'Stampcards', 'action' => 'index'], 'class' => 'c-footerMenu__listItem__img--stamp']); ?>
                <?= $this->Html->Link('スタンプ', ['controller' => 'Stampcards', 'action' => 'index'], ['class' => 'c-footerMenu__listItem__heading']); ?>
            </a>
        </li>
        <li class="c-footerMenu__listItem">
            <a href="/qr_leader"><p class="c-footerMenu__listItem__img--qr"><?PHP echo $this->Html->image('icon/icon_qr.png', ['alt' => 'qr']);?></p></a>
        </li>
        <li class="c-footerMenu__listItem">
            <a class="c-footerMenu__listItem__link"
                <?= $this->Html->image('icon/icon_user.png', ['url' => ['controller' => 'Myusers', 'action' => 'user_management'], 'class' => 'c-footerMenu__listItem__img--user']); ?>
                <?= $this->Html->Link('ユーザー', ['controller' => 'Myusers', 'action' => 'user_management'], ['class' => 'c-footerMenu__listItem__heading__user']); ?>
            </a>
        </li>
        <li class="c-footerMenu__listItem">
            <a class="c-footerMenu__listItem__link"
                <?= $this->Html->image('icon/icon_shop.png', ['url' => ['controller' => 'Shops', 'action' => 'index'], 'class' => 'c-footerMenu__listItem__img--shop']); ?>
                <?= $this->Html->Link('店舗', ['controller' => 'Shops', 'action' => 'index'], ['class' => 'c-footerMenu__listItem__heading']); ?>
            </a>
        </li>
    </ul>
</div>
