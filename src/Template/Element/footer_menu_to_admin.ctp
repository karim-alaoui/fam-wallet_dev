<div class="c-footerMenu">
    <ul class="c-footerMenu__list">
        <li class="c-footerMenu__listItem">
            <a class="c-footerMenu__listItem__link"
            <?= $this->Html->image('icon/icon_coin.png', ['url' => ['controller' => 'Admins', 'action' => 'index'], 'class' => 'c-footerMenu__listItem__img--coupon']); ?>
            <?= $this->Html->Link('換金申請一覧', ['controller' => 'Admins', 'action' => 'index'], ['class' => 'c-footerMenu__listItem__heading']); ?>
            </a>
        </li>
        <li class="c-footerMenu__listItem">
            <a class="c-footerMenu__listItem__link"
            <?= $this->Html->image('icon/icon_user.png', ['url' => ['controller' => 'Admins', 'action' => 'my_page'], 'class' => 'c-footerMenu__listItem__img--user']); ?>
            <?= $this->Html->Link('ユーザー', ['controller' => 'Admins', 'action' => 'my_page'], ['class' => 'c-footerMenu__listItem__heading__user']); ?>
            </a>
        </li>
    </ul>
</div>
