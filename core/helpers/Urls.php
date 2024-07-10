<?php

class Urls {

    

    /**
     * Generates a URL slug from a string, replacing special characters and converting to lowercase.
     *
     * @param string $string The string to convert into a slug.
     * @return string The generated URL slug.
     */
    function createSlug(string $text): string
    {
        // Define replacement characters for Turkish characters (modify for other languages)
        $turkishChars = [
            'ş' => 's', 'Ş' => 's',
            'ı' => 'i', 'I' => 'i', 'İ' => 'i',
            'ğ' => 'g', 'Ğ' => 'g',
            'ü' => 'u', 'Ü' => 'u',
            'ö' => 'o', 'Ö' => 'o',
            'ç' => 'c', 'Ç' => 'c',
        ];

        // Combine replacements for Turkish characters and common punctuation
        $replacements = array_merge($turkishChars, [
            '(' => '', ')' => '', '/' => '-', ':' => '-', ',' => '-',
            '+' => '-', '#' => '-', '.' => '-', '_' => '-',
        ]);

        // Transliterate non-ASCII characters for broader support (optional)
        if (function_exists('mb_strtolower')) {
            $text = mb_strtolower(mb_convert_encoding($text, 'ASCII', mb_detect_encoding($text, 'UTF-8, ISO-8859-1')), 'UTF-8');
        } else {
            $text = strtolower($text); // Fallback for systems without mb_ functions
        }

        // Remove remaining unwanted characters and convert spaces to hyphens
        $slug = trim(preg_replace('/[^\w\-]+/', '-', str_replace(array_keys($replacements), array_values($replacements), $text)));

        return $slug;
    }

    /**
     * Parses a URL into its component parts.
     *
     * This function parses a given URL string and returns an associative array containing
     * various components like scheme, host, port, path, query, and fragment.
     *
     * @param string $url The URL string to parse.
     * @return array An associative array containing the parsed URL components.
     * @throws InvalidArgumentException If the provided URL is not a valid string.
     */
    function parseUrl(string $url): array
    {
        if (!is_string($url)) {
            throw new InvalidArgumentException('Invalid URL provided. Expecting a string.');
        }

        // Parse the URL and handle potential missing components gracefully
        $parts = array_merge([
            'scheme' => null,
            'host' => null,
            'port' => null,
            'user' => null,
            'pass' => null,
            'path' => null,
            'query' => null,
            'fragment' => null,
        ], parse_url($url));

        // Decode URL-encoded parts (optional)
        if (!empty($parts['path'])) {
            $parts['path'] = urldecode($parts['path']);
        }
        if (!empty($parts['query'])) {
            $parts['query'] = urldecode($parts['query']);
        }

        return $parts;
    }

}

?>