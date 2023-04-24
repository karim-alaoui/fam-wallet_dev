<div class="c-list" id="affiliater_points">
  <section class="c-list__title__wrap">
    <h1 class="c-list__title">換金申請情報の確認</h1>
  </section>
  <div class="">
    <div class="">
        <?= $this->Form->create($validate, ['class' => 'c-input__form']) ?>
      <ul class="c-list__list">
        <li class="affiliater__payment__list">
          <p class="c-list__heading">現在保有ポイント</p>
          <p><?= $point ?>P</p>
        </li>
        <li class="affiliater__payment__list">
          <p class="c-list__heading">換金申請ポイント</p>
            <?= $this->Form->control('point' , [
                'type' => 'hidden',
                'value' => $this->request->getData('point')]) ?>
          <span><?= $this->request->getData('point') ?>P</span>

            <?= $this->Form->control('point_all' , [
                'type' => 'hidden',
                'value' => 'point_all'
            ]); ?>
        </li>
        <li class="affiliater__payment__list">
          <p class="c-list__heading">振込手数料</p>
          <p><?= \App\Model\Entity\AffiliaterApplication::TRANSFER_FEE ?>円</p>
        </li>

        <li class="affiliater__payment__list color--orange">
          <p class="c-list__heading">実質の振込金額</p>
          <p><span id="application_cash" class="font-weight-600">
                  <?= $this->request->getData('point') - \App\Model\Entity\AffiliaterApplication::TRANSFER_FEE ?></span>円</p>
        </li>

        <li class="affiliater__payment__list">
          <p class="c-list__heading">申請後のポイント残高</p>
          <p><span id="application_after_point"><?= $point - $this->request->getData('point') ?></span>P</p>
        </li>
      </ul>

      <div class="p-signUp__form__checkbox__wrap m-10 flex align-items-center justify-content-center">
        <label class="p-signUp__form__checkbox__label">
            <?= $this->Form->control('consent' , [
                'class' => 'p-signUp__form__checkbox__input',
                'type' => 'checkbox',
                'label' => false,
                'required' => false,
                'error' => false
            ]) ?>
            <?= $this->Html->link('利用規約・プライバシーポリシー', ['action' => 'policy']) ?><span>に同意します</span>
        </label>
          <?= $this->Form->error('consent') ?>
      </div>

      <div class="p-signUp__form__checkbox__wrap ml-10 mr-10">
          <?= $this->Form->button('申請内容を送信',[
              'class' => 'p-couponEdit__form__btn c-btn--orange',
              'id' => 'application_submit',
          ]) ?>
        <div class="">
          <?= $this->Form->submit('戻る', [
              'name' => 'back',
              'class' => 'p-couponEdit__form__btn c-btn--white '
          ])?>
        </div>
      </div>
    </div>
      <?= $this->Form->control('coupon_id_list', ['type' => 'hidden', 'value' => json_encode($this->request->getData('coupon_id_list'))]); ?>
      <?= $this->Form->control('myuser_id', ['type' => 'hidden', 'value' => $this->request->session()->read('Auth.User.id')]); ?>
      <?= $this->Form->end()?>

  </div>

</div>
