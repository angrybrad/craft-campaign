<?php
/**
 * @link      https://craftcampaign.com
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\campaign\elements\db;

use putyourlightson\campaign\elements\SendoutElement;

use craft\elements\db\ElementQuery;
use craft\helpers\Db;
use yii\db\Connection;
use yii\db\Expression;

/**
 * SendoutElementQuery
 *
 * @method SendoutElement[]|array all($db = null)
 * @method SendoutElement|array|null one($db = null)
 * @method SendoutElement|array|null nth(int $n, Connection $db = null)
 *
 * @author    PutYourLightsOn
 * @package   Campaign
 * @since     1.0.0
 */
class SendoutElementQuery extends ElementQuery
{
    // Properties
    // =========================================================================

    // General parameters
    // -------------------------------------------------------------------------

    /**
     * @var string SID
     */
    public $sid;

    /**
     * @var string The sendout type that the resulting sendouts must have.
     */
    public $sendoutType;

    /**
     * @var int The campaign ID that the resulting sendouts must be to.
     */
    public $campaignId;

    /**
     * @var int The mailing list ID that the resulting sendouts must contain.
     */
    public $mailingListId;

    // Public Methods
    // =========================================================================

    /**
     * Sets the [[sid]] property.
     *
     * @param string $value The property value
     *
     * @return static self reference
     */
    public function sid(string $value)
    {
        $this->sid = $value;

        return $this;
    }

    /**
     * Sets the [[sendoutType]] property.
     *
     * @param string $value The property value
     *
     * @return static self reference
     */
    public function sendoutType(string $value)
    {
        $this->sendoutType = $value;

        return $this;
    }

    /**
     * Sets the [[campaignId]] property.
     *
     * @param int $value The property value
     *
     * @return static self reference
     */
    public function campaignId(int $value)
    {
        $this->campaignId = $value;

        return $this;
    }

    /**
     * Sets the [[mailingListId]] property.
     *
     * @param int $value The property value
     *
     * @return static self reference
     */
    public function mailingListId(int $value)
    {
        $this->mailingListId = $value;

        return $this;
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function beforePrepare(): bool
    {
        $this->joinElementTable('campaign_sendouts');

        $this->query->select([
            'campaign_sendouts.sid',
            'campaign_sendouts.campaignId',
            'campaign_sendouts.senderId',
            'campaign_sendouts.sendoutType',
            'campaign_sendouts.sendStatus',
            'campaign_sendouts.sendStatusMessage',
            'campaign_sendouts.fromName',
            'campaign_sendouts.fromEmail',
            'campaign_sendouts.subject',
            'campaign_sendouts.notificationEmailAddress',
            'campaign_sendouts.googleAnalyticsLinkTracking',
            'campaign_sendouts.mailingListIds',
            'campaign_sendouts.excludedMailingListIds',
            'campaign_sendouts.segmentIds',
            'campaign_sendouts.recipients',
            'campaign_sendouts.pendingRecipientIds',
            'campaign_sendouts.sentRecipientIds',
            'campaign_sendouts.automatedSchedule',
            'campaign_sendouts.htmlBody',
            'campaign_sendouts.plaintextBody',
            'campaign_sendouts.sendDate',
            'campaign_sendouts.lastSent',
        ]);

        if ($this->sid) {
            $this->subQuery->andWhere(Db::parseParam('campaign_sendouts.sid', $this->sid));
        }

        if ($this->sendoutType) {
            $this->subQuery->andWhere(Db::parseParam('campaign_sendouts.sendoutType', $this->sendoutType));
        }

        if ($this->campaignId) {
            $this->subQuery->andWhere(Db::parseParam('campaign_sendouts.campaignId', $this->campaignId));
        }

        if ($this->mailingListId) {
            $expression = new Expression(
                'FIND_IN_SET(:mailingListId, campaign_sendouts.mailingListIds)',
                [':mailingListId' => $this->mailingListId]
            );
            $this->subQuery->andWhere($expression);
        }

        return parent::beforePrepare();
    }

    /**
     * @inheritdoc
     */
    protected function statusCondition(string $status)
    {
        switch ($status) {
            case SendoutElement::STATUS_SENT:
                return [
                    'campaign_sendouts.sendStatus' => 'sent',
                ];
            case SendoutElement::STATUS_SENDING:
                return [
                    'campaign_sendouts.sendStatus' => 'sending',
                ];
            case SendoutElement::STATUS_QUEUED:
                return [
                    'campaign_sendouts.sendStatus' => 'queued',
                ];
            case SendoutElement::STATUS_PENDING:
                return [
                    'campaign_sendouts.sendStatus' => 'pending',
                ];
            case SendoutElement::STATUS_PAUSED:
                return [
                    'campaign_sendouts.sendStatus' => 'paused',
                ];
            case SendoutElement::STATUS_CANCELLED:
                return [
                    'campaign_sendouts.sendStatus' => 'cancelled',
                ];
            case SendoutElement::STATUS_FAILED:
                return [
                    'campaign_sendouts.sendStatus' => 'failed',
                ];
            case SendoutElement::STATUS_DRAFT:
                return [
                    'campaign_sendouts.sendStatus' => 'draft',
                ];
            default:
                return parent::statusCondition($status);
        }
    }
}
