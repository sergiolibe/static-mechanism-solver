<?php
declare(strict_types=1);

namespace SMSolver\StaticSystem;


use SMSolver\Utils\FileUtils;

class StaticSystemRepository
{
    private static ?self $instance = null;
    private string $folder = __DIR__ . '/../../static_system_files/';

    private function __construct()
    {
    }

    public static function getInstance(): self
    {
        if (is_null(self::$instance))
            self::$instance = new self();

        return self::$instance;
    }

    /**
     * @return string[]
     */
    public function getAll(): array
    {
        $files = FileUtils::getFilesInMatchingExpressionDirectory($this->folder, '/.\.json$/');

        foreach ($files as &$fileName)
            $fileName = FileUtils::removeExtension($fileName);

        return array_values($files);
    }

    /**
     * @param string $systemName
     * @return array
     */
    public function getByName(string $systemName): array
    {
        return FileUtils::getContentOfJsonFileAsArray($this->folder . '/' . $systemName . '.json');
    }

    /**
     * @param array<string,scalar> $systemData
     * @param string $systemName
     * @return bool
     */
    public function saveStaticSystem(array $systemData, string $systemName): bool
    {
        return FileUtils::putContentOnFile($this->folder . '/' . $systemName . '.json', json_encode($systemData));
    }
}
