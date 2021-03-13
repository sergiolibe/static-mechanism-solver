<?php
declare(strict_types=1);

namespace SMSolver\Utils;


use JsonException;
use RuntimeException;

class FileUtils
{
    /**
     * @param string $directoryToScan
     * @param string $regExpression
     * @return string[]
     */
    public static function getFilesInMatchingExpressionDirectory(string $directoryToScan, string $regExpression): array
    {
        $filesInDirectory = self::getFilesInDirectory($directoryToScan);
        return array_filter($filesInDirectory,
            fn($fileName) => preg_match($regExpression, $fileName)
        );
    }

    /**
     * @param string $directoryToScan
     * @return string[]
     */
    public static function getFilesInDirectory(string $directoryToScan): array
    {
        $filesInDirectory = scandir($directoryToScan);
        return array_diff($filesInDirectory, ['.', '..']);
    }

    /**
     * @param string $fileName
     * @return string
     */
    public static function removeExtension(string $fileName): string
    {
        $dotPosition = strrpos($fileName, '.');
        return substr($fileName, 0, $dotPosition);
    }

    /**
     * @param string $fileName
     * @return string
     */
    public static function getContentOfFile(string $fileName): string
    {
        $data = @file_get_contents($fileName);
        if ($data === false)
            throw new RuntimeException('Failed to open file: ' . $fileName);
        return $data;
    }

    /**
     * @param string $fileName
     * @param string $data
     * @return bool
     */
    public static function putContentOnFile(string $fileName, string $data): bool
    {
        $result = @file_put_contents($fileName, $data);
        if ($result === false)
            throw new RuntimeException('Failed to put info into file: ' . $fileName);
        return true;
    }

    /**
     * @param string $fileName
     * @return array
     */
    public static function getContentOfJsonFileAsArray(string $fileName): array
    {
        try {
            return json_decode(self::getContentOfFile($fileName), true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException('JsonError: ' . $e->getMessage());
        }
    }
}