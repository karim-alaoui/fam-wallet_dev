<div class="c-list">
    <section class="c-list__title__wrap">
        <h1 class="c-list__title">メンバー一覧</h1>
        <!-- 店舗情報がなければ消す -->
        <?php if (!empty($shops)): ?>
          <div class="c-list__title__btn">
            <?= $this->Html->link('' ,['controller' => 'Members' , 'action' => 'input'] , ['class' => 'button']) ?>
            <span class="c-list__title__btn__plus"></span>
            <span class="c-list__title__btn__plus"></span>
          </div>
        <?php endif; ?>
        <!-- ここまで -->
    </section>
        <!-- 店舗情報がなかった場合の表示 -->
        <?php if (empty($shops) && empty($myusers)): ?>
          <div class="c-list__content__noData">
            <p class="c-list__text">
              メンバーを登録する前に、店舗情報を先にご登録ください。
            </p>
          <div class="c-list__btn c-btn--orange"><?= $this->Html->link('店舗情報を登録する',['controller' => 'Shops', 'action'=>'new']) ?>店舗情報を登録する</div>
    </div>
        <?php endif; ?>
    <!-- リーダー追加後の画面 -->
    <div class="c-list__content">
        <!-- リーダーの登録がない場合の文言表示 -->
        <?php if (empty($myusers)): ?>
          <div class="c-list__text__wrap">
            <p class="c-list__text">メンバーが登録されていません。<br>右上の追加ボタンからメンバーを追加してください。</p>
          </div>
        <?php endif; ?>
        <!-- ここまで -->
        <?php foreach ($myusers as $member): ?>
        <ul class="c-list__list">
            <li class="c-list__listItem">
                <a href="<?= $this->Url->build(['controller' => 'Members', 'action' => 'edit', $member->id]); ?>">
                    <p class="c-list__heading"><?= $member->username ?></p>
                    <ul class="c-list__shop__list">
                        <li class="c-list__shop__listItem__none">
                            <p class="c-list__shop__listItem__text__none">
                            <?php if (empty($member->myuser_shops)): ?>
                              <?= '所属店舗が登録されていません。' ?>
                            <?php else: ?>
                              <?php foreach ($member->myuser_shops as $shop_name): ?>
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

