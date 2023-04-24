<section class="p-shopInfo">
    <div class="p-shopInfo__title__wrap">
        <h1 class="p-shopInfo__title">店舗情報</h1>
    </div>
    <div class="p-shopInfo__contents">
        <div class="p-shopInfo__img__wrap">
            <p class="p-shopInfo__img">
            <?php if (!empty($file_check)): ?>
              <?= $this->Html->image('shop_images/'.$shop->id.'/'.$shop->image, ['alt' => 'SHOP IMAGE']) ?>
            <?php else: ?>
              <?= $this->Html->image('img_default.png', ['alt' => 'SHOP IMAGE']) ?>
            <?php endif; ?>
            </p>
        </div>
        <div class="p-shopInfo__item__wrap">
            <div class="p-shopInfo__item">
                <p class="p-shopInfo__item__label">店舗名</p>
                <?= $this->Html->para('p-shopInfo__item__text' , $shop->name) ?>
            </div>
            <div class="p-shopInfo__item">
                <p class="p-shopInfo__item__label">紹介文</p>
                <?= $this->Html->para('p-shopInfo__item__text' , $shop->introdaction) ?>
            </div>
            <div class="p-shopInfo__item">
                <p class="p-shopInfo__item__label">住所</p>
                <?= $this->Html->para('p-shopInfo__item__text' , $shop->address) ?>
            </div>
            <div class="p-shopInfo__item">
                <p class="p-shopInfo__item__label">電話番号</p>
                <?= $this->Html->para('p-shopInfo__item__text' , $shop->tel) ?>
            </div>
            <div class="p-shopInfo__item">
                <p class="p-shopInfo__item__label">ホームページ</p>
                <?= $this->Html->para('p-shopInfo__item__text' , $shop->homepage) ?>
            </div>
            <div class="p-shopInfo__item">
                <p class="p-shopInfo__item__label">LINE</p>
                <?= $this->Html->para('p-shopInfo__item__text' , $shop->line) ?>
            </div>
            <div class="p-shopInfo__item">
                <p class="p-shopInfo__item__label">Twitter</p>
                <?= $this->Html->para('p-shopInfo__item__text' , $shop->twitter) ?>
            </div>
            <div class="p-shopInfo__item">
                <p class="p-shopInfo__item__label">Facebook</p>
                <?= $this->Html->para('p-shopInfo__item__text' , $shop->facebook) ?>
            </div>
            <div class="p-shopInfo__item">
                <p class="p-shopInfo__item__label">Instagram</p>
                <?= $this->Html->para('p-shopInfo__item__text' , $shop->instagram) ?>
            </div>
            <?php if ($auth['role_id'] == \App\Model\Entity\Myuser::ROLE_OWNER): ?>
              <div class="p-shopInfo__form__btn c-btn--orange"><?= $this->Html->link('編集' , ['controller' => 'Shops', 'action' => 'edit', $shop->id]) ?></div>
            <?php endif; ?>
            <div class="p-shopInfo__form__btn c-btn--white"><a href="/shops">戻る</a></div>
        </div>
    </div>
</section>
<?php echo $this->element('footer');

