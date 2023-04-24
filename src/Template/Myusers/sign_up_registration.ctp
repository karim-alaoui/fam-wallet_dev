<?php

use Cake\Core\Configure;

?>
<section class="p-signUpRegistration">
    <div class="p-signUpRegistration__title__wrap">
        <h1 class="p-signUpRegistration__title">新規会員登録</h1>
    </div>
    <div class="p-signUpRegistration__contents">
        <p class="p-signUpRegistration__heading">ご登録いただきありがとうございます！</p>
        <p class="p-signUpRegistration__text">
            登録が完了しました。<br>
            ログインをして、サービスをご利用いただけます。
        </p>
        <div class="p-signUpRegistration__btn c-btn--orange"><?= $this->Html->link('ログインする', ['action' => 'login']) ?></div>
    </div>
    <div style="font-family: 'Inter';font-style: normal;padding-left: 1%;padding-top: 3%;padding-right: 2%;">
        <div>
            <p style="padding-bottom: 25px;font-weight: 700;font-size: 18px;color: #060606;">利用方法<small style="padding-left: 10px;font-weight: 900;font-size: 14px;color: #FF0000;">※必ずお読みください！</small></p>
            </div>
        <div>
            <div>
                <div>
                    <h1 style="padding-bottom: 15px;font-weight: 700;font-size: 14px;color: #060606;">一般ユーザー<h1>
                    <h2 style="padding-bottom: 15px;padding-left: 2%;font-weight: 900;font-size: 12px;color: #060606;">クーポン/スタンプの利用</h2>
                    <div style="line-height: 3;padding-bottom: 30px;padding-left: 4%;font-weight: 500;font-size: 10px;color: #060606;">
                        <p>①：アカウント開設</p>
                        <p style="color: #FF0000;font-weight: 900;">②：ページ下部より「ウォレット対応スマホアプリ」をダウンロード</p>
                        <p style="padding-left: 1%;color: #FF0000;font-weight: 500;font-size: 8px;line-height: 10px;">※大変忘れ安くなっております。必ず端末を確認し、対応ウォレットアプリをダウンロードしてください。</p>
                        <p>③：クーポンを発行している店舗のSNSなどよりクーポンリンクをクリック</p>
                        <p>④：クーポン詳細画面より「クーポン/スタンプのダウンロード」ボタンをクリック、クーポン/スタンプをダウンロード</p>
                        <p>⑤：②でダウンロードした対応アプリから、ダウンロードしたクーポン/スタンプのQRコードを表示</p>
                        <p>⑥：店舗スタッフにQRコードを読み取ってもらう</p>
                    </div>
                    <h2 style="padding-bottom: 15px;padding-left: 2%;font-weight: 900;font-size: 12px;color: #060606;">クーポンのシェア（アフィリエイトの実施）〜換金</h2>
                    <div style="line-height: 3;padding-bottom: 60px;padding-left: 4%;font-weight: 500;font-size: 10px;color: #060606;">
                        <p>①マイページにログインし、「クーポン」タブよりシェア（アフィリエイト）可能なクーポンを確認</p>
                        <p style="padding-left: 1%;color: #FF0000;font-weight: 500;font-size: 8px;line-height: 10px;">※クーポンは実際に店舗で利用すると、シェア（アフィリエイト）が可能となります。</p>
                        <p>②クーポンの詳細画面より、「リンクをコピー」して、クーポンを友達に共有</p>
                        <p style="padding-left: 1%;color: #FF0000;font-weight: 500;font-size: 8px;line-height: 10px;">※共有したリンクからクーポンが利用されると、所定の還元率に応じてポイントが還元されます。</p>
                        <p>③「アカウント」タブ ＞ 「換金申請」画面 ＞ 「ポイントの換金申請」から換金するポイントを指定</p>
                        <p style="padding-left: 1%;color: #FF0000;font-weight: 500;font-size: 8px;line-height: 10px;">※換金には、「振込先口座」の登録が必要となります。事前にマイページよりご登録をお願いいたします。</p>
                        <p>④「アカウント」タブ ＞ 「換金申請」画面 から、支払いステータスを確認</p>
                        <p style="padding-left: 1%;color: #FF0000;font-weight: 500;font-size: 8px;line-height: 10px;">※Not applied＝申請前、Paid=振込済み</p>
                    </div>
                    <h1 style="padding-bottom: 15px;font-weight: 700;font-size: 14px;color: #060606;">店舗スタッフ（オーナー／店長様含む）<h1>
                    <h2 style="padding-bottom: 15px;padding-left: 2%;font-weight: 900;font-size: 12px;color: #060606;">※運営会社 担当者へお問合せください。</h2>
                </div>
                <div>
                </div>
            </div>
        </div>
    </div>
    <div class="p-enabledDevice__content">
        <div class="p-enabledDevice__list">
            <div class="p-enabledDevice__tab">対応端末</div>
            <div class="p-enabledDevice__tab__list">
                <p class="p-enabledDevice__label">対応OS</p>
                <p class="p-enabledDevice__text">・iOS：12.0.0以上</p>
                <p class="p-enabledDevice__text">・Android：9.0以上</p>
            </div>
            <div class="p-enabledDevice__tab__list">
                <p class="p-enabledDevice__label">対応ブラウザ</p>
                <p class="p-enabledDevice__text">・Safari（最新版）</p>
                <p class="p-enabledDevice__text">・Google Chrome（最新版）</p>
            </div>
            <div class="p-enabledDevice__tab__list">
                <div class="p-enabledDevice__label__wrap">
                    <p class="p-enabledDevice__label">ウォレット対応スマホアプリ</p>
                    <p class="p-enabledDevice__annotation">※ユーザー端末側で必要</p>
                </div>
                <p class="p-enabledDevice__heading">・iPhone：Wallet（最新版）</p>
                <p class="p-enabledDevice__heading__text">※iPadは対応しておりません</p>
                <p class="p-enabledDevice__heading">・Android</p>
                <p class="p-enabledDevice__heading__text">WalletPasses（最新版）</p>
                <p class="p-enabledDevice__heading__text">Pass2U（最新版）</p>
            </div>
        </div>
    </div>
</section>
<?php echo $this->element('footer');