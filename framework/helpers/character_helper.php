<?php

if (!function_exists('removeBadUTF8Chars')) {
    function removeBadUTF8Chars($text)
    {
        if (is_array($text)) {
            $newArr = [];
            foreach ($text as $k => $v) {
                $newArr[$k] = removeBadUTF8Chars($text[$k]);
            }
            return $newArr;
        }

        return preg_replace('/[\x00-\x1F\x7F]/u', '', $text);
    }
}

if (!function_exists('replaceBadUTF8Chars')) {
    function replaceBadUTF8Chars($text)
    {
        if (is_array($text)) {
            $newArr = [];
            foreach ($text as $k => $v) {
                $newArr[$k] = replaceBadUTF8Chars($text[$k]);
            }
            return $newArr;
        }

        $regex = <<<'END'
/
  (
    (?: [\x00-\x7F]               # single-byte sequences   0xxxxxxx
    |   [\xC0-\xDF][\x80-\xBF]    # double-byte sequences   110xxxxx 10xxxxxx
    |   [\xE0-\xEF][\x80-\xBF]{2} # triple-byte sequences   1110xxxx 10xxxxxx * 2
    |   [\xF0-\xF7][\x80-\xBF]{3} # quadruple-byte sequence 11110xxx 10xxxxxx * 3 
    ){1,100}                      # ...one or more times
  )
| ( [\x80-\xBF] )                 # invalid byte in range 10000000 - 10111111
| ( [\xC0-\xFF] )                 # invalid byte in range 11000000 - 11111111
/x
END;
        if (!function_exists('utf8replacer')) {
            function utf8replacer($captures)
            {
                if ($captures[1] != "") {
                    // Valid byte sequence. Return unmodified.
                    return $captures[1];
                } elseif ($captures[2] != "") {
                    // Invalid byte of the form 10xxxxxx.
                    // Encode as 11000010 10xxxxxx.
                    return "\xC2" . $captures[2];
                } else {
                    // Invalid byte of the form 11xxxxxx.
                    // Encode as 11000011 10xxxxxx.
                    return "\xC3" . chr(ord($captures[3]) - 64);
                }
            }
        }

        return preg_replace_callback($regex, "utf8replacer", $text);
    }
}
