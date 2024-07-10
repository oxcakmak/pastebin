<?php

class Generators {

    /**
     * Generates a random strong password with specified character sets and length.
     *
     * @param int $minLength (optional) Minimum password length (defaults to 20).
     * @param int $maxLength (optional) Maximum password length (defaults to 20). Enforces minimum if greater.
     * @param bool $useLowercase (optional) Whether to include lowercase letters (defaults to true).
     * @param bool $useUppercase (optional) Whether to include uppercase letters (defaults to true).
     * @param bool $useNumbers (optional) Whether to include numbers (defaults to true).
     * @param bool $useSymbols (optional) Whether to include special symbols (defaults to false).
     * @throws InvalidArgumentException If the minimum length is greater than the maximum length.
     * @return string The generated random password.
     */
    function generateStrongPassword(int $minLength = 20, int $maxLength = 20, bool $useLowercase = true, bool $useUppercase = true, bool $useNumbers = true, bool $useSymbols = false): string
    {
        if ($minLength > $maxLength) {
            throw new InvalidArgumentException('Minimum length cannot be greater than maximum length.');
        }

        $characterSets = [];
        if ($useLowercase) {
            $characterSets[] = 'abcdefghijklmnopqrstuvwxyz';
        }
        if ($useUppercase) {
            $characterSets[] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        if ($useNumbers) {
            $characterSets[] = '1234567890';
        }
        if ($useSymbols) {
            $characterSets[] = "~@#$%^*()_+-={}|[]\."; // Added dot (".") as a potential symbol
        }

        if (empty($characterSets)) {
            throw new InvalidArgumentException('At least one character set must be chosen.');
        }

        $length = mt_rand($minLength, $maxLength);
        $combinedChars = implode('', $characterSets);
        $charsLength = strlen($combinedChars);

        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $combinedChars[mt_rand(0, $charsLength - 1)];
        }

        // Shuffle the password characters for better randomness (optional)
        $passwordChars = str_split($password);
        shuffle($passwordChars);
        $password = implode('', $passwordChars);

        return $password;
    }

    /**
     * Generates a random string of a specified length containing letters and numbers.
     *
     * @param int $length The desired length of the random string.
     * @throws InvalidArgumentException If the length is less than 1.
     * @return string The generated random string.
     */
    function generateRandomString(int $length): string
    {
        if ($length < 1) {
            throw new InvalidArgumentException('String length must be at least 1.');
        }

        // Combine all character sets
        $allChars = implode('', array_merge(range(0, 9), range('a', 'z'), range('A', 'Z')));

        // Generate random string using more efficient character selection
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $allChars[mt_rand(0, strlen($allChars) - 1)];
        }

