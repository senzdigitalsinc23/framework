<?php
namespace App\Core;

class Queue
{
    protected string $path;

    public function __construct(string $path)
    {
        $this->path = rtrim($path, '/');
        if (!is_dir($this->path)) {
            mkdir($this->path, 0777, true);
        }
    }

    public function push(Job $job): void
    {
        $filename = $this->path . '/' . uniqid('job_', true) . '.job';
        file_put_contents($filename, serialize($job));
    }

    public function work(): void
    {
        foreach (glob($this->path . '/*.job') as $file) {
            $job = unserialize(file_get_contents($file));
            if ($job instanceof Job) {
                $job->handle();
            }
            unlink($file);
        }
    }
}
