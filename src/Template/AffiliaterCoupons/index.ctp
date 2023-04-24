<div class="p-couponList">
  <section class="p-couponList__title__wrap">
    <h1 class="p-couponList__title">クーポン管理</h1>
  </section>
  <?php
//  debug($this->AffiliaterCoupons->getIsActive('Enable', 'Disable', true));
//  dd($this->AffiliaterCoupons->getIsActive('Disable', 'Enable', false))
  ?>
  <div class="p-couponList__contents">
    <div class="p-couponList__coupon__wrap">
      <div class="p-couponList__coupon__tabs">
        <div id="public-tab-box"
             class="
             p-couponList__coupon__tab
             jsc-coupon-public
             <?= $this->AffiliaterCoupons->getIsActive('Enable', 'Disable', true) ?>
">
          <p id="public-tab" class="p-couponList__coupon__tab__text">公開中
            <span><?= $this->Paginator->params('Enable')['count']; ?></span>
          </p>
        </div>
        <div id="private-tab-box"
             class="
             p-couponList__coupon__tab
             jsc-coupon-private
             <?= $this->AffiliaterCoupons->getIsActive('Disable', 'Enable') ?>
">
          <p id="private-tab" class="p-couponList__coupon__tab__text">非公開
            <span><?= $this->Paginator->params('Disable')['count']; ?></span>
          </p>
        </div>
      </div>
      <div class="p-couponList__coupon__list__wrap">
        <div class="p-couponList__coupon__list__text__wrap">
          <p class="p-couponList__coupon__list__text">
            発行されているクーポンがありません。<br>
            ページ上部の新規発行ボタンよりクーポンを作成してください。
          </p>
        </div>
        <div id="hide-false-box"
             class="
             p-couponList__coupon__list
             jsc-coupon-public
             <?= $this->AffiliaterCoupons->getIsActive('Enable', 'Disable', true)?>
">
            <?php foreach ($affiliaterCoupons as $affiliaterCoupon): ?>
              <a href="<?= $this->Url->build(['controller' => 'AffiliaterCoupons', 'action' => 'edit', $affiliaterCoupon->id]) ?>">
                <ul class="p-couponList__coupon">
                  <li class="p-couponList__coupon__content">
                    <p class='p-couponList__coupon__heading'><?= $affiliaterCoupon->coupon->title ?></p>
                      <?php foreach ($affiliaterCoupon->coupon->coupon_shops as $shop_name): ?>
                        <p class='p-couponList__coupon__shop'><?= $shop_name->shop->name ?></p>
                      <?php endforeach; ?>
                    <p class='p-couponList__coupon__price'><?= $affiliaterCoupon->coupon->reword ?></p>
                  </li>
                  <li  class="p-couponList__coupon__use">
                    <p class="p-couponList__coupon__text">使用回数</p>
                    <p class="p-couponList__coupon__num">
                        <?php $child_count = 0; foreach ($affiliaterCoupon->affiliater_child_coupons as $child_coupon): ?>
                            <?php $child_count  += $child_coupon->used_count ?>
                        <?php endforeach; ?>
                        <?= $child_count ?>
                    </p>
                  </li>
                </ul>
              </a>
            <?php endforeach; ?>
        </div>
        <div id="hide-false-box2"
             class="
             p-couponList__coupon__list
             jsc-coupon-private
             <?= $this->AffiliaterCoupons->getIsActive('Disable', 'Enable')?>
">
            <?php foreach ($affiliaterCouponsHide as $affiliaterCoupon): ?>
              <a href="<?= $this->Url->build(['controller' => 'AffiliaterCoupons', 'action' => 'edit', $affiliaterCoupon->id]) ?>">
                <ul class="p-couponList__coupon">
                  <li class="p-couponList__coupon__content">
                    <p class='p-couponList__coupon__heading'><?= $affiliaterCoupon->coupon->title ?></p>
                      <?php foreach ($affiliaterCoupon->coupon->coupon_shops as $shop_name): ?>
                        <p class='p-couponList__coupon__shop'><?= $shop_name->shop->name ?></p>
                      <?php endforeach; ?>
                    <p class='p-couponList__coupon__price'><?= $affiliaterCoupon->coupon->reword ?></p>
                  </li>
                  <li  class="p-couponList__coupon__use">
                    <p class="p-couponList__coupon__text">使用回数</p>
                    <p class="p-couponList__coupon__num">
                        <?php $child_count = 0; foreach ($affiliaterCoupon->affiliater_child_coupons as $child_coupon): ?>
                            <?php $child_count  += $child_coupon->used_count ?>
                        <?php endforeach; ?>
                        <?= $child_count ?>
                    </p>
                  </li>
                </ul>
              </a>
            <?php endforeach; ?>
        </div>
      </div>
    </div>

    <ul id="paginator-box" class="pagination__list m-15">
      <?= $this->Paginator->prev('< ', ['model' => 'Enable'])?>
      <li><?= $this->Paginator->counter(['format' => '{{page}}/{{pages}}', 'model' => 'Enable']) ?></li>
        <?= $this->Paginator->next(' >', ['model' => 'Enable',
        ])?>
    </ul>

    <ul id="paginator-box2" class="pagination__list m-15">
        <?= $this->Paginator->prev('< ', ['model' => 'Disable'])?>
      <li><?= $this->Paginator->counter(['format' => '{{page}}/{{pages}}', 'model' => 'Disable']) ?></li>
        <?= $this->Paginator->next(' >', ['model' => 'Disable',
        ])?>
    </ul>

    <div class="loader-box hide"><div class="loader"></div></div>

    <script>
        $(function () {
            $('#public-tab-box').on('click', function (e) {
                $('.loader-box').removeClass('hide')
                f('public', true)
            })
            $('#private-tab-box').on('click', function (e) {
                $('.loader-box').removeClass('hide')
                f('public', false)
            })
            var observer = new MutationObserver(function (m) {
                m.forEach(function(e) {
                    if($(e.target).hasClass('is-active')) {
                        $('#paginator-box2').hide()
                        $('#paginator-box').show()
                    } else {
                        $('#paginator-box').hide()
                        $('#paginator-box2').show()
                    }
                })
            })

            observer.observe(document.getElementById('hide-false-box'), {attributes: true})

            if($('#public-tab-box').hasClass('is-active')) {
                $('#paginator-box2').hide()
                $('#paginator-box').show()
            } else if($('#private-tab-box').hasClass('is-active')){
                $('#paginator-box').hide()
                $('#paginator-box2').show()
            } else {
                $('#paginator-box2').hide()
                $('#paginator-box').show()
                $('#hide-false-box').addClass('is-active')
                $('#public-tab-box').addClass('is-active')
            }

            var f = function insertParam(key, value)
            {
                key = encodeURI(key); value = encodeURI(value);

                var kvp = document.location.search.substr(1).split('&');

                var i=kvp.length; var x; while(i--)
            {
                x = kvp[i].split('=');

                if (x[0]==key)
                {
                    x[1] = value;
                    kvp[i] = x.join('=');
                    break;
                }
            }

                if(i<0) {kvp[kvp.length] = [key,value].join('=');}

                //this will reload the page, it's likely better to store this until finished
                document.location.search = kvp.join('&');
            }

        })
    </script>

  </div>
</div>
<?php
/**
 * @var \App\View\AppView $this
 */
echo $this->element('footer_login');
echo $this->element('footer_menu_to_affiliater');
