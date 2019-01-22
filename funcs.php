<?php
function _log($data) {
    \Yii::info(\yii\helpers\VarDumper::dumpAsString($data, 5), '_');
}

function _end($data) {
    echo \yii\helpers\VarDumper::dumpAsString($data, 5, true);
}

/**
 * @return \yii\console\Application|\yii\web\Application|app\components\Application
 */
function app() {
    return \Yii::$app;
}
