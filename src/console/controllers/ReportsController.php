<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\campaign\console\controllers;

use Craft;
use putyourlightson\campaign\Campaign;
use putyourlightson\campaign\records\ContactCampaignRecord;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\BaseConsole;

/**
 * Allows you to manage reports.
 *
 * @since 2.0.0
 */
class ReportsController extends Controller
{
    /**
     * Anonymize all previously collected personal data.
     */
    public function actionAnonymize(): int
    {
        ContactCampaignRecord::deleteAll();

        $this->stdout(Craft::t('campaign', 'Personal data successfully anonymized.') . PHP_EOL, BaseConsole::FG_GREEN);

        return ExitCode::OK;
    }

    /**
     * Syncs campaign reports.
     *
     * @since 2.4.0
     */
    public function actionSync(): int
    {
        Campaign::$plugin->reports->sync();

        $this->stdout(Craft::t('campaign', 'Campaign reports successfully synced.') . PHP_EOL, BaseConsole::FG_GREEN);

        return ExitCode::OK;
    }
}
