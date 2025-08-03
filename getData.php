<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');
header('Access-Control-Allow-Origin: *');

try {
    $tmp = null;

    // Get memory information with error handling
    $memoryCmd = "free | grep 'Mem:' | awk {'print $2\" \"$3\" \"$4\" \"$6'}";
    $memoryOutput = exec($memoryCmd);
    if (!$memoryOutput) {
        throw new Exception("Failed to get memory information");
    }
    
    $memoryValues = explode(" ", $memoryOutput);
    if (count($memoryValues) < 4) {
        throw new Exception("Invalid memory data format");
    }

    // Get CPU detail information
    $cpuDetailCmd = "sed -n 's/^cpu\s//p' /proc/stat";
    $cpuDetail = trim(exec($cpuDetailCmd));
    if (!$cpuDetail) {
        throw new Exception("Failed to get CPU detail information");
    }

    // Get storage information with error handling
    $totalSpace = disk_total_space("/");
    $freeSpace = disk_free_space("/");
    if ($totalSpace === false || $freeSpace === false) {
        throw new Exception("Failed to get storage information");
    }

    // Get network information with fallback
    $networkCmd = "cat /proc/net/dev | grep -E '(eth0|enp|ens|wlan0):' | head -1 | awk {'print $2\" \"$3\" \"$10\" \"$11'}";
    $networkOutput = exec($networkCmd);
    if (!$networkOutput) {
        // Fallback to any network interface
        $networkCmd = "cat /proc/net/dev | grep ':' | grep -v 'lo:' | head -1 | awk {'print $2\" \"$3\" \"$10\" \"$11'}";
        $networkOutput = exec($networkCmd);
    }
    
    $networkValues = $networkOutput ? explode(" ", $networkOutput) : [0, 0, 0, 0];

    // Get uptime
    $uptimeCmd = "cut -d. -f1 /proc/uptime";
    $uptime = (int)exec($uptimeCmd);
    if (!$uptime) {
        $uptime = 0;
    }

    // Get OS information
    $osCmd = "cat /etc/*-release | grep 'PRETTY_NAME' | cut -d \\\" -f2";
    $os = exec($osCmd);
    if (!$os) {
        $os = "Unknown Linux Distribution";
    }

    // Build response data
    $data = array(
        "memory" => array_map(
            function($value) {
                return (float)($value / 1000000); // Convert to GB
            },
            $memoryValues
        ),
        "CPUDetail" => $cpuDetail,
        "CPU" => array(),
        "storage" => array(
            "total" => round($totalSpace / 1000000000, 2), 
            "free" => round($freeSpace / 1000000000, 2), 
            "used" => round(($totalSpace - $freeSpace) / 1000000000, 2)
        ),
        "network" => array_map('intval', $networkValues),
        "uptime" => $uptime,
        "OS" => $os,
        "timestamp" => time(),
        "status" => "success"
    );

    // Get CPU information
    exec("cat /proc/cpuinfo | grep -i 'model name\|cpu cores\|cpu mhz'", $tmp);

    if ($tmp) {
        foreach($tmp as $line) {
            $parts = explode(":", $line, 2);
            if (count($parts) === 2) {
                $data["CPU"][] = array(trim($parts[0]), trim($parts[1]));
            }
        }
    }

    // Add system load average
    $loadAvgCmd = "cat /proc/loadavg | cut -d' ' -f1-3";
    $loadAvg = exec($loadAvgCmd);
    if ($loadAvg) {
        $data["loadAverage"] = explode(" ", $loadAvg);
    }

    echo json_encode($data, JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(array(
        "status" => "error",
        "message" => $e->getMessage(),
        "timestamp" => time()
    ), JSON_PRETTY_PRINT);
} catch (Error $e) {
    http_response_code(500);
    echo json_encode(array(
        "status" => "error",
        "message" => "System error occurred",
        "timestamp" => time()
    ), JSON_PRETTY_PRINT);
}
?>
