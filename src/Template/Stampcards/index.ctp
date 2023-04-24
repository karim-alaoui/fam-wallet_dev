<div class="p-stampList">
    <section class="p-stampList__title__wrap">
        <h1 class="p-stampList__title">スタンプカード管理</h1>
    </section>
    <?php echo $this->element('payment_pending_notif'); ?>
    <div class="p-stampList__contents">
        <div class="p-stampList__btn">
          <?php if ($auth['role_id'] == 1 || $auth['role_id'] == 2): ?>
            <div class="p-stampList__btn__wrap">
                <?= $this->Html->link(
                    $this->Html->para('p-stampList__btn__text p-stampList__btn__img--stamp' , '新規発行'),
                    '/stampcards/new', 
                    ['escape' => false]) ?>
            </div>
            <div class="p-stampList__btn__wrap">
                <?= $this->Html->link(
                        $this->Html->para('p-stampList__btn__text p-stampList__btn__img--analytics' , '分析'),
                        '/analytics/stampcards',
                        ['escape' => false]) ?>
            </div>
          <?php endif; ?>
        </div>
        <div class="p-stampList__input__wrap">
            <p class="p-stampList__label">スタンプカード一覧</p>
            <div class="p-stampList__select__wrap">
            <?= $this->Form->create() ?>
                    <?= $this->Form->control('shop', ['type' => 'select', 'options' => $shop_list, 'multiple' => false, 'class' => 'p-stampList__select jsc-shop-list']) ?>
            </div>
            <?= $this->Form->button('店舗検索',['class' => 'p-couponEdit__form__btn c-btn--orange']) ?>
        </div>
        <div class="p-stampList__stamp__wrap">
            <div class="p-stampList__stamp__tabs">
                <div class="p-stampList__stamp__tab jsc-stampcard-public is-active">
                    <p class="p-stampList__stamp__tab__text">公開中<span><?= $release_record ?></span></p>
                </div>
                <div class="p-stampList__stamp__tab jsc-stampcard-private">
                    <p class="p-stampList__stamp__tab__text">非公開<span><?= $private_record ?></span></p>
                </div>
            </div>
            <div class="p-stampList__stamp__list__wrap">
                <div class="p-stampList__stamp__list__text__wrap">
                    <p class="p-stampList__stamp__list__text">
                        発行されているスタンプカードがありません。<br>
                        ページ上部の新規発行ボタンよりスタンプカードを作成してください。
                    </p>
                </div>
                <div class="p-stampList__stamp__list jsc-stampcard-public is-active">
                    <?php foreach ($result_record as $stampcard): ?>
                      <?php if ($stampcard->release_id == 1): ?>
                        <a href="<?= $this->Url->build(['controller' => 'Stampcards', 'action' => 'edit', $stampcard->id]) ?>">
                            <div class="p-stampList__card">
                                <div class="p-stampList__card__top">
                                    <p class="p-stampList__card__img">
                                    <?php if (file_exists(WWW_ROOT.'/img/shop_images/'.$stampcard->stampcard_shops[0].'/'.$stampcard->stampcard_shops[0]->shop->image)): ?>
                                      <?php echo $this->Html->image('shop_images/'.$stampcard->stampcard_shops[0].'/'.$stampcard->stampcard_shops[0]->shop->image, ['alt' => 'logo']) ?>
                                    <?php else: ?>
                                      <?php echo $this->Html->image('img_default.png', ['alt' => 'logo']);?>
                                    <?php endif; ?>
                                    </p>
                                    <div class="p-stampList__card__top__wrap">
                                            <p class="p-stampList__card__shop"><?= $stampcard->title ?></p>
                                            <p class="p-stampList__card__date">有効期限：<?= date('Y/m/d', strtotime($stampcard->after_expiry_date)) ?></p>
                                    </div>
                                </div>
                                <div class="p-stampList__card__bottom">
                                    <p class="p-stampList__card__dl">
                                    <?php $i = 0; foreach ($stampcard->child_stampcards as $dl_val): ?>
                                    <?php $i++ ?>
                                    <?php endforeach; ?>
                                    <?= $i ?>
                                    <span>DL</span></p>
                                </div>
                            </div>
                        </a>
                      <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="p-stampList__stamp__list jsc-stampcard-private">
                    <div class="p-stampList__card">
                      <?php foreach ($result_record as $private_stampcard): ?>
                        <?php if ($private_stampcard->release_id == 2): ?>
                        <a href="<?= $this->Url->build(['controller' => 'Stampcards', 'action' => 'edit', $private_stampcard->id]) ?>">
                        <div class="p-stampList__card__top">
                            <p class="p-stampList__card__img"><?php echo $this->Html->image('img_default.png', ['alt' => 'logo']);?></p>
                            <div class="p-stampList__card__top__wrap">
                                <p class="p-stampList__card__shop"><?= $private_stampcard->title ?></p>
                                <p class="p-stampList__card__date">有効期限：<?= date('Y/m/d', strtotime($private_stampcard->after_expiry_date)) ?></p>
                            </div>
                        </div>
                        <div class="p-stampList__card__bottom">
                            <p class="p-stampList__card__dl">
                            <?php $i = 0; foreach ($private_stampcard->child_stampcards as $dl_val): ?>
                              <?php $i++ ?>
                            <?php endforeach; ?>
                            <?= $i ?>
                            <span>DL</span></p>
                        </div>
                        </a>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    echo $this->element('footer_login');
    echo $this->element('footer_menu');
