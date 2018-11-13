<?php
// get認めず

$ctl = new Control();
$ctl->initialize();

class Control
{
    function initialize()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            // 例外
            $this->actionIndex();
        } else if ($_POST["pagePath"] === "lotImage") {
            $this->actionLotImage();
        } else if ($_POST["pagePath"] === "lotNumber") {
            $this->actionLotNumber();
        } else if ($_POST["pagePath"] === "lotResult"){
            $this->actionLotResult();
        }else
        // 例外
        $this->actionIndex();// 仮
    }

    function actionIndex()
    {
        header('Location:../index.html');
    }

    function actionLotImage()
    {
        // 参加人数格納
        if ($_POST["participants"]) {
            $this->updateParticipants($_POST["participants"]);
        }
        // 大当たりチェック
        $mData = $this->readMasterData();
        $url = "../lotImage.php";
        for($i = 0; $i < sizeof($mData); $i++){
            if($mData[$i]["end"] === false){
                if($mData[$i]["bigChance"] === true) $url = "../lotImage.php?bc=" . "true";
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
        $this->updateMasterData($winner);
        $url = "../lotResult.php?winner=" . $winner;
        header('Location:' . $url);
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
        $elmNum = 0;
        $filePath = '../asset/master.json';
        $mData = $this->readMasterData();
        for ($i = 0; $i < sizeof($mData); $i++) {
            if ($mData[$i]["end"] === false) {
                $elmNum = $i;
                $mData[$i]["end"] = true;
                break;
            }
        }
        $mData[$elmNum]["winner"] = $winner;
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
}