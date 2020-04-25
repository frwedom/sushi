<?php

namespace App;

use App;

class Functions
{

//Получить полный адрес текущей страницы
    public function getSiteURL()
    {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        $url = $protocol . $_SERVER['HTTP_HOST'];
        return $url;
    }

// Проверка чек бокса
    public function check_checkbox($value)
    {
        if ($value == 1) {
            return "checked";
        }
    }

//Рандомное имя
    public function random_name($name)
    {
        $on_name = substr(uniqid(), 0, 9);
        $random_name = rand(10000, 99999) . '_' . $on_name . '.' . pathinfo($name, PATHINFO_EXTENSION);

        return $random_name;
    }

//Для проверки вводимых пользователем данных
    public function check_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

// Распечатывает дамп переменной на экран
    public function dumper($obj)
    {
        echo
        "<pre>",
        htmlspecialchars($this->dumperGet($obj)),
        "</pre>";
    }

    public function dumperGet(&$obj, $leftSp = "")
    {
        if (is_array($obj)) {

            $type = "Array[" . count($obj) . "]";

        } elseif (is_object($obj)) {

            $type = "Object";

        } elseif (gettype($obj) == "boolean") {

            return $obj ? "true" : "false";

        } else {

            return "\"$obj\"";

        }
        $buf = $type;

        $leftSp .= " ";

        for (Reset($obj); list($k, $v) = each($obj);) {
            if ($k === "GLOBALS") continue;
            $buf .= "\n$leftSp$k => " . $this->dumperGet($v, $leftSp);
        }

        return $buf;
    }

// Timestamp в читаемый вид
    public function timestampToString($timestamp, $get_time = false)
    {
        $language = \App::$language->getCurrentLanguage();
        $time = '';

        $timestamp = explode(' ', $timestamp);

        $date = explode('-', $timestamp[0]);

        $year = $date[0];
        $month = $date[1];
        $day = $date[2];

        $months = [
            'ru' => [
                '01' => 'Янв',
                '02' => 'Фев',
                '03' => 'Мар',
                '04' => 'Апр',
                '05' => 'Май',
                '06' => 'Июн',
                '07' => 'Июл',
                '08' => 'Авг',
                '09' => 'Сен',
                '10' => 'Окт',
                '11' => 'Ноя',
                '12' => 'Дек',
            ],
            'kz' => [
                '01' => 'Қаң',
                '02' => 'Ақп',
                '03' => 'Нау',
                '04' => 'Сәу',
                '05' => 'Мам',
                '06' => 'Мау',
                '07' => 'Шіл',
                '08' => 'Там',
                '09' => 'Қыр',
                '10' => 'Қаз',
                '11' => 'Қар',
                '12' => 'Жел',
            ],
            'en' => [
                '01' => 'Jan',
                '02' => 'Feb',
                '03' => 'Mar',
                '04' => 'Apr',
                '05' => 'May',
                '06' => 'Jun',
                '07' => 'Jul',
                '08' => 'Aug',
                '09' => 'Sep',
                '10' => 'Oct',
                '11' => 'Nov',
                '12' => 'Dec',
            ],
            'zh' => [
                '01' => '一月',
                '02' => '二月',
                '03' => '损伤',
                '04' => '四月',
                '05' => '可以',
                '06' => '君',
                '07' => '七月',
                '08' => '八月',
                '09' => '九月',
                '10' => '十月',
                '11' => '十一月',
                '12' => '十二月',
            ],
        ];

        foreach ($months[$language] as $k => $v) {
            if ($k == $month) {
                $month = $v;
                break;
            }

        }

        if ($get_time) {
            $time = $timestamp = explode(':', $timestamp[1]);

            $hour = $time[0];
            $minute = $time[1];

            $time = $hour . ':' . $minute;

        }

        return $month . ' ' . $day . ', ' . $year . ' ' . $time;
    }

    public function stringDate($timestamp)
    {
        $time = time() - strtotime($timestamp);
        if ($time < 60) {
            return $this->dimension((int)($time/60), 'now');
        } elseif ($time < 3600) {
            return $this->dimension((int)($time/60), 'i');
        } elseif ($time < 86400) {
            return $this->dimension((int)($time/3600), 'G');
        } elseif ($time < 2592000) {
            return $this->dimension((int)($time/86400), 'j');
        } elseif ($time < 31104000) {
            return $this->dimension((int)($time/2592000), 'n');
        } elseif ($time >= 31104000) {
            return $this->dimension((int)($time/31104000), 'Y');
        }
    }

