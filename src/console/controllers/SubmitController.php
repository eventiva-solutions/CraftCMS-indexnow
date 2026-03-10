<?php

namespace eventiva\indexnow\console\controllers;

use Craft;
use craft\console\Controller;
use craft\helpers\FileHelper;
use eventiva\indexnow\IndexNow;
use yii\console\ExitCode;

class SubmitController extends Controller
{
    public function actionIndex(): int
    {
        $service = IndexNow::getInstance()->getIndexNowService();
        $settings = $service->getSettings();

        if (!$settings['enabled']) {
            $this->stdout("IndexNow ist deaktiviert.\n");
            return ExitCode::OK;
        }

        $result = $service->submitAllPendingUrls();

        if ($result['success']) {
            $this->stdout("Erfolgreich: {$result['urlCount']} URLs gesendet.\n");
        } else {
            $this->stderr("Fehler: " . ($result['error'] ?? 'Unbekannt') . "\n");
            $this->logError('submit', $result);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        return ExitCode::OK;
    }

    public function actionAll(): int
    {
        $service = IndexNow::getInstance()->getIndexNowService();
        $settings = $service->getSettings();

        if (!$settings['enabled']) {
            $this->stdout("IndexNow ist deaktiviert.\n");
            return ExitCode::OK;
        }

        $result = $service->submitAllSiteUrls();

        if ($result['success']) {
            $this->stdout("Erfolgreich: {$result['urlCount']} URLs gesendet.\n");
        } else {
            $this->stderr("Fehler: " . ($result['error'] ?? 'Unbekannt') . "\n");
            $this->logError('submit-all', $result);
            return ExitCode::UNSPECIFIED_ERROR;
        }

        return ExitCode::OK;
    }

    private function logError(string $action, array $result): void
    {
        $logPath = Craft::getAlias('@storage/logs');
        FileHelper::createDirectory($logPath);

        $filePath = $logPath . DIRECTORY_SEPARATOR . 'indexnow-' . date('Y-m-d') . '.log';
        $error = $result['error'] ?? 'Unbekannt';
        $error = str_replace(["\r", "\n"], ' ', $error);
        $httpCode = $result['httpCode'] ?? 'n/a';
        $urlCount = $result['urlCount'] ?? 'n/a';
        $response = $result['response'] ?? '';
        if ($response !== null && $response !== '') {
            $response = str_replace(["\r", "\n"], ' ', (string)$response);
        } else {
            $response = 'n/a';
        }

        $line = sprintf(
            "[%s] action=%s error=%s httpCode=%s urlCount=%s response=%s\n",
            date('Y-m-d H:i:s'),
            $action,
            $error,
            $httpCode,
            $urlCount,
            $response
        );

        file_put_contents($filePath, $line, FILE_APPEND | LOCK_EX);
    }
}
