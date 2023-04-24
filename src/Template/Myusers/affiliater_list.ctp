<div class="c-list">
    <section class="c-list__title__wrap">
        <h1 class="c-list__title">支払い申請一覧</h1>
    </section>


    <div class="p-affiliaterAccount__contents">
        <!-- <?= $this->Form->create() ?>
        <ul class="c-list__list">
            <li class="affiliater__payment__list">
                <p class="c-list__heading">換金申請額</p>
                <p><?= $allFee ?>円</p>
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
        <?= $this->Form->button('支払う', ['class' => 'p-memberEdit__form__btn c-btn--orange']) ?>
        <?= $this->Form->end() ?> -->

<!--        <div class="p-affiliaterAccount__contents">-->
        <div>
            <div class="c-list__content">
                <div class="tab-wrap">
                    <input id="TAB-01" type="radio" name="TAB" class="tab-switch" checked="checked"/>
                    <label class="tab-label" for="TAB-01">支払申請中<span
                            class="affiliater__list__num"><?= count($appAffiliater); ?></span></label>
                    <div class="tab-content">
                        <!-- 店舗情報がなかった場合の表示 -->
                        <?php if (empty($affiliater)): ?>
                            <div class="c-list__content__noData">
                                <p class="c-list__text">
                                    支払い申請が存在しません。
                                </p>
                            </div>
                        <?php endif; ?>
                        <ul class="c-list__list">
                            <?php foreach ($appAffiliater as $member): ?>
                                <li class="c-list__listItem">
                                    <a
                                        style="display: flex;"
                                        class="align-items-center justify-content-between"
                                        href="<?= $this->Url->build(['controller' => 'Myusers', 'action' => 'affiliater_payment', $member->id]); ?>">
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
                                            <p class="u-fs-12 color--gray"><?= \App\Model\Entity\AffiliaterApplication::STATUS_LIST[$member->status_id] ?></p>
                                            <p class="u-fs-12 color--gray"><?= $member->create_at ?></p>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!--<input id="TAB-02" type="radio" name="TAB" class="tab-switch"/>
                    <label class="tab-label" for="TAB-02">アフィリエイター<span
                            class="affiliater__list__num"><?= count($affiliater) ?></span></label>
                    <div class="tab-content">
                        <ul class="c-list__list">
                            <?php foreach ($affiliater as $member): ?>
                                <li class="c-list__listItem">
                                    <a href="<?= $this->Url->build(['controller' => 'Myusers', 'action' => 'affiliater_edit', $member->id]); ?>">
                                        <p class="c-list__heading"><?= $member->username ?></p>
                                        <ul class="c-list__shop__list">
                                            <li class="c-list__shop__listItem__none">
                                                <p class="c-list__shop__listItem__text__none flex__center">
                                                    <?= $this->Html->image('icon/icon_coin.png', ['class' => 'affiliater__coin__icon']) ?><?= $member->point ?>
                                                </p>
                                            </li>
                                        </ul>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>-->

                </div>
            </div>
        </div>
<?php
echo $this->element('footer');
echo $this->element('footer_menu');


