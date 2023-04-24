<div class="p-userManagement">
    <section class="p-userManagement__title__wrap">
        <h1 class="p-userManagement__title">ユーザー管理</h1>
    </section>
    <?php echo $this->element('payment_pending_notif'); ?>
    <div class="p-userManagement__contents">
        <ul class="p-userManagement__list">
          <?php if ($auth['role_id'] == \App\Model\Entity\Myuser::ROLE_OWNER): ?>
            <li class="p-userManagement__listItem">
            <?= $this->Html->link('リーダー一覧', ['controller' => 'Leaders', 'action' => 'index']); ?>
            </li>
          <?php endif; ?>
          <?php if ($auth['role_id'] != \App\Model\Entity\Myuser::ROLE_MEMBER): ?>
            <li class="p-userManagement__listItem">
                <?= $this->Html->link('メンバー一覧', ['controller' => 'Members', 'action' => 'index']); ?>
            </li>
            <li class="p-userManagement__listItem">
              <?= $this->Html->link('支払い申請一覧', ['controller' => 'Myusers', 'action' => 'affiliater_list']); ?>
            </li>
          <?php endif; ?>
            <li class="p-userManagement__listItem">
                <?= $this->Html->link('アカウント情報', ['controller' => 'Myusers', 'action' => 'edit', $auth['id']]); ?>
            </li>
        </ul>
        <ul class="p-userManagement__list">
            <li class="p-userManagement__listItem">
                <?= $this->Html->link('利用規約・プライバシーポリシー', ['controller' => 'Myusers', 'action' => 'privacy_policy']); ?>
            </li>
            <li class="p-userManagement__listItem">
                <?= $this->Html->link('対応端末', ['controller' => 'Myusers', 'action' => 'enabled_device']); ?>
            </li>
        </ul>
        <p class="p-userManagement__link">
        <?= $this->Html->link('ログアウト', ['controller' => 'Myusers', 'action' => 'logout']); ?></p>
    </div>
</div>

<?php
echo $this->element('footer_login');
echo $this->element('footer_menu');
