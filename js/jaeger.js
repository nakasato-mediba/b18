const default_sec = 15;
var hide_str = '';// '○○○○○○'
var app = new Vue({
    el: '#app',

    data: {
        rnd_name: '',
        number: 0,
        tweenedNumber: 0,
        selected: 'さとうみつお',
        members: [
            {choice: 'Aさん', name: '佐藤光勇', kana: 'さとうみつお'},
            {choice: 'Bさん', name: '沢田知樹', kana: 'さわだかずき'},
            {choice: 'Cさん', name: '森慎二', kana: 'もりしんじ'},
        ],
        sec: default_sec,
        mSec: 0,
        timerOn: false,
        timerObj: null,
    },

    computed: {
        // タイマー表示周り
        formatTime: function() {
            let timeStrings = [
                this.sec.toString(),
                this.mSec.toString()
            ].map(function(str) {
                return str.length < 2 ? "0" + str : str;
            });
            return timeStrings[0] + ":" + timeStrings[1]
        }
    },

    methods: {
        // 以下 名前当てゲーム用 関数群
        /**
         * startFunc()
         *
         * メイン処理を開始する
         * - シャッフルされた「かな」の名前を返却する
         * - タイマーを開始する。
         */
        startFunc: function () {
            this.init();
            this.rnd_name = this.randomSortFunc();
            this.start();
        },

        /**
         * randomSortFunc()
         *
         * ドロップダウンリスト:selectedで選択されている名前のかなをランダムに並び替える関数
         *
         */
        randomSortFunc: function () {
            this.player = this.selected;
            var rnd_array = this.sortFunc(this.player.split(''));
            // 本名と同じ文字の並びが3つ以上ある場合、再帰呼出しでシャッフルし直し
            return this.checkFunc(rnd_array.join('')) ? this.randomSortFunc() : rnd_array.join('');
        },

        /**
         * sortFunc()
         *
         * ランダムに文字配列を並び替える
         * フィッシャーイェーツのシャッフルを利用
         *
         * 引数 　str_array array型 名前がひらがなで１文字ずつ格納された配列 例：['さ','と','う','み','つ','お']
         * 返り値 str_array array型 シャッフルに並び替えられた値           例：['う','と','み','お','つ','さ']
         *
         **/
        sortFunc: function (str_array) {
            for (var i = str_array.length - 1; i > 0; i--) {
                var r = Math.floor(Math.random() * (i + 1));
                var tmp = str_array[i];
                str_array[i] = str_array[r];
                str_array[r] = tmp;
            }
            return str_array;
        },

        /**
         * checkFunc()
         *
         * ランダムに並び変えた名前の配列に、本来の名前と同じ並びの文字が3つ以上ある場合
         * 再度並び替えるための判定プログラム
         *
         * 引数   rnd_array array型 sortFunc()で並び変えた名前が１文字ずつ入った配列 例：['う','と','み','お','つ','さ']
         * 返り値 boolean           判定結果がtrueなら重複、falseなら重複なし
         *
         **/
        checkFunc: function (rnd_array) {
            flg = false;
            console.log("【" + rnd_array + "】");
            for (var i = 0; i < rnd_array.length - 2; i++) {
                var s = this.selected.slice(i, i + 3);
                for (var k = 0; k < rnd_array.length - 2; k++) {
                    var r = rnd_array.slice(k, k + 3);
                    flg = s === r;
                    // console.log(s + " と " + r + " 結果：" + flg);
                    if (flg) {
                        return true;
                    }
                }
            }
            return false;
        },

        // 以下タイマー用 関数群
        const: function () {
            if (this.mSec >= 1) {
                this.mSec--;
            }else{
                this.sec --;
                this.mSec = 99;
            }
            if (this.sec <= 0 && this.mSec <= 0) this.complete();
        },

        start: function (){
            let self = this;
            this.timerObj = setInterval(function () {self.const()},10);
            this.timerOn = true; // timerの状態を保持する
        },
        stop: function (){
            clearInterval(this.timerObj);
            this.timerOn = false; // timerの状態を保持する
            this.cHideStr();
            this.rnd_name = this.cHideStr();// かなを○で隠す
        },
        complete:function(){
            clearInterval(this.timerObj);
            this.rnd_name = this.selected;
        },

        /**
         * 初期化用関数
         *
         */
        init:function(){
            this.stop();
            this.sec = default_sec;
            this.mSec = 0;
        },

        /**
         * 選択中の人の「かな」の文字長に合わせた ○を生成する
         * 例：さとう -> ○○○
         *
         * @returns {string} ○
         */
        cHideStr:function () {
            var hide_str = '';
            for(var i = 0;i < this.selected.length;i++){
                hide_str += '○';
            }
            return hide_str;
        }
    },
});
app.init();