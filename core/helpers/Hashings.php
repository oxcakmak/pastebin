<?php

class Hashings {

    /**
     * Hashes a password securely using a strong one-way hashing algorithm.
     *
     * This function adheres to industry best practices for secure password hashing.
     * It utilizes the built-in `password_hash` function with the recommended
     * `PASSWORD_DEFAULT` algorithm or a specified algorithm for compatibility
     * with older systems. It emphasizes security by generating a random salt for
     * each password, mitigating rainbow table attacks.
     *
     * @param string $password The password to be hashed.
     * @param int $cost (Optional) The cost factor for the algorithm. A higher cost
     *        indicates slower hashing but better security. Defaults to `PASSWORD_DEFAULT`.
     * @return string The hashed password.
     * @throws InvalidArgumentException If the password is not a string.
     */
    function hashPasswordSecure(string $password, int $cost = PASSWORD_DEFAULT): string
    {
        if (!is_string($password)) {
            throw new InvalidArgumentException('Invalid password provided. Expecting a string.');
        }

        return password_hash($password, $cost);
    }

    /**
     * Verifies a password against a stored hash securely.
     *
     * This function employs the `password_verify` function to compare the provided
     * password with the stored hash, adhering to timing-safe practices to prevent
     * side-channel attacks. It returns `true` if they match, indicating a successful
     * login attempt.
     *
     * @param string $password The password entered by the user.
     * @param string $storedHash The previously hashed password stored in the database.
     * @return bool True if the password matches the stored hash, false otherwise.
     */
    function verifyPasswordSecure(string $password, string $storedHash): bool
    {
        return password_verify($password, $storedHash);
    }

    /**
     * Checks if a string resembles a valid hash format.
     *
     * This function employs regular expressions to assess if the provided input string
     * adheres to a common hash format. However, it cannot definitively confirm the hash
     * type or guarantee its content's validity. It's recommended to utilize appropriate
     * password hashing functions (e.g., password_hash) for secure password storage
     * and verification.
     *
     * @param string $input The string to be evaluated as a potential hash.
     * @param array $supportedAlgorithms (Optional) An array of supported hash algorithms
     *        represented by their expected output lengths (e.g., ['md5' => 32, 'sha256' => 64]).
     *        Defaults to a common set of algorithms.
     * @return bool True if the input appears to be a valid hash based on provided criteria,
     *              false otherwise.
     * @throws InvalidArgumentException If the input is not a string.
     */
    function isPotentialHash(string $input, array $supportedAlgorithms = ['md5' => 32, 'sha256' => 64, 'sha1' => 40]): bool
    {
        if (!is_string($input)) {
            throw new InvalidArgumentException('Invalid input provided. Expecting a string.');
        }

        // Build the regular expression pattern dynamically based on supported algorithms
        $pattern = '/^[a-f0-9]{' . implode('}|[a-f0-9]{', array_values($supportedAlgorithms)) . '}$/';

        // Check if the input matches the pattern and has a minimum length
        return preg_match($pattern, $input) === 1 && strlen($input) >= min(array_values($supportedAlgorithms));
    }
    
    /**
     * Generates a secure hash of a string using a recommended hashing algorithm.
     *
     * @param string $string The string to be hashed.
     * @param string $algorithm (optional) The hashing algorithm to use (defaults to sha256).
     * @throws RuntimeException If the specified hashing algorithm is not supported.
     * @return string The generated hash.
     */
    function generateSecureHash(string $string, string $algorithm = 'sha256'): string
    {
        if (!in_array($algorithm, hash_algos())) {
            throw new RuntimeException("Unsupported hashing algorithm: $algorithm");
        }

        return hash($algorithm, $string);
    }

}

?>