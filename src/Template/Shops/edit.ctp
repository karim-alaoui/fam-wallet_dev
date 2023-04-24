<div class="p-shopEdit">
    <section class="p-shopEdit__title__wrap">
        <h1 class="p-shopEdit__title">店舗情報編集</h1>
    </section>
    <div class="p-shopEdit__contents">
        <?= $this->Form->create($shop, ['type' => 'file']) ?>
                <fieldset>
                    <div class="p-shopEdit__form__file__wrap">
                        <label class="p-shopEdit__form__file__cycle">
                            <p class="p-shopEdit__form__file__img" style="height: 100%">
                              <?php if (!empty($file_check)): ?>
                                <?= $this->Html->image('shop_images/'.$shop->id.'/'.$shop->image, ['id' =>'shop_image' , 'alt' => 'SHOP IMAGE']) ?>
                              <?php else: ?>
                                <?= $this->Html->image('img_default.png', ['id' =>'shop_image', 'alt' => 'SHOP IMAGE']) ?>
                              <?php endif; ?>
                            </p>

                            <div class="p-shopEdit__form__file__label">
                            </div>
                            <?= $this->Form->control('image' , ['type' => 'file', 'accept'=>'.png, .jpg']) ?>
                        </label>
                        <?php if (!empty($size_error)): ?>
                            <p class="c-usersForm-input__error"><?= $size_error ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="p-shopEdit__form__text__wrap">
                        <p class="p-shopEdit__form__text">正方形以外の画像を入れた場合、クーポン・スタンプカード発行時に画像がずれる場合があります。</p>
                    </div>
                    <div class="p-shopEdit__form__input__container">
                        <div class="p-shopEdit__form__input__wrap">
                            <p class="p-shopEdit__form__label">店舗名</p>
                            <?= $this->Form->control('name' , ['class' => 'p-shopEdit__form__input' , 'required' => true]) ?>
                        </div>
                        <div class="p-shopEdit__form__input__wrap">
                            <p class="p-shopEdit__form__label">紹介文</p>
                            <?= $this->Form->control('introdaction' , ['class' => 'p-shopEdit__form__textarea' , 'type' => 'textarea','required' => false]) ?>
                        </div>
                        <div class="p-shopEdit__form__input__wrap">
                            <p class="p-shopEdit__form__label">住所</p>
                            <?= $this->Form->control('address' , ['class' => 'p-shopEdit__form__input' , 'required' => false]) ?>
                        </div>
                        <div class="p-shopEdit__form__input__wrap">
                            <p class="p-shopEdit__form__label">電話番号</p>
                            <?= $this->Form->control('tel' , ['class' => 'p-shopEdit__form__input' , 'required' => false]) ?>
                        </div>
                        <div class="p-shopEdit__form__input__wrap">
                            <p class="p-shopEdit__form__label">ホームページ</p>
                            <?= $this->Form->control('homepage' , ['class' => 'p-shopEdit__form__input' , 'required' => false]) ?>
                        </div>
                        <div class="p-shopEdit__form__input__wrap">
                            <p class="p-shopEdit__form__label">LINE</p>
                            <?= $this->Form->control('line' , ['class' => 'p-shopEdit__form__input' , 'required' => false]) ?>
                        </div>
                        <div class="p-shopEdit__form__input__wrap">
                            <p class="p-shopEdit__form__label">Twitter</p>
                            <?= $this->Form->control('twitter' , ['class' => 'p-shopEdit__form__input' , 'required' => false]) ?>
                        </div>
                        <div class="p-shopEdit__form__input__wrap">
                            <p class="p-shopEdit__form__label">Facebook</p>
                            <?= $this->Form->control('facebook' , ['class' => 'p-shopEdit__form__input' , 'required' => false]) ?>
                        </div>
                        <div class="p-shopEdit__form__input__wrap">
                            <p class="p-shopEdit__form__label">Instagram</p>
                            <?= $this->Form->control('instagram' , ['class' => 'p-shopEdit__form__input' , 'required' => false]) ?>
                        </div>
                    </div>
                </fieldset>
                <?= $this->Form->button('保存',['class' => 'p-shopEdit__form__btn c-btn--orange']) ?>
                <div class="p-shopEdit__form__btn c-btn--white"><a href="/shops">戻る</a></div>
        <?= $this->Form->end() ?>
    </div>
    <div class="p-shopEdit__delete__wrap">
        <?php if (!empty($shop_result)): ?>
            <p class="p-shopEdit__delete jsc-modal-trigger">この店舗を削除する</p>
        <?php else: ?>
          <p class='p-shopEdit__delete'>店舗に所属するクーポン、スタンプカード、ユーザーが存在するため、店舗の削除は出来ません。<br>店舗に所属する情報を解除してから削除してください。<p>
        <?php endif; ?>
    </div>
</div>
<div class="p-shopEdit__overlay model-overlay" style="display:none">
    <div class="p-shopEdit__modal">
        <div class="p-shopEdit__modal__content" style="">
            <p class="p-shopEdit__modal__heading">この店舗を本当に削除しますか？</p>
            <p class="p-shopEdit__modal__text">店舗を削除しても作成したクーポンは残ります</p>
            <div class="p-shopEdit__modal__btn__wrap">
                <?= $this->Form->button('キャンセル' , ['class' => 'p-shopEdit__modal__btn jsc-check-cancel c-btn--gray']) ?>
                <?= $this->Form->postButton('削除' , ['action' => 'delete', $shop->id], ['class' => 'p-shopEdit__modal__btn jsc-check-decied c-btn--red']) ?>
            </div>
        </div>
    </div>
</div>
<?php
echo $this->element('footer_login');
echo $this->element('footer_menu');
echo $this->element('footer');
?>

<script>
$(function() {
    // アップロードするファイルを選択
    $('input[type=file]').change(function(ev) {
      const reader = new FileReader();
      //--ファイル名を取得
      const fileName = ev.target.files[0].name;

      //--画像が読み込まれた時の動作を記述
      reader.onload = function (ev) {
        $('#shop_image').attr('src', ev.target.result).css('height', '100%');
      }
      reader.readAsDataURL(this.files[0]);
    });
});
</script>
