<div class="p-shopList">
    <section class="p-shopList__title__wrap">
        <h1 class="p-shopList__title">店舗一覧</h1>
        <div class="p-shopList__title__btn"><?= $this->Html->link(null,['action' => '/shop-input']) ?>
            <span class="p-shopList__title__btn__plus"></span>
            <span class="p-shopList__title__btn__plus"></span>
        </a></div>
    </section>
    <div class="p-shopList__content">
        <!-- 店舗の登録がない場合の文言表示 -->
        <div class="p-shopList__text__wrap">
            <p class="p-shopList__text">店舗が登録されていません。<br>右上の店舗追加ボタンから店舗を追加してください。</p>
        </div>
        <!-- ここまで -->
        <ul class="p-shopList__list">
            <li class="p-shopList__listItem">
                <a href="">
                    <p class="p-shopList__heading">ROLLEN渋谷本店</p>
                </a>
            </li>
            <li class="p-shopList__listItem">
                <a href="">
                    <p class="p-shopList__heading">ROLLEN原宿店</p>
                </a>
            </li>
        </ul>
    </div>
</div>
<?php echo $this->element('footer');
