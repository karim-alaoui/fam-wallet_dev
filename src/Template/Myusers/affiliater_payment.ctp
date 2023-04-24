<div class="p-memberEdit">
    <section class="p-memberEdit__title__wrap">
        <h1 class="p-memberEdit__title">支払情報</h1>
    </section>

    <?= $this->Form->create() ?>
    <p class="affiliaterPayment_sectionTitle">支払い情報</p>

    <div>
      <ul class="c-list__list">
        <li class="affiliater__payment__list">
          <p class="c-list__heading">換金申請額</p>
          <p><?= $appAffiliater->point ?>円</p>
        </li>
        <li class="affiliater__payment__list">
          <p class="c-list__heading">振込手数料</p>
          <p><?= $commission ?>円</p>
        </li>
        <li class="affiliater__payment__list border__bottom__none affiliater__payment__sum">
          <p class="c-list__heading">支払金額</p>
          <p><?= $totalAmount ?>円</p>
        </li>
      </ul>

        <p class="affiliaterPayment_sectionTitle">ステータス</p>
        <ul class="c-list__list">
            <li class="affiliater__payment__list">
                <p><?= \App\Model\Entity\AffiliaterApplication::STATUS_LIST[$appAffiliater->status_id] ?></p>
            </li>
        </ul>

      <div class="p-memberEdit__contents">
          <?php if ($appAffiliater->status_id == \App\Model\Entity\AffiliaterApplication::STATUS_ID_APPLYING): ?>
              <?= $this->Form->button('承認する',['class' => 'p-memberEdit__form__btn c-btn--orange']) ?>

          <?php elseif ($appAffiliater->status_id == \App\Model\Entity\AffiliaterApplication::STATUS_ID_APPROVED
              || $appAffiliater->status_id == \App\Model\Entity\AffiliaterApplication::STATUS_ID_ERROR): ?>
              <?= $this->Form->button('支払う',['class' => 'p-memberEdit__form__btn c-btn--orange']) ?>

          <?php endif; ?>

        <div class="c-input__form__btn c-btn--white"><a href="/affiliaters">戻る</a></div>
      </div>
    </div>
    <?= $this->Form->end()?>

</div>
<?php
/**
 * @var \App\View\AppView $this
 */

echo $this->element('footer');


