<?php
namespace App\Core;

class EventDispatcher
{
    protected array $listeners = [];

    public function listen(string $event, callable $listener): void
    {
        $this->listeners[$event][] = $listener;
    }

    public function dispatch(string $event, $payload = null): void
    {
        if (!empty($this->listeners[$event])) {
            foreach ($this->listeners[$event] as $listener) {
                $listener($payload);
            }
        }
    }
}
