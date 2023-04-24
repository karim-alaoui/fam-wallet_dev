<div class="c-list" id="affiliater_points">
    <section class="c-list__title__wrap">
        <h1 class="c-list__title">換金申請情報の入力</h1>
    </section>
    <div class="">
      <div class="application_info mt-15 mb-15 ml-10 mr-10">
        <p class="title">振込スケジュール</p>
        <p class="mt-5">末締め、翌月末支払い。 祝日の場合は前日の17時までに振込まれます。</p>
      </div>

      <div class="">
        <?= $this->Form->create($validate, ['class' => 'c-input__form']) ?>
        <ul class="c-list__list">
          <li class="affiliater__payment__list">
            <p class="c-list__heading">現在保有ポイント</p>
            <p><?= $point ?>P</p>
          </li>
          <li class="affiliater__payment__list" style="display: block;">
            <div class="flex align-items-center justify-content-between">
              <p class="c-list__heading">換金申請ポイント</p>
              <div class="flex align-items-center">
                  <?= $this->Form->control('point' , [
                      'type' => 'text',
                      'inputmode' => "numeric",
                      'pattern' => '\d*',
                      'class' => 'c-input__form__input text-align-end',
                      'placeholder' => $point,
                      'id' => 'point',
                      'error' => false,
                      'readonly' => true,
                      'required' => true]) ?><span>P</span>
              </div>
            </div>
              <div class="flex justify-content-flex-end">
                  <?= $this->Form->error('point') ?>
              </div>

              <div class="c-input__form__checkbox__wrap">
                  <p class="c-input__form__label">ポイント取得履歴</p><p id="all_select" class="c-input__form__selectall jsc-selectAll">全て選択</p>
                  <ul class="c-input__form__checkbox__list">
                      <li class="c-cinput__form__checkbox__listItem">
                          <label id="coupon_history_list" class="c-input__form__checkbox__label" >
                              <?= $this->Form->control('coupon_id_list', [
                                  'type' => 'select',
                                  'multiple' => 'checkbox',
                                  'options' => $coupon_list,
                                  'class' => 'c-input__form__checkbox__input',
                              ]) ?>
                          </label>
                      </li>
                  </ul>
              </div>

            <div class="p-couponEdit__form__state__radio__label flex justify-content-flex-end mt-15">
                <?= $this->Form->control('point_all' , [
                    'type' => 'checkbox',
                    'class' => 'p-couponEdit__form__state__radio',
                    'label' => ['text' => '全てのポイントを換金', 'style' => 'font-size: 11px;' ],
                    'value' => 1
                ]); ?>
            </div>

            <div class="application_info mt-10">
              <p>申請金額は211Pから可能です。</p>
            </div>
          </li>
          <li class="affiliater__payment__list">
            <p class="c-list__heading">振込手数料</p>
            <p>210円</p>
          </li>

          <li class="affiliater__payment__list color--orange">
            <p class="c-list__heading">実質の振込金額</p>
            <p><span id="application_cash" class="font-weight-600">0</span>円</p>
          </li>

          <li class="affiliater__payment__list">
            <p class="c-list__heading">申請後のポイント残高</p>
            <p><span id="application_after_point">0</span>P</p>
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
          <?= $this->Form->button('申請内容を確認',[
            'class' => 'p-couponEdit__form__btn c-btn--orange',
              'id' => 'application_submit',
              'disabled' => true
          ]) ?>
          <div class="p-couponEdit__form__btn c-btn--white"><a href="/affiliater/point">戻る</a></div>
        </div>
      </div>
        <?= $this->Form->control('mode', ['type' => 'hidden', 'value' => 'confirm']); ?>
        <?= $this->Form->control('myuser_id', ['type' => 'hidden', 'value' => $this->request->session()->read('Auth.User.id')]); ?>
      <?= $this->Form->end()?>

    </div>

</div>

<script>
  $(function () {
      var allPoint = <?php echo $point; ?>;
      var affiliaterPoints = <?php echo $affiliater_points; ?>;

      $('#point').keyup(function(e) {
          var point = e.target.value.replace(/[^0-9]/g, '')
          e.target.value = point

          setPoint(point)
      })

      $('#point').change(function(e) {
          if(allPoint != e.target.value) {

              $('#affiliater_points input[name="point_all"]').closest('label').removeClass('is-check')
          }
      })

      $('#affiliater_points input[name="point_all"]').click(function () {
          setTimeout(() => {
              if($(this).closest('label').hasClass('is-check')) {
                  $('#point').val(allPoint)
              } else {
                  $('#point').val(0)
              }
              $('#point').trigger('keyup')
          }, 300)
      })

      var setPoint = function(point) {
          if(!point || point == 0) {
              $('#application_cash').text(0)
              $('#application_after_point').text(0)
              return
          }
          $('#application_cash').text(point - 210)
          $('#application_after_point').text(allPoint - point)
          $('#application_submit').attr('disabled', false)
      }

      var calcPoint = function () {
          const elements = $('.c-input__form__checkbox__input').get()
          var point = 0
          elements.forEach(x => {
              if (x.checked) {
                  point += affiliaterPoints.find(y => y.id === Number(x.value)).point
              }
          })

          if (!point) {
            point = ''
          }

          $('#point').val(point)
          setPoint(point)
      }



      // var setSubmitButtonDisabled = function () {
      //     if ($('#point').val() && $('#consent').val()) {
      //         $('#application_submit').attr('disabled', false)
      //     } else {
      //         $('#application_submit').attr('disabled', true)
      //     }
      // }

      calcPoint()

      $('.c-input__form__checkbox__input').change(function(e) {
          calcPoint()
          // setSubmitButtonDisabled()
      });

      $('#all_select').click(function() {
          setTimeout(() => {
              calcPoint()
          }, 300)
      })

      $('#point-all').click(function() {
          $('.c-input__form__checkbox__input').each(function (x) {
              $(this).trigger('click')
          })
      })

      // $('#consent').change(function(e) {
      //     setSubmitButtonDisabled()
      // })

      var point = $('#point').val().replace(/[^0-9]/g, '')
      setPoint(point)
  })
</script>

