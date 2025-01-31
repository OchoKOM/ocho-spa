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
                    // Split the line by whitespace and filter out empty elements
                    $parts = array_filter(preg_split('/\s+/', $line));
                    $parts = array_values($parts); // Re-index the array
                    if (isset($parts[1])) {
                        $ip = trim($parts[1]);
                        $ipAddresses[] = explode('/', $ip)[0]; // Remove subnet mask
                    }
                }
            }

            // If no IPs found, try 'ifconfig' (older Linux/macOS)
            if (empty($ipAddresses)) {
                exec('ifconfig', $output);
                foreach ($output as $line) {
                    if (strpos($line, 'inet ') !== false && strpos($line, '127.0.0.1') === false) {
                        $parts = array_filter(preg_split('/\s+/', $line));
                        $parts = array_values($parts); // Re-index the array
                        if (isset($parts[1])) {
                            $ip = trim($parts[1]);
                            $ipAddresses[] = explode('/', $ip)[0]; // Remove subnet mask
                        }
                    }
                }
            }
        }
    }

    return $ipAddresses;
}

// Get all IP addresses
$ipAddresses = getAllIPAddresses();

// Get the server port
$serverPort = $_SERVER['SERVER_PORT'];

$serverPort = $serverPort !== 80 ? ":$serverPort" : "";

// Print the results
if (!empty($ipAddresses)) {
    echo "Network Addresses: <br>\n";
    foreach ($ipAddresses as $ip) {
        echo "<br>\n <a href=\"http://$ip$serverPort\" class=\"button\">$ip$serverPort</a>\n";
    }
} else {
    echo "No network adress.\n";
}
?>