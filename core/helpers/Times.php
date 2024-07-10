<?php

class Times {
  /**
   * Gets the current date and time in a formatted string.
   *
   * This function utilizes the `date` function with various format specifiers
   * to provide flexibility in output format.
   *
   * @param string $format (optional) The desired date and time format.
   *                        Defaults to "Y-m-d H:i:s" (YYYY-MM-DD HH:MM:SS).
   * @return string The formatted date and time string.
   */
  public function getCurrentDateTime(string $format = 'Y-m-d H:i:s'): string
  {
    return date($format);
  }

  /**
   * Calculates the time difference between a given date and the current date
   * and returns a user-friendly string representation.
   *
   * @param string $dateString The date string in a format supported by strtotime (e.g., "2024-06-12 14:32:00").
   * @return string A user-friendly string representing the time difference (e.g., "1 second ago", "2 hours ago").
   * @throws Exception If the provided date string cannot be parsed by strtotime.
   */
  public function timeAgo($dateString): string
  {
    $timestamp = strtotime($dateString);
    if (!$timestamp) {
      throw new Exception("Invalid date format: $dateString");
    }
  
    $diff = time() - $timestamp;
  
    if ($diff < 1) {
      return 'just now';
    }
  
    $timeUnits = [
      31536000 => 'year',
      2592000 => 'month',
      604800 => 'week',
      86400 => 'day',
      3600 => 'hour',
      60 => 'minute',
      1 => 'second',
    ];
  
    foreach ($timeUnits as $seconds => $unit) {
      $count = floor($diff / $seconds);
      if ($count > 0) {
        return $count . ' ' . $unit . ($count > 1 ? 's' : '') . ' ago';
      }
    }
  
    return 'just now';
  }
}

?>
