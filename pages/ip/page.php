<?php
// Function to get all IP addresses based on the OS
function getAllIPAddresses() {
    $os = strtoupper(substr(PHP_OS, 0, 3));
    $ipAddresses = [];

    if ($os === 'WIN') {
        // Windows: Use ipconfig
        exec('ipconfig', $output);
        foreach ($output as $line) {
            if (strpos($line, 'IPv4') !== false) {
                $parts = explode(':', $line);
                if (isset($parts[1])) {
                    $ip = trim($parts[1]);
                    $ipAddresses[] = $ip;
                }
            }
        }
    } else {
        // Linux/macOS: Use ifconfig or ip addr
        if ($os === 'LIN' || $os === 'DAR') { // LIN = Linux, DAR = macOS (Darwin)
            // Try 'ip addr' first (modern Linux)
            exec('ip addr', $output);
            foreach ($output as $line) {
                if (strpos($line, 'inet ') !== false && strpos($line, '127.0.0.1') === false) {
                    $parts = preg_split('/\s+/', $line);
                    $ip = trim($parts[1]);
                    $ipAddresses[] = explode('/', $ip)[0]; // Remove subnet mask
                }
            }

            // If no IPs found, try 'ifconfig' (older Linux/macOS)
            if (empty($ipAddresses)) {
                exec('ifconfig', $output);
                foreach ($output as $line) {
                    if (strpos($line, 'inet ') !== false && strpos($line, '127.0.0.1') === false) {
                        $parts = preg_split('/\s+/', $line);
                        $ip = trim($parts[1]);
                        $ipAddresses[] = $ip;
                    }
                }
            }
        }
    }

    return $ipAddresses;
}

// Get all IP addresses
$ipAddresses = getAllIPAddresses();

// Print the results
if (!empty($ipAddresses)) {
    echo "All IP Addresses found:\n";
    foreach ($ipAddresses as $ip) {
        echo "<br> $ip\n";
    }
} else {
    echo "No IP addresses found.\n";
}
?>