<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>忘年会2018</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" type="text/css" href="./css/common.css">
    <link rel="stylesheet" type="text/css" href="./css/ar.css">
    <script>
        window.onload = (function getAspectRatio() {
            ar = window.innerWidth / window.innerHeight;
            console.log("画面アスペクト比 : " + Math.floor(ar * 100));
        })();
    </script>
</head>
<body>
<div id="lotNumber">
    <div id="numberFrame">
        <div id="alp" class="item">A</div>
        <div id="num" class="item">02</div>
    </div>
</div>
<div class="menu">
    <form action="controller/Control.php" method="post" name="toLotResult">
        <input type="hidden" name="pagePath" value="lotResult">
        <input type="hidden" id="hiddenWinner" name="winner" value="">
    </form>
</div>
<form action="controller/Control.php" method="get" name="toIndex"></form>
<script>

    var max = <?php echo $_GET["max"] ?>;// 忘年会参加人数
    waiters = new Array(max);
    alps = new Array(Math.floor(max / 20));
    const char = 'A'.charCodeAt(0);
    var alpsNum;
    var turnNum = 0;
    var noDuplicationFlg = false;
    var random;
    var winAlp = "";
    var winNum = "";
    alpStopFlg = false;
    numStopFlg = false;

    // 抽選
    window.onload = (function (handleLoad) {
        let xhr = new XMLHttpRequest();
        xhr.addEventListener('load', handleLoad, false);
        xhr.open('GET', './asset/master.json', true);
        xhr.send(null);
    }(function handleLoad(event) {
        let xhr = event.target,
            mData = JSON.parse(xhr.responseText);
        // jsonデータ利用
        // 未当選の配列を生成
        for (let i = 0; i < mData.length; i++) {
            if (mData[i].winner !== "") {
                waiters[mData[i].winner] = -1;
            }
        }
        console.table(waiters);
        while (noDuplicationFlg === false) {
            random = Math.floor(Math.random() * max + 1);
            console.table(waiters);
            if (waiters[random] !== -1) {
                noDuplicationFlg = true;
                break;
            }
            console.log("再抽選：" + random)
        }
        // ToDo ここでTrueとかFalseが出てくるのやばすぎ A00の原因 解決済み？
        // 抽選結果いれま
        document.getElementById("hiddenWinner").value = random;
        winAlp = String.fromCharCode(char + Math.floor(random / 20));
        winNum = ("00" + Math.floor(random % 20 + 1)).slice(-2);

        console.log("抽選結果：" + random + " : " + winAlp + "-" + winNum);
    }));

    // 文字回転
    var turnAlpObj = setInterval(function () {
        if (alpStopFlg === false || alp.innerText !== winAlp ) {
            turnAlphabet();// 回転
        } else {
            alp.innerText = winAlp;
            clearInterval(turnAlpObj);
        }
    },200);

    // 数字回転
    var turnNumObj = setInterval(function () {
        if (numStopFlg === false || num.innerText !== winNum) {
            turnNumber();
        } else {
            num.innerText = winNum;
            clearInterval(turnNumObj);
            document.forms["toLotResult"].submit();
        }
    },100);


    function turnAlphabet() {
        alpsNum = alpsNum < Math.floor(max / 20) ? alpsNum + 1 : 0;
        alp.innerText = String.fromCharCode(char + alpsNum);
    }

    function turnNumber() {
        turnNum = turnNum < 20 ? turnNum + 1 : 1;
        num.innerText = ("00" + turnNum).slice(-2);
    }


    function lotStop() {
        if (alpStopFlg === false) {
            alpStopFlg = true;
        }else{
            numStopFlg = true;
        }
    }

    window.addEventListener('keydown', (function (e) {
        if (e.code === 'Enter' || e.code === 'Space') {
            lotStop();
        } else if (e.code === 'Escape') {
            document.forms['toIndex'].submit();
        }
    }));
</script>
</body>
</html>