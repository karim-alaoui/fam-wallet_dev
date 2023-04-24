<div class="c-qrCode">
    <div class="c-qrCode__header">
        <div class="c-qrCode__header__close">
            <a href="/coupons">
                <span></span>
                <span></span>
            </a>
        </div>
        <div class="c-qrCode__link__wrap">
            <button id="downloadButton" value='<?= $qrUrl ?>' style="margin-top: 15px;">クーポンをダウンロード</button>
        </div>
    </div>
    <div class="c-qrCode__contents">
        <p class="c-qrCode__text">
            QRコードを読み込んで<br>
            クーポンをゲットしてください
        </p>
        <p class="c-qrCode__img">
            <?= $this->QrCode->text($this->Url->build(['controller' => 'AffiliaterCoupons', 'action' => 'qrcode', $affiliaterCoupon->coupon->id, $affiliaterCoupon->id, '?' => ['param' => $affiliaterCoupon->coupon->token, 'openExternalBrowser'=> 1]], true), ["size"=>"500x200"], ["error_correction"=>"H"]) ?>
        </p>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.1.js"></script>
    <script>
        $("#downloadButton").click(function() {
            $(location).prop('href', $("#downloadButton").val())
        });
    </script>
</div>
