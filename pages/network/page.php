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

?>

<div class="logo">
    <svg class="logo-svg" width="100" height="100"
        viewBox="0 0 328 326.6">
        <g>
            <path fill="#EEF5FC"
                d="M160.2,110.2h0.7c14,0,28.8,0,42.8,0c0,1.3,0,2,0,3.3c0.7,0.7,1.3,1.3,2,2c18.7,18.4,36.8,36.9,55.6,56
            c-19.4,18.4-38.2,36.9-57.6,55.3c0,1.3,0,2.6,0,3.9H189c-0.7,0-0.7,0-0.7,0.7c0-0.7-0.7-0.7-0.7-0.7h-4.7c-18.1,0-36.1,0-54.2,0
            c0-0.7,0-1.3,0-2c-1.3-1.3-2.6-2.6-3.4-3.9c-18.7-18.4-36.8-36.9-55.6-56c19.4-19.1,39.5-38.2,58.9-56.6c0-0.7,0-2,0-2.6h21.5
            C153.5,110.2,156.9,110.2,160.2,110.2z" />
        </g>
        <g>
            <path
                d="M189.4,147.2c3.9,0,7.2,0,11.2,0c2.6,7.9,5.3,16.5,7.9,25c2.6-8.6,5.3-16.5,7.9-25c3.3,0,7.2-0.7,10.5,0
            c1.3,13.8,3.3,28.3,5.3,42.1h-8.6c-1.3-9.9-2.6-19.7-3.9-29.6c-3.3,9.9-5.9,19.7-9.2,29.6h-4.6c-3.3-9.9-5.9-19.7-9.2-29.6
            c-1.3,9.9-2.6,19.7-3.3,29.6h-8.6C186.1,174.8,187.4,161,189.4,147.2z" />
            <path fill="none" stroke="#000000" stroke-width="8" stroke-miterlimit="10"
                d="M145.9,156.4c2-2,5.3-3.9,9.2-4.6c4.6-0.7,8.6,0.7,11.2,2c2.6,2,5.3,4.6,7.2,9.2c2,6.6,0.7,11.8-3.3,17.1
            c-2.6,2.6-5.9,4.6-8.6,5.3c-3.3,0.7-6.6,0.7-9.9-0.7c-3.9-2-6.6-4.6-8.6-7.2c-1.3-2.6-2-6.6-2-11.2
            C142,161.7,144,158.4,145.9,156.4z" />
            <path
                d="M100.5,147.2h9.2c0,5.9,0,11.8,0,17.8c4.6-5.9,9.2-11.8,13.8-17.8c3.9,0,7.2,0,11.2,0c-5.3,6.6-10.5,13.2-15.8,20.4
            c5.3,7.2,10.5,14.5,16.5,21.7c-3.9,0-7.2,0-11.2,0c-4.6-6.6-9.9-13.2-14.5-19.1c0,6.6,0,13.2,0,19.7h-9.2
            C100.5,190,100.5,147.2,100.5,147.2z" />
        </g>
        <g>
            <path fill="#04A9FB"
                d="M203.2,231.4v-1.3c0-1.3,0-2.6,0-3.9c19.1-18.4,37.5-36.9,56.6-55.3c-17.8-18.4-36.2-37.5-54.6-56
            c-0.7-0.7-1.3-1.3-2-2c0-1.3,0-2,0-3.3c0-19.7,0-39.5,0-59.2c37.5,38.2,74.4,76.4,111.9,114.5c2,2.6,4.6,4.6,6.6,7.2
            c-39.5,38.8-79,77.7-118.5,116.5C203.2,269,203.2,249.9,203.2,231.4z M128.8,51.1v58.6c0,0.7,0,2,0,2.6
            c-19.1,19.1-38.8,38.2-57.9,56.6c17.8,18.4,36.2,37.5,54.6,56c1.3,1.3,2,2.6,3.3,3.9c0,0.7,0,1.3,0,2c0,20.4,0,40.8,0,61.2L9,168.2
            c1.3-2,3.3-3.9,5.3-5.3c3.3-2.6,5.9-5.3,9.2-8.6c1.3-1.3,3.3-3.3,4.6-4.6c3.3-2.6,6.6-5.9,9.2-9.2c1.3-1.3,3.3-3.3,4.6-4.6
            c3.3-2.6,6.6-5.9,9.2-9.2c1.3-1.3,3.3-3.3,4.6-4.6c3.3-2.6,6.6-5.9,9.2-9.2c1.3-1.3,3.3-3.3,4.6-4.6c3.3-2.6,6.6-5.9,9.2-9.2
            c1.3-1.3,3.3-3.3,4.6-4.6c3.3-2.6,6.6-5.9,9.2-9.2c1.3-1.3,3.3-3.3,4.6-4.6c3.3-2.6,5.9-5.3,9.2-8.6c1.3-1.3,3.3-3.3,4.6-4.6
            c2-1.3,3.3-3.3,5.3-4.6L128.8,51.1z" />
        </g>
    </svg>
    <?php
        // Print the results
    if (!empty($ipAddresses)) {
        ?>
        Network Addresses: <br>
        <?php
        foreach ($ipAddresses as $ip) {
            ?>
        <a href="http://<?=$ip.$serverPort?>" class="button"><?=$ip.$serverPort?></a>
        <?php
        }
    } else {
        echo "No network adress.";
    }
    ?>
</div>