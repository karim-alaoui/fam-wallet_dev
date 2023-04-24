<div class="p-affiliaterAccount">
  <section class="p-affiliaterAccount__title__wrap">
    <h1 class="p-affiliaterAccount__title">申請情報詳細</h1>
  </section>

  <p class="affiliaterPayment_sectionTitle">アフィリエイター情報</p>
  <ul class="c-list__list">
    <li class="affiliater__payment__list">
      <p class="c-list__heading">アカウント名</p>
      <p><?= $affiliater->username ?></p>
    </li>
    <li class="affiliater__payment__list">
      <p class="c-list__heading">メールアドレス</p>
      <p><?= $affiliater->email ?></p>
    </li>
  </ul>

  <p class="affiliaterPayment_sectionTitle">換金申請情報</p>
  <ul class="c-list__list">
    <li class="affiliater__payment__list">
      <p class="c-list__heading">換金申請ポイント</p>
      <p><?= $affiliaterApplication->point ?>P</p>
    </li>
    <li class="affiliater__payment__list">
      <p class="c-list__heading">ステータス</p>
      <p><?= \App\Model\Entity\AffiliaterApplication::STATUS_LIST[$affiliaterApplication->status_id] ?></p>
    </li>
  </ul>

  <p class="affiliaterPayment_sectionTitle">会社情報</p>
  <ul class="c-list__list">
    <li class="affiliater__payment__list">
      <p class="c-list__heading">会社名</p>
      <p><?= $company->name ?></p>
    </li>
    <li class="affiliater__payment__list">
      <p class="c-list__heading">住所</p>
      <p><?= $company->address ?></p>
    </li>
    <li class="affiliater__payment__list">
      <p class="c-list__heading">メールアドレス</p>
      <p><?= $company->email ?></p>
    </li>
    <li class="affiliater__payment__list">
      <p class="c-list__heading">電話番号</p>
      <p><?= $company->tel ?></p>
    </li>
    <li class="affiliater__payment__list">
      <p class="c-list__heading">担当者名</p>
      <p><?= $company->manager_name ?></p>
    </li>
  </ul>
  <div class="p-signIn__btn c-btn--white">
    <a href="<?= $this->Url->build(['controller' => 'Admins', 'action' => 'affiliaterPayment', $affiliaterApplication->application_id]); ?>"">戻る</a>
  </div>

</div>


<?php
echo $this->element('footer_login');
echo $this->element('footer_menu_to_admin');
?>

