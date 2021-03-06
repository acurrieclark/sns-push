<?php

namespace SNSPush\ARN;

use InvalidArgumentException;
use SNSPush\Region;
use SNSPush\SNSPush;

class ARNBuilder
{
    /**
     * AWS Account ID.
     *
     * @var string
     */
    protected $accountId;

    /**
     * AWS region.
     *
     * @var Region
     */
    protected $region;

    /**
     * Platform application.
     *
     * @var array
     */
    protected $platformApplications;

    /**
     * ARNBuilder constructor.
     *
     * @param $config
     *
     * @throws InvalidArgumentException
     */
    public function __construct($config)
    {
        $this->accountId = $config['account_id'];
        $this->platformApplications = $config['platform_applications'];

        $this->region = Region::parse($config['region']);
    }

    /**
     * Create relevant ARN from provided type.
     *
     * @param $type
     * @param $target
     *
     * @throws InvalidArgumentException
     *
     * @return ApplicationARN|EndpointARN|SubscriptionARN|TopicARN
     */
    public function create($type, $target)
    {
        if ($type === SNSPush::TYPE_ENDPOINT) {
            return $this->createEndpointARN($target);
        }
        if ($type === SNSPush::TYPE_TOPIC) {
            return $this->createTopicARN($target);
        }
        if ($type === SNSPush::TYPE_APPLICATION) {
            return $this->createApplicationARN($target);
        }
        if ($type === SNSPush::TYPE_SUBSCRIPTION) {
            return $this->createSubscriptionARN($target);
        }

        throw new InvalidArgumentException('Invalid type.');
    }

    /**
     * Create a topic ARN.
     *
     * @param $target
     *
     * @throws InvalidArgumentException
     */
    public function createTopicARN($target): TopicARN
    {
        return new TopicARN($this->region, $this->accountId, $target);
    }

    /**
     * Create an Application ARN.
     *
     * @param $target
     *
     * @throws InvalidArgumentException
     */
    public function createApplicationARN($target): ApplicationARN
    {
        $target = 'app/'.$this->platformApplications[$target];

        return new ApplicationARN($this->region, $this->accountId, $target);
    }

    /**
     * Create an Endpoint ARN.
     *
     * @param $target
     *
     * @throws InvalidArgumentException
     */
    public function createEndpointARN($target): EndpointARN
    {
        return new EndpointARN($this->region, $this->accountId, $target);
    }

    /**
     * Create an Subscription ARN.
     *
     * @param $target
     *
     * @throws InvalidArgumentException
     */
    public function createSubscriptionARN($target): SubscriptionARN
    {
        return new SubscriptionARN($this->region, $this->accountId, $target);
    }
}
