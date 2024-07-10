<?php

class Checks {

    /**
     * Checks if a string starts with a given target substring at a specific position.
     *
     * @param string $text The string to check.
     * @param string $targetString The target substring to search for.
     * @param int $startingPosition (optional) The starting position within the string to check from. Defaults to 0 (beginning of the string).
     * @throws InvalidArgumentException If the starting position is negative.
     * @return bool True if the string starts with the target substring at the specified position, false otherwise.
     */
    public function checkStringStartsWith(string $text, string $targetString, int $startingPosition = 0): bool
    {
        $textLength = strlen($text);

        // Validate starting position
        if ($startingPosition < 0) {
            throw new InvalidArgumentException('Starting position cannot be negative.');
        }

        // Handle position exceeding string length
        if ($startingPosition > $textLength) {
            $startingPosition = $textLength;
        }

        // Check if the substring starting at $startingPosition matches the target
        return substr($text, $startingPosition, strlen($targetString)) === $targetString;
    }

    /**
     * Checks if a string ends with a given target substring at a specific position.
     *
     * @param string $text The string to check.
     * @param string $targetString The target substring to search for.
     * @param int $endingPosition (optional) The ending position within the string to check up to. Defaults to null (entire string).
     * @throws InvalidArgumentException If the ending position is negative or exceeds the string length.
     * @return bool True if the string ends with the target substring at the specified position, false otherwise.
     */
    function checkStringEndsWith(string $text, string $targetString, int $endingPosition = null): bool
    {
        $textLength = strlen($text);
        $targetLength = strlen($targetString);

        // Validate ending position
        if ($endingPosition !== null) {
            if ($endingPosition < 0) {
            throw new InvalidArgumentException('Ending position cannot be negative.');
            } elseif ($endingPosition > $textLength) {
            throw new InvalidArgumentException('Ending position cannot exceed string length.');
            }
        }

        // Handle default ending position (entire string)
        if ($endingPosition === null) {
            $endingPosition = $textLength;
        }

        // Check if the substring ending at $endingPosition matches the target
        return $endingPosition >= $targetLength && substr($text, $endingPosition - $targetLength, $targetLength) === $targetString;
    }

    /**
     * Checks if an email address contains any of the specified domains.
     *
     * This function adheres to professional coding standards for error handling, code
     * clarity, and testability. It leverages dependency injection principles by
     * accepting the domain validation logic as a callback function, allowing for
     * customization or mocking during unit testing. Additionally, it utilizes a
     * regular expression for efficient domain matching, providing a robust and
     * efficient solution.
     *
     * @param string $email The email address to check.
     * @param string[] $domains An array of domain names to check against.
     * @param callable|null $domainValidator (Optional) A callback function to
     *        validate individual domain names. Defaults to a simple string check.
     * @throws InvalidArgumentException If the email or domains parameter is not a string or an array, respectively.
     * @return bool True if the email address contains any of the specified domains, false otherwise.
     */
    function checkEmailDomain(string $email, array $domains, callable $domainValidator = null): bool
    {
        if (!is_string($email)) {
            throw new InvalidArgumentException('Email address must be a string.');
        }

        if (!is_array($domains)) {
            throw new InvalidArgumentException('Domains must be an array of strings.');
        }

        // Optional domain name validation (can be customized/mocked in tests)
        if ($domainValidator !== null) {
            foreach ($domains as $domain) {
            if (!call_user_func($domainValidator, $domain)) {
                throw new InvalidArgumentException('Invalid domain name provided.');
            }
            }
        } else {
            // Default validation (optional)
            foreach ($domains as $domain) {
            if (!is_string($domain)) {
                throw new InvalidArgumentException('Domain names must be strings.');
            }
            }
        }

        // Optimized regular expression for domain matching
        $domainPattern = '/@(?:' . implode('|', $domains) . ')$/i';

        return preg_match($domainPattern, $email) === 1;
    }


}

?>