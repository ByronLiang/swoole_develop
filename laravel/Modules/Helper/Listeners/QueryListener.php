<?php

namespace Modules\Helper\Listeners;

use Illuminate\Database\Events\QueryExecuted;

class QueryListener
{
    private static $instance;

    public static function getInstance(): self
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private $printSqlLogEnv;

    private $ms = [];

    private $logs = [];

    private $logPath;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        $this->printSqlLogEnv = config('app.log_sql');

        $this->logPath = storage_path('logs/sql_'.date('Y-m-d').'.log');
    }

    /**
     * Handle the event.
     *
     * @param QueryExecuted $event
     */
    public function handle(QueryExecuted $event)
    {
        if ($this->printSqlLogEnv) {
            static::getInstance()->genLog($event);
        }
    }

    public function genLog(QueryExecuted $event)
    {
        $sql = $event->sql;
        foreach ($event->bindings as $val) {
            $sql = preg_replace('/\?/', $this->queryValueToString($val), $sql, 1);
        }

        $log = '['.count($this->ms).'] '.$sql.' ['.$event->time.'ms]';
//        $log .= "\n" . implode("\n", $this->debugBacktrace());

        $this->ms[] = $event->time;
        $this->logs[] = $log;
    }

    /**
     * 打印 sql 调用堆栈, 如非必要, 不要使用这个函数,影响性能.
     *
     * @param int $limit
     *
     * @return array
     */
    public function debugBacktrace($limit = 0)
    {
        $traces = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $limit);

        $traces = array_map(function ($i) {
            $str = '';

            $str .= isset($i['file']) ? str_replace(base_path(), '', $i['file']) : '';
            $str .= isset($i['line']) ? "({$i['line']}): " : '';
            $str .= isset($i['class']) ? $i['class'].'->' : '';
            $str .= $i['function'] ?? '';

            return $str;
        }, array_filter($traces, function ($i) {
            if (empty($i['file'])) {
                return true;
            }

            $filters_str = [
                'QueryListener',
                '/vendor/',
                '/laravel/ganguo/',
                'server.php',
            ];

            foreach ($filters_str as $v) {
                if (false !== strpos($i['file'], $v)) {
                    return false;
                }
            }

            return true;
        }));

        array_splice($traces, 0, 1);
        array_splice($traces, -1, 1);

        return $traces;
    }

    private function queryValueToString($val)
    {
        if (is_string($val)) {
            return "'{$val}'";
        } elseif (is_bool($val)) {
            return (string) (int) $val;
        } else {
            return (string) $val;
        }
    }

    private function writeLog($log)
    {
        if (true === $this->printSqlLogEnv || 'stderr' === $this->printSqlLogEnv) {
            error_log($log);
        }
        if (true === $this->printSqlLogEnv || 'log' === $this->printSqlLogEnv) {
            $file = new \SplFileObject($this->logPath, 'a');
            $file->fwrite($log."\n");
        }
    }

    public function __destruct()
    {
        if (count($this->ms)) {
            $path = PHP_SAPI != 'cli' ? '['.request()->path().']' : '';
            $this->logs[] = $path.'['.date('H:i:s').'] total time '.array_sum($this->ms).'ms';
            $this->writeLog(implode("\n", $this->logs)."\n");
        }
    }
}
