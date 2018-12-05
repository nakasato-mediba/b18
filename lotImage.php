<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv=“Cache-Control” content=“no-cache”>
    <title>忘年会2018</title>
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link rel="stylesheet" type="text/css" href="./css/ar.css">
</head>
<body>
<div id="wrapper">
    <div id="lotImage">
        <div id="premiumFrame">
            <img id="premium" src="" alt=""/>
        </div>
    </div>
</div>
<div class="menu">
    <form action="controller/Control.php" method="post" name="toLotNumber">
        <input type="hidden" name="pagePath" value="lotNumber">
    </form>
</div>
<form action="controller/Control.php" method="post" name="toIndex">
    <input type="hidden" name="pagePath" value="index">
</form>
<form action="controller/Control.php" method="post" name="toReLottery">
    <input type="hidden" name="pagePath" value="reLottery">
</form>
<form action="controller/Control.php" method="post" name="toSkip">
    <input type="hidden" name="pagePath" value="skip">
</form>
<script>
    getWindowSize();
    var endLockFlg = false;
    var endAll = "<?php echo $_GET["endAll"] ?>";
    // ※ Chromeデベロッパーツールの  Networkの　Disable Cacheをチェック
    // json読み込み
    window.onload = (function (handleLoad) {
        // アスペクト比出力 TODO: リハ終ったら消す
        ar = window.innerWidth / window.innerHeight;
        console.log('アスペクト比 : ' + Math.floor(ar * 100));

        // 背景変更
        if(endAll === "true") {
            endLockFlg = true;
            //全抽選終了
            lotImage.style.backgroundImage = "url('./img/pajero.png')";
        }else{
            lotImage.style.backgroundImage = "url('./img/keihin.png')";//TODO 高橋くんの画像待ち　背景を景品ごとに変える
        }

        // json読み込み
        let xhr = new XMLHttpRequest();
        xhr.addEventListener('load', handleLoad, false);
        xhr.open('GET', './asset/master.json', true);
        xhr.send(null);
    }(function handleLoad(event) {
        let xhr = event.target,
            mData = JSON.parse(xhr.responseText);
        // jsonデータ利用
        let idx = 0;
        for (let i = 0; i < mData.length; i++) {
            if (mData[i].end === false) {
                idx = i;
                break;
            }
        }
        if(endAll !== "true") {
            premium.src = mData[idx].imgUrl;
        }
    }));

    window.addEventListener('keydown', (function (e) {
        if ((endLockFlg === false) && (e.code === 'Enter' || e.code === 'Space')) {
            document.forms['toLotNumber'].submit();
        } else if (e.code === 'Escape') {
            document.forms['toIndex'].submit();
        } else if (e.code === 'Backspace'){
            document.forms['toReLottery'].submit();
        } else if (e.code === 'KeyS'){
            document.forms['toSkip'].submit();
        }
    }));


    function getWindowSize() {
        console.log('ウィンドウサイズの横幅 : ' + window.innerWidth);
        console.log('ウィンドウサイズの高さ : ' + window.innerHeight);
    }

</script>
</body>
</html>