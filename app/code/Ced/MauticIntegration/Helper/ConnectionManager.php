<?php
/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category    Ced
 * @package     Ced_MauticIntegration
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license     http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\MauticIntegration\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Ced\MauticIntegration\Model\ErrorLogFactory;
use Magento\Framework\App\ResourceConnection;
use Ced\MauticIntegration\Model\Source\RequestType;

class ConnectionManager extends AbstractHelper
{
    /** @var \Magento\Store\Model\StoreManagerInterface */
    public $storeManager;

     /**
     * @var ResourceConnection
     */
    public $resource;

    /** @var \Magento\Framework\App\Cache\TypeListInterface */
    public $cache;

    /** @var */
    public $logger;

     /**
     * @var ErrorLogFactory
     */
    private $errorLogFactory;

    /** @var Properties */
    public $properties;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public $scopeConfig;

    public $oauth2AccessToken = '';
    public $oauth2RefreshToken = '';
    public $oauth2TokenExpiresIn = '';
    public $clientId = '';
    public $clientSecret = '';
    public $mauticUrl = '';
    public $redirecturl = '';
    public $oauth2Code = '';
    public $mauticUsername = "";
    public $mauticPassword = "";
    public $oauthType = "";
    public $connectionEstablished;

    /**
     * ConnectionManager constructor.
     * @param Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Config\Model\ResourceModel\Config $resourceConfig
     * @param \Magento\Framework\App\Cache\TypeListInterface $cache
     * @param Properties $properties
     * @param ResourceConnection $resource
     * @param ErrorLogFactory $errorLogFactory
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function __construct(
        Context $context,
        ErrorLogFactory $errorLogFactory,
        ResourceConnection $resource,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Config\Model\ResourceModel\Config $resourceConfig,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Cache\TypeListInterface $cache,
        Properties $properties
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->resource = $resource;
        $this->resourceConfig = $resourceConfig;
        $this->cache = $cache;
        $this->errorLogFactory = $errorLogFactory;
        $this->properties = $properties;
        $this->prepareConfiguration();
        parent::__construct($context);
    }

    /**
     * Prepare the configuration for Mautic
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function prepareConfiguration()
    {
        $this->mauticUrl = $this->getMauticConfig('mautic_url');
        $this->redirecturl = $this->getRedirectUrl();
        $this->oauthType = $this->getMauticConfig('oauth_type');
        if ($this->oauthType == 'Oauth2') {
            $this->clientId = $this->getMauticConfig('client_id');
            $this->clientSecret = $this->getMauticConfig('client_secret');
            $this->oauth2AccessToken = $this->getMauticConfig('oauth2_access_token');
            $this->oauth2RefreshToken = $this->getMauticConfig('oauth2_refresh_token');
            $this->oauth2Code = $this->getMauticConfig('oauth2_code');
            $this->oauth2TokenExpiresIn = $this->getMauticConfig('oauth2_token_expires_in');
        } elseif ($this->oauthType == 'Basic') {
            $this->mauticUsername = $this->getMauticConfig('mautic_username');
            $this->mauticPassword = $this->getMauticConfig('mautic_password');
        }

        $this->connectionEstablished = $this->getMauticConfig('connection_established');
    }

    /**
     * @param $code
     * @return array
     */
    public function getToken($code)
    {
        if ($this->clientId == null || $this->clientSecret == null || $this->mauticUrl == null) {
            return ['success' => false, 'message' => 'Please Fill Mautic Credential(s).'];
        }
        $params = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirecturl,
            'code' => $code
        ];

