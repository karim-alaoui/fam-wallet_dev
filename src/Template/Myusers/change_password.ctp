<section class="p-passwordResetInput">
  <div class="p-passwordResetInput__title__wrap">
    <h1 class="p-passwordResetInput__title">パスワードの再設定</h1>
  </div>
  <div class="p-passwordResetInput__contents">
    <p class="p-passwordResetInput__text">
      新しいパスワードを入力し、保存ボタンを押してください。
    </p>
    <?= $this->Form->create() ?>
    <div class="p-passwordResetInput__form__input__wrap">
      <p class="p-passwordResetInput__form__label">新しいパスワード</p>
      <?= $this->Form->control('password',['class' => 'p-passwordResetInput__form__input','required' => true]) ?>
      <p class="p-passwordResetInput__form__note">半角英数字8文字以上</p>
    </div>
    <div class="p-passwordResetInput__form__input__wrap">
      <p class="p-passwordResetInput__form__label">新しいパスワード（確認）</p>
      <?= $this->Form->control('password_confirm',['class' => 'p-passwordResetInput__form__input','type' => 'password','required' => true]) ?>
      <p class="p-passwordResetInput__form__note">半角英数字8文字以上</p>
    </div>
    <?= $this->Form->button('保存',['class' => 'p-passwordResetInput__btn c-btn--orange']) ?>
    <?= $this->Form->end() ?>
  </div>
</section>
<?php echo $this->element('footer');
