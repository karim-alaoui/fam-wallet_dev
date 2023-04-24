<div class="p-requestPasswordReset">
  <?= $this->Flash->render('auth') ?>
  <div class="p-requestPasswordReset__title__wrap">
    <p class="p-requestPasswordReset__title">パスワード再設定</p>
  </div>
  <div class="p-requestPasswordReset__contents">
    <p class="p-requestPasswordReset__text">
      ご登録のメールアドレスを入力してください。パスワード再登録URLを発行します。
    </p>
    <?= $this->Form->create('User',['class'=>'p-requestPasswordReset__form']) ?>
    <fieldset>
      <div class="p-requestPasswordReset__form__input__wrap">
        <p class="p-requestPasswordReset__form__label">メールアドレス</p>
        <?= $this->Form->control('reference', ['class' => 'p-requestPasswordReset__form__input']) ?>
      </div>
    </fieldset>
    <?= $this->Form->button('送信', ['class' => 'p-requestPasswordReset__btn c-btn--orange']); ?>
    <?= $this->Form->end() ?>
  </div>
</div>
