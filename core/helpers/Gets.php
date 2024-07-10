<?php

class Gets {
  /**
   * Retrieves the current user's IP address.
   *
   * This function attempts to retrieve the user's IP address using a prioritized order
   * of server variables:
   * 1. REMOTE_ADDR: This is the most common and reliable source for the user's IP.
   * 2. HTTP_CLIENT_IP: This is used for proxies that preserve the original client IP.
   * 3. HTTP_X_FORWARDED_FOR: This is used for load balancers that forward the client IP.
   *
   * It is important to note that user IP addresses can be spoofed, so consider this
   * information for informational purposes only and not for security-critical operations.
   *
   * @return string|null The user's IP address or null if not found.
   */
  public function getCurrentIpAddress(): ?string
  {
    if (isset($_SERVER['REMOTE_ADDR'])) {
      return $_SERVER['REMOTE_ADDR'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
      return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      // Parse multiple IPs from X-Forwarded-For (comma-separated)
      $forwardedIps = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
      return trim($forwardedIps[0]); // Return the first IP
    }
    // No IP address found
    return null; 
  }

  /**
   * Generates the next character in the sequence based on the provided input code.
   *
   * @param string $currentCode The current code in the sequence.
   * @return string The next code in the sequence.
   * @throws Exception If an invalid character is found in the current code.
   */
  function getNextCode($currentCode) {
    $characters = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
    $charCount = count($characters);

    // Split the current code into an array of characters
    $currentCodeArr = str_split($currentCode);
    $length = count($currentCodeArr);

    // Iterate from the last character
    for ($i = $length - 1; $i >= 0; $i--) {
      $index = array_search($currentCodeArr[$i], $characters);

      // Check for invalid characters
      if ($index === false) {
        throw new Exception("Invalid character found in the current code.");
      }

      // Increment character within the range
      if ($index < $charCount - 1) {
        $currentCodeArr[$i] = $characters[$index + 1];
        return implode('', $currentCodeArr);
      } else {
        // Reached 'Z', handle transition
        if ($currentCodeArr[$i] === 'Z') {
          // Add a new character 'a' if last character
          if ($i === 0) {
            return 'a' . implode('', $currentCodeArr);
          } else {
            // Reset current character, move to previous
            $currentCodeArr[$i] = $characters[0];
            // Update the next character (assuming it exists)
            $currentCodeArr[$i - 1] = $characters[$index + 1];
            return implode('', $currentCodeArr);
          }
        } else {
          // Reset character for non-Z cases (handled in previous iteration)
          $currentCodeArr[$i] = $characters[0];
        }
      }
    }
  }
}

?>