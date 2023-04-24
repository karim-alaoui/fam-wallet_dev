<div class="p-memberEdit">
  <section class="p-memberEdit__title__wrap">
    <h1 class="p-memberEdit__title">申請内容</h1>
  </section>

  <p class="affiliaterPayment_sectionTitle">支払い情報</p>

  <div>
    <ul class="c-list__list">
      <li class="affiliater__payment__list">
        <p class="c-list__heading">換金申請額</p>
        <p><?= $application->point ?>円</p>
      </li>
      <li class="affiliater__payment__list">
        <p class="c-list__heading">振込手数料</p>
        <p><?= \App\Model\Entity\AffiliaterApplication::TRANSFER_FEE ?>円</p>
      </li>
      <li class="affiliater__payment__list border__bottom__none affiliater__payment__sum">
        <p class="c-list__heading">支払金額</p>
        <p><?= $application->point - \App\Model\Entity\AffiliaterApplication::TRANSFER_FEE ?>円</p>
      </li>
    </ul>

    <p class="affiliaterPayment_sectionTitle">口座情報</p>
    <?php if (!isset($myuser->myuser_bank)): ?>
      <ul class="c-list__list">
        <li class="affiliater__payment__list">
          <p class="c-list__heading">口座情報が登録されていません</p>
        </li>
      </ul>
    <?php endif; ?>
    <div>
      <?php if (isset($myuser->myuser_bank)): ?>
        <ul class="c-list__list">
          <li class="affiliater__payment__list">
            <p class="c-list__heading">口座名義人</p>
            <p><?= $myuser->myuser_bank->account_holder_name ?></p>
          </li>
          <li class="affiliater__payment__list">
            <p class="c-list__heading">金融機関名</p>
            <p><?= $myuser->myuser_bank->bank_name ?></p>
          </li>
          <li class="affiliater__payment__list">
            <p class="c-list__heading">支店名</p>
            <p><?= $myuser->myuser_bank->branch ?></p>
          </li>
          <li class="affiliater__payment__list">
            <p class="c-list__heading">預金種類</p>
            <p><?= \App\Model\Entity\MyuserBank::DEPOSIT_TYPE_LIST[$myuser->myuser_bank->deposit_type] ?></p>
          </li>
          <li class="affiliater__payment__list">
            <p class="c-list__heading">口座番号</p>
            <p><?= $myuser->myuser_bank->account_number ?></p>
          </li>
        </ul>
      <?php endif; ?>
    </div>

    <p class="affiliaterPayment_sectionTitle">アフィリエイター情報</p>
    <ul class="c-list__list">
      <li class="affiliater__payment__list">
        <p class="c-list__heading">アカウント名</p>
        <p><?= $myuser->username ?></p>
      </li>
      <li class="affiliater__payment__list">
        <p class="c-list__heading">メールアドレス</p>
        <p><?= $myuser->email ?></p>
      </li>
    </ul>

    <?= $this->Form->create() ?>
    <p class="affiliaterPayment_sectionTitle">ステータス</p>
    <div class="c-input__form__input__wrap">
      <?= $this->Form->control('status_id', ['type' => 'select', 'id' => 'status_id', 'value' => $application->status_id, 'options' => \App\Model\Entity\Application::STATUS_LIST, 'multiple' => false, 'class' => 'c-input__form__input']) ?>
    </div>

    <div class="p-memberEdit__contents">
      <?= $this->Form->button('保存', ['id' => 'status_save', 'class' => 'p-memberEdit__form__btn c-btn--orange']) ?>
      <div class="c-input__form__btn c-btn--white"><a href="/admins">戻る</a></div>
    </div>
    <?= $this->Form->end() ?>
  </div>

  <div class="c-list__content">
    <div class="tab-wrap">
      <input id="TAB-01" type="radio" name="TAB" class="tab-switch" checked="checked"/>
      <label class="tab-label" for="TAB-01">支払申請中<span
          class="affiliater__list__num"><?= count($affiliaterApplications); ?></span></label>
      <div class="tab-content">
        <ul class="c-list__list">
          <?php foreach ($affiliaterApplications as $member): ?>
            <li class="c-list__listItem">

              <a
                style="display: flex;"
                class="align-items-center justify-content-between"
                href="<?= $this->Url->build(['controller' => 'Admins', 'action' => 'applicationDetail', $member->id]); ?>">

                <div>
                  <p class="c-list__heading"><?= $member->company->name ?></p>
                  <ul class="c-list__shop__list">
                    <li class="c-list__shop__listItem__none">

                      <p class="c-list__shop__listItem__text__none flex__center">
                        <?= $this->Html->image('icon/icon_coin.png', ['class' => 'affiliater__coin__icon']) ?><?= $member->point ?>
                      </p>
                    </li>
                  </ul>
                </div>
                <div>
                  <p
                    class="u-fs-12 color--gray"><?= \App\Model\Entity\AffiliaterApplication::STATUS_LIST[$member->status_id] ?></p>
                  <p class="u-fs-12 color--gray"><?= $member->create_at ?></p>
                </div>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>

</div>

<script>
  $(function(){
    $('#status_id').change(function() {
      $(window).on('beforeunload', function() {
        return '投稿が完了していません。このまま移動しますか？';
      });
    });
    $('#status_save').click(function() {
      $(window).off('beforeunload');
    });
  });
</script>





