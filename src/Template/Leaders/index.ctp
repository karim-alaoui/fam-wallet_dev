<div class="c-list">
    <section class="c-list__title__wrap">
        <h1 class="c-list__title">リーダー一覧</h1>
        <!-- 店舗情報がなければ消す -->
        <?php if (!empty($shops)): ?>
          <div class="c-list__title__btn">
            <?= $this->Html->link('', ['controller' => 'Leaders', 'action' => 'input'], ['class' => 'button']); ?>
            <?= $this->Html->tag('span' , null , ['class' => 'c-list__title__btn__plus']) ?>
            <?= $this->Html->tag('span' , null , ['class' => 'c-list__title__btn__plus']) ?>
          </div>
        <?php endif; ?>
        <!-- ここまで -->
    </section>
    <!-- 店舗情報がなかった場合の表示 -->
    <?php if (empty($shops) && empty($myusers)): ?>
    <div class="c-list__content__noData">
        <p class="c-list__text">
            リーダーを登録する前に、店舗情報を先にご登録ください。
        </p>
        <div class="c-list__btn c-btn--orange"><?= $this->Html->link('店舗情報を登録する',['controller' => 'Shops', 'action' => 'new']) ?></div>
    </div>
    <?php endif; ?>
    <!-- リーダー追加後の画面 -->
    <div class="c-list__content">
        <!-- リーダーの登録がない場合の文言表示 -->
        <?php if (empty($myusers)): ?>
        <div class="c-list__text__wrap">
            <p class="c-list__text">リーダーが登録されていません。<br>右上の追加ボタンからリーダーを追加してください。<br>店舗登録後にボタンが表示されます。</p>
        </div>
        <?php endif; ?>
        <!-- ここまで -->
        <?php foreach ($myusers as $leader): ?>
        <ul class="c-list__list">
            <li class="c-list__listItem">
                <a href="<?= $this->Url->build(['controller' => 'Leaders', 'action' => 'edit', $leader->id]); ?>">
                    <p class="c-list__heading"><?= $leader->username ?></p>
                    <ul class="c-list__shop__list">
                        <?php if (empty($leader->myuser_shops)): ?>
                            <li class="c-list__shop__listItem__none">
                                <p class="c-list__shop__listItem__text__none">
                              <?= '所属店舗が登録されていません。' ?>
                        <?php else: ?>
                              <?php foreach ($leader->myuser_shops as $shop_name): ?>
                            <li class="c-list__shop__listItem">
                                 <p class="c-list__shop__listItem__text">
                              <?= $shop_name->Shops['name'] ?><br>
                            <?php endforeach; ?>
                        <?php endif; ?>
                            </p>
                        </li>
                    </ul>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php
echo $this->element('footer');
echo $this->element('footer_menu');
