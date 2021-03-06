<?php

namespace Underdog1987\SimpleCURL;

use Underdog1987\SimpleCURL\Exceptions\SimpleCURLException;

final class SimpleCURL{

    /**
     * User-Agent used by default on each request.
     *
     * @const String
     */
    public const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; WOW64) Underdog1987/PHP Simple cURL Client';

    /**
     * Requested URL.
     *
     * @var String
     */
    private $url;
    
    /**
     * Current User-agent.
     *
     * @var String
     */
    private $userAgent;
    
    /**
     * Request method.
     *
     * @var String
     */
    private $method;
    
    /**
     * Determines if https request will ignore certs.
     *
     * @var bool
     */
	private $ignoreCerts;

    /**
     * Full path of CA-Cert used by https requests.
     *
     * @var String
     */
	private $caInfo;

    /**
     * Headers will be send with request.
     *
     * @var Array
     */
    private $headers;
    
    /**
     * Cookies will be send with request.
     *
     * @var Array
     */
    private $cookies;
    
    /**
     * cURL resource.
     *
     * @var cURL resource
     */
    private $cURL_executor;

    /**
     * cURL resource.
     *
     * @var Array data to send
     */
    private $data;
    
    /**
     * Determines if current request is ready to be sent.
     *
     * @var bool
     */
	private $isPrepared;

    /**
     * Stores username & password of Basic Auth.
     *
     * @var Array
     */
    private $authBasicData;

    /**
     * Determines if current request use Basic Authentication.
     *
     * @var bool
     */
    private $useAuthBasic;

    /**
     * Set timeout for excecution.
     *
     * @var int
     */
    private $timeout = 28;

    /**
     * Set timeout for connection (seconds).
     *
     * @var int
     */
    private $cTimeout = 10;

    /**
     * Determine if cURL follow redirects.
     *
     * @var int
     */
    private $follow = TRUE;
    
    /**
     * Creates a new SimpleCURL instance.
     *
     */
    public function __construct($url = ''){
		$this->url = $url;
        $this->userAgent = self::USER_AGENT;
        $this->method = 'POST';
		$this->caInfo = '';
		
        $this->headers = [];
        $this->cookies = [];

        $this->cURL_executor = curl_init();
		$this->data=NULL;
		$this->isPrepared = FALSE;

		$this->useAuthBasic = FALSE;
		$this->authBasicData = [];
	}

    /**
     * Set URL for request.
     *
     * @return void
     */
    public function setUrl(String $url){
		$this->url = $url;
	}

    /**
     * Set User-agent for request.
     *
     * @return void
     */
	public function setUserAgent(String $ua){
		$this->userAgent = $ua;
	}

    /**
     * Set request method to GET.
     *
     * @return void
     */
    public function isGet(){
		$this->method = 'GET';
    }
    
    /**
     * Set request method to PUT.
     *
     * @return void
     */
    public function isPut(){
		$this->method = 'PUT';
    }
    
    /**
     * Set request method to DELETE.
     *
     * @return void
     */
    public function isDelete(){
		$this->method = 'DELETE';
    }
    
    /**
     * Set request's Basic Auth credentials.
     *
     * @param String $username
     * @param String $password
     * @return void
     */
    public function useAuthBasic(String $username ='', String $password=''){
		$this->useAuthBasic = TRUE;
		$this->authBasicData = ['username' => $username, 'password'=>$password];
    }
    
    /**
     * Specifies if SSL will be ignored.
     *
     * @param bool $ic
     * @return void
     */
	public function ignoreCerts(bool $ic){
		$this->ignoreCerts = $ic;
	}

    /**
     * Set certificate's path for https requests.
     *
     * @param String $cainf
     * @return void
     */
    public function setCaInfo(String $cainf){
		$this->caInfo = $cainf;
    }
    
    /**
     * Set connection timeout
     *
     * @param int $seconds
     * @return void
     */
    public function setConnectionTimeout(int $seconds){
        $this->cTimeout = $seconds;
    }

    /**
     * Set execution timeout
     *
     * @param int $seconds
     * @return void
     */
    public function setTimeout(int $seconds){
        $this->timeout = $seconds;
    }

    /**
     * Set if curl is following redirects
     *
     * @param bool $g
     * @return void
     */
    public function followRedirects(bool $f){
        $this->follow = $f;
    }

    /**
     * Add custom header to request.
     *
     * @param Array $header
     * @return void
     */
    public function addHeader(Array $header){
		if(is_array($header) && isset($header['name'], $header['value'])){
			if(strlen(trim($header['name'])) == 0 || strlen(trim($header['value'])) == 0 ){
				throw new SimpleCURLException('Cannot add header without name or value');
			}else{
				$this->headers[]=$header;
			}
		}else{
			throw new SimpleCURLException('Array format is not correct. Must use ["name" => $headerName, "value"=>$headerValue]');
		}
	}

    /**
     * Add cookie to request.
     *
     * @param Array $cookie
     * @return void
     */
    public function addCookie(Array $cookie){
		if(is_array($cookie) && isset($cookie['name'], $cookie['value'])){
			if(strlen(trim($cookie['name'])) == 0 || strlen(trim($cookie['value'])) == 0 ){
				throw new SimpleCURLException('Cannot add cookie without name or value');
			}else{
				$this->cookies[]=$cookie;
			}
		}else{
			throw new SimpleCURLException('Array format is not correct. Must use ["name" => $cookieName, "value"=>$cookieValue]');
		}
    }

    /**
     * Prepares the request to be sent
     *
     * @return void
     */
	public function prepare(){
		// Validate URL
		if(strlen(trim($this->url))==0 ){
			throw new SimpleCURLException ('URL is required to execute cURL');
		}else{
			if (!(filter_var($this->url, FILTER_VALIDATE_URL))) {
				throw new SimpleCURLException ($this->url. 'is not valid URL');
			}
		}
		// Attach certificate on SSL request
		if(FALSE!==strpos($this->url,'https://')){
			if($this->caInfo == ''){
                if($this->ignoreCerts){
                    curl_setopt($this->cURL_executor, CURLOPT_SSL_VERIFYPEER, FALSE);
                }else{
                    throw new SimpleCURLException('CA Info value is not set. This value is required when using HTTPS');
                }
			}else{
				curl_setopt ($this->cURL_executor, CURLOPT_CAINFO, $this->caInfo);
			}
		}
		// Check if cURL is initialized
		if(FALSE===$this->cURL_executor){
			throw new SimpleCURLException('cURL is not initialized: '.curl_error($this->cURL_executor));
		}else{
			curl_setopt($this->cURL_executor, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($this->cURL_executor, CURLOPT_VERBOSE, FALSE);
			curl_setopt($this->cURL_executor, CURLOPT_HEADER, TRUE);
			curl_setopt($this->cURL_executor, CURLOPT_CONNECTTIMEOUT, $this->cTimeout);
            curl_setopt($this->cURL_executor, CURLOPT_TIMEOUT, $this->timeout);
            curl_setopt($this->cURL_executor, CURLOPT_FOLLOWLOCATION, $this->follow);

			// Validate Method
			if($this->method == 'GET'){
				$this->data =
					strlen(trim($this->data))>0?
						substr($this->data,0,1)!='?'?'?'.$this->data:$this->data:
						'';
				curl_setopt($this->cURL_executor, CURLOPT_URL, $this->url . $this->data);
			}else{ 
				curl_setopt($this->cURL_executor, CURLOPT_URL, $this->url);
				curl_setopt($this->cURL_executor, CURLOPT_CUSTOMREQUEST, $this->method);
				curl_setopt($this->cURL_executor, CURLOPT_POSTFIELDS, $this->data);
				if(is_string($this->data)){
					$this->addHeader(['name' => 'Content-Length', 'value' => strlen($this->data)]);
				}
			}
		}
		// Prepare headers
		$arrCurlHeaders = [];
		for($h=0;$h<count($this->headers);$h++){
			$arrCurlHeaders[] = $this->headers[$h]['name'] . ': ' . $this->headers[$h]['value'];
		}
		// Prepare cookies
		$strCurlCookies = '';
		for($c=0;$c<count($this->cookies);$c++){
			$strCurlCookies .= $this->cookies[$c]['name'] . '=' . $this->cookies[$c]['value'] . ';';
		}
		if(count($this->cookies)>0){
			$arrCurlHeaders[] = 'Cookie: '.$strCurlCookies;
		}
		curl_setopt($this->cURL_executor, CURLOPT_HTTPHEADER, $arrCurlHeaders);
		
		// Set User Agent
		curl_setopt($this->cURL_executor, CURLOPT_USERAGENT, $this->userAgent);

		// Set auth basic
		if($this->useAuthBasic){
			if(isset($this->authBasicData['username']) && isset($this->authBasicData['password'])){
				curl_setopt($this->cURL_executor, CURLOPT_USERPWD, $this->authBasicData['username'] . ":" . $this->authBasicData['password']);
			}else{
				throw new SimpleCURLException ('Basic Auth user and password must be specified');
			}
		}
		$this->isPrepared = TRUE;
	}

    /**
     * Check if request is ready to be sent
     *
     * @return bool
     */    
    public function isPrepared():bool{
		return $this->isPrepared;
	}

    /**
     * Send request
     *
     * @return CURLResponse
     */
	public function execute():CURLResponse{
		if($this->isPrepared()){
			$ret = curl_exec($this->cURL_executor);
			if($ret  === FALSE){
                throw new SimpleCURLException('cURL failed with error: '.curl_error($this->cURL_executor));
            }
            // Create CURLResponse
            $responseInfo = curl_getinfo($this->cURL_executor);
            $responseInfo['response_body'] = substr($ret,$responseInfo['header_size']);
            $responseInfo['response_headers'] = substr($ret,0,$responseInfo['header_size']);
            $_ret = CURLResponse::createWith($responseInfo);

		}else{
			throw new SimpleCURLException("Don't call execute() before prepare()");
        }
        
		return $_ret;
	}

    /**
     * Destroys SimpleCURL instance
     * (magic method)
     * 
     * @return void
     */
    public function __destruct(){
        if($this->cURL_executor){
			curl_close($this->cURL_executor);
        }
	}
    
    /**
     * Display SimpleCURL as String
     *
     * @return String
     */
	public function __toString():String{
        return '
        {
            "Simple cURL":{
                "url": "'.$this->url.'",
                "userAgent":"'.$this->userAgent.'",
                "method":"'.$this->method.'",
                "isPrepared": "'.$this->isPrepared.'",
                "useAuthBasic":"'.$this->useAuthBasic.'"
            }
        }';
    }
    
    /**
     * Display SimpleCURL as String
     *
     * @return String
     */
	public function getCurlInfo(){
        return print_r(curl_getinfo($this->cURL_executor),FALSE);
	}

    /**
     * Set data to send in request
     *
     * @param String $strData
     * @return void
     */
	public function setData($strData){
		$this->data=$strData;
    }
    
    /**
     * Get raw data to send in request
     *
     * @param 
     * @return String
     */
	public function getData(){
		return $this->data;
	}


    /**
     * Determines if this SimpleCURL is currently runnable on server
     * @return bool
     */
    public static function isRunnable():bool{
        return
            function_exists('curl_init')
            && function_exists('curl_setopt')
            && function_exists('curl_exec')
            && function_exists('curl_error')
            && function_exists('curl_close')
        ;
    }
}