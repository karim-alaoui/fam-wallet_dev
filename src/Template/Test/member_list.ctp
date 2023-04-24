<div class="p-memberList">
    <section class="p-memberList__title__wrap">
        <h1 class="p-memberList__title">メンバー一覧</h1>
        <!-- 店舗情報がなければ消す -->
        <div class="p-memberList__title__btn"><a href="">
            <span class="p-memberList__title__btn__plus"></span>
            <span class="p-memberList__title__btn__plus"></span>
        </a></div>
        <!-- ここまで -->
    </section>
        <!-- 店舗情報がなかった場合の表示 -->
        <div class="p-memberList__content__noData">
        <p class="p-memberList__text">
            メンバーーを登録する前に、店舗情報を先にご登録ください。
        </p>
        <div class="p-memberList__btn c-btn--orange"><a href="#">店舗情報を登録する</a></div>
    </div>
    <!-- リーダー追加後の画面 -->
    <div class="p-memberList__content">
        <!-- リーダーの登録がない場合の文言表示 -->
        <div class="p-memberList__text__wrap">
            <p class="p-memberList__text">メンバーが登録されていません。<br>右上の追加ボタンからメンバーを追加してください。</p>
        </div>
        <!-- ここまで -->
        <ul class="p-memberList__list">
            <li class="p-memberList__listItem">
                <a href="">
                    <p class="p-memberList__heading">ちゃぴりー</p>
                    <ul class="p-memberList__shop__list">
                        <li class="p-memberList__shop__listItem">
                            <p class="p-memberList__shop__listItem__text">ROLLEN原宿店</p>
                        </li>
                    </ul>
                </a>
            </li>
            <li class="p-memberList__listItem">
                <a href="">
                    <p class="p-memberList__heading">ちゃぴりー</p>
                    <ul class="p-memberList__shop__list">
                        <li class="p-memberList__shop__listItem">
                            <p class="p-memberList__shop__listItem__text">ROLLEN原宿店</p>
                        </li>
                        <li class="p-memberList__shop__listItem">
                            <p class="p-memberList__shop__listItem__text">ROLLEN原宿店</p>
                        </li>
                        <li class="p-memberList__shop__listItem">
                            <p class="p-memberList__shop__listItem__text">ROLLENROLLENROLLENROLLENROLLENROLLEN原宿店</p>
                        </li>
                    </ul>
                </a>
            </li>
        </ul>
    </div>
</div>
<?php echo $this->element('footer');
