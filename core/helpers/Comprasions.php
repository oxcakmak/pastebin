<?php


class Comprasions {
    /**
     * Checks if two values are equal using strict comparison.
     *
     * @param mixed $varOne The first value to compare.
     * @param mixed $varTwo The second value to compare.
     * @return bool True if the values are equal, false otherwise.
     */
    function isEqual($varOne, $varTwo): bool
    {
        return $varOne === $varTwo;
    }

    /**
     * Checks if two values are not equal using strict comparison.
     *
     * @param mixed $varOne The first value to compare.
     * @param mixed $varTwo The second value to compare.
     * @return bool True if the values are not equal, false otherwise.
     */
    function isNotEqual($varOne, $varTwo): bool
    {
        return $varOne !== $varTwo;
    }

    /**
     * Checks if one value is greater than another.
     *
     * @param mixed $varOne The first value to compare.
     * @param mixed $varTwo The second value to compare.
     * @return bool True if the first value is greater than the second, false otherwise.
     */
    function isGreaterThan($varOne, $varTwo): bool
    {
        return $varOne > $varTwo;
    }

    /**
     * Checks if one value is less than another.
     *
     * @param mixed $varOne The first value to compare.
     * @param mixed $varTwo The second value to compare.
     * @return bool True if the first value is less than the second, false otherwise.
     */
    function isLessThan($varOne, $varTwo): bool
    {
        return $varOne < $varTwo;
    }
}





?>