<div class="c-list" id="affiliater_points">
  <section class="c-list__title__wrap">
    <h1 class="c-list__title">ポイント</h1>
  </section>
  <div class="c-list__content">
    <div class="point_info_box">
      <p class="u-fs-14">あなたの総保有ポイント</p>
      <div class="flex align-items-center">
        <div>
            <?= $this->Html->image('icon/icon_coin.png', ['style' => 'width:20px;']);?>
        </div>
        <p class="u-fs-24 ml-5 color--orange font-weight-500"><?= $point; ?></p>
      </div>

        <?php if($disabled):?>
          <div class="c-input__form__btn c-btn--orange">
            <a class="b-disabled" href="/affiliater/application">ポイントの換金申請</a>
          </div>
          <div>
            <a href="/affiliater/detail" class="u-fs-14 color--orange">振込口座を登録してください</a>
          </div>
        <?php else:?>
          <div class="c-input__form__btn c-btn--orange">
            <a href="/affiliater/application">ポイントの換金申請</a>
          </div>
        <?php endif;?>
    </div>

    <p class="p-couponEdit__heading mb-10">獲得ポイント履歴</p>
    <ul class="c-list__list">
      <?php foreach ($points as $point):?>
        <li class="c-list__listItem p-10">
          <ul class="c-list__shop__list">
            <li class="c-list__shop__listItem__none">
              <div class="c-list__shop__listItem__text__none flex__center ">
                  <?= $this->html->image('icon/icon_coupon.png', ['class' => 'affiliater__coin__icon']) ?>
                <div class="coupon_title">
                  <p class="u-fs-12 font-weight-600 color--black"
                     style="-webkit-box-orient: vertical;">
                      <?= $point->affiliater_coupon->coupon->title;?>
                  </p>
                </div>
                <div style="margin-left: 50%;width: 100px;max-width: 100px;min-width: 70px;display: inline-block;">
                  <p class="u-fs-20 color--orange font-weight-500"><?= $point->point; ?>P</p>
                  <?php if(array_key_exists($point->id, $affAppStatusId)){?>
                    <?php print_r($affAppStatusId[$point->id])?>
                  <?php }else{ ?>
                    <?php print_r('Not applied')?>
                  <?php }?>
                </div>
              </div>

              <div class="flex align-items-center">
                <div>
                    <?= $this->html->image('icon/icon_time.png', ['style' => 'width: 9px;']) ?>
                </div>
                <p class="u-fs-10 ml-5 mr-10 color--gray"><?= $point->create_at; ?></p>
                  <?php if(isset($point->affiliater_coupon->coupon->coupon_shops)):?>
                    <div>
                        <?= $this->html->image('icon/icon_shop.png', ['style' => 'width: 9px;']) ?>
                    </div>
                    <p class="u-fs-10 ml-5 color--gray">
                        <?= $point->affiliater_coupon->coupon->coupon_shops[0]->shop->name; ?>
                    </p>
                  <?php endif;?>
              </div>

            </li>
          </ul>
        </li>
      <?php endforeach;?>
    </ul>

    <ul class="pagination__list m-15">
        <?= $this->Paginator->prev('< '); ?>
      <li><?= $this->Paginator->counter('{{page}}/{{pages}}'); ?></li>
        <?= $this->Paginator->next(' >'); ?>
    </ul>

  </div>

</div>
<?php
echo $this->element('footer');
echo $this->element('footer_menu_to_affiliater');


