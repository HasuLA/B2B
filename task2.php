<?php
const PARAM_VALUE_TO_DELETE = 3;
/**
 * @param string $string
 * @return string
 */
function processString(string $string): string
{
    // Разбиваем строку на некоторые более мелкие части, с которыми удобно работать.
    $parsedString = parse_url($string);

    // Работа с параметрами
    $params = getParams($parsedString['query']);
    asort($params);
    deleteParamsByValue($params);
    $paramString = getParamString($params);

    // Возвращаем склеенную по частям строку
    return $parsedString['scheme'] . '://' . $parsedString['host'] . '?' . $paramString . '&url=' . $parsedString['path'];
}

/**
 * Удаляет параметры с определенным значением (см. PARAM_VALUE_TO_DELETE)
 *
 * @param $params
 */
function deleteParamsByValue(&$params) {
    foreach ($params as $key => $value) {
        if ((int)$value !== PARAM_VALUE_TO_DELETE) {
            continue;
        }
        unset($params[$key]);
    }
}

/**
 * Склеивает массив из параметров в строку paramName1=paramValue1&paramName2= ...
 *
 * @param array $params
 * @return false|string
 */
function getParamString(array $params)
{
    $str = '';
    foreach ($params as $paramName => $paramValue) {
        $str .= $paramName . '=' . $paramValue . '&';
    }
    // Удаляем последний знак '&'
    return substr($str, 0, -1);
}

/**
 * Возвращает массив с параметрами запроса в виде paramTitle => paramValue
 *
 * @param string $string
 * @return array
 */
function getParams(string $string): array
{
    $ret = [];
    $params = explode('&', $string);
    foreach ($params as $param) {
        $keyAndValue = explode('=', $param);
        // $keyAndValue[0] - это название параметра, $keyAndValue[1] - Это значение параметра
        $ret[$keyAndValue[0]] = $keyAndValue[1];
    }
    return $ret;
}


$initialString = "https://www.somehost.com/test/index.html?param1=4&param2=3&param3=2&param4=1&param5=3";
$result = processString($initialString);


?>