<?php
use Cake\Core\Configure;

?>
<div class="p-signIn">
  <?= $this->Flash->render('auth') ?>
  <?= $this->Html->image('Get Real-3.png', ['style' => 'width: 200px; height: auto']) ?>
  <?= $this->Form->create() ?>
  <div class="p-signIn__form__wrap">
    <form action="" class="p-sign__form">
      <div class="p-signIn__form__input__wrap">
        <p class="p-signIn__form__label">メールアドレス/ID名</p>
        <?= $this->Form->control('username', ['class' => 'p-signIn__form__input', 'required' => true]) ?>
      </div>
      <div class="p-signIn__form__input__wrap">
        <p class="p-signIn__form__label">パスワード</p>
        <?= $this->Form->control('password', ['class' => 'p-signIn__form__input', 'required' => true]) ?>
      </div>
      <button class="p-signIn__form__btn c-btn--signin__orange">ログイン</button>
    </form>
    <p class="p-singIn__form__link">
    <?= $this->Html->link('パスワードを忘れた場合', ['action' => 'request_reset_password']) ?></p>
  </div>

  <div class="p-signIn__form__wrap">
    <form action="" class="p-sign__form">
<!--      <p class="p-signIn__signInText">本サービスはオーナー様がご登録下さい</p>-->

      <div class="p-signUpComfirm__btn c-btn--white"><a href="https://forms.gle/ZbbDKExCtSWaQb5v8">オーナーとして登録</a></div>
        <div class="p-signUpComfirm__btn c-btn--white"><?= $this->Html->link('一般ユーザーとして登録',
                [
                    'action' => 'signUp',
                    'role_id' => \App\Model\Entity\Myuser::ROLE_AFFILIATER
                ]) ?></div>
    </form>
  </div>

  <p class="p-singIn__form__device__link">
    <?= $this->Html->link('対応端末・利用方法 はこちら', ['controller' => 'Myusers', 'action' => 'enabled_device']) ?></p>
  </p>
  <footer class="p-signIn__footer">
    <p class="p-signIn__footer__text">copyright 2020 FAM Inc.</p>
  </footer>
</div>
