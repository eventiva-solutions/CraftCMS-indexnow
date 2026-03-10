<?php

namespace eventiva\indexnow\services;

use Craft;
use eventiva\indexnow\IndexNow;
use yii\base\Component;

class IndexNowService extends Component
{
    private const SUBMISSION_LOG_KEY = 'indexnow_submission_log';

    private const INDEXNOW_ENDPOINTS = [
        'api.indexnow.org' => 'https://api.indexnow.org/indexnow',
        'www.bing.com' => 'https://www.bing.com/indexnow',
        'search.seznam.cz' => 'https://search.seznam.cz/indexnow',
        'yandex.com' => 'https://yandex.com/indexnow',
    ];

    public function getSettings(): array
    {
        $settings = IndexNow::getInstance()->getSettings();

        return [
            'enabled' => $settings->enabled,
            'apiKey' => $settings->apiKey,
            'endpoint' => $settings->endpoint,
            'autoSubmit' => $settings->autoSubmit,
            'scheduledTime' => $settings->scheduledTime,
        ];
    }

    public function generateApiKey(): string
    {
        return bin2hex(random_bytes(16));
    }

    public function getPendingUrls(): array
    {
        return Craft::$app->getCache()->get('indexnow_pending_urls') ?: [];
    }

    public function clearPendingUrls(): void
    {
        Craft::$app->getCache()->delete('indexnow_pending_urls');
    }

    public function addPendingUrl(string $url): void
    {
        $pendingUrls = $this->getPendingUrls();

        if (!in_array($url, $pendingUrls)) {
            $pendingUrls[] = $url;
            Craft::$app->getCache()->set('indexnow_pending_urls', $pendingUrls, 86400);
        }
    }

    public function submitUrls(array $urls): array
    {
        $settings = $this->getSettings();

        if (empty($settings['apiKey'])) {
            return [
                'success' => false,
                'error' => Craft::t('indexnow', 'API key not configured'),
            ];
        }

        if (empty($urls)) {
            return [
                'success' => false,
                'error' => Craft::t('indexnow', 'No URLs to submit'),
            ];
        }

        $siteUrl = Craft::$app->getSites()->getPrimarySite()->getBaseUrl();
        $host = parse_url($siteUrl, PHP_URL_HOST);

        $endpoint = self::INDEXNOW_ENDPOINTS[$settings['endpoint']] ?? self::INDEXNOW_ENDPOINTS['api.indexnow.org'];

        $payload = [
            'host' => $host,
            'key' => $settings['apiKey'],
            'keyLocation' => rtrim($siteUrl, '/') . '/' . $settings['apiKey'] . '.txt',
            'urlList' => array_values($urls),
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        $result = [
            'success' => in_array($httpCode, [200, 202]),
            'httpCode' => $httpCode,
            'urlCount' => count($urls),
            'timestamp' => date('Y-m-d H:i:s'),
            'response' => $response,
        ];

        if ($curlError) {
            $result['success'] = false;
            $result['error'] = $curlError;
        }

        $this->logSubmission($result, $urls);

        Craft::info("IndexNow submission: " . json_encode($result), __METHOD__);

        return $result;
    }

    public function submitAllPendingUrls(): array
    {
        $urls = $this->getPendingUrls();

        if (empty($urls)) {
            return [
                'success' => true,
                'message' => 'Keine URLs zum Senden',
                'urlCount' => 0,
            ];
        }

        $result = $this->submitUrls($urls);

        if ($result['success']) {
            $this->clearPendingUrls();
        }

        return $result;
    }

    public function submitAllSiteUrls(): array
    {
        $urls = $this->getAllSiteUrls();

        if (empty($urls)) {
            return [
                'success' => false,
                'error' => Craft::t('indexnow', 'No URLs found on site'),
            ];
        }

        return $this->submitUrls($urls);
    }

    public function getAllSiteUrls(): array
    {
        $urls = [];

        $entries = \craft\elements\Entry::find()
            ->status('live')
            ->all();

        foreach ($entries as $entry) {
            $url = $entry->getUrl();
            if ($url) {
                $urls[] = $url;
            }
        }

        return array_unique($urls);
    }

    private function logSubmission(array $result, array $urls): void
    {
        $log = Craft::$app->getCache()->get(self::SUBMISSION_LOG_KEY) ?: [];

        $log[] = [
            'timestamp' => date('Y-m-d H:i:s'),
            'success' => $result['success'],
            'httpCode' => $result['httpCode'] ?? null,
            'urlCount' => count($urls),
            'error' => $result['error'] ?? null,
        ];

        $log = array_slice($log, -50);

        Craft::$app->getCache()->set(self::SUBMISSION_LOG_KEY, $log, 604800);
    }

    public function getSubmissionLog(): array
    {
        return Craft::$app->getCache()->get(self::SUBMISSION_LOG_KEY) ?: [];
    }

    public function clearSubmissionLog(): void
    {
        Craft::$app->getCache()->delete(self::SUBMISSION_LOG_KEY);
    }
}
