<?php

require_once __DIR__ . '/../FlexPay.php';

class FlexPayTest extends PHPUnit_Framework_TestCase {

    function setUp() {
        $this->secret = "zpXwe2D77g4P7ysGJcr3rY87TBYs6J";
        $this->params = array(
            'shopID'            => '68849',
            'saleID'            => '433456',
            'referenceID'       => '5566',
            'priceAmount'       => '0.00',
            'referenceID'       => 'reference1234',
            'priceCurrency'     => 'USD',
            'custom1'           => 'My',
            'description'       => 'My Dščřčřřěřě&?=blah123',
            'version'           => '3.2',
            'subscriptionType'  => 'RECURRING',
            'period'            => 'P1M',
            'name'              => 'My name',
            'trialAmount'       => '0.01',
            'trialPeriod'       => 'P3D',
            'cancelDiscountPercentage'  => '30',
            'blah'                      => 'something',
        );

        $this->signOfFiltered = '2e2ab2017f2f649e79a35fa065e89658407a8f69';
        $this->signOfAll = '9c6abde0e9172cb9acd802183a500c7796f48492';
        $this->baseUrl = 'https://secure.verotel.com/';

        $this->urlParamsPurchase
            = "blah=something"
            . "&cancelDiscountPercentage=30"
            . "&custom1=My"
            . "&description="
                . "My+D%C5%A1%C4%8D%C5%99%C4%8D%C5%99%C5%99"
                . "%C4%9B%C5%99%C4%9B%26%3F%3Dblah123"
            . "&name=My+name"
            . "&period=P1M"
            . "&priceAmount=0.00"
            . "&priceCurrency=USD"
            . "&referenceID=reference1234"
            . "&saleID=433456"
            . "&shopID=68849"
            . "&subscriptionType=RECURRING"
            . "&trialAmount=0.01"
            . "&trialPeriod=P3D"
            . "&type=purchase"
            . "&version=3.2"
            . "&signature=bbe653e328d8a4234b45321b98cb2d9581dfa078";

        $this->urlParamsSomeEmpty
            = "blah=something"
            . "&cancelDiscountPercentage=30"
            . "&custom1=My"
            . "&custom4=0"
            . "&description="
                . "My+D%C5%A1%C4%8D%C5%99%C4%8D%C5%99%C5%99"
                . "%C4%9B%C5%99%C4%9B%26%3F%3Dblah123"
            . "&name=My+name"
            . "&period=P1M"
            . "&priceAmount=0.00"
            . "&priceCurrency=USD"
            . "&referenceID=reference1234"
            . "&saleID=433456"
            . "&shopID=68849"
            . "&subscriptionType=RECURRING"
            . "&trialAmount=0.01"
            . "&trialPeriod=P3D"
            . "&type=purchase"
            . "&version=3.2"
            . "&signature=bbe653e328d8a4234b45321b98cb2d9581dfa078";

        $this->urlParamsSubscription
            = "blah=something"
            . "&cancelDiscountPercentage=30"
            . "&custom1=My"
            . "&description="
                . "My+D%C5%A1%C4%8D%C5%99%C4%8D%C5%99%C5%99"
                . "%C4%9B%C5%99%C4%9B%26%3F%3Dblah123"
            . "&name=My+name"
            . "&period=P1M"
            . "&priceAmount=0.00"
            . "&priceCurrency=USD"
            . "&referenceID=reference1234"
            . "&saleID=433456"
            . "&shopID=68849"
            . "&subscriptionType=RECURRING"
            . "&trialAmount=0.01"
            . "&trialPeriod=P3D"
            . "&type=subscription"
            . "&version=3.2"
            . "&signature=6f6353084a4cd194730470186ad9696c98328800";

        $this->urlParamsStatus
            = "blah=something"
            . "&cancelDiscountPercentage=30"
            . "&custom1=My"
            . "&description="
                . "My+D%C5%A1%C4%8D%C5%99%C4%8D%C5%99%C5%99"
                . "%C4%9B%C5%99%C4%9B%26%3F%3Dblah123"
            . "&name=My+name"
            . "&period=P1M"
            . "&priceAmount=0.00"
            . "&priceCurrency=USD"
            . "&referenceID=reference1234"
            . "&saleID=433456"
            . "&shopID=68849"
            . "&subscriptionType=RECURRING"
            . "&trialAmount=0.01"
            . "&trialPeriod=P3D"
            . "&version=3.2"
            . "&signature=2e2ab2017f2f649e79a35fa065e89658407a8f69";

        $this->urlWithBackUrl
            = "backURL=http%3A%2F%2FbackURL.test"
            . "&blah=something"
            . "&cancelDiscountPercentage=30"
            . "&custom1=My"
            . "&description=My+D%C5%A1%C4%8D%C5%99%C4%8D%C5%99%C5%99%C4%9B%C5%99%C4%9B%26%3F%3Dblah123"
            . "&name=My+name"
            . "&period=P1M"
            . "&priceAmount=0.00"
            . "&priceCurrency=USD"
            . "&referenceID=reference1234"
            . "&saleID=433456"
            . "&shopID=68849"
            . "&subscriptionType=RECURRING"
            . "&trialAmount=0.01"
            . "&trialPeriod=P3D"
            . "&type=subscription"
            . "&version=3.2"
            . "&signature=e4f8410897d28c556e889bf1162353e387d350ba";
    }

    function test_get_signature__returns_correct_signature() {
        $this->assertEquals(
            $this->signOfFiltered,
            FlexPay::get_signature( $this->secret, $this->params )
        );
    }

    function test_validate_signature__returns_true_if_correct() {
        $signedParams = array_merge( $this->params,
            array( 'signature' => strtoupper($this->signOfAll) ) );

        $this->assertEquals(
            true,
            FlexPay::validate_signature( $this->secret, $signedParams )
        );
    }

    function test_url_with_backURL() {
        $params = array_merge( $this->params,
            array('backURL'   => 'http://backURL.test',)
        );

        $this->assertEquals(
                $this->baseUrl . 'startorder?' . $this->urlWithBackUrl,
                FlexPay::get_subscription_URL( $this->secret, $params )
        );
    }

    function test_validate_signature__returns_false_if_incorrect() {
        $signedParams = array_merge( $this->params,
            array( 'signature' => $this->signOfAll ) );

        $signedParams["custom1"] = 'Your';

        $this->assertEquals(
            false,
            FlexPay::validate_signature( $this->secret, $signedParams )
        );
    }

    function test_get_purchase_URL__returns_correct_url() {
        unset($this->params['version']);

        $this->assertEquals(
            $this->baseUrl . 'startorder?' . $this->urlParamsPurchase,
            FlexPay::get_purchase_URL( $this->secret, $this->params )
        );
    }

    function test_get_purchase_URL__removes_empty_parameters() {
        $this->params['custom2'] = '';
        $this->params['custom3'] = null;
        $this->params['custom4'] = 0;

        $this->assertEquals(
            $this->baseUrl . 'startorder?' . $this->urlParamsSomeEmpty,
            FlexPay::get_purchase_URL( $this->secret, $this->params )
        );
    }

    function test_get_subscription_URL__returns_correct_url() {
        unset($this->params['version']);

        $this->assertEquals(
            $this->baseUrl . 'startorder?' . $this->urlParamsSubscription,
            FlexPay::get_subscription_URL( $this->secret, $this->params )
        );
    }

    function test_get_status_URL__returns_correct_url() {
        $this->assertEquals(
            $this->baseUrl . 'salestatus?' . $this->urlParamsStatus,
            FlexPay::get_status_URL( $this->secret, $this->params )
        );
    }
};
