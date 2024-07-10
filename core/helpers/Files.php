<?php

class Files {

    /**
     * Converts a file size in bytes to a human-readable format (e.g., KB, MB, GB).
     *
     * This function adheres to professional coding standards and best practices for
     * error handling and resource management. It efficiently translates file sizes
     * into user-friendly strings with proper unit scaling and supports a comprehensive
     * range of units. Additionally, it throws a descriptive exception for invalid
     * input types or unsupported file size magnitudes.
     *
     * @param int $size The file size in bytes.
     * @param int $precision (Optional) The number of decimal places to round the
     *        resulting size to. Defaults to 2.
     * @return string The human-readable file size string.
     * @throws InvalidArgumentException If the provided file size is not a positive integer.
     * @throws OutOfRangeException If the file size exceeds the supported range.
     */
    function humanReadableFilesize(int $size, int $precision = 2): string
    {
        // Validate input type and handle negative values
        if (!is_int($size) || $size < 0) {
            throw new InvalidArgumentException('Invalid file size provided. Expecting a positive integer.');
        }

        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $maxUnitIndex = count($units) - 1;

        // Check if file size exceeds the supported range
        if ($size > pow(1024, $maxUnitIndex + 1)) {
            throw new OutOfRangeException('File size exceeds supported range.');
        }

        $i = 0;
        while ($size >= 1024 && $i < $maxUnitIndex) {
            $size /= 1024;
            $i++;
        }

        // Round the size to specified precision (optional)
        $formattedSize = number_format(round($size, $precision), $precision);

        return "$formattedSize {$units[$i]}"; // Use string interpolation for cleaner formatting
    }

}

?>