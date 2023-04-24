<div class="p-analytics-narrow">
    <section class="p-analytics-narrow__title__wrap">
        <h1 class="p-analytics-narrow__title">絞り込み期間</h1>
    </section>
    <div class="p-analytics-narrow__contents">
    <?= $this->Form->create() ?>
        <div class="p-analytics-narrow__select__wrap">
            <div class="p-analytics-narrow__select">
                <?= $this->Form->control('before_expiry_date',[
                    'required' => true,
                    'class' => 'datepicker',
                    'placeholder' => '選択してください',
                    'readonly' => true
                    ]); 
                ?>
            </div>
            <p class="p-analytics-narrow__select__text">~</p>
            <div class="p-analytics-narrow__select">
                <?= $this->Form->control('after_expiry_date',[
                    'required' => true,
                    'class' => 'datepicker date',
                    'placeholder' => '選択してください',
                    'readonly' => true
                    ]); 
                ?>
            </div>
        </div>
        <div class="analytics-narrow__btn c-btn--orange"><a>絞り込む</a></div>
        <div class="analytics-narrow__btn c-btn--white"><a href="/analytics">戻る</a></div>
    <?= $this->Form->end() ?>
    </div>
</div>
<?= $this->Html->css('//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', [ 'block' => true ]) ?>
<?= $this->Html->script('//code.jquery.com/jquery-1.12.4.js', [ 'block' => true ]) ?>
<?= $this->Html->script('//code.jquery.com/ui/1.12.1/jquery-ui.js', [ 'block' => true ]) ?>
<?= $this->Html->scriptStart([ 'block' => true ]) ?>
  $( function() {
    $(".datepicker").datepicker({
      dateFormat: 'yy/mm/dd',
      monthNames: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
      showOtherMonths: true,
      selectOtherMonths: true,
      disableTouchKeyboard: true,
    });
  });
<?= $this->Html->scriptEnd() ?>
<?php echo $this->element('footer'); ?>
