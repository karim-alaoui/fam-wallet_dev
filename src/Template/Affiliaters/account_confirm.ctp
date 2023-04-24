<div class="p-affiliaterAccount">
    <section class="p-affiliaterAccount__title__wrap">
        <h1 class="p-affiliaterAccount__title">振込先口座</h1>
    </section>
    <div class="p-affiliaterAccount__contents">

        <div class="p-affiliaterAccount__item__wrap">

            <div class="p-affiliaterAccount__item">

            </div>
        </div>
        <p class="pt-15 mb-15 p-affiliaterAccount__link logOut ">
            <?= $this->Html->link('ログアウト', ['controller' => 'Myusers', 'action' => 'logout']); ?>
        </p>
    </div>
</div>


<?php
echo $this->element('footer_login');
echo $this->element('footer_menu_to_affiliater');


