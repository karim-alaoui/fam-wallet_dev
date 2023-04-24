<div class="p-affiliaterAccount">
    <section class="p-affiliaterAccount__title__wrap">
        <h1 class="p-affiliaterAccount__title">振込先口座</h1>
    </section>
    <div class="p-affiliaterAccount__contents">

        <div class="p-affiliaterAccount__item__wrap">
            <div class="p-stampEdit__form__btn c-btn--orange">
                <?= $this->Html->link('進む' , "https://connect.stripe.com/oauth/authorize?response_type=code&client_id=$client_id&scope=read_write&redirect_uri=$redirect_uri") ?>
            </div>
            <div class="p-stampEdit__form__btn c-btn--white"><a href="/affiliater/detail">戻る</a></div>


        </div>

    </div>
</div>


<?php
echo $this->element('footer_login');


