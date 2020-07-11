<?php

namespace Underdog1987\SimpleCURL;

class CURLResponse{

    private function __construct(){}

	/**
	 * Properties
	 */
    private $url;
    private $content_type;
    private $http_code;
    private $header_size;
    private $request_size;
    private $filetime;
    private $ssl_verify_result;
    private $redirect_count;
    private $total_time;
    private $namelookup_time;
    private $connect_time;
    private $pretransfer_time;
    private $size_upload;
    private $size_download;
    private $speed_download;
    private $speed_upload;
    private $download_content_length;
    private $upload_content_length;
    private $starttransfer_time;
    private $redirect_time;
    private $redirect_url;
    private $primary_ip;
    private $certinfo;
    private $primary_port;
    private $local_ip;
    private $local_port;
    private $http_version;
    private $protocol;
    private $ssl_verifyresult;
    private $scheme;
    private $appconnect_time_us;
    private $connect_time_us;
    private $namelookup_time_us;
    private $pretransfer_time_us;
    private $redirect_time_us;
    private $starttransfer_time_us;
    private $total_time_us;
	private $response_body;
	private $response_headers;
	
	/**
	 * create CURLResponse instance with 
	 */
    public static function createWith(Array $curlInfo):CURLResponse{
		$ret = new self;
		foreach($curlInfo as $property => $value){
			if(property_exists($ret,$property)){
				$ret->$property = $value;
			}
		}
        return $ret;
    }

	public function getUrl(){
		return $this->url;
	}

	public function getContentType(){
		return $this->content_type;
	}

	public function getHttpCode(){
		return $this->http_code;
	}


	public function getHeaderSize(){
		return $this->header_size;
	}

	public function getRequestSize(){
		return $this->request_size;
	}

	public function getFileTime(){
		return $this->filetime;
	}

	public function getRedirectCount(){
		return $this->redirect_count;
	}


	public function getTotalTime(){
		return $this->total_time;
	}


	public function getNameLookupTime(){
		return $this->namelookup_time;
	}

	public function getConnectTime(){
		return $this->connect_time;
	}


	public function getPretransferTime(){
		return $this->pretransfer_time;
	}

	public function getSizeUpload(){
		return $this->size_upload;
	}

	public function getSizeDownload(){
		return $this->size_download;
	}


	public function getSpeedDownload(){
		return $this->speed_download;
	}


	public function getSpeedUpload(){
		return $this->speed_upload;
	}


	public function getDownloadContentLength(){
		return $this->download_content_length;
	}


	public function getUploadContentLength(){
		return $this->upload_content_length;
	}

	public function getStartTransferTime(){
		return $this->starttransfer_time;
	}

	public function getRedirectTime(){
		return $this->redirect_time;
	}

	public function getRedirectUrl(){
		return $this->redirect_url;
	}


	public function getPrimaryIp(){
		return $this->primary_ip;
	}


	public function getCertInfo(){
		return $this->certinfo;
	}

	public function getPrimaryPort(){
		return $this->primary_port;
	}


	public function getLocalIp(){
		return $this->local_ip;
	}

	public function getLocalPort(){
		return $this->local_port;
	}


	public function getHttpVersion(){
		return $this->http_version;
	}


	public function getProtocol(){
		return $this->protocol;
	}

	public function getSslVerifyResult(){
		return $this->ssl_verifyresult;
	}


	public function getScheme(){
		return $this->scheme;
	}


	public function getAppConnectTimeUs(){
		return $this->appconnect_time_us;
	}

	public function getConnectTimeUs(){
		return $this->connect_time_us;
	}

	public function getNameLookupTimeUs(){
		return $this->namelookup_time_us;
	}


	public function getPretransferTimeUs(){
		return $this->pretransfer_time_us;
	}


	public function getRedirectTimeUs(){
		return $this->redirect_time_us;
	}


	public function getStartTransferTimeUs(){
		return $this->starttransfer_time_us;
	}

	public function getTotalTimeUs(){
		return $this->total_time_us;
	}

	/**
	 * Get Response Body
	 * 
	 * @return String
	 */
	public function getResponseBody():String{
		return $this->response_body;
	}

	/**
	 * Get Response Headers
	 * 
	 * @param bool $asArray = FALSE
	 * @return Object/Array
	 */
	public function getResponseHeaders($asArray = FALSE){
		$ret = Array();
		$headers = nl2br($this->response_headers);
		$arHeaders=explode('<br />',$headers);
		$c=1;
		foreach($arHeaders as $header){
			if($c>1){
				$header=trim($header);
				if(strlen($header) > 0){
					list($headerName,$headerValue) = explode(':',$header,2);
					$headerName = trim($headerName);
					$headerValue = trim($headerValue);
					$ret[$headerName] = $headerValue;
				}
			}
			$c++;
		}
		$oRet = (Object)$ret;
		return $asArray?get_object_vars($oRet):$oRet;
	}

}