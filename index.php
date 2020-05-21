<?
/********************Работа со строками************************/

/**
 * 1. Функция подсчета вхождений слова в строку 
 * @param $str - строка поиска
 * @param $substr - что ищем
 */
function getSubstringCount($str, $substr){
    $matches = [];
    $sub_string = strval($substr);
    $result = preg_match_all("/\b$sub_string\b/ui", strval($str), $matches, PREG_PATTERN_ORDER);
    return count($matches[0]);
}
echo getSubstringCount("Никогда не говори никогда", "никогда")." - раз(а) слово \"никогда\" встречается в строке \"Никогда не говори никогда\" ";
echo "<br>";
echo "<br>";


/**
 * 2. Функция проверки являются ли две фразы перестановками друг друга
 * @param $firstStr - первая фраза
 * @param $secondStr - вторая фраза
 */
function checkStringsToMatches($firstStr, $secondStr){
    $firstArr = getWordsArray($firstStr);
    $secondArr = getWordsArray($secondStr);
   
    if(!empty($firstArr) && !empty($secondArr) && count($firstArr)===count($secondArr)){ 
        foreach($firstArr as $key=>$word){
            if(!in_array($word, $secondArr))
                return false;
        }
        
        return true;
    }
    
    return false;
}
/**
 * Вспомогательная функция. Возвращает массив слов фразы в нижнем регистре
 * @param $str - строка для преобразования в массив
 */
function getWordsArray($str){
    $arrWords = explode(" ",trim($str));
    
    foreach($arrWords as $key=>&$word){
        $word = strtolower($word);
    }
    unset($word);  
    
    return $arrWords;
}
var_dump(checkStringsToMatches("Мама мыла раму", "Мыла мама раму"));
echo "<br>";
var_dump(checkStringsToMatches("Мама мыла раму", "Мыла мама окно"));
echo "<br>";
echo "<br>";



/********************Работа с датами************************/

/*
 * 1. Вывести текущую дату в формате Y/m/d - H:i 
 * Без комментариев. Можно обойтись без функции.
 */
echo date("Y/m/d - H:i");
echo "<br>";
echo "<br>";


/**
 * 2. Подсчитать кол-во пн и пт в текущем году
 * @param $dayKey - порядковый номер дня недели для поиска (от 1 до 7)
 */
$arWeekDays = ["пн", "вт", "ср", "чт", "пт", "сб", "вс"];

function getCountWeekDay($dayKey){
	$year = date("Y");
	$date = new DateTime("01.01.$year");
	$flag = false;
	$arrDates = [];	
	$month = $date->format("m");
	
	while ($date->format("Y") == $year)
	{
		if ($flag === false){
			if ($date->format("N") == $dayKey){
				$flag = true;
			} else {
				$date->add(new DateInterval("P1D"));
			}
		}
		
		if ($flag === true){
			$arrDates[] = $date->format("d.m")."\t";
			$date->add(new DateInterval("P1W"));
		}
		
		if ($month != $date->format("m")) {
			$month = $date->format("m");
		}
	}
	
	return count($arrDates);
}

echo (getCountWeekDay(1))." - кол-во понедельников";
echo "<br>";
echo (getCountWeekDay(7))." - кол-во воскресений";
echo "<br>";
echo "<br>";


/**
 * 3. Дата следующего воскресенья
 * @param $num - порядковый номер дня недели для поиска (от 1 до 7)
 */
function getNextWeekDay($num){
    $secondsInOneDay = 84600; 
    $today = date("N",time());//день недели сегодня
    
    if($today>$num){
        $nextDate = 7 - $today + $num;
    }elseif($today<$num){
        $nextDate = $num - $today;
    }else{
        $nextDate = 7;
    }    
       
    $nextWeekDay = date ("d.m.Y", time() + $nextDate*$secondsInOneDay);
    return $nextWeekDay;
}
echo getNextWeekDay(7)." - следующее воскресенье";
echo "<br>";
echo "<br>";



/********************Работа с массивами************************/

/**
 * 1. Функция сортировки первого массива в порядке возрастания значений во втором массиве
 * @param $arr1 - первый массив
 * @param $arr2 - второй массив
 */
