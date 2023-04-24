
<section class="p-shopInfo">
    <div class="p-shopInfo__title__wrap">
        <h1 class="p-shopInfo__title">店舗情報編集</h1>
    </div>
    <div class="p-shopInfo__contents">
        <div class="p-shopInfo__img__wrap">
            <p class="p-shopInfo__img"><?php echo $this->Html->image('img_default.png', ['alt' => '']);?></p>
        </div>
        <div class="p-shopInfo__item__wrap">
            <div class="p-shopInfo__item">
                <p class="p-shopInfo__item__label">店舗名</p>
                <?= $this->Html->para('p-shopInfo__item__text' , 'ROLLEN原宿店') ?>
            </div>
            <div class="p-shopInfo__item">
                <p class="p-shopInfo__item__label">紹介文</p>
                <?= $this->Html->para('p-shopInfo__item__text' , '原宿にあるカラーが得意な美容室です。ダメージを99%カットした当店オリジナルのケアブリーチで、髪を傷めず、ご希望のカラーに仕上げます。') ?>
            </div>
            <div class="p-shopInfo__item">
                <p class="p-shopInfo__item__label">住所</p>
                <?= $this->Html->para('p-shopInfo__item__text' , '東京都渋谷区原宿1-11-11 2F') ?>
            </div>
            <div class="p-shopInfo__item">
                <p class="p-shopInfo__item__label">電話番号</p>
                <?= $this->Html->para('p-shopInfo__item__text' , '03-1234-5678') ?>
            </div>
            <div class="p-shopInfo__item">
                <p class="p-shopInfo__item__label">ホームページ</p>
                <?= $this->Html->para('p-shopInfo__item__text' , 'https://www.google.com/') ?>
            </div>
            <div class="p-shopInfo__item">
                <p class="p-shopInfo__item__label">LINE</p>
                <?= $this->Html->para('p-shopInfo__item__text' , 'https://line.me/ja/') ?>
            </div>
            <div class="p-shopInfo__item">
                <p class="p-shopInfo__item__label">Twitter</p>
                <?= $this->Html->para('p-shopInfo__item__text' , 'https://twitter.com/') ?>
            </div>
            <div class="p-shopInfo__item">
                <p class="p-shopInfo__item__label">Facebook</p>
                <?= $this->Html->para('p-shopInfo__item__text' , 'https://www.facebook.com') ?>
            </div>
            <div class="p-shopInfo__item">
                <p class="p-shopInfo__item__label">Instagram</p>
                <?= $this->Html->para('p-shopInfo__item__text' , 'https://www.instagram.com/?hl=ja/aaaaaaaaaaaaaaaaaaaaaa') ?>
            </div>
        </div>
    </div>
</section>
<?php echo $this->element('footer');

