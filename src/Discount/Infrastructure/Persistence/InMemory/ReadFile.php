<?php

namespace Kata\Discount\Infrastructure\Persistence\InMemory;

class ReadFile
{
    public function content(string $file): array
    {
        $file = sprintf('%s/%s', __DIR__, $file);

        if (!$this->guardFileExists($file)) {
            return [];
        }

        $contentFile = file_get_contents($file);

        if (!$contentFile) {
            return [];
        }

        return json_decode($contentFile, true);
    }

    private function guardFileExists(string $file): bool
    {
        return file_exists($file);
    }
}
