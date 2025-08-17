<?php
namespace App\CLI;

class MakeModel extends Command
{
    public function handle(array $args): void
    {
        $name = $args[0] ?? null;

        if (!$name) {
            $this->error("Please provide a model name.");
            return;
        }

        $filename = __DIR__ . "/../Models/{$name}.php";

        if (file_exists($filename)) {
            $this->error("Model {$name} already exists.");
            return;
        }

        $template = <<<PHP
        <?php
        namespace App\Models;

        use App\Core\ORM;

        class {$name} extends ORM
        {
            protected static string \$table = '{$this->toSnakeCase($name)}s';
            
            // Define your model properties and methods here
        }

        PHP;

        //show($filename);

        file_put_contents($filename, $template);
        $this->info("{$name} model created successfully.");
    }

    private function toSnakeCase(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    
}
