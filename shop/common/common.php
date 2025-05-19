<?php

function gengo($seireki)
{
  if (1868 <= $seireki && $seireki <= 1911) {
    $gengo = '明治';
  }
  if (1912 <= $seireki && $seireki <= 1925) {
    $gengo = '大正';
  }
  if (1926 <= $seireki && $seireki <= 1988) {
    $gengo = '昭和';
  }
  if (1989 <= $seireki && $seireki <= 2018) {
    $gengo = '平成';
  }
  if (2019 <= $seireki) {
    $gengo = '令和';
  }

  return $gengo;
}

function pulldown_year($start = 2025, $length = 3)
{
  $format = '<option value="%1$04d">%1$d</option>';
  print '<select name="year">';
  for ($i = $start; $i < $start + $length; $i++) {
    printf($format, $i);
  }
  print '</select>';
}

function pulldown_month()
{
  $format = '<option value="%1$02d">%1$d</option>';
  print '<select name="month">';
  for ($i = 1; $i <= 12; $i++) {
    printf($format, $i);
  }
  print '</select>';
}

function pulldown_day()
{
  $format = '<option value="%1$02d">%1$d</option>';
  print '<select name="day">';
  for ($i = 1; $i <= 31; $i++) {
    printf($format, $i);
  }
  print '</select>';
}

function sanitize($before)
{
  foreach ($before as $key => $value) {
    $after[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
  }
  return $after;
}