        return $randomString;
    }

    /**
     * Generates a random hexadecimal color code.
     *
     * @param bool $uppercase (optional) Whether to generate the color code in uppercase (default: false).
     * @return string The generated random hex color code with a leading # symbol.
     * @throws InvalidArgumentException If the uppercase parameter is not a boolean.
     */
    function generateRandomHexColor(bool $uppercase = false): string
    {
        if (!is_bool($uppercase)) {
            throw new InvalidArgumentException('Uppercase parameter must be a boolean.');
        }

        $randomInt = mt_rand(0, 0xFFFFFF);
        $hexString = sprintf('%06X', $randomInt);

        return '#' . ($uppercase ? strtoupper($hexString) : $hexString);
    }

    /**
     * Generates a random number within a specified range, optionally allowing floating-point values.
     *
     * @param int|float $lowerBound (optional) The lower bound of the range (defaults to 0).
     * @param int|float $upperBound (optional) The upper bound of the range (defaults to the value of $lowerBound).
     * @param bool $allowFloat (optional) Whether to allow floating-point values (defaults to false).
     * @throws InvalidArgumentException If the lower bound is greater than the upper bound.
     * @return int|float A random number within the specified range.
     */
    function generateRandomNumber(float $lowerBound = 0.0, float $upperBound = null, bool $allowFloat = false): float
    {
        // Handle default and invalid parameters
        if ($upperBound === null) {
            $upperBound = $lowerBound;
            $lowerBound = 0.0;
        } elseif ($lowerBound > $upperBound) {
            throw new InvalidArgumentException('Lower bound cannot be greater than upper bound.');
        }

        // Determine floating-point requirement
        $allowFloat = $allowFloat || (is_float($lowerBound) || is_float($upperBound));

        // Generate random number based on type requirement
        if ($allowFloat || $lowerBound != round($lowerBound, 0) || $upperBound != round($upperBound, 0)) {
            $range = abs($upperBound - $lowerBound);
            return $lowerBound + $range * mt_rand(0, mt_getrandmax()) / mt_getrandmax();
        } else {
            return rand((int) $lowerBound, (int) $upperBound);
        }
    }

   /**
     * Generates a simple 20-character ID consisting of numbers, starting with 1.
     *
     * This function is suitable for scenarios where a unique but non-cryptographically
     * secure identifier is needed. For more robust unique identifiers, consider
     * using UUIDs or database-generated sequences.
     *
     * @return string The generated 20-character ID.
     * @throws Exception If random number generation fails.
     */
    function generateSimpleId(): string
    {
      $id = '';
      for ($i = 0; $i < 20; $i++) {
        try {
          // Generate a random digit (1-9) using a secure method
          $digit = random_int(1, 9);
        } catch (UnexpectedValueException $e) {
          throw new Exception("Failed to generate random digit: " . $e->getMessage());
        }
        $id .= (string) $digit;
      }
    
      return '1' . $id; // Prepend '1' to ensure it starts with 1
    }

    /**
     * Generates a simple 32-character string resembling a version 4 UUID.
     *
     * This function utilizes random_bytes and formatting to create a string
     * with the same structure as a version 4 UUID. However, it does not guarantee
     * cryptographic randomness. Consider libraries like Ramsey/uuid for stronger
     * security needs.
     *
     * @return string The generated 32-character string resembling a version 4 UUID.
     * @throws Exception If random byte generation fails.
     */
    function generateSimpleUuid(): string
    {
      try {
        $bytes = random_bytes(16); // Generate 16 random bytes
      } catch (UnexpectedValueException $e) {
        throw new Exception("Failed to generate random bytes: " . $e->getMessage());
      }
    
      // Format the bytes into the desired UUID structure (version 4)
      $hex = bin2hex($bytes);
      $uuid = sprintf('%08s-%04s-%04s-%04s-%012s',
        substr($hex, 0, 8),
        substr($hex, 8, 4),
        substr($hex, 12, 4),
        substr($hex, 16, 4),
        substr($hex, 20)
      );
    
      // Convert to uppercase for consistency with version 4 format
      return $uuid;
    }

    /**
     * Generates the next code in the sequence based on the provided input code,
     * handling characters from 0 to 9 and a to z only.
     *
     * @param string $currentCode The current code in the sequence.
     * @return string The next code in the sequence.
     * @throws Exception If an invalid character is found in the current code.
     */
    function generateNextCode(string $currentCode): string
    {
    for ($i = strlen($currentCode) - 1; $i >= 0; $i--) {
        $char = $currentCode[$i];

        switch (true) {
        case ctype_digit($char):
            $nextChar = ($char === '9') ? 'a' : chr(ord($char) + 1);
            break;
        case ctype_lower($char):
            $nextChar = ($char === 'z') ? null : chr(ord($char) + 1); // Don't increment 'z'
            break;
        default:
            throw new Exception("Invalid character '{$char}' in the code.");
        }

        // Prepend next character if valid
        if (isset($nextChar)) {
        $currentCode = substr($currentCode, 0, $i) . $nextChar . substr($currentCode, $i + 1);
        return $currentCode; // Early return on successful increment
        }
    }

    // If no characters were incremented, return the original code
    return $currentCode;
    }
    
}

?>