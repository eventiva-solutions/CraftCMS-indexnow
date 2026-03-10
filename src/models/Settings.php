<?php

namespace eventiva\indexnow\models;

use craft\base\Model;

class Settings extends Model
{
    public bool $enabled = false;
    public string $apiKey = '';
    public string $endpoint = 'api.indexnow.org';
    public bool $autoSubmit = false;
    public string $scheduledTime = '03:00';
}
