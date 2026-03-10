<?php

namespace eventiva\indexnow\controllers;

use Craft;
use craft\helpers\FileHelper;
use craft\web\Controller;
use eventiva\indexnow\IndexNow;

class SettingsController extends Controller
{
    protected array|int|bool $allowAnonymous = false;

    public function beforeAction($action): bool
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        $this->requireAdmin();

        return true;
    }

    public function actionIndex(): \yii\web\Response
    {
        $service = IndexNow::getInstance()->getIndexNowService();

        $settings = $service->getSettings();
        $pendingUrls = $service->getPendingUrls();
        $submissionLog = $service->getSubmissionLog();
        $allUrls = $service->getAllSiteUrls();

        return $this->renderTemplate('indexnow/_settings', [
            'settings' => $settings,
            'pendingUrls' => $pendingUrls,
            'submissionLog' => array_reverse($submissionLog),
            'totalSiteUrls' => count($allUrls),
        ]);
    }

    public function actionSaveSettings(): ?\yii\web\Response
    {
        $this->requirePostRequest();

        $request = Craft::$app->getRequest();

        $enabled = $request->getBodyParam('enabled');
        $autoSubmit = $request->getBodyParam('autoSubmit');

        $scheduledTime = $request->getBodyParam('scheduledTime');
        if (is_array($scheduledTime)) {
            $scheduledTime = ($scheduledTime['time'] ?? '03:00');
        }

        $settings = [
            'enabled' => ($enabled === '1' || $enabled === 1 || $enabled === true),
            'apiKey' => $request->getBodyParam('apiKey') ?: '',
            'endpoint' => $request->getBodyParam('endpoint') ?: 'api.indexnow.org',
            'autoSubmit' => ($autoSubmit === '1' || $autoSubmit === 1 || $autoSubmit === true),
            'scheduledTime' => $scheduledTime ?: '03:00',
        ];

        if (Craft::$app->getPlugins()->savePluginSettings(IndexNow::getInstance(), $settings)) {
            Craft::$app->getSession()->setNotice(Craft::t('indexnow', 'Settings saved.'));
        } else {
            Craft::$app->getSession()->setError(Craft::t('indexnow', 'Error saving settings.'));
        }

        return $this->redirectToPostedUrl();
    }

    public function actionGenerateKey(): \yii\web\Response
    {
        $this->requirePostRequest();

        $service = IndexNow::getInstance()->getIndexNowService();
        $newKey = $service->generateApiKey();

        $currentSettings = IndexNow::getInstance()->getSettings();

        // Remove old key file if it exists
        if (!empty($currentSettings->apiKey)) {
            $oldKeyFile = Craft::getAlias('@webroot') . DIRECTORY_SEPARATOR . $currentSettings->apiKey . '.txt';
            if (file_exists($oldKeyFile)) {
                @unlink($oldKeyFile);
            }
        }

        Craft::$app->getPlugins()->savePluginSettings(IndexNow::getInstance(), [
            'enabled' => $currentSettings->enabled,
            'apiKey' => $newKey,
            'endpoint' => $currentSettings->endpoint,
            'autoSubmit' => $currentSettings->autoSubmit,
            'scheduledTime' => $currentSettings->scheduledTime,
        ]);

        // Create the key verification file in the web root
        $webRoot = Craft::getAlias('@webroot');
        $keyFile = $webRoot . DIRECTORY_SEPARATOR . $newKey . '.txt';

        try {
            FileHelper::createDirectory($webRoot);
            file_put_contents($keyFile, $newKey);
            Craft::$app->getSession()->setNotice(Craft::t('indexnow', 'New API key generated and file {key}.txt created in web root.', ['key' => $newKey]));
        } catch (\Throwable $e) {
            Craft::error('IndexNow: Could not create key file: ' . $e->getMessage(), __METHOD__);
            Craft::$app->getSession()->setNotice(Craft::t('indexnow', 'New API key generated: {key} (File could not be created automatically)', ['key' => $newKey]));
        }

        return $this->redirect('indexnow/settings');
    }

    public function actionSubmitNow(): \yii\web\Response
    {
        $this->requirePostRequest();

        $request = Craft::$app->getRequest();
        $submitType = $request->getBodyParam('submitType', 'pending');

        $service = IndexNow::getInstance()->getIndexNowService();

        if ($submitType === 'all') {
            $result = $service->submitAllSiteUrls();
        } else {
            $result = $service->submitAllPendingUrls();
        }

        if ($result['success']) {
            Craft::$app->getSession()->setNotice(Craft::t('indexnow', '{count} URLs successfully submitted to IndexNow.', ['count' => $result['urlCount'] ?? 0]));
        } else {
            $error = $result['error'] ?? Craft::t('indexnow', 'Unknown error');
            Craft::$app->getSession()->setError(Craft::t('indexnow', 'Error: {error}', ['error' => $error]));
        }

        return $this->redirect('indexnow/settings');
    }

    public function actionClearLog(): \yii\web\Response
    {
        $this->requirePostRequest();

        $service = IndexNow::getInstance()->getIndexNowService();
        $service->clearSubmissionLog();

        Craft::$app->getSession()->setNotice(Craft::t('indexnow', 'Log cleared.'));

        return $this->redirect('indexnow/settings');
    }

    public function actionClearPending(): \yii\web\Response
    {
        $this->requirePostRequest();

        $service = IndexNow::getInstance()->getIndexNowService();
        $service->clearPendingUrls();

        Craft::$app->getSession()->setNotice(Craft::t('indexnow', 'Queue cleared.'));

        return $this->redirect('indexnow/settings');
    }
}
