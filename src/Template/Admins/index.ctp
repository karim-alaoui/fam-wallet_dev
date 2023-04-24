

<div class="p-userTop__contents">
    <div class="c-list__content">
        <?= $this->Form->create($search) ?>
            <div class="c-input__form__input__wrap">
                <p class="c-input__form__label">申請日時</p>
                <div class="c-input__form__select__wrap">
                    <div class="c-input__form__select">
                        <?= $this->Form->control('before_date',[
                            'required' => true,
                            'class' => 'datepicker',
                            'placeholder' => '選択してください',
                            'readonly' => true,
                            'value' => $before_date
                        ]);
                        ?>
                    </div>
                    <p class="c-input__form__select__text">~</p>
                    <div class="c-input__form__select">
                        <?= $this->Form->control('after_date',[
                            'required' => true,
                            'class' => 'datepicker date',
                            'placeholder' => '選択してください',
                            'readonly' => true,
                            'value' => $after_date
                        ]);
                        ?>
                    </div>
                </div>
            </div>

      <div style="margin-top: 20px">
        <label class="p-stampEdit__form__state__radio__label">
            <?= $this->Form->control('status' , [
              'type' => 'radio',
              'options' => \App\Model\Entity\Application::STATUS_LIST,
              'class' => 'p-stampEdit__form__state__radio',
              'value' => $status
            ]); ?>
        </label>
      </div>

            <div style="display: flex;
        justify-content: space-between;">
                <?= $this->Form->button('検索' , ['class' => 'c-input__form__btn c-btn--orange', 'type' => 'submit', 'name' => 'search']) ?>
                <?= $this->Form->button('クリア' , ['class' => 'c-input__form__btn c-btn--white', 'type' => 'delete', 'name' => 'clear']) ?>
            </div>

        <?= $this->Form->end() ?>

        <div class="tab-wrap">
            <input id="TAB-01" type="radio" name="TAB" class="tab-switch" checked="checked" />
            <label class="tab-label" for="TAB-01">申請一覧<span class="affiliater__list__num"><?= count($applications); ?></span></label>
            <?php if(!$applications):?>
                <p class="u-fs-12">入金待ちのユーザーはいません</p>
            <?php endif;?>
            <div class="tab-content">
                <ul class="c-list__list">
                    <?php foreach ($applications as $member): ?>
                        <li class="c-list__listItem">
                            <a
                                style="display: flex;"
                                class="align-items-center justify-content-between"
                                href="<?= $this->Url->build(['controller' => 'Admins', 'action' => 'affiliater_payment', $member->id]); ?>">
                                <div>
                                    <p class="c-list__heading"><?= $member->myuser->username ?></p>
                                    <ul class="c-list__shop__list">
                                        <li class="c-list__shop__listItem__none">
                                            <p class="c-list__shop__listItem__text__none flex__center">
                                                <?= $this->Html->image('icon/icon_coin.png', ['class' => 'affiliater__coin__icon']) ?><?= $member->point ?>
                                            </p>
                                        </li>
                                    </ul>
                                </div>
                                <div>
                                    <p class="u-fs-12 color--gray"><?= \App\Model\Entity\Application::STATUS_LIST[$member->status_id] ?></p>
                                    <p class="u-fs-12 color--gray"><?= $member->created ?></p>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php
    echo $this->element('footer_login');
    echo $this->element('footer_menu_to_admin');
    ?>
<?= $this->Html->css('//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', [ 'block' => true ]) ?>
<?= $this->Html->script('//code.jquery.com/jquery-1.12.4.js', [ 'block' => true ]) ?>
<?= $this->Html->script('//code.jquery.com/ui/1.12.1/jquery-ui.js', [ 'block' => true ]) ?>
<script>
    $( function() {
        $(".datepicker").datepicker({
            dateFormat: 'yy/mm/dd',
            monthNames: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
            showOtherMonths: true,
            selectOtherMonths: true,
            disableTouchKeyboard: true,
        });

        // $('#affiliater_is_use').on('click', function() {
        //     var enable = $(this).hasClass('is-check');
        //     $('#affiliater_rate_box').css('display', enable ? 'block' : 'none');
        //
        //     $('#affiliater_rate_box input').each(function(i, v) {
        //         if($(v).attr('id') === 'reword-rate-2') {
        //             return true;
        //         }
        //         $(v).prop('disabled', !enable);
        //     });
        //
        //     $('#affiliater_rate_box select').each(function(i, v) {
        //         $(v).prop('disabled', !enable);
        //     });
        // });
        //
        //
        // $('input[name="reword_type"]').click(function() {
        //     if($(this).attr('id') === 'reword-type-1') {
        //         $('#reword-rate-1').prop('disabled', false)
        //         $('#reword-rate-2').prop('disabled', true)
        //     } else {
        //         $('#reword-rate-2').prop('disabled', false)
        //         $('#reword-rate-1').prop('disabled', true)
        //     }
        // })
        //
        // setTimeout(function() {
        //     if($('#affiliater_is_use').hasClass('is-check')) {
        //         $('#affiliater_is_use').trigger('click');
        //     }
        // }, 1500)

    });
</script>
