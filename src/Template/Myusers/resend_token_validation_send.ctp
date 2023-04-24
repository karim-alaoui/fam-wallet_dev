<section class="p-resendTokenValidationSend">
    <div class="p-resendTokenValidationSend__title__wrap">
        <h1 class="p-resendTokenValidationSend__title">認証メール再送信</h1>
    </div>
    <div class="p-resendTokenValidationSend__contents">
        <p class="p-resendTokenValidationSend__heading">認証メールを送信しました</p>
        <p class="p-resendTokenValidationSend__text">
        <?= $this->Html->tag('span', 'chapily@rollen.com',['class' => 'u-fw-b']) ?>にメールを送信しました。メールに記載しているリンクから登録を完了させてください。</p>
    </div>
</section>
<?php echo $this->element('footer');