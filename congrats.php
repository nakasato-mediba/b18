<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>忘年会2018</title>
    <link rel="stylesheet" type="text/css" href="./css/common.css">
    <link rel="stylesheet" type="text/css" href="./css/ar.css">
    <script>
        const char = 'A'.charCodeAt(0);
        var imgUrl = "<?php echo $_GET["img"] ?>";
        var winner = "<?php echo $_GET["winner"] ?>";
        var alphabet = String.fromCharCode(char + (Math.floor(winner / 20)));
        var number = ("00" + (winner % 20 + 1)).slice(-2);
    </script>
</head>
<body>
<div id="congrats">
    <div id="alp_c" class="item"></div>
    <div id="num_c" class="item"></div>
    <img id="lotImage_c" src="" />
</div>
<form action="controller/Control.php" method="post" name="toLotImage">
    <input type="hidden" name="pagePath" value="lotImage">
</form>
<form action="controller/Control.php" method="get" name="toIndex"></form>
<form action="controller/Control.php" method="post" name="toReLottery">
    <input type="hidden" name="pagePath" value="reLottery">
</form>
</body>
<script>
    window.onload = (function () {
        alp_c.innerText = alphabet;
        num_c.innerText = number;
        lotImage_c.src= imgUrl;
    });

    window.addEventListener('keydown', (function (e) {
        if (e.code === 'Enter' || e.code === 'Space') {
            document.forms['toLotImage'].submit();
        } else if (e.code === 'Escape') {
            document.forms['toIndex'].submit();
        }
        if (e.code === 'Backspace') {
            document.forms['toReLottery'].submit();
        }
    }));
</script>
</html>