<div class="c-list">
    <section class="c-list__title__wrap">
        <h1 class="c-list__title">リーダー一覧</h1>
        <!-- 店舗情報がなければ消す -->
        <div class="c-list__title__btn"><a href="">
            <span class="c-list__title__btn__plus"></span>
            <span class="c-list__title__btn__plus"></span>
        </a></div>
        <!-- ここまで -->
    </section>
    <!-- 店舗情報がなかった場合の表示 -->
    <div class="c-list__content__noData">
        <p class="c-list__text">
            リーダーを登録する前に、店舗情報を先にご登録ください。
        </p>
        <div class="c-list__btn c-btn--orange"><a href="#">店舗情報を登録する</a></div>
    </div>
    <!-- リーダー追加後の画面 -->
    <div class="c-list__content">
        <!-- リーダーの登録がない場合の文言表示 -->
        <div class="c-list__text__wrap">
            <p class="c-list__text">リーダーが登録されていません。<br>右上の追加ボタンからリーダーを追加してください。</p>
        </div>
        <!-- ここまで -->
        <ul class="c-list__list">
            <li class="c-list__listItem">
                <a href="">
                    <p class="c-list__heading">ちゃぴりー</p>
                    <ul class="c-list__shop__list">
                        <li class="c-list__shop__listItem">
                            <p class="c-list__shop__listItem__text">ROLLEN原宿店</p>
                        </li>
                    </ul>
                </a>
            </li>
            <li class="c-list__listItem">
                <a href="">
                    <p class="c-list__heading">ちゃぴりー</p>
                    <ul class="c-list__shop__list">
                        <li class="c-list__shop__listItem">
                            <p class="c-list__shop__listItem__text">ROLLEN原宿店</p>
                        </li>
                        <li class="c-list__shop__listItem">
                            <p class="c-list__shop__listItem__text">ROLLEN原宿店</p>
                        </li>
                        <li class="c-list__shop__listItem">
                            <p class="c-list__shop__listItem__text">ROLLENROLLENROLLENROLLENROLLENROLLEN原宿店</p>
                        </li>
                        <li class="c-list__shop__listItem">
                            <p class="c-list__shop__listItem__text">ROLLEN原宿店</p>
                        </li>
                    </ul>
                </a>
            </li>
        </ul>
    </div>
</div>
<?php echo $this->element('footer');