    function dimension($time, $type) { // Определяем склонение единицы измерения

        $language = \App::$language->getCurrentLanguage();

        $dimension['ru'] = array(
            'n' => array('месяцев', 'месяц', 'месяца', 'месяц'),
            'j' => array('дней', 'день', 'дня'),
            'G' => array('часов', 'час', 'часа'),
            'i' => array('минут', 'минуту', 'минуты'),
            'Y' => array('лет', 'год', 'года'),
            'now' => 'прямо сейчас'
        );

        $dimension['kz'] = array(
            'n' => array('айлар', 'ай', 'ай', 'ай'),
            'j' => array('күн', 'күн', 'күн'),
            'G' => array('сағат', 'сағат', 'сағат'),
            'i' => array('минут', 'минут', 'минут'),
            'Y' => array('жыл', 'жыл', 'жыл'),
            'now' => 'дәл қазір'
        );

        $dimension['en'] = array(
            'n' => array('months', 'month', 'месяца', 'month'),
            'j' => array('days', 'day', 'day'),
            'G' => array('hours', 'hour', 'hours'),
            'i' => array('minutes', 'minute', 'minutes'),
            'Y' => array('year', 'year', 'years'),
            'now' => 'right now'
        );

        if ($type == 'now') {
            return $dimension[$language]['now'];
        }

        $dimensionEnd['ru'] = ' назад';
        $dimensionEnd['kz'] = ' бұрын';
        $dimensionEnd['en'] = ' ago';


        if ($time >= 5 && $time <= 20)
            $n = 0;
        else if ($time == 1 || $time % 10 == 1)
            $n = 1;
        else if (($time <= 4 && $time >= 1) || ($time % 10 <= 4 && $time % 10 >= 1))
            $n = 2;
        else
            $n = 0;
        return $time.' '.$dimension[$language][$type][$n]. $dimensionEnd[$language];

    }

    public function selectItems($items, $selected = 0)
    {

        $explode_selected = explode("\r\n", $selected);
        $text = "";
        $ch = "";

        foreach ($items as $v) {
            foreach ($explode_selected as $selected) {
                if ($v == $selected) {
                    $ch = " selected";
                    break;
                } else {
                    $ch = "";
                }
            }

            $text .= "<option$ch value='$v'>$v</option>\n";
        }

        return $text;
    }

    public function selectColors($colors, $selected = 0)
    {

        $explode_selected = explode("\r\n", $selected);
        $text = "";
        $ch = "";

        foreach ($colors as $v) {
            foreach ($explode_selected as $selected) {
                if ($v == $selected) {
                    $ch = " selected";
                    break;
                } else {
                    $ch = "";
                }
            }

            $fullColor = explode(',', $v);

            $text .= "<option$ch value='$v' style='background: " . trim($fullColor[1]) . ";'>$v</option>\n";
        }

        return $text;
    }

//Цифры в читаемый вид
    public function readablePrice($price)
    {
        return number_format($price, 0, ',', ' ');
    }

    public function simpleToken() {
        return substr(bin2hex(random_bytes(64)), 0, 22);
    }

    function downcounter($date){
        $check_time = strtotime($date) - time();
        if($check_time <= 0){
            return false;
        }

        $days = floor($check_time/86400);

        $language = \App::$language->getCurrentLanguage();

        if ($language == 'kz') {
            $array = array('күн','күн','күн');
        } else {
            $array = array('день','дня','дней');
        }

        $str = '';
        if($days > 0) $str .= $this->declension($days,$array).' ';

        return $str;
    }

    function declension($digit,$expr,$onlyword=false){
        if(!is_array($expr)) $expr = array_filter(explode(' ', $expr));
        if(empty($expr[2])) $expr[2]=$expr[1];
        $i=preg_replace('/[^0-9]+/s','',$digit)%100;
        if($onlyword) $digit='';
        if($i>=5 && $i<=20) $res=$digit.' '.$expr[2];
        else
        {
            $i%=10;
            if($i==1) $res=$digit.' '.$expr[0];
            elseif($i>=2 && $i<=4) $res=$digit.' '.$expr[1];
            else $res=$digit.' '.$expr[2];
        }
        return trim($res);
    }

}