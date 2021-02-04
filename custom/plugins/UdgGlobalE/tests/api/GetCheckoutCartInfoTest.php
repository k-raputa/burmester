<?php declare(strict_types=1);

namespace UdgGlobalE\Tests\Api;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

class GetCheckoutCartInfoTest extends TestCase
{

    public function requestGetCheckoutCartInfoDataProvider(): array
    {
        return [
            'normal' => [
                'requestJson' => json_encode([
                    'MerchantGUID' => 'bf9a2cc4-304c-47bc-9722-8967dfd97734',
                    'merchantCartToken' => 'OGUpws5784Ky351Ja6PkDbNnKHCUPKXL-2fbb5fe2e29a4d70aa5854ce7ce3e20b',
                    'countryCode' => 'GER',
                ]),
                'expectedResponseJson' => '{"productsList":[{"CartItemId":"0b9a8e203fa7485099f0a2d46f36fb2a","ProductCode":"705C.2","Name":"Kopfh\u00f6rer 705C","Description":null,"IsFixedPrice":false,"OrderedQuantity":2,"URL":"http:\/\/shopware.local\/en\/Kopfhoerer-705C\/705C.2__english","OriginalSalePrice":7000,"VATRateType":{"VATRateTypeCode":"vat19","Rate":19},"ImageURL":"http:\/\/shopware.local\/media\/79\/7c\/b4\/1582107053\/200.jpg.jpg","ImageHeight":200,"ImageWidth":300,"MetaData":{"Attributes":[{"AttributeKey":"Color","AttributeValue":"Brown"}]}},{"CartItemId":"7b6af9236f784493bcd570a27c5afbf0","ProductCode":"705C.1","Name":"Kopfh\u00f6rer 705C","Description":null,"IsFixedPrice":false,"OrderedQuantity":1,"URL":"http:\/\/shopware.local\/en\/Kopfhoerer-705C\/705C.1","OriginalSalePrice":7000,"VATRateType":{"VATRateTypeCode":"vat19","Rate":19},"ImageURL":"http:\/\/shopware.local\/media\/79\/7c\/b4\/1582107053\/200.jpg.jpg","ImageHeight":200,"ImageWidth":300,"MetaData":{"Attributes":[{"AttributeKey":"Color","AttributeValue":"Cool Grey"}]}}],"billingDetails":{"UserId":"3a4b974294d943378ecbd49d5830a35a","Email":"test@asd.de","Salutation":"Mrs.","FirstName":"Daniel","LastName":"Kyak","Address1":"asfd","Address2":"","City":"dghdfh","Zip":"234","CountryCode":"HUN","Phone1":null,"StateOrProvice":"Rhineland-Palatinate"},"shippingDetails":{"UserId":"3a4b974294d943378ecbd49d5830a35a","Email":"test@asd.de","Salutation":"Mrs.","FirstName":"Daniel","LastName":"Kyak","Address1":"asfd","Address2":"","City":"dghdfh","Zip":"234","CountryCode":"HUN","Phone1":null,"StateOrProvice":"Rhineland-Palatinate"},"merchantCartHash":"6f74de34a7493975e28a99e45af87a78"}',
            ],
            'with Gravur' => [
                'requestJson' => json_encode([
                    'MerchantGUID' => 'bf9a2cc4-304c-47bc-9722-8967dfd97734',
                    'merchantCartToken' => 'XNH3GvZY9up8tXl5o92RRdPtd7Hz0apP-2fbb5fe2e29a4d70aa5854ce7ce3e20b',
                    'countryCode' => 'GER',
                ]),
                'expectedResponseJson' => '{"productsList":[{"CartItemId":"314baf695bf5410fabe0dc7a51b34b5c","ProductCode":"705C.2","Name":"Kopfh\u00f6rer 705C","Description":null,"IsFixedPrice":false,"OrderedQuantity":1,"URL":"http:\/\/shopware.local\/en\/Kopfhoerer-705C\/705C.2__english","OriginalSalePrice":7000,"VATRateType":{"VATRateTypeCode":"vat19","Rate":19},"ImageURL":"http:\/\/shopware.local\/media\/79\/7c\/b4\/1582107053\/200.jpg.jpg","ImageHeight":200,"ImageWidth":300,"MetaData":{"Attributes":[{"AttributeKey":"Color","AttributeValue":"Brown"},{"AttributeKey":"Gravur","AttributeValue":"HANSI"}]}}],"merchantCartHash":"af4d771bc0af8a4038f089e024db5565"}',
            ],
        ];
    }

    /**
     * @dataProvider requestGetCheckoutCartInfoDataProvider
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testRequestGetCheckoutCartInfo(string $requestJson, string $expectedResponseJson): void
    {

        $client = HttpClient::create();
        $response = $client->request('POST', 'http://shopware.local/global-e/api/GetCheckoutCartInfo',
            [
                'body' => $requestJson,
            ]);

        $this->assertEquals(200, $response->getStatusCode());
        var_dump($response->getContent());
        $this->assertEquals(json_decode($expectedResponseJson), json_decode($response->getContent()));
    }

    public function requestSendOrderToMerchantDataProvider(): array
    {
        return [
            'created Order' => [
                'requestJson' => json_encode([
                    'MerchantGUID' => 'bf9a2cc4-304c-47bc-9722-8967dfd97734',
                    "OrderId" => "GE12345678GB",
                    'CartId' => 'zTeiMbzYSDLOMbpGYUFeF9fQMKqZPbcp-2fbb5fe2e29a4d70aa5854ce7ce3e20b',
                    'UserId' => 'cecofzj348wdhbr98UbNNDa',
                    'CurrencyCode' => 'EUR',
                    #"PriceCoefficientRate" => 1.340000,
                    #"RoundingRate" => 0.8774285714285714285714285714,
                    #"WebStoreCode" => null,
                    #"WebStoreInstanceCode" => "GlobalEDefaultStoreInstance",
                    #"DiscountedShippingPrice" => 8.77,
                    #"DoNotChargeVAT" => false,
                    "AllowMailsFromMerchant" => true,
	                #"CustomerComments" => null,
	                #"IsFreeShipping" => false,
	                #"FreeShippingCouponCode" => null,
	                #"ShipToStoreCode" => null,
                    "CartHash" => 'e51a9fc7bae0ccb4a07802dd3e9ab5a9',
	                "InternationalDetails"  => [
                                "CurrencyCode" => "USD",
                                "TotalPrice" => 38.00,
                                #"TransactionCurrencyCode":"USD",
                                #"TransactionTotalPrice":38.0000,
                                #"TotalShippingPrice":15.3600,
                                "DiscountedShippingPrice" => 10.0000,
                                "DutiesGuaranteed" => false,
                                "TotalDutiesPrice" => 0.0000,
                                #"PaymentMethodCode":"1",
                                "PaymentMethodName" => "Visa",
                                #"ShippingMethodCode":"608",
                                #"ShippingMethodName":"DHL Express Worldwide",
                                #"ShippingMethodTypeCode":"Express",
                                #"ShippingMethodTypeName":"Express Courier (Air)",
                                "DeliveryDaysFrom" => 3,
                                "DeliveryDaysTo" => 4,
                                #"OrderTrackingNumber":null,
                                "OrderTrackingUrl" => "https%3a%2f%2fwww2.bglobale.com%2fOrder%2fTrack%2fmZyd%3fOrderId%3dGE4874348GB%26ShippingEmail%3djsmith%40merchant.com",
                                #"CardNumberLastFourDigits":"7854",
                                #"ExpirationDate":"2023-06-18"
                            ],
                            "Products" => [
                                [
                                "CartItemId" => "10367295488044",
                                "Sku" => "2410016114",
                                "Price" => 30.7100,
                                "Quantity" => 1,
                                "VATRate" => 20.000000,
                                "InternationalPrice" => 35.0000,
                                #"RoundingRate":0.8774285714285714285714285714,
                                #"IsBackOrdered":false,
                                #"BackOrderDate":null,
                                #"DiscountedPrice":24.57,
                                #"InternationalDiscountedPrice":28.0000,
                                #"GiftMessage":null
                                ]
                            ],
                            #"Discounts":[
                            #{
                            #    "DiscountType":1,
                            #    "Name":"20% off",
                            #    "Description":"20% off all non-sale items.",
                            #    "Price":6.1400,
                            #    "VATRate":20.000000,
                            #    "InternationalPrice":7.0000,
                            #    "CouponCode":"GO20",
                            #    "DiscountCode":"Email_Sign_up-20%_off",
                            #    "ProductCartItemId":"10367295488044",
                            #    "LoyaltyVoucherCode":null
                            #},
                            #{
                            #    "DiscountType":2,
                            #    "Name":"Shipping discount for fixed price",
                            #    "Description":"Shipping discount provided from fixed price range 24663",
                            #    "Price":4.7000,
                            #    "VATRate":20.000000,
                            #    "InternationalPrice":5.3600,
                            #    "CouponCode":null,
                            #    "DiscountCode":null,
                            #    "ProductCartItemId":null,
                            #    "LoyaltyVoucherCode":null
                            #}],
                            "PrimaryShipping" => [
                                "FirstName" => "Jenny",
                                "LastName" => "Smith",
                                "MiddleName" => null,
                                "Salutation" => null,
                                "Company" => null,
                                "Address1" => "12+E+11th+St",
                                "Address2" => null,
                                "City" => "New+York",
                                "StateCode" => "NY",
                                "StateOrProvince" => "New York",
                                "Zip" => "10003",
                                "Email" => "jsmith%40merchant.com",
                                "Phone1" => "0123456789",
                                "Fax" => null,
                                "CountryCode" => "US",
                                "CountryCode3" => "USA",
                                "CountryName" => "United States"
                            ],
                            #"SecondaryShipping"
                            #    "FirstName":"GlobalE",
                            #    "LastName":"East Midlands Airport",
                            #    "MiddleName":null,
                            #    "Salutation":null,
                            #    "Company":null,
                            #    "Address1":"96a, Beverley Road",
                            #    "Address2":"East Midlands Airport\r\nGE12345678GB",
                            #    "City":"Derby",
                            #    "StateCode":"NN",
                            #    "StateOrProvince":null,
                            #    "Zip":"DE74 2SA",
                            #    "Email":"ema.hub@norsk-global.com",
                            #    "Phone1":"01332 818723",
                            #    "Fax":null,
                            #    "CountryCode":"GB",
                            #    "CountryCode3":"GBR",
                            #    "CountryName":"United Kingdom"
                            #},
                            "PrimaryBilling" => [
                                "FirstName" => "Jenny",
                                "LastName" => "Smith",
                                "MiddleName" => null,
                                "Salutation" => null,
                                "Company" => null,
                                "Address1" => "12+E+11th+St",
                                "Address2" => null,
                                "City" => "New+York",
                                "StateCode" => "NY",
                                "StateOrProvince" => "New York",
                                "Zip" => "10003",
                                "Email" => "jsmith%40merchant.com",
                                "Phone1" => "0123456789",
                                "Fax" => null,
                                "CountryCode" => "US",
                                "CountryCode3" => "USA",
                                "CountryName" => "United+States",
                            ],
                            #"SecondaryBilling":{
                            #    "FirstName":"GlobalE",
                            #    "LastName":"UK Limited",
                            #    "MiddleName":null,
                            #    "Salutation":null,
                            #    "Company":"GlobalE",
                            #    "Address1":"45 Leather Lane",
                            #    "Address2":null,
                            #    "City":"London",
                            #    "StateCode":null,
                            #    "StateOrProvince":null,
                            #    "Zip":"EC1N 7TJ",
                            #    "Email":"info@global-e.com",
                            #    "Phone1":"+ 44 (0)808 258 0300",
                            #    "Fax":"+ 44 (0)203 514 7171",
                            #    "CountryCode":"GB",
                            #    "CountryCode3":"GBR",
                            #    "CountryName":"United Kingdom"
                            #}

                ]),
                'expectedResponseJson' => json_encode([
                    'InternalOrderId' => 'globale123',
                    'OrderId'         => 'globale123',
                    'StatusCode' => '200',
                    'Success' => 'true',
                ])
            ],
        ];
    }

    /**
     * @dataProvider requestSendOrderToMerchantDataProvider
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testRequestSendOrderToMerchanto(string $requestJson, string $expectedResponseJson): void
    {

        $client = HttpClient::create();
        $response = $client->request('POST', 'http://shopware.local/global-e/api/SendOrderToMerchant',
            [
                'body' => $requestJson,
            ]);

        var_dump($response->getContent());
        var_dump($expectedResponseJson);
        $this->assertEquals(json_decode($expectedResponseJson), json_decode($response->getContent()));
        $this->assertEquals(200, $response->getStatusCode());
    }

}
