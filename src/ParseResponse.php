<?php

namespace Caspla;

class ParseResponse
{
    /**
     * store Class
     *
     * @var array
     */
    public $storeClass = [
        CASPLA::BASE_ENDPOINT . 'v2.0/api/auth/customer/login2FA'                      => 'Stelin\Response\Login2FAResponse',
        CASPLA::BASE_ENDPOINT . 'v2.0/api/auth/customer/login2FA/verify'               => 'Stelin\Response\Login2FAVerifyResponse',
        CASPLA::BASE_ENDPOINT . 'v2.0/api/auth/customer/loginSecurityCode/verify'      => 'Stelin\Response\LoginSecurityCodeResponse',
        CASPLA::BASE_ENDPOINT . 'v1.0/api/front/'                                      => 'Stelin\Response\FrontResponse',
        CASPLA::BASE_ENDPOINT . 'v1.0/budget/detail'                                   => 'Stelin\Response\BudgetResponse',
        CASPLA::BASE_ENDPOINT . 'v1.0/api/customers/transfer'                          => 'Stelin\Response\CustomerTransferResponse',
        CASPLA::BASE_ENDPOINT . 'v1.0/api/auth/customer/genTrxId'                      => 'Stelin\Response\GenTrxIdResponse',
        CASPLA::BASE_ENDPOINT . 'v1.0/notification/status/count/UNREAD'                => 'Stelin\Response\NotificationUnreadResponse',
        CASPLA::BASE_ENDPOINT . 'v1.0/notification/status/all'                         => 'Stelin\Response\NotificationAllResponse',
        CASPLA::BASE_ENDPOINT . 'v1.0/api/auth/customer/logout'                        => 'Stelin\Response\LogoutResponse',
        CASPLA::AWS . 'gpdm/ovo/ID/v2/billpay/get-billers?categoryID=5C6'              => 'Stelin\Response\BillpayResponse',
        CASPLA::AWS . 'gpdm/ovo/ID/v1/billpay/inquiry'                                 => 'Stelin\Response\InquiryResponse',
        CASPLA::BASE_ENDPOINT . 'v1.0/api/auth/customer/unlock'                        => 'Stelin\Response\CustomerUnlockResponse',
        CASPLA::AWS . 'gpdm/ovo/ID/v1/billpay/pay'                                     => 'Stelin\Response\PayResponse',
        CASPLA::AWS . 'gpdm/ovo/ID/v1/billpay/checkstatus'                             => 'Stelin\Response\PayCheckStatusResponse',
        CASPLA::BASE_ENDPOINT . 'v1.0/reference/master/ref_bank'                       => 'Stelin\Response\Ref_BankResponse',
        CASPLA::BASE_ENDPOINT . 'transfer/inquiry'                                     => 'Stelin\Response\TransferInquiryResponse',
        CASPLA::BASE_ENDPOINT . 'transfer/direct'                                      => 'Stelin\Response\TransferDirectResponse',
        CASPLA::BASE_ENDPOINT . 'v1.1/api/auth/customer/isOVO'                          => 'Stelin\Response\isOVOResponse'
    ];
    private $response;
    /**
     * Parse response init
     *
     * @param mixed  $chResult
     * @param string $url
     */
    public function __construct($chResult, $url)
    {
        $jsonDecodeResult = json_decode($chResult);
        //-- Cek apakah ada error dari OVO Response
        if (isset($jsonDecodeResult->code)) {
            throw new \Stelin\Exception\OvoidException($jsonDecodeResult->message . ' ' . $url);
        }
        $parts = parse_url($url);
        if ($parts['path'] == '/wallet/v2/transaction') {
            $this->response = new \Stelin\Response\WalletTransactionResponse($jsonDecodeResult);
        } elseif (strpos($parts['path'], '/gpdm/ovo/ID/v1/billpay/get-denominations/') !== false) {
            $this->response = new \Stelin\Response\DenominationsReponse($jsonDecodeResult);
        } else {
            $this->response = new $this->storeClass[$url]($jsonDecodeResult);
        }
    }
    /**
     * Get response following by class
     *
     * @return void
     */
    public function getResponse()
    {
        return $this->response;
    }
}