        $response = $this->saveToken($params);
        return $response;
    }

    /**
     * @param null $token
     * @return bool|mixed|null|string
     */
    public function validateToken($token = null)
    {
        if ($token === null) {
            $token = $this->oauth2AccessToken;
        }
        $expiry_time = $this->oauth2TokenExpiresIn;
        if (time() < $expiry_time) {
            $this->oauth2AccessToken = $token;
        } else {
            $refresh_token = $this->refreshToken();
            if ($refresh_token && $refresh_token['success']) {
                $this->oauth2AccessToken = $refresh_token['data'];
            } else {
                $this->oauth2AccessToken = false;
            }
        }
        return $this->oauth2AccessToken;
    }

    /**
     * @return array
     */
    public function refreshToken()
    {
        if ($this->clientId == null || $this->clientSecret == null || $this->oauth2RefreshToken == null ||
            $this->mauticUrl == null) {
            return ['success' => false, 'message' => 'Please Fill Mautic Credential(s).'];
        }

        $params = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->oauth2RefreshToken,
            'redirect_uri' => $this->redirecturl

        ];

        $response = $this->saveToken($params);
        return $response;
    }

    public function saveToken($params)
    {
        $post_params = $this->prepareQueryArguments($params);
        $endpoint = '/oauth/v2/token';
        $headers = [];
        $tokenResponse = $this->postRequest($endpoint, $post_params, $headers, "");
        $response = json_decode($tokenResponse['response'], true);
        if ($tokenResponse['status_code'] == 200 && $response != null) {
            if (isset($response['expires_in'])) {
                $this->setMauticConfig('oauth2_token_expires_in', time() + $response['expires_in'] - 5);
                $this->oauth2TokenExpiresIn = time() + $response['expires_in'] - 5;
            }
            if (isset($response['refresh_token'])) {
                $this->setMauticConfig('oauth2_refresh_token', $response['refresh_token']);
                $this->oauth2RefreshToken = $response['refresh_token'];
            }
            if (isset($response['access_token'])) {
                $this->setMauticConfig('oauth2_access_token', $response['access_token']);
                $this->oauth2AccessToken = $response['access_token'];
                $this->cleanCache();
                return ['success' => true, 'data' => $response['access_token']];
            } else {
                return ['success' => false, 'message' => 'some problem occured'];
            }
        } else {
            return ['success' => false, 'message' => 'Some Problem Occured'];
        }
    }

    /**
     * @param $endpoint
     * @param $get_params
     * @return array
     */
    public function getRequest($endpoint, $get_params, $headers, $authType)
    {
        if (!empty($get_params)) {
            $url = $this->mauticUrl . $endpoint . '?' . $this->prepareQueryArguments($get_params);
        } else {
            $url=$this->mauticUrl.$endpoint;
        }
        try{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, false);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_ENCODING, '');
            curl_setopt($ch, CURLOPT_HTTPAUTH, $authType);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $response = curl_exec($ch);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_errors = curl_error($ch);
            curl_close($ch);
            $this->createLog($url, $response, [], RequestType::REQUEST_TYPE_GET , $status_code);
        }catch (\Exception $e) {
            $this->createLog(
                400,
                'exception',
                [
                    'request_method' => '_get',
                    'message' => $e->getMessage()
                ],
                RequestType::REQUEST_TYPE_EXCEPTION,
                []
            );
            $status_code = 400;
            $response = '';
            $curl_errors = '';
        }

        return [
            'status_code' => $status_code,
            'response' => $response,
            'error' => $curl_errors
        ];
    }

    /**
     * @param $endpoint
     * @param $post_params
     * @param $headers
     * @param $authType
     * @return array
     */
    public function postRequest($endpoint, $post_params, $headers, $authType)
    {
        if ($this->mauticUrl == null) {
            return ['success' => false, 'message' => 'Please Fill Mautic Credential(s).'];
        }
        try{
            $url = $this->mauticUrl . $endpoint;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_ENCODING, '');
            curl_setopt($ch, CURLOPT_HTTPAUTH, $authType);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $response = curl_exec($ch);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curl_errors = curl_error($ch);
            curl_close($ch);
            $this->createLog($url, $response, $post_params , RequestType::REQUEST_TYPE_POST , $status_code);
        }catch (\Exception $e) {
            $this->createLog(
                400,
                'exception',
                [
                    'request_method' => '_post',
                    'message' => $e->getMessage()
                ],
                RequestType::REQUEST_TYPE_EXCEPTION,
                []
            );
            $status_code = 400;
            $response = '';
            $curl_errors = '';
        }

        return [
            'status_code' => $status_code,
            'response' => $response,
            'error' => $curl_errors
        ];
    }

    /**
     * @param $endpoint
     * @param $params
     * @return array
     */

    public function createRequest($method, $endpoint, $params)
    {
        if ($this->oauthType == null) {
            return ['success' => false, 'message' => 'Please Fill Mautic Credential(s).'];
        }

        if ($this->oauthType == 'Oauth2') {
            if ($this->clientId == null || $this->clientSecret == null || $this->mauticUrl == null) {
                return ['success' => false, 'message' => 'Please Fill Mautic Credential(s).'];
            }
            if ($method == 'POST') {
                $params['access_token'] = $this->validateToken();
                $post_params = http_build_query($params);
                $headers = [];
                $authType = "";
                $response = $this->postRequest($endpoint, $post_params, $headers, $authType);
                return $response;
            } elseif ($method == 'GET') {
                $headers = [];
                $authType = "";
                $params['access_token'] = $this->validateToken();
                $response = $this->getRequest($endpoint, $params, $headers, $authType);
                return $response;
            }
        } elseif ($this->oauthType == 'Basic') {
            if ($this->mauticUsername == null || $this->mauticPassword == null) {
                return ['success' => false, 'message' => 'Please Fill Mautic Credential(s).'];
            }
            $credentials = $this->mauticUsername . ':' . $this->mauticPassword;
            $encodedCredentials = base64_encode($credentials);
            $headers = ['Authorization: Basic ' . $encodedCredentials];
            $authType = 'CURLAUTH_BASIC';
            if ($method == 'POST') {
                $post_params = http_build_query($params);
                $response = $this->postRequest($endpoint, $post_params, $headers, $authType);
                return $response;
            } elseif ($method == 'GET') {
                $response = $this->getRequest($endpoint, $params, $headers, $authType);
                return $response;
            }
        }
    }

    /**
     * @return array
     */
    public function getListOfFields()
    {
        $endpoint = '/api/fields/contact';
        $method = 'GET';
        $params['limit'] = 200;
        $response = $this->createRequest($method, $endpoint, $params);
        return $response;
    }

    public function getListOfSegments()
    {
        $endpoint = '/api/segments';
        $method = 'GET';
        $params['limit'] = 200;
        $response = $this->createRequest($method, $endpoint, $params);
        return $response;
    }

    /**
     * @return mixed|null
     */
    public function getClientId()
    {
        return $this->getMauticConfig('client_id');
    }

    /**
     * @return mixed|null
     */
    public function getClientSecret()
    {
        return $this->getMauticConfig('client_secret');
    }

    /**
     * @return mixed|null
     */
    public function getMauticUrl()
    {
        return $this->getMauticConfig('mautic_url');
    }

    /**
     * @return mixed|null
     */
    public function getCode()
    {
        return $this->getMauticConfig('oauth2_code');
    }

    /**
     * @return mixed|null
     */
    public function getAccessToken()
    {
        return $this->getMauticConfig('oauth2_access_token');
    }

    /**
     * @return mixed|null
     */
    public function getRefreshToken()
    {
        return $this->getMauticConfig('oauth2_refresh_token');
    }

    /**
     * @return mixed|null
     */
    public function getExpiryTime()
    {
        return $this->getMauticConfig('oauth2_token_expires_in');
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getRedirectUrl()
    {
        $redirectUrl = $this->getMauticConfig('redirect_url');
        if ($redirectUrl && $redirectUrl!="" && $redirectUrl!=null) {
            return urlencode($redirectUrl);
        }
        if ($this->storeManager->getStore()->isFrontUrlSecure()) {
            $baseUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB, true);
        } else {
            $baseUrl = $this->storeManager->getStore()->getBaseUrl();
        }

        return urlencode($baseUrl . 'mauticintegration/config/getcode');
    }

    public function getModuleStatus()
    {
        return $this->getMauticConfig('enable');
    }

    /**
     * @param $field
     * @return mixed|null
     */
    public function getMauticConfig($field)
    {
        if (!$field) {
            return null;
        }
        $path = 'mautic_integration/mauticapi_integration/' . $field;
        return $this->scopeConfig->getValue($path);
    }

     /**
     * @param $days
     * @return int
     */
    public function deleteError($days) {
        $errorLogDeleteTime = date('Y-m-d H:i:s' , strtotime(date('Y-m-d H:i:s'). ' -'.$days.' days'));
        $connection = $this->resource->getConnection();
        $cedErrorLogTable = $this->resource->getTableName('mautic_ced_error_log');
        $effectedRowsCount = $connection->delete($cedErrorLogTable, ['created_at < ?' => $errorLogDeleteTime]);
        return $effectedRowsCount;
    }

    /**
     * @param $field
     * @return mixed|null
     */
    public function isCustomerGroupEnabled($field)
    {
        if (!$field) {
            return null;
        }

        $path = 'mautic_integration/mautic_property_groups/' . $field;
        return $this->scopeConfig->getValue($path);
    }

     /**
     * @param $path
     * @return mixed
     */
    public function getConfigValue($path)
    {
        return $this->scopeConfig->getValue($path);
    }

    /**
     * @param $field
     * @param $value
     * @return \Magento\Config\Model\ResourceModel\Config|null
     */
    public function setMauticConfig($field, $value)
    {
        if (!$field) {
            return null;
        }
        $path = 'mautic_integration/mauticapi_integration/' . $field;
        return $this->resourceConfig->saveConfig($path, $value, 'default', 0);
    }

    /**
     * @param $args
     * @return string
     */
    public function prepareQueryArguments($args, $returnArray = false, $query = [], $key = '')
    {
        foreach ($args as $k => $v) {
            if (is_array($v)) {
                $query = $this->prepareQueryArguments($v, true, $query, $k);
            } else {
                if ($key) {
                    $k = $key;
                }
                $query[] = $k . '=' . $v;
            }
        }
        return $returnArray ? $query : implode('&', $query);
    }

    /**
     *
     */
    public function cleanCache()
    {
        $cacheType = [
            'config',
        ];
        foreach ($cacheType as $cache) {
            $this->cache->cleanType($cache);
        }
    }

    /**
     * @param $url
     * @param $response
     * @param $requestType
     * @param array $postParams
     */
    public function createLog($url, $response, $postParams = [],$requestType, $statuscode = 0)
    {
        $allowedStatus = [200, 201, 202, 204];
        if (!in_array($statuscode, $allowedStatus)) {
            $response = is_array($response) ? json_encode($response) : $response;
            $params = is_array($postParams) ? json_encode($postParams) : $postParams;
            $this->errorLogFactory->create()
                    ->setRequestUrl($url)
                    ->setRequestType($requestType)
                    ->setRequestParams($params)
                    ->setResponseCode($statuscode)
                    ->setResponse($response)
                    ->save();
        }
    }
}
