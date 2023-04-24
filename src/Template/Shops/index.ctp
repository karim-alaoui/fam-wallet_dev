<div class="p-shopList">
    <section class="p-shopList__title__wrap">
        <h1 class="p-shopList__title">店舗一覧</h1>
        <?php if ($auth['role_id'] == 1): ?>
        <div class="p-shopList__title__btn"><?= $this->Html->link(null,['controller' => 'Shops', 'action' => 'new']) ?>
            <span class="p-shopList__title__btn__plus"></span>
            <span class="p-shopList__title__btn__plus"></span>
        </a></div>
        <?php endif; ?>
    </section>
    <?php echo $this->element('payment_pending_notif'); ?>
    <div class="p-shopList__content">
        <!-- 店舗の登録がない場合の文言表示 -->
        <?php if (empty($shop_count)) : ?>
            <div class="p-shopList__text__wrap">
                <p class="p-shopList__text">店舗が登録されていません。<br>右上の店舗追加ボタンから店舗を追加してください。</p>
            </div>
        <?php endif; ?>
        <!-- ここまで -->
        <ul class="p-shopList__list">
        <!-- オーナーの場合 -->
          <?php foreach ($shops as $shop): ?>
            <li class="p-shopList__listItem">
              <?php if ($auth['role_id'] == 1): ?>
                <a href="<?= $this->Url->build(['controller' => 'Shops', 'action' => 'view', $shop->id]) ?>">
                <p class="p-shopList__heading"><?= $shop->name ?></p>
                </a>
              <?php elseif ($auth['role_id'] == 2): ?>
                <a href="<?= $this->Url->build(['controller' => 'Shops', 'action' => 'view', $shop->shop_id]) ?>">
                <p class="p-shopList__heading"><?= $shop->shop->name ?></p>
                </a>
              <?php elseif ($auth['role_id'] == 3): ?>
                <a href="<?= $this->Url->build(['controller' => 'Shops', 'action' => 'view', $shop->shop_id]) ?>">
                <p class="p-shopList__heading"><?= $shop->shop->name ?></p>
                </a>
            </li>
            <?php endif; ?>
          <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php
  echo $this->element('footer_login');
  echo $this->element('footer_menu');
