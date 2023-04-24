<div class="p-memberEdit">
    <section class="p-memberEdit__title__wrap">
        <h1 class="p-memberEdit__title">メンバー詳細・編集</h1>
    </section>

    <div class="p-memberEdit__account">
    <!-- username,emailを変更できるのは該当ユーザのみ-->
    <?= $this->Form->create($myuser) ?>
      <?php if ($auth['id'] == $myuser['id']): ?>
        <p class="p-memberEdit__account__name">
        <?= $this->Form->control('username' , ['readonly' => true]); ?></p>
        <p class="p-memberEdit__account__email">
        <?= $this->Form->control('email' , ['readonly' => true]); ?></p>
      <?php else: ?>
        <p class="p-memberEdit__account__name">
        <?= $myuser['username'] ?></p>
        <p class="p-leaderEdit__account__email">
        <?= $myuser['email'] ?></p>
      <?php endif; ?>
      <?= $this->Form->end()?>
    </div>

    <div class="p-memberEdit__account">
      <div class="affiliaterEdit__pointBox">
        <p>保有ポイント数</p>
        <div>
          <?= $this->Html->image('icon/icon_coin.png', ['class' => 'affiliater__coin__icon']) ?><?= $myuser->point ?>
        </div>
      </div>
    </div>

    <div class="p-memberEdit__account">
      <div class="affiliaterEdit__bankBox">
          <p class="affiliaterPayment_sectionTitle">口座情報</p>
          <?php if(!isset($myuserBank)):?>
              <ul class="c-list__list">
                  <li class="affiliater__payment__list">
                      <p class="c-list__heading">口座情報が登録されていません</p>
                  </li>
              </ul>
          <?php endif;?>
          <div>
              <?php if(isset($myuserBank)):?>
              <ul class="c-list__list">
                  <li class="affiliater__payment__list">
                      <p class="c-list__heading">口座名義人</p>
                      <p><?= $myuserBank->account_holder_name ?></p>
                  </li>
                  <li class="affiliater__payment__list">
                      <p class="c-list__heading">金融機関名</p>
                      <p><?= $myuserBank->bank_name ?></p>
                  </li>
                  <li class="affiliater__payment__list">
                      <p class="c-list__heading">支店名</p>
                      <p><?= $myuserBank->branch ?></p>
                  </li>
                  <li class="affiliater__payment__list">
                      <p class="c-list__heading">預金種類</p>
                      <p><?= \App\Model\Entity\MyuserBank::DEPOSIT_TYPE_LIST[$myuserBank->deposit_type] ?></p>
                  </li>
                  <li class="affiliater__payment__list">
                      <p class="c-list__heading">口座番号</p>
                      <p><?= $myuserBank->account_number ?></p>
                  </li>
              </ul>
              <?php endif;?>
          </div>
    </div>
</div>

<?php echo $this->element('footer');


