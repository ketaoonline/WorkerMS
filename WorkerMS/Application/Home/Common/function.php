<?php

function parseExamerTime($data){
    $data_examer_time = explode('#', $data);
    $data_examer_time = array_slice($data_examer_time, 1, -1);
    foreach($data_examer_time as $k => $v){
        $tmp = explode('>', $v);
        $examer_time[$tmp[0]] = $tmp[1];
    }
    return $examer_time;
}

function parseExam($data){
    return array_slice(explode('#', $data), 1, -1);
}

function parseExamWeight($data){
    return array_slice(explode('#', $data), 1, -1);
}

function parseExamData($data){
    $examer_time = parseExamerTime($data['examer_time']);
    $examer = array();
    foreach($examer_time as $examor => $time){
        array_push($examer, $examor);
    }
    $exam = parseExam($data['exam']);
    $examer_exam = array();
    for($i=0;$i<count($examer);$i++){
        $examer_exam[$examer[$i]] = $exam[$i];
    }
    $exam_weight = parseExamWeight($data['exam_weight']);
    return array(
        'examer_time' => $examer_time,
        'exam'          => $exam,
        'weight'    => $exam_weight,
        'examer_exam'   => $examer_exam,
        'examer_weight' => zip($examer, $exam_weight),
    );
}
function zip($data1, $data2){
    for($i=0;$i<count($data1);$i++){
        $data[$data1[$i]] = $data2[$i];
    }
    return $data;
}