<?php

class Strings {

    /**
     * Determines if the provided input is a string data type.
     *
     * This function utilizes the built-in `is_string` function to rigorously
     * verify if the given variable is indeed a string.
     *
     * @param mixed $input The variable to be evaluated for type.
     * @return bool True if the input is a string, false otherwise.
     * @throws InvalidArgumentException If the input is not a valid variable.
     */
    function isString(mixed $input): bool
    {
        if (!is_string($input) || gettype($input) !== 'string') {
            throw new InvalidArgumentException('Invalid input provided. Expecting a variable.');
        }

        return true;
    }

    /**
     * Converts a string from its detected encoding to UTF-8.
     *
     * @param string $text The string to convert.
     * @return string The converted string in UTF-8 encoding, or the original string if conversion fails.
     * @throws InvalidArgumentException If the string is not valid UTF-8 after conversion.
     */
    function convertToUtf8(string $text): string
    {
        $originalEncoding = mb_detect_encoding($text, mb_detect_order(), true);
        
        if ($originalEncoding === false) {
            // Handle cases where encoding detection fails (e.g., log or return default)
            // echo "Warning: Could not detect encoding for the string.";
            return $text;
        }

        // Attempt conversion to UTF-8
        $convertedText = iconv($originalEncoding, "UTF-8", $text);

        // Validate the converted string
        if (!mb_check_encoding($convertedText, "UTF-8")) {
            throw new InvalidArgumentException("Conversion to UTF-8 failed. Original encoding: $originalEncoding");
        }

        return $convertedText;
    }

    /**
     * Sanitizes a string for output by removing HTML tags, slashes, and trimming whitespace.
     * Additionally, escapes special characters to prevent XSS vulnerabilities.
     *
     * @param string $text The string to sanitize.
     * @return string The sanitized string.
     * @throws InvalidArgumentException If the string is not valid UTF-8 after escaping.
     */
    function sanitizeOutput(string $text): string
    {
        $sanitizedText = trim(strip_tags(stripslashes($text)));

        // Escape special characters using htmlspecialchars with ENT_QUOTES and UTF-8 encoding
        $escapedText = htmlspecialchars($sanitizedText, ENT_QUOTES, 'UTF-8');

        // Validate the escaped string
        if (!mb_check_encoding($escapedText, "UTF-8")) {
            throw new InvalidArgumentException("String escaping failed. Original encoding unknown.");
        }

        return $escapedText;
    }

    /**
     * Escapes special characters in a string to prevent XSS vulnerabilities.
     *
     * @param string $text The string to escape.
     * @return string The string with escaped special characters.
     * @throws InvalidArgumentException If the string is not valid UTF-8 after escaping.
     */
    function escapeXss(string $text): string
    {
        $escapedText = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

        // Validate the escaped string
        if (!mb_check_encoding($escapedText, "UTF-8")) {
            throw new InvalidArgumentException("String escaping failed. Original encoding unknown.");
        }

        return $escapedText;
    }

    /**
     * Removes all whitespace characters from a string.
     *
     * This function utilizes a regular expression to efficiently remove all
     * whitespace characters, including spaces, tabs, newlines, carriage returns,
     * null bytes, and vertical tabs.
     *
     * @param string $str The input string containing whitespaces.
     * @return string The string with all whitespaces removed.
     *
     * @throws InvalidArgumentException If the input string is not a string.
     */
    function stripAllWhitespace(string $str): string
    {
        if (!is_string($str)) {
            throw new InvalidArgumentException('Input must be a string.');
        }

        return preg_replace('/\s+/', '', $str);
    }

    /**
     * Truncates a string to a specified length without cutting a word off.
     *
     * This function ensures the truncated string ends with a whole word. If 
     * the string is shorter than the specified length, it returns the original string.
     *
     * @param string $string The string to truncate.
     * @param int $maxLength The maximum length of the truncated string.
     * @param string $ending (optional) The string to append to the end of the 
     * truncated string (default: "...").
     * @return string The truncated string.
     *
     * @throws InvalidArgumentException If the input string or maximum length is not valid.
     */
    function truncateStringWithoutCuttingWord(string $string, int $maxLength, string $ending = "..."): string
    {
    if (!is_string($string) || !is_int($maxLength) || $maxLength <= 0) {
        throw new InvalidArgumentException('Invalid input: string or maximum length must be a string and positive integer, respectively.');
    }

    if (strlen($string) <= $maxLength) {
        return $string; // String is already shorter or equal to max length
    }

    // Find the last space within the allowed length
    $lastSpace = strrpos(substr($string, 0, $maxLength), ' ');

    if ($lastSpace !== false) {
        // Truncate at the last space and append the ending
        return substr($string, 0, $lastSpace) . $ending;
    } else {
        // No space found within the limit, truncate at max length
        return substr($string, 0, $maxLength) . $ending;
    }
    }

}

?>