<?php

?>

<!DOCTYPE html>
<html lang="ja">
<meta charset="UTF-8">
<title>忘年会2018</title>
<style type="text/css">
    .textBox{
        border: #c0c0c0 solid 2px;
    }
</style>
<div id="manage">
    <img src="./img/manage.jpg" />
<form action="controller/Control.php" method="post" name="toIndex">
    <input type="hidden" name="pagePath" value="index">
    参加人数 : <input class="textBox" type="number" name="participants">
    <select id="resetBox" name="resetBox">
        <input type="checkbox" name="reset[]" value="true"> 抽選データをリセットする
    </select>
</form>
</div>
<script>
    window.addEventListener('keydown', (function (e) {
        if (e.code === 'Enter' || e.code === 'Space') {
            document.forms['toIndex'].submit();
        }
    }));
</script>
</html>
