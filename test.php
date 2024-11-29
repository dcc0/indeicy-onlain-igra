<?php
$pattern="xyz";
$number=3;

$str="xyz, zxy, yzx, zzz, yy, zxy, yyy, zzz, zyz, yzx, yzx, yzx";
preg_match_all('/['.$pattern.']{'.$number.'}/', $str, $out);
$out=array_unique($out['0']);


$resh=array_unique(preg_split('//', $out, -1, PREG_SPLIT_NO_EMPTY));




print_r(array_filter($resh, function($growth) use ($number) {
    return $growth < $number;
}));
?>