<?php


#
# PHP8 Compatibility for PHP7
#


if(function_exists('str_ends_with') == false) {

  // str_ends_with(string $haystack, string $needle): bool
  function str_ends_with($haystack,$needle) {

    // str_starts_with(string $haystack, string $needle): bool
    $strlen_needle = mb_strlen($needle);
    if(mb_substr($haystack, -$strlen_needle, $strlen_needle) == $needle) {
      return true;
    }
    return false;

  }

}