function getSortingArray($arr1, $arr2){
    if(!is_array($arr1) || !is_array($arr2)){
        return "Один из входных массивов - не массив";
    }
    
    if(count($arr1)!=count($arr2)){
        return "У массивов разное количество элементов";
    }
    
    if(!count($arr1)){
        return "Пустые массивы нет смысла сортировать...";
    }
            
    $tempArr = $arr2;
    asort($tempArr);
    
    $newSortArr = [];
    
    foreach($tempArr as $key=>$val){
        $newSortArr[$key] = $arr1[$key];
    }
    return $newSortArr;    
}

$a = ['x', 'm', 'g', 's', 'a'];
//$a = [];
//$a = "ляляля";
$b = [3, 6, 1, 4, 2];
//$b = [];
print_r(getSortingArray($a, $b));
echo "<br>";
echo "<br>";


/**
 * 2. Функция - аналог array_merge()
 * @param ...$arrs - массивы для слияния
 */
function customArrayMerge(...$arrays){
    if(empty($arrays)){
        return "Передайте на вход массивы";
    }
    
    $i=0;
    $newArr = [];
    if(count($arrays)==1){
        return getNewElement($arrays[0], $i);
    }
    
    foreach($arrays as $key => $array){
        if(!is_array($array)){
            $k = $key+1;
            return "Аргумент №$k - не массив";
        }
        
        $tempArr = getNewElement($array, $i);
        foreach($tempArr as $k=>$val){
            $newArr[$k] = $val;
        }
    }
    
    return $newArr;
}

/**
 * Вспомогательная фугкция смены ключей массива
 * @param $arr - массив
 * @param $i - ссылка на порядковый номер
 */
function getNewElement($arr, &$i){
    $newArr=[];
    foreach($arr as $k=>$val){
        if(is_int($k)) {
            $newArr[$i] = $val;
            $i++;
        } else {
            $newArr[$k] = $val;
        }
    }
    return $newArr;
}

$a = [
    0 => 'x', 
    9 =>'m', 
    2 => 'g', 
    3 => 's',
    4 => 'a'
];
$b = [
    "a" => 3, 
    "5" => 6, 
    "8" => 1,
    "n" => 4,
    "0" => 2    
];
print_r(customArrayMerge($a,$b));
echo "<br>";
print_r(array_merge($a,$b));
echo "<br>";
echo "<br>";


/**
 * 3. Функция подсчета суммы числовых значений массива любой вложенности
 * @param $arr - массив
 */
function customSumArray($arr, &$sum = 0){
    foreach($arr as $val){
        if(is_numeric($val)){
            $sum += $val;
        }
        if(is_array($val) && !empty($val)){
            customSumArray($val, $sum);
        }        
    }
    return $sum;
}
$a = [[12, 18], 40, [4, 6, [10]]]; 
print_r(customSumArray($a));
echo "<br>";
print_r(array_sum($a));
echo "<br>";
echo "<br>";


/**
 * 4. Функция поиска 2х и более пересечений нескольких массивов
 * @param ...$arrays - массивы для поиска
 */
function searchMatchesInArrays(...$arrays){
	$newArray = $tmpArr = [];
	
    foreach($arrays as $key => $array){
        if(!is_array($array)){
            $k = $key+1;
            return "Аргумент №$k - не массив";
        }   
        
        $newArray[] = implode(" ",array_unique($array));
    }
    
    $tmpArr = explode(" ",implode(" ",$newArray)); 
    
    return array_diff_assoc($tmpArr, array_unique($tmpArr));
}
$arr1 = [1, 5, 6, 8];
$arr2 = [2, 3, 4, 5, 4];
$arr3 = [10, 3, 12, 7];
echo "<pre>";
print_r(searchMatchesInArrays($arr1, $arr2, $arr3));
echo "<br>";
echo "<br>";


/************************Рекурсия**************************/
/**
 * 1. Функция вычисляет факториал числа
 * @param $num - число
 */
function getFactorial($num){	
	if(!is_numeric($num)){
		return("Заданный аргумент не число");
	} else {
		if($num <= 1){
			return 1;
		}
		
		return $num * getFactorial($num-1);
	}
}
print_r(getFactorial(7));
echo "<br>";
echo "<br>";
?>
