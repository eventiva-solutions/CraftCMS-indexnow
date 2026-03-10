<?php

namespace eventiva\indexnow;

use Craft;
use craft\base\Element;
use craft\base\Plugin;
use craft\elements\Entry;
use craft\events\ModelEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use eventiva\indexnow\models\Settings;
use eventiva\indexnow\services\IndexNowService;
use yii\base\Event;
use yii\i18n\PhpMessageSource;

class IndexNow extends Plugin
{
    public bool $hasCpSection = true;

    protected function createSettingsModel(): Settings
    {
        return new Settings();
    }

    public function init(): void
    {
        parent::init();

        // Register translations
        Craft::$app->getI18n()->translations['indexnow*'] = [
            'class' => PhpMessageSource::class,
            'sourceLanguage' => 'en',
            'basePath' => __DIR__ . '/translations',
            'forceTranslation' => true,
            'allowOverrides' => true,
        ];

        if (Craft::$app->getRequest()->getIsConsoleRequest()) {
            $this->controllerNamespace = 'eventiva\\indexnow\\console\\controllers';
        }

        $this->setComponents([
            'indexNowService' => IndexNowService::class,
        ]);

        // Register CP URL rules
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['indexnow'] = 'indexnow/settings/index';
                $event->rules['indexnow/settings'] = 'indexnow/settings/index';
                $event->rules['indexnow/settings/save-settings'] = 'indexnow/settings/save-settings';
                $event->rules['indexnow/settings/generate-key'] = 'indexnow/settings/generate-key';
                $event->rules['indexnow/settings/submit-now'] = 'indexnow/settings/submit-now';
                $event->rules['indexnow/settings/clear-log'] = 'indexnow/settings/clear-log';
                $event->rules['indexnow/settings/clear-pending'] = 'indexnow/settings/clear-pending';
            }
        );

        $this->registerEventListeners();

        Craft::info('IndexNow plugin loaded', __METHOD__);
    }

    public function getCpNavItem(): ?array
    {
        if (!Craft::$app->getUser()->getIsAdmin()) {
            return null;
        }

        $item = parent::getCpNavItem();
        $item['label'] = 'IndexNow';
        $item['url'] = 'indexnow/settings';
        $item['icon'] = __DIR__ . '/icon.svg';
        return $item;
    }

    public function getIndexNowService(): IndexNowService
    {
        return $this->get('indexNowService');
    }

    private function registerEventListeners(): void
    {
        Event::on(
            Entry::class,
            Element::EVENT_AFTER_SAVE,
            function (ModelEvent $event) {
                /** @var Entry $entry */
                $entry = $event->sender;

                if ($entry->enabled && $entry->uri !== null) {
                    $this->queueUrlForSubmission($entry);
                }
            }
        );

        Event::on(
            Entry::class,
            Element::EVENT_AFTER_DELETE,
            function (Event $event) {
                /** @var Entry $entry */
                $entry = $event->sender;

                if ($entry->uri !== null) {
                    $this->queueUrlForSubmission($entry);
                }
            }
        );
    }

    private function queueUrlForSubmission(Entry $entry): void
    {
        $settings = $this->getSettings();

        if (!$settings->enabled) {
            return;
        }

        $url = $entry->getUrl();
        if (!$url) {
            return;
        }

        $pendingUrls = Craft::$app->getCache()->get('indexnow_pending_urls') ?: [];

        if (!in_array($url, $pendingUrls)) {
            $pendingUrls[] = $url;
            Craft::$app->getCache()->set('indexnow_pending_urls', $pendingUrls, 86400);
        }

        if ($settings->autoSubmit) {
            $this->getIndexNowService()->submitUrls([$url]);

            $pendingUrls = array_diff($pendingUrls, [$url]);
            Craft::$app->getCache()->set('indexnow_pending_urls', $pendingUrls, 86400);
        }

        Craft::info("URL queued for IndexNow: {$url}", __METHOD__);
    }
}
