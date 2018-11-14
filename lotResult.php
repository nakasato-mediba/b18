<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>忘年会2018</title>
    <link rel="stylesheet" type="text/css" href="./css/common.css">
    <link rel="stylesheet" type="text/css" href="./css/ar.css">
    <script>
        const char = 'A'.charCodeAt(0);
        var winner = "<?php echo $_GET["winner"] ?>";
        var alphabet = String.fromCharCode(char + (Math.floor(winner / 20)));
        var number = ("00" + (winner % 20 + 1)).slice(-2);
    </script>
</head>
<body>
<div id="lotNumber">
    <div id="numberFrame">
        <div id="alp" class="item"></div>
        <div id="num" class="item"></div>
    </div>
</div>
<div class="menu">
    <form action="controller/Control.php" method="post" name="toLotImage">
        <input type="hidden" name="pagePath" value="lotImage">
    </form>
</div>
<form action="controller/Control.php" method="get" name="toIndex"></form>
</body>
<script>
    window.onload = (function () {
        alp.innerText = alphabet;
        num.innerText = number;
    });

    window.addEventListener('keydown', (function (e) {
        if (e.code === 'Enter' || e.code === 'Space' || e.code === 'Escape'){
            document.forms['toIndex'].submit();
        }
    }));
</script>
</html>