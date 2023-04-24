<div class="c-qrCode">
    <div class="c-qrCode__header">
        <div class="c-qrCode__header__close">
            <a href="/stampcards">
                <span></span>
                <span></span>
            </a>
        </div>
        <div class="c-qrCode__link__wrap">
            <button id="jsc-linkcopy" value='<?= $view_url ?>'>リンクを共有する</button>
            <?php if ($authUser['role_id'] == 4): ?>
            <button id="downloadButton" value='<?= $view_url_download ?>' style="margin-top: 15px;">スタンプダウンロード</button>
            <?php endif; ?>
        </div>
    </div>
    <div class="c-qrCode__contents">
        <p class="c-qrCode__text">
            QRコードを読み込んで<br>
            クーポンをゲットしてください
        </p>
        <p class="c-qrCode__img">
            <?= $this->QrCode->text($this->Url->build(['controller' => 'Stampcards', 'action' => 'qrcode', $stampcard->id, '?' => ['param' => $stampcard->token, 'openExternalBrowser'=> 1]], true),  ["size"=>"500x200"], ["error_correction"=>"H"]) ?>
        </p>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.1.js"></script>
    <script>
        $("#downloadButton").click(function() {
            $(location).prop('href', $("#downloadButton").val())
        });
    </script>
</div>
