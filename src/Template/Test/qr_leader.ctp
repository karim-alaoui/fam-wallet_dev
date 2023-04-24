<section class="p-qrLeader">
    <!-- カメラ映像 -->
    <video class="p-qrLeader__camera" autoplay playsinline></video>
    <div class="p-qrLeader__overlay">
        <div class="p-qrLeader__header__close">
            <a href="javascript:history.back()">
                <span></span>
                <span></span>
            </a>
        </div>
        <p class="p-qrLeader__text">QRコードを読み込む</p>
        <p class="p-qrLeader__footer__text">QRコードを認識すると自動的に読み込みます。</p>

    </div>
</section>
 <!-- 処理結果表示用 -->
<canvas class="p-qrLeader__picture"></canvas>
<?= $this->Html->css('//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', ['block' => true ]) ?>
<?= $this->Html->script('//code.jquery.com/ui/1.12.1/jquery-ui.js', [ 'block' => true ]) ?>
<?= $this->Html->script('//cdn.jsdelivr.net/npm/jsqr@latest/dist/jsQR.min.js', ['block' => true]) ?>
<script>
    const video  = document.querySelector(".p-qrLeader__camera");
    const canvas = document.querySelector(".p-qrLeader__picture");
    const ctx = canvas.getContext("2d");

    var ua = window.navigator.userAgent.toLowerCase();

    if(ua.indexOf('iphone') > -1 || ua.indexOf('ipad') > -1){
		// iOS系端末用
		window.addEventListener('pageshow', function() {
            /** カメラ設定 */
            const constraints = {
                audio: false,
                video: {
                    width: 1280,
                    height: 720,
                    facingMode: "environment"  // リアカメラを利用する
                }
            }
            /**
            * カメラを<video>と同期
            */
            navigator.mediaDevices.getUserMedia(constraints)
                .then((stream) =>  {
                    video.srcObject = stream;
                    video.onloadedmetadata = (e) => {
                        video.play();

                        // QRコードのチェック開始
                        checkPicture();
                    };
                })
                .catch( (err) => {
                    console.log(err.name + ": " + err.message);
            });
		});
	} else {
        // それ以外の端末用
        window.addEventListener('pageshow', function() {
            /** カメラ設定 */
            const constraints = {
                audio: false,
                video: {
                    width: 1280,
                    height: 680,
                    facingMode: "environment" // リアカメラを利用する
                }
            }
            
            /**
            * カメラを<video>と同期
            */
            navigator.mediaDevices.getUserMedia(constraints)
                .then((stream) =>  {
                    video.srcObject = stream;
                    video.onloadedmetadata = (e) => {
                        video.play();

                        // QRコードのチェック開始
                        checkPicture();
                    };
                })
                .catch( (err) => {
                    console.log(err.name + ": " + err.message);
            });
        });
    }
    /**
     * QRコードの読み取り
     */
    function checkPicture(){
        // カメラの映像をCanvasに複写
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        // QRコードの読み取り
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, canvas.width, canvas.height);

        // 読み込み後背景画像を修正
        // var elem = document.getElementsByClassName("p-qrLeader__overlay");

        //----------------------
        // 存在する場合
        //----------------------
        if( code ){
            // 結果を表示
            setQRResult("#result", code.data);  // 文字列
            drawLine(ctx, code.location);       // 見つかった箇所に線を引く

            // video と canvas を入れ替え
            canvas.style.display = 'block';
            video.style.display = 'none';
            video.pause();
            if(window.open(code.data,'_brank')) {
                window.location.href = code.data;
            }else{
                window.location.href = code.data;
            }
        }
        //----------------------
        // 存在しない場合
        //----------------------
        else {
        // 0.3秒後にもう一度チェックする
            setTimeout( () => {
            // requestAnimationFrame(checkPicture);
            checkPicture();
            }, 300);
        }
    }
    /**
     * 発見されたQRコードに線を引く
     *
     * @param {Object} ctx
     * @param {Object} pos
     * @param {Object} options
     * @return {void}
     */
    function drawLine(ctx, pos, options={color:"blue", size:5}){
        // 線のスタイル設定
        ctx.strokeStyle = options.color;
        ctx.lineWidth   = options.size;

        // 線を描く
        ctx.beginPath();
        ctx.moveTo(pos.topLeftCorner.x, pos.topLeftCorner.y);         // 左上からスタート
        ctx.lineTo(pos.topRightCorner.x, pos.topRightCorner.y);       // 右上
        ctx.lineTo(pos.bottomRightCorner.x, pos.bottomRightCorner.y); // 右下
        ctx.lineTo(pos.bottomLeftCorner.x, pos.bottomLeftCorner.y);   // 左下
        ctx.lineTo(pos.topLeftCorner.x, pos.topLeftCorner.y);         // 左上に戻る
        ctx.stroke();
    }
    /**
     * QRコードの読み取り結果を表示する
     *
     * @param {String} id
     * @param {String} data
     * @return {void}
     */
    function setQRResult(id, data){
        $(id).innerHTML = escapeHTML(data);
    }
    /**
     * HTML表示用に文字列をエスケープする
     *
     * @param {string} str
     * @return {string}
     */
    function escapeHTML(str){
        let result = "";
        result = str.replace("&", "&amp;");
        result = str.replace("'", "&#x27;");
        result = str.replace("`", "&#x60;");
        result = str.replace('"', "&quot;");
        result = str.replace("<", "&lt;");
        result = str.replace(">", "&gt;");
        result = str.replace(/\n/, "<br>");

        return(result);
    }
</script>