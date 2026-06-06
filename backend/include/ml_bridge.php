<?php
/**
 * Bridge helper to execute Machine Learning logic in Python.
 */
class MLBridge
{
    public static function runPython($task, $payload)
    {
        $pythonPath = "python";
        $scriptPath = dirname(__DIR__) . "/api/ml_service.py";

        if (!file_exists($scriptPath)) {
            return ['ok' => false, 'error' => 'Python ML script not found.'];
        }

        $input = json_encode([
            'task' => $task,
            'payload' => $payload
        ]);

        $descriptorspec = [
            0 => ["pipe", "r"], // stdin
            1 => ["pipe", "w"], // stdout
            2 => ["pipe", "w"]  // stderr
        ];

        // Bypass shell command parsing limits and avoid command injection
        $process = proc_open('"' . $pythonPath . '" "' . $scriptPath . '"', $descriptorspec, $pipes);

        if (is_resource($process)) {
            fwrite($pipes[0], $input);
            fclose($pipes[0]);

            $stdout = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $stderr = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            $returnValue = proc_close($process);

            if ($returnValue !== 0) {
                return [
                    'ok' => false,
                    'error' => 'Python exited with code ' . $returnValue,
                    'stderr' => $stderr
                ];
            }

            $response = json_decode($stdout, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return [
                    'ok' => false,
                    'error' => 'Invalid JSON returned from Python.',
                    'raw' => $stdout
                ];
            }

            return $response;
        }

        return ['ok' => false, 'error' => 'Failed to launch Python process.'];
    }
}
?>
