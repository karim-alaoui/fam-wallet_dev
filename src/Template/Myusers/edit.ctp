<?php
use Cake\Core\Configure;
$Users = ${$tableAlias};
?>
<div class="p-accountInfo">
    <section class="p-accountInfo__title__wrap">
        <h1 class="p-accountInfo__title">アカウント情報</h1>
    </section>
    <div class="p-accountInfo__contents">
            <?= $this->Form->create($Users); ?>
            <div class="p-accountInfo__form__input__wrap">
                <p class="p-accountInfo__form__label">名前</p>
                <?= $this->Form->control('username', ['class' => 'p-accountInfo__form__input']); ?>
            </div>
            <div class="p-accountInfo__form__input__wrap">
                <p class="p-accountInfo__form__label">メールアドレス</p>
                <?= $this->Form->control('email', ['class' => 'p-accountInfo__form__input']); ?>
            </div>

            <?php if ($roleId == \App\Model\Entity\Myuser::ROLE_OWNER):; ?>
              <fieldset style="margin-top: 50px">
              <legend>会社情報</legend>
              <div class="p-signUp__form__input__wrap">
                <p class="p-signUp__form__label">会社名</p>
                <?= $this->Form->control('company_name' , ['type' => 'text', 'value' => $company->name, 'readonly' => true, 'class' => 'p-signUp__form__input']) ?>
                <?php if (!empty($company_name_check)): ?>
                  <p class="c-usersForm-input__error"><?= $company_name_check ?></p>
                <?php endif; ?>
                <p class="p-signUp__form__note">会社名は後から変更することができません</p>
              </div>
              <div class="p-shopInput__form__input__wrap">
                <p class="p-shopInput__form__label">住所</p>
                <?= $this->Form->control('company_address', ['class' => 'p-shopInput__form__input', 'value' => $company->address,'required' => false]); ?>
                <?php if (!empty($company_address_check)): ?>
                  <p class="c-usersForm-input__error"><?= $company_address_check ?></p>
                <?php endif; ?>
              </div>
              <div class="p-shopInput__form__input__wrap">
                <p class="p-shopInput__form__label">メールアドレス</p>
                <?= $this->Form->control('company_email', ['class' => 'p-shopInput__form__input', 'value' => $company->email, 'required' => false]); ?>
                <?php if (!empty($company_email_check)): ?>
                  <p class="c-usersForm-input__error"><?= $company_email_check ?></p>
                <?php endif; ?>
              </div>
              <div class="p-shopInput__form__input__wrap">
                <p class="p-shopInput__form__label">電話番号</p>
                <p class="p-signUp__form__note">例: 0399999999</p>
                <?= $this->Form->control('company_tel', ['class' => 'p-shopInput__form__input', 'value' => $company->tel, 'required' => false]); ?>
                <?php if (!empty($company_tel_check)): ?>
                  <p class="c-usersForm-input__error"><?= $company_tel_check ?></p>
                <?php endif; ?>
              </div>
              <div class="p-shopInput__form__input__wrap">
                <p class="p-shopInput__form__label">ご担当者名</p>
                <?= $this->Form->control('company_manager_name', ['class' => 'p-shopInput__form__input', 'value' => $company->manager_name ,'required' => false]); ?>
                <?php if (!empty($company_manager_name_check)): ?>
                  <p class="c-usersForm-input__error"><?= $company_manager_name_check ?></p>
                <?php endif; ?>
              </div>
            </fieldset>
            <?php endif; ?>

            <?= $this->Form->button('変更', ['class' => 'p-accountInfo__form__btn c-btn--orange']) ?>
        <?= $this->Form->end() ?>
    </div>
    <ul class="p-accountInfo__list">
        <li class="p-accountInfo__listItem">
            <p class="p-accountInfo__listItem__label">パスワード</p>
            <p class="p-accountInfo__listItem__link">
            <?= $this->Html->link('パスワードを変更する', ['action' => 'requestResetPassword']) ?></p>
        </li>
        <li class="p-accountInfo__listItem">
            <p class="p-accountInfo__listItem__label">権限</p>
            <!-- オーナーの権限変更は出来ません -->
            <?php if ($roleId == \App\Model\Entity\Myuser::ROLE_OWNER) : ?>
              <?= $this->Html->para('p-accountInfo__listItem__text', $Myusers->role->name) ?>

            <?php elseif ($roleId != \App\Model\Entity\Myuser::ROLE_OWNER) : ?>
              <?= $this->Html->para('p-accountInfo__listItem__text', $Myusers->role->name) ?>

            <?php else : ?>
              <?= $this->Form->control('role_id', [
                'type' => 'select',
                'options' => $roles,
                'multiple' => false,
                'class' => 'p-accountInfo__listItem__text'
              ]) ?>

            <?php endif; ?>
        </li>
        <li class="p-accountInfo__listItem">
            <?php if ($roleId == \App\Model\Entity\Myuser::ROLE_AFFILIATER) : ?>
                <p class="p-affiliaterAccount__item__label">振込先口座</p>

                <?php if(!empty($bank)) : ?>
                    <p class="p-affiliaterAccount__item__text">登録済</p>
                    <?= $this->Html->link('振込先口座を変更する', ['controller' => 'Affiliaters', 'action' => 'bank_edit'],['class' => 'p-affiliaterAccount__link']) ?>
                <?php else : ?>
                    <?= $this->Html->link('振込先口座を登録する', ['controller' => 'Affiliaters', 'action' => 'bank_edit'],['class' => 'p-affiliaterAccount__link']) ?>
                <?php endif ?>

            <?php else : ?>

                <p class="p-accountInfo__listItem__label">支払方法</p>

                <?php if(!empty($card)) : ?>
                    <div class="p-accountInfo__credit mt-5">
                        <?php echo $this->Html->para('p-accountInfo__credit--brand', $card['brand']); ?>
                        <?php echo $this->Html->para('p-accountInfo__credit--number', $card['last4']); ?>
                        <?php echo $this->Html->para('p-accountInfo__credit--expiration_date', '有効期限：'.$card['exp_month'].'/'.$card['exp_year']); ?>
                    </div>
                <?php endif; ?>

                <p class="p-accountInfo__listItem__link">
                    <?= $this->Html->link('支払方法を設定する', ['controller' => 'Myusers', 'action' => 'payment_edit', $auth['id']]) ?>
                </p>

                <?php if(!empty($card)): ?>
                    <p class="p-accountInfo__listItem__link">
                        <?= $this->Form->postLink(
                            'カード情報を削除',
                            ['controller' => 'Myusers', 'action' => 'card_delete'],
                            [
                                'block' => true,
                                'method' => 'delete',
                                'confirm' => 'カード情報を削除しますか？',
                                'class' => 'color--danger']) ?>
                    </p>
                <?php endif;?>

            <?php endif; ?>
        </li>

        <li class="p-accountInfo__listItem">
            <p class="p-accountInfo__listItem__label">所属店舗</p>
            <?php if ($roleId == \App\Model\Entity\Myuser::ROLE_OWNER): ?>
              <?php foreach ($shops as $shop) : ?>
                <?= $this->Html->para('p-accountInfo__listItem__text', $shop->name) ?>
              <?php endforeach; ?>
            <?php else: ?>
              <?php foreach ($shops as $shop => $name) : ?>
                <?= $this->Html->para('p-accountInfo__listItem__text', $name) ?>
                <?php endforeach; ?>
              <?php endif; ?>
        </li>
    </ul>
    <?php if ($auth['role_id'] == 1): ?>
      <div class="p-accountInfo__delete__wrap">
        <p class="p-accountInfo__delete jsc-modal-trigger">アカウントを削除する</p>
      </div>
    <?php endif; ?>

  <div class="p-couponEdit__contents">
    <div class="c-input__form__btn c-btn--white"><a href="/users">戻る</a></div>
  </div>

    <div class="p-accountInfo__overlay model-overlay" style="display:none">
        <div class="p-accountInfo__modal">
            <p class="p-accountInfo__modal__heading">このユーザーを本当に削除しますか？</p>
            <div class="p-accountInfo__modal__btn__wrap">
                <?= $this->Form->button('キャンセル',['class' => 'p-accountInfo__modal__btn c-btn--gray jsc-check-cancel']) ?>
                <?= $this->Form->postButton('削除', ['action' => 'delete', $Users->id], ['class' => 'p-accountInfo__modal__btn c-btn--red jsc-check-decied']) ?>
            </div>
        </div>
    </div>
  <?= $this->fetch('postLink');?>
</div>
<?php
/**
 * @var \App\View\AppView $this
 */


echo $this->element('footer');
