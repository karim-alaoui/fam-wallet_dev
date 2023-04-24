<div class="p-userManagement">
    <section class="p-userManagement__title__wrap">
        <h1 class="p-userManagement__title">ユーザー管理</h1>
    </section>
    <div class="p-userManagement__contents">
        <ul class="p-userManagement__list">
            <li class="p-userManagement__listItem">
                <a href="">リーダー一覧</a>
            </li>
            <li class="p-userManagement__listItem">
                <a href="">メンバー一覧</a>
            </li>
            <li class="p-userManagement__listItem">
                <a href="">アカウント情報</a>
            </li>
        </ul>
        <ul class="p-userManagement__list">
            <li class="p-userManagement__listItem">
                <a href="">ヘルプ</a>
            </li>
            <li class="p-userManagement__listItem">
                <?= $this->Html->link('利用規約・プライバシーポリシー', ['controller' => 'Myusers', 'action' => 'privacy_policy']); ?>
            </li>
        </ul>
        <p class="p-userManagement__link">
        <?= $this->Html->link('ログアウト', ['controller' => 'Myusers', 'action' => 'logout']); ?></p>
    </div>
</div>

<?php
echo $this->element('footer_login');
echo $this->element('footer_menu');
