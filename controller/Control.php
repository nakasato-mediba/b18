<?php
// get認めず

$ctl = new Control();
$ctl->initialize();

class Control
{
    function initialize()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            $this->actionIndex();
        } elseif ($_POST["pagePath"] === "lotImage") {
            $this->actionLotImage();
        } elseif ($_POST["pagePath"] === "lotNumber") {
            $this->actionLotNumber();
        } elseif ($_POST["pagePath"] === "lotResult") {
            $this->actionLotResult();
        } elseif ($_POST["pagePath"] === "congrats"){
            $this->actionCongrats();
        } elseif ($_POST["pagePath"] === "reLottery") {
            $this->actionReLottery();
        } elseif ($_POST["pagePath"] === "manage") {
            $this->actionManage();
        } else
            // 例外
            $this->actionIndex();// 仮
    }

    function actionIndex()
    {
        // 参加人数格納
        if ($_POST["participants"]) {
            $this->updateParticipants($_POST["participants"]);
        }

        // 当選情報リセット
        if($_POST["reset"][0]){
            copy('../asset/master_bk.json','../asset/master.json');
        }
        header('Location:../index.html');
    }

    function actionLotImage()
    {
        $url = "../lotImage.php";
        // 抽選終了チェック
        $mData = $this->readMasterData();
        for($i = 0; $i < sizeof($mData) + 1; $i++){
            if($mData[$i]["end"] === false){
                break;
            }
            // 全景品が終了したら特殊画像表示
            if($i === 25){
                $url = "../lotImage.php?endAll=" . "true";
                break;
            }
        }
        // 当選者データが存在すれmasterデータを変更する TODO
        header('Location:' . $url);
    }

    function actionLotNumber()
    {
        $array = $this->readParticipants();
        $max = $array["participants"];
        $url = "../lotNumber.php?max=" . $max;
        header('Location:' . $url);
    }

    function actionLotResult()
    {
        $winner = $_POST["winner"];
        $img = "./...";
        $this->updateMasterData($winner);
        $url = "../lotResult.php?winner=" . $winner;
        header('Location:' . $url);
    }

    function actionCongrats(){
        // 画像url取得する必要あり
        $img = "";
        $winner = $_POST["winner"];
        $mData = $this->readMasterData();
        for($i = 0; $i < sizeof($mData) + 1; $i++){
            if($mData[$i]["end"] === false){
                $img = $mData[$i - 1]["imgUrl"];
                break;
            }
            // エラー処理無いけどとりあえずおｋ
        }
        $url = "../congrats.php?winner=" . $winner . "&img=" . $img;
        header('Location:' . $url);
    }

    function actionReLottery(){
        $this->reLottery();
        $this->actionLotNumber();
    }

    // 各種抽選データ読み込み 返却値：jsonから既にデコード済み連想配列
    function readMasterData()
    {
        $file = file_get_contents('../asset/master.json');
        $jsonData = mb_convert_encoding($file, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        $mData = json_decode($jsonData, true);
        return $mData;
    }

    // 抽選データ書き込み
    function updateMasterData($winner)
    {
        $filePath = '../asset/master.json';
        $mData = $this->readMasterData();
        for ($i = 0; $i < sizeof($mData); $i++) {
            if ($mData[$i]["end"] === false) {
                $mData[$i]["winner"] = $winner;
                $mData[$i]["end"] = true;
                break;
            }
        }
        system('chmod -R 777 ' . $filePath);
        $masterFile = fopen($filePath, 'w+b');
        fwrite($masterFile, json_encode($mData));
        fclose($masterFile);
    }

    function reLottery(){
        $filePath = '../asset/master.json';
        $mData = $this->readMasterData();
        for($i = sizeof($mData); $i > 0;$i--){
            if($mData[$i]["end"] === true){
                $mData[$i]["winner"] = "";
                $mData[$i]["end"] = false;
                break;
            }
        }
        system('chmod -R 777 ' . $filePath);
        $masterFile = fopen($filePath, 'w+b');
        fwrite($masterFile, json_encode($mData));
        fclose($masterFile);
    }

    // 参加人数格納用Json 読み取り
    function readParticipants()
    {
        $ppFile = '../asset/participants.json';
        $file = file_get_contents($ppFile);
        $jsonData = mb_convert_encoding($file, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
        return json_decode($jsonData, true);
    }

    // 参加人数ファイル書き込み 引数 : 人数
    function updateParticipants($newNum)
    {
        $ppFile = '../asset/participants.json';
        $pp = $this->readParticipants();

        $pp["participants"] = $newNum;
        $this->hoge = $pp;
        system('chmod -R 777 ' . $ppFile);
        $file = fopen('../asset/participants.json', 'w+b');
        fwrite($file, json_encode($pp));
        fclose($file);
    }

    function actionManage(){
        header('Location:../manage.php');
    }
}