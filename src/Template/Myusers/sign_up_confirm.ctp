<?php

use Cake\Core\Configure;
?>
<section class="p-signUpComfirm">
    <div class="p-signUpComfirm__title__wrap">
        <h1 class="p-signUpComfirm__title">新規会員登録</h1>
    </div>
    <?= $this->Form->create(); ?>
    <div class="p-signUpComfirm__contents">
        <div class="p-signUpComfirm__item">
            <?php if ($role_id == \App\Model\Entity\Myuser::ROLE_AFFILIATER): ?>
                <p class="p-signUpComfirm__item__label">名前</p>
            <?php elseif ($role_id == \App\Model\Entity\Myuser::ROLE_OWNER): ?>
                <p class="p-signUpComfirm__item__label">オーナー名</p>
            <?php endif; ?>

            <?= $this->Form->control('username', ['value' => $this->request->getData('username'), 'class' => 'p-signUp__form__comfirm' , 'readonly' => true, 'required' => false]) ?>
        </div>

        <div class="p-signUpComfirm__item">
            <p class="p-signUpComfirm__item__label">メールアドレス</p>
            <?= $this->Form->control('email' , ['value' => $this->request->getData('email'), 'class' => 'p-signUp__form__comfirm' , 'readonly' => true, 'required' => false]) ?>
        </div>
        <div class="p-signUpComfirm__item">
            <p class="p-signUpComfirm__item__label">パスワード</p>
            <?= $this->Form->control('password' , ['value' => $this->request->getData('password'), 'type' => 'password', 'class' => 'p-signUp__form__comfirm' , 'readonly' => true,'required' => false]) ?>
        </div>
        <div class="p-signUpComfirm__item">
        <p class="p-signUpComfirm__item__label">パスワード(確認)</p>
        <?= $this->Form->control('password_confirm' , ['value' => $this->request->getData('password_confirm'), 'class' => 'p-signUp__form__comfirm' , 'readonly' => true,'required' => false , 'type' => 'password']) ?>
        </div>

      <?php if ($role_id == \App\Model\Entity\Myuser::ROLE_OWNER):; ?>
      <fieldset style="margin-top: 50px">
        <legend>会社情報</legend>
        <div class="p-signUp__form__input__wrap">
          <p class="p-signUpComfirm__item__label">会社名</p>
          <?= $this->Form->control('company_name' , ['class' => 'p-signUp__form__comfirm', 'readonly' => true, 'value' => $this->request->getData('company_name')]) ?>
        </div>
        <div class="p-shopInput__form__input__wrap">
          <p class="p-signUpComfirm__item__label">住所</p>
          <?= $this->Form->control('company_address', ['class' => 'p-signUp__form__comfirm', 'readonly' => true, 'value' => $this->request->getData('company_address')]); ?>
        </div>
        <div class="p-shopInput__form__input__wrap">
          <p class="p-signUpComfirm__item__label">メールアドレス</p>
          <?= $this->Form->control('company_email', ['class' => 'p-signUp__form__comfirm', 'readonly' => true, 'value' => $this->request->getData('company_email')]); ?>
        </div>
        <div class="p-shopInput__form__input__wrap">
          <p class="p-signUpComfirm__item__label">電話番号</p>
          <p class="p-signUp__form__note">例: 0399999999</p>
          <?= $this->Form->control('company_tel', ['class' => 'p-signUp__form__comfirm', 'required' => false, 'value' => $this->request->getData('company_tel')]); ?>
        </div>
        <div class="p-shopInput__form__input__wrap">
          <p class="p-signUpComfirm__item__label">ご担当者名</p>
          <?= $this->Form->control('company_manager_name', ['class' => 'p-signUp__form__comfirm', 'required' => false, 'value' => $this->request->getData('company_manager_name')]); ?>
        </div>
      </fieldset>
      <?php endif; ?>

        <?= $this->Form->control('company_id', ['type' => 'hidden']) ?>
        <?= $this->Form->control('role_id', ['type' => 'hidden', 'value' => $role_id]) ?>
        <div class="p-signUpComfirm__btn"><?= $this->Form->button('確認',['class' =>  'c-btn--orange']) ?></div>
        <?= $this->Form->end(); ?>
        <div class="p-signUpComfirm__btn c-btn--white"><?= $this->Html->link('戻る', ['action' => 'signUp', '?' => ['role_id' => $role_id]]) ?></div>
    </div>
</section>
<?php echo $this->element('footer'); ?>

<style>
  .p-signUp__form__comfirm {
    width: 100%;
  }

</style>
