<?php

namespace iboxs\payment\lib;

trait Convert
{
   public function objectToArray($array)
    {
        if (is_object($array)) {
            $array = (array)$array;
        }
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] =$this-> objectToArray($value);
            }
        }
        return $array;
    }
}