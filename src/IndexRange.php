<?php

/**
 * Class IndexRange
 */
class IndexRange {
    /**
     * @param string $str index range definition
     *
     * @return array
     */
    public static function computeIndexRange($str)
    {
        $indexes = [];
        $range   = explode(',', $str);

        foreach ($range as $val) {
            $pos = strpos($val, '-');
            if ($pos === false) {
                // check int
                $indexes[] = $val;
            } else {
                $first = substr($val, 0, $pos);
                $last  = substr($val, $pos + 1);
                $indexes = array_merge($indexes, range($first, $last));
            }
        }

        return $indexes;
    }

    /**
     * @param array $haystack haystack to get elements from
     * @param array $indexes  list of indexes
     *
     * @return array
     */
    public static function arraySliceIndexedValues(array $haystack, array $indexes)
    {
        $result = [];
        foreach ($indexes as $i) {
            $result[] = $haystack[$i];
        }

        return $result;
    }
}
