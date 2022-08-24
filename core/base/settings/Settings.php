<?php

namespace core\base\settings;

class Settings
{
    static private $_instance;

    private $routes = [
        'admin' => [
            'name' => 'admin',
            'path' => 'core/admin/controller/',
            'hrUrl' => false
        ],
        'settings' => [
            'path' => 'core/base/settings/'
        ],
        'plugins' => [
            'path' => 'core/plugins/',
            'hrUrl' => false
        ],
        'user' => [
            'path' => 'core/user/controller/',
            'hrUrl' => true,
            'routes' => [

            ]
        ],
        'default' => [
            'controller' => 'IndexController',
            'inputMethod' => 'inputData',
            'outputMethod' => 'outputData'
        ]
    ];

    private $templateArr = [
        'text' => ['name', 'phone', 'adress'],
        'textarea' => ['content','keywords']
    ];

    private function __construct(){

    }

    private function __clone(){

    }

    static public function get($property){
        return self::instance() -> $property;
    }

    static public function instance(){
        if(self::$_instance instanceof self){
            return self::$_instance;
        }
        return self::$_instance = new self;
    }

    public function clueProperties($class){
        $baseProperties = [];
        foreach ($this as $name => $item){ //this - указатель на класс Settings
           $property = $class::get($name);
           if(is_array($property) && is_array($item)){
                $baseProperties[$name] = $this->arrayMergeRecursive($this->$name, $property);
           }
        }
        //return $baseProperties;
        exit();
    }


    public function arrayMergeRecursive(){
        $arrays = func_get_args(); // массив массивов
        $base = array_shift($arrays); // достаем первый массив (базовый) из массива массивов $arrays

        // $arrays = массив аргументов, массив массивов которые будем клеить
        // $base = первый агрумент то есть массив к которому все будем клеить
        // $array = остальные массивы которые клеим к базовому
        // $key - ключ в массиве $array/$base
        // $value - значение в массиве $array/$base


        foreach ($arrays as $array){
            foreach ($array as $key => $value){
                if(is_array($value) && is_array($base[$key])){
                    $base[$key] = $this->arrayMergeRecursive($base[$key], $value);
                }else{
                    if(is_int($key)){ // если ключ интовый то есть массив выглядит как  ['dfgdfg','sdfasd','ertfdg']
                                      // то мы клеим массивы если же нет то перезаписываем
                        if(!in_array($value,  $base))array_push($base, $value); // если такого элемента в массиве не
                        // существует то мы этот элемент закидываем (пушим) в этот массив
                        continue; // уходим на следующую итерацию цикла
                    }
                    $base[$key] = $value; // переопределяем значение по ключу в массиве $base
                }
            }
        }
        return $base; // возвращаем бызовый уже склеиный массив
    }
}