<?php

use Cake\Core\Configure;

?>
<section class="p-signUp">
    <div class="p-signUp__title__wrap">
        <h1 class="p-signUp__title">新規会員登録</h1>
    </div>
    <div class="p-signUp__contents">
        <?= $this->Flash->render('auth') ?>
    　　<?= $this->Form->create($user); ?>
        <fieldset>
            <div class="p-signUp__form__input__wrap">
            <?php if ($role_id == \App\Model\Entity\Myuser::ROLE_AFFILIATER): ?>
                <p class="p-signUp__form__label">名前</p>
            <?php elseif ($role_id == \App\Model\Entity\Myuser::ROLE_OWNER):; ?>
                <p class="p-signUp__form__label">オーナー名</p>
            <?php endif; ?>
                <?= $this->Form->control('username' , ['type' => 'text', 'class' => 'p-signUp__form__input' , 'required' => false]) ?>
            </div>

            <div class="p-signUp__form__input__wrap">
                <?= $this->Form->control('role_id' , ['type' => 'hidden', 'value' => '1',  'class' => 'p-signUp__form__input']) ?>
            </div>
            <div class="p-signUp__form__input__wrap">
                <p class="p-signUp__form__label">メールアドレス</p>
                <?= $this->Form->control('email' , ['class' => 'p-signUp__form__input' , 'required' => false]) ?>
                <?php if (!empty($email_check)): ?>
                    <p class="c-usersForm-input__error"><?= $email_check ?></p>
                <?php endif; ?>
            </div>
            <div class="p-signUp__form__input__wrap">
                <p class="p-signUp__form__label">パスワード</p>
                <?= $this->Form->control('password' , ['class' => 'p-signUp__form__input' , 'required' => false]) ?>
                <p class="p-signUp__form__note">半角英数字8文字以上</p>
            </div>
            <div class="p-signUp__form__input__wrap">
                <p class="p-signUp__form__label">パスワード(確認)</p>
                <?= $this->Form->control('password_confirm' , ['class' => 'p-signUp__form__input' , 'required' => false , 'type' => 'password']) ?>
                <p class="p-signUp__form__note">半角英数字8文字以上</p>
            </div>

          <?php if ($role_id == \App\Model\Entity\Myuser::ROLE_OWNER):; ?>

          <fieldset style="margin-top: 50px">
            <legend>会社情報</legend>
            <div class="p-signUp__form__input__wrap">
              <p class="p-signUp__form__label">会社名</p>
              <?= $this->Form->control('company_name' , ['type' => 'text', 'required' => false, 'class' => 'p-signUp__form__input']) ?>
              <?php if (!empty($company_name_check)): ?>
                <p class="c-usersForm-input__error"><?= $company_name_check ?></p>
              <?php endif; ?>
              <p class="p-signUp__form__note">会社名は後から変更することができません</p>
            </div>
            <div class="p-shopInput__form__input__wrap">
              <p class="p-shopInput__form__label">住所</p>
              <?= $this->Form->control('company_address', ['class' => 'p-shopInput__form__input', 'required' => false]); ?>
              <?php if (!empty($company_address_check)): ?>
                <p class="c-usersForm-input__error"><?= $company_address_check ?></p>
              <?php endif; ?>
            </div>
            <div class="p-shopInput__form__input__wrap">
              <p class="p-shopInput__form__label">メールアドレス</p>
              <?= $this->Form->control('company_email', ['class' => 'p-shopInput__form__input', 'required' => false]); ?>
              <?php if (!empty($company_email_check)): ?>
                <p class="c-usersForm-input__error"><?= $company_email_check ?></p>
              <?php endif; ?>
            </div>
            <div class="p-shopInput__form__input__wrap">
              <p class="p-shopInput__form__label">電話番号</p>
              <p class="p-signUp__form__note">例: 0399999999</p>
              <?= $this->Form->control('company_tel', ['class' => 'p-shopInput__form__input', 'required' => false]); ?>
              <?php if (!empty($company_tel_check)): ?>
                <p class="c-usersForm-input__error"><?= $company_tel_check ?></p>
              <?php endif; ?>
            </div>
            <div class="p-shopInput__form__input__wrap">
              <p class="p-shopInput__form__label">ご担当者名</p>
              <?= $this->Form->control('company_manager_name', ['class' => 'p-shopInput__form__input', 'required' => false]); ?>
              <?php if (!empty($company_manager_name_check)): ?>
                <p class="c-usersForm-input__error"><?= $company_manager_name_check ?></p>
              <?php endif; ?>
            </div>
          </fieldset>
          <?php endif; ?>

            <div class="p-signUp__form__checkbox__wrap">
                <label class="p-signUp__form__checkbox__label">
                    <?= $this->Form->control('consent' , [
                        'class' => 'p-signUp__form__checkbox__input',
                        'type' => 'checkbox',
                        'label' => false,
                        'required' => false,
                        'error' => false
                        ]) ?>
                    <?= $this->Html->link('利用規約・プライバシーポリシー', ['controller' => 'Myusers', 'action' => 'privacy_policy']) ?><span>に同意します</span>
                </label>
            <?= $this->Form->error('consent') ?>
            </div>
        </fieldset>
            <?= $this->Form->control('mode', ['type' => 'hidden', 'value' => 'confirm']) ?>
            <?= $this->Form->control('company_id', ['type' => 'hidden']) ?>
            <?= $this->Form->button('確認',['class' => 'p-signUp__form__btn c-btn--orange']) ?>
            <p class="p-signUp__link"><?= $this->Html->link('確認メールが届かない場合', ['action' => 'resend_token_validation']); ?></p>
        <?= $this->Form->end(); ?>
    </div>
</section>
<?php echo $this->element('footer') ?>
