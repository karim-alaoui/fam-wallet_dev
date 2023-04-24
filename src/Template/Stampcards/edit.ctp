<div class="p-stampEdit">
    <section class="p-stampEdit__title__wrap">
        <h1 class="p-stampEdit__title">スタンプカード詳細</h1>
    </section>
    <div class="p-stampEdit__btn">
        <p class="p-stampEdit__btn__heading">スタンプカードをシェアする</p>
        <div class="p-stampEdit__btn__wrap">
            <?= $this->Html->link(
                'QRコードを表示', ['action' => 'qrcode', $stampcard->id, '?' => ['param' => $stampcard->token, 'openExternalBrowser'=> 1]], ['class' => 'p-stampEdit__btn__text p-stampEdit__btn__img--qr']) ?>
        </div>
        <div class="p-stampEdit__btn__wrap">
            <button class="p-stampEdit__btn__text p-stampEdit__btn__img--share" id="jsc-linkcopy" value="<?= $view_url ?>">リンクをコピー</button>
        </div>
    </div>
    <div class="p-stampEdit__status__wrap">
        <div class="p-stampEdit__status">
            <p class="p-stampEdit__status__name">DL数</p>
            <p class="p-stampEdit__status__count">
              <?php if (!empty($stampcard->child_stampcards)): ?>
                <?php $i = 0; foreach ($stampcard->child_stampcards as $dl_val): ?>
                  <?php $i++ ?>
                <?php endforeach; ?>
                <?= $i ?>
              <?php else: ?>
              0
              <?php endif; ?>
        </div>
    </div>
    <p class="p-stampEdit__heading">スタンプカード登録内容</p>
    <div class="p-stampEdit__contents">
        <dl class="p-stampEdit__item">
            <dt class="p-stampEdit__item__heading">スタンプカードタイトル</dt>
            <dd class="p-stampEdit__item__text"><?= $stampcard->title ?></dd>
        </dl>
        <dl class="p-stampEdit__item">
            <dt class="p-stampEdit__item__heading">内容</dt>
            <dd class="p-stampEdit__item__text">
            <?= $stampcard->content ?>
            </dd>
        </dl>
        <dl class="p-stampEdit__item">
            <dt class="p-stampEdit__item__heading">スタンプ数</dt>
            <dd class="p-stampEdit__item__text"><?= $stampcard->max_limit ?></dd>
        </dl>
        <dl class="p-stampEdit__item">
            <dt class="p-stampEdit__item__heading">特典</dt>
            <dd class="p-stampEdit__item__text"><?= $stampcard->reword ?></dd>
        </dl>
        <dl class="p-stampEdit__item">
            <dt class="p-stampEdit__item__heading">対象店舗</dt>
            <?php foreach ($stampcard->stampcard_shops as $stampcard_shops) : ?>
                <p class="p-stampEdit__item__text">
                <?= $stampcard_shops->shop->name ?>
            <?php endforeach; ?>
        </dl>
        <dl class="p-stampEdit__item">
            <dt class="p-stampEdit__item__heading">有効期限</dt>
            <dd class="p-stampEdit__item__text"><?= date('Y/m/d', strtotime($stampcard->before_expiry_date)) ?> ~ <?= date('Y/m/d', strtotime($stampcard->after_expiry_date)) ?></dd>
        </dl>
        <dl class="p-stampEdit__item">
            <dt class="p-stampEdit__item__heading">色</dt>
                    <?php if ($stampcard->background_color == '255,255,255'): ?>
                      <div class="p-stampConfirm__item__colorTile u-bg__color--gray">
                    <?php elseif ($stampcard->background_color == '51,51,51'): ?>
                      <div class="p-stampConfirm__item__colorTile u-bg__color--black">
                    <?php elseif ($stampcard->background_color == '213,80,80'): ?>
                      <div class="p-stampConfirm__item__colorTile u-bg__color--red">
                    <?php elseif ($stampcard->background_color == '62,122,211'): ?>
                      <div class="p-stampConfirm__item__colorTile u-bg__color--blue">
                    <?php elseif ($stampcard->background_color == '14,198,116'): ?>
                      <div class="p-stampConfirm__item__colorTile u-bg__color--green">
                    <?php elseif ($stampcard->background_color == '245,210,28'): ?>
                      <div class="p-stampConfirm__item__colorTile u-bg__color--yellow">
                    <?php elseif ($stampcard->background_color == '236,161,37'): ?>
                      <div class="p-stampConfirm__item__colorTile u-bg__color--orange">
                    <?php elseif ($stampcard->background_color == '105,58,202'): ?>
                      <div class="p-stampConfirm__item__colorTile u-bg__color--purple">
                    <?php elseif ($stampcard->background_color == '233,156,157'): ?>
                      <div class="p-stampConfirm__item__colorTile u-bg__color--pink">
                    <?php elseif ($stampcard->background_color == '112,82,63'): ?>
                      <div class="p-stampConfirm__item__colorTile u-bg__color--brown">
                    <?php endif; ?>
                    <span>A</span>
            </div>
        </dl>
    </div>
    <p class="p-stampEdit__heading">プッシュ通知設定</p>
    <div class="p-stampEdit__contents">
        <dl class="p-stampEdit__item">
            <dt class="p-stampEdit__item__heading">通知メッセージ</dt>
            <dd class="p-stampEdit__item__text"><?= $stampcard->relevant_text ?></dd>
        </dl>
        <dl class="p-stampEdit__item">
            <dt class="p-stampEdit__item__heading">配信住所</dt>
            <dd class="p-stampEdit__item__text address"><?= $stampcard->address ?></dd>
        </dl>
    </div>
    <?= $this->Form->create($stampcard) ?>
    <p class="p-stampEdit__heading">公開設定</p>
        <div class="p-stampEdit__contents">
            <div class="p-stampEdit__form__input__wrap">
                <div class="p-stampEdit__form__state__radio__wrap">
                    <label class="p-stampEdit__form__state__radio__label">
                <?php if($roleId != 3): ?>
                <?php if($stampcard->release_id == 1): ?>
                        <?= $this->Form->control('release_id' , [
                            'type' => 'radio',
                            'options' => [
                                ['text' => '公開中', 'value' => '1', 'checked' => 'checked','label' => ['class' => 'is-check']],
                                ['text' => '非公開', 'value' => '2']
                            ],
                            'class' => 'p-stampEdit__form__state__radio'
                            ]); ?>
                <?php elseif($stampcard->release_id == 2): ?>
                    <?= $this->Form->control('release_id' , [
                            'type' => 'radio',
                            'options' => [
                                ['text' => '公開中', 'value' => '1'],
                                ['text' => '非公開', 'value' => '2', 'checked' => 'checked','label' => ['class' => 'is-check']]
                            ],
                            'class' => 'p-stampEdit__form__state__radio'
                            ]); ?>
                <?php endif; ?>
                <?php else: ?>
                    <?php if($stampcard->release_id == 1): ?>
                        <p>公開中</p>
                    <?php elseif($stampcard->release_id == 2): ?>
                        <p>非公開</p>
                    <?php endif; ?>
                <?php endif; ?>
                    </label>
                </div>
            </div>
            <?php if($roleId != 3): ?>
                <?= $this->Form->button('保存', ['class' => 'p-stampEdit__form__btn c-btn--orange']) ?>
            <?php endif; ?>
            <div class="p-stampEdit__form__btn c-btn--white"><a href="/stampcards">戻る</a></div>
        </div>
    <?= $this->Form->end() ?>
    <div class="p-stampEdit__delete__wrap">
        <?php if($roleId != 3): ?>
            <p class="p-stampEdit__delete jsc-modal-trigger">このスタンプカードを削除する</p>
        <?php endif; ?>
    </div>
