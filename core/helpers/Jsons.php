<?php

class Jsons {

    /**
     * Encodes a PHP variable to JSON format.
     *
     * This function rigorously encodes a PHP variable to a JSON string, adhering
     * to professional coding standards. It throws exceptions for encoding errors
     * and offers optional parameters for JSON formatting.
     *
     * @param mixed $data The variable to be encoded.
     * @param int $options (Optional) A bitmask of JSON encoding options.
     *        Defaults to 0 (JSON_NORMAL_REP).
     * @return string The encoded JSON string.
     * @throws JsonException If JSON encoding fails.
     */
    function encodeJson(mixed $data, int $options = 0): string
    {
        $encoded = json_encode($data, $options);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonException(json_last_error_msg());
        }
        return $encoded;
    }


    /**
     * Decodes a JSON string into a PHP variable.
     *
     * This function employs the `json_decode` function to convert a JSON string
     * back into a PHP variable. It includes error handling and an optional
     * parameter to specify the desired associative array format.
     *
     * @param string $json The JSON string to be decoded.
     * @param bool $assoc (Optional) Whether to return an associative array
     *        (true) or a stdClass object (false). Defaults to false.
     * @return mixed The decoded PHP variable or false on error.
     * @throws JsonException If JSON decoding fails.
     */
    function decodeJson(string $json, bool $assoc = false): mixed
    {
        $decoded = json_decode($json, $assoc);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonException(json_last_error_msg());
        }
        return $decoded;
    }

    /**
     * Validates the structure of a JSON string.
     *
     * This function utilizes `json_decode` with the JSON_ERROR_NONE flag to
     * check if the JSON string can be successfully decoded without generating any
     * errors. It doesn't validate the actual data content.
     *
     * @param string $json The JSON string to be validated.
     * @return bool True if the JSON structure is valid, false otherwise.
     */
    function validateJson(string $json): bool
    {
        return json_decode($json, true, 512, JSON_ERROR_NONE) !== null;
    }

    /**
     * Beautifies a JSON string for readability.
     *
     * This function leverages the `json_encode` function with the
     * JSON_PRETTY_PRINT flag to format the JSON string with proper indentation.
     *
     * @param string $json The JSON string to be beautified.
     * @return string The beautified JSON string.
     */
    function beautifyJson(string $json): string
    {
        return json_encode(json_decode($json), JSON_PRETTY_PRINT);
    }

    /**
     * Formats a JSON string into a well-presented, human-readable format.
     *
     * This function adheres to professional coding standards by employing strict
     * type hinting, detailed documentation, and robust input validation. It utilizes
     * the built-in `json_decode` and `json_encode` functions with the JSON_PRETTY_PRINT
     * flag to transform the JSON string into a well-indented and easy-to-understand
     * representation.
     *
     * @param string $jsonString The JSON string to be formatted.
     * @return string The formatted JSON string with proper indentation and spacing.
     * @throws InvalidArgumentException If the provided input is not a valid JSON string.
     */
    function formatJson(string $jsonString): string
    {
        // Validate input as a JSON string using a combination of is_string and json_decode
        if (!is_string($jsonString) || json_decode($jsonString) === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('The provided input is not a valid JSON string.');
        }

        // Decode and re-encode with JSON_PRETTY_PRINT for human-readable format
        $data = json_decode($jsonString);
        return json_encode($data, JSON_PRETTY_PRINT);
    }

    /**
     * Paginates an array of data and returns a secure and comprehensive JSON response
     * with pagination details.
     *
     * This function adheres to professional coding standards and prioritizes security
     * by sanitizing user-provided input for the base URL. It facilitates efficient
     * JSON pagination by allowing customization of the JSON response structure
     * through an optional `$responseData` array.
     *
     * @param array $data The array of data to be paginated.
     * @param int $currentPage The requested page number (starting from 1).
     * @param int $perPage The number of items to display per page.
     * @param string $baseUrl (Optional) The base URL for pagination links.
     *        **Sanitized before use to mitigate potential security risks.**
     * @param array $responseData (Optional) An array containing additional data to
     *        include in the JSON response. Merged with the default response structure.
     * @return string A JSON string representing the paginated data and pagination details.
     * @throws InvalidArgumentException If invalid arguments are provided.
     */
    function paginateToJsonSecure(array $data, int $currentPage, int $perPage, string $baseUrl = '', array $responseData = []): string
    {
        // Validate input arguments
        if (!is_array($data) || $currentPage < 1 || $perPage <= 0 || !is_string($baseUrl)) {
            throw new InvalidArgumentException('Invalid arguments provided.');
        }

        // Sanitize base URL (mitigate potential security risks)
        $baseUrl = filter_var($baseUrl, FILTER_SANITIZE_URL);

        $totalItems = count($data);
        $totalPages = (int) ceil($totalItems / $perPage);

        // Ensure requested page is within valid range
        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }

        $startIndex = ($currentPage - 1) * $perPage;
        $paginatedData = array_slice($data, $startIndex, $perPage);

        // Build the pagination links (optional)
        $paginationLinks = [];
        if ($baseUrl !== '') {
            if ($currentPage > 1) {
            $paginationLinks['prev'] = $baseUrl . '?page=' . ($currentPage - 1);
            }
            if ($currentPage < $totalPages) {
            $paginationLinks['next'] = $baseUrl . '?page=' . ($currentPage + 1);
            }
        }

        // Create the JSON response
        $defaultResponseData = [
            'data' => $paginatedData,
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'perPage' => $perPage,
        ];

        $responseData = array_merge($defaultResponseData, $responseData);

        return json_encode($responseData, JSON_PRETTY_PRINT); // Consider removing for production
    }

}

?>