<div class="p-leaderConfirm">
    <section class="p-leaderConfirm__title__wrap">
        <h1 class="p-leaderConfirm__title">メンバー追加</h1>
    </section>
    <div class="p-leaderConfirm__contents">
        <?= $this->Form->create($user) ?>
        <div class="p-leaderConfirm__item">
            <p class="p-leaderConfirm__item__label">名前</p>
            <?= $this->Form->control('username' , ['value' => $this->request->getData('username') , 'class' => 'p-leaderConfirm__item__text' , 'readonly' => true ]) ?>
        </div>
        <div class="p-leaderConfirm__item">
            <p class="p-leaderConfirm__item__label">メールアドレス</p>
            <?= $this->Form->control('email',['value' => $this->request->getData('email') ,'class' => 'p-leaderConfirm__item__text' , 'readonly' => true ]) ?>
        </div>
        <div class="p-leaderConfirm__item">
            <p class="p-leaderConfirm__item__label">パスワード</p>
            <?= $this->Form->control('password',['value' => $this->request->getData('password') , 'class' => 'p-leaderConfirm__item__text' , 'readonly' => true ]) ?>
        </div>
        <div class="p-leaderConfirm__item">
            <p class="p-leaderConfirm__item__label">パスワード(確認)</p>
            <?= $this->Form->control('password_confirm',['value' => $this->request->getData('password_confirm') , 'type' => 'password', 'class' => 'p-leaderConfirm__item__text' , 'readonly' => true ]) ?>
        </div>
        <div class="p-leaderConfirm__item">
            <p class="p-leaderConfirm__item__label">所属店舗</p>
            <?php foreach ($shop_result_array as $shop_id) : ?>
              <?= $this->Form->control('shop_id',['type' => 'text' , 'value' => $shop_id , 'class' => 'p-leaderConfirm__item__text' , 'readonly' => true ]) ?><br>
            <?php endforeach; ?>
        </div>
        <?= $this->Form->control('active', ['type' => 'hidden', 'value' => $this->request->getData('active')]) ?>
        <?= $this->Form->control('company_id', ['type' => 'hidden', 'value' => $this->request->getData('company_id')]) ?>
        <?= $this->Form->control('role_id', ['type' => 'hidden', 'value' => $this->request->getData('role_id')]) ?>
        <?php foreach ($this->request->getData('shop_id') as $shop_id): ?>
        <?php $protect_value = htmlspecialchars($shop_id, ENT_QUOTES, 'UTF-8') ?>
        <?= $this->Form->control("after_save_data[]", ['type' => 'hidden', 'value' => $protect_value]) ?>
        <?php endforeach; ?>
        <div class="p-leaderConfirm__btn"><?= $this->Form->button('追加',['class' => 'c-btn--orange']) ?></div>
        <div class="p-leaderConfirm__btn c-btn--white"><?= $this->Html->link('戻る',['action' => 'member_input']) ?></div>
    </div>
</div>
<?php echo $this->element('footer'); 