</div>
<div class="p-stampEdit__overlay model-overlay" style="display:none">
    <div class="p-stampEdit__modal">
        <p class="p-stampEdit__modal__heading">本当に削除しますか？</p>
        <p class="p-stampEdit__modal__text">スタンプカードを削除すると分析など紐づく情報全てが削除されます</p>
        <div class="p-stampEdit__modal__btn__wrap">
            <?= $this->Form->button('キャンセル', ['class' => 'p-stampEdit__modal__btn jsc-check-cancel c-btn--gray']) ?>
            <?= $this->Form->postButton('削除', ['controller' => 'Stampcards', 'action' => 'delete', $stampcard->id], ['class' => 'p-stampEdit__modal__btn jsc-check-decied c-btn--red']) ?>
        </div>
    </div>
    <div class="p-stampEdit__modal" style="display:none">
        <div class="p-stampEdit__modal__close">
            <span></span>
            <span></span>
        </div>
        <p class="p-stampEdit__modal__heading">QRコードを取得できませんでした</p>
        <p class="p-stampEdit__modal__text">しばらくしてからもう一度お試しください</p>
        <?= $this->Form->button('リトライ',['class' => 'p-stampEdit__modal__btn--retry jsc-check-decied c-btn--orange']) ?>
    </div>
</div>
<?php
echo $this->element('footer_login');
echo $this->element('footer_menu');
