<?php
require_once(__DIR__ . '/vendor/autoload.php');

    //callback функция для array_walk_recursive. Параметры - значение, ключ 
    //и , скоторой надо сравнить значение
    function isDate($value, $key, $date){
        if(strtotime($value) === strtotime($date)){
            global $count;
            $count++;
        }
    }
    //Функция ,которая ищет сделки по заданным дате и статусу
    function searchLeadsByDateAndStatus($status = 142,$date = '2017-06-15') {
        Introvert\Configuration::getDefaultConfiguration()->setApiKey('key', 'a68eb01d5aa7d40ae45af4825d8d713a');
        $api = new Introvert\ApiClient();
        $result = $api->lead->getAll(null, $status);
        //$count - счетчик, в котором будем считать кол-во вхождений искомой даты
        global $count;
        //перебираем все сделки, у которых доп.поля(custom_fields)
        //не пустые , и там даты и сравниваем с исходной.
        //Если есть совпадение - увеличиваем $count
        foreach ($result['result'] as $key=>$value) {
            if (count($value['custom_fields']) === 0)  continue;
            array_walk_recursive($value['custom_fields'], 'isDate',$date);
            
        }
        if ($count >= 5){
            echo 'День '.$date.' занят!<br>';
            $count = 0;
        } else {
            echo 'День '.$date.' свободен!<br>';
            $count = 0;
        }
    }
    searchLeadsByDateAndStatus();
    searchLeadsByDateAndStatus(142, '2017-06-16');