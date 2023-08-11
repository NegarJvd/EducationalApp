<?php

namespace Negar\Smsirlaravel;
use Negar\Smsirlaravel\models\SmsirlaravelLogs;

class Smsirlaravel
{
	/**
	 * This method used for log the messages to the database if db-log set to true (@ smsirlaravel.php in config folder).
	 *
	 * @param $result
	 * @param $messages
	 * @param $numbers
	 * @internal param bool $addToCustomerClub | set to true if you want to log another message instead main message
	 */
	public static function DBlog($result, $messages, $numbers) {
		if(config('smsirlaravel.db-log')) {
			if (!is_array($numbers)) {
				$numbers = array($numbers);
			}
//			$res = json_decode($result->getBody()->getContents(),true);

			if(count($messages) == 1) {
				foreach ( $numbers as $number ) {
					if (is_array($messages)) {
						$msg = $messages[0];
					} else {
						$msg = $messages;
					}
					$log = SmsirlaravelLogs::create( [
						'response' => $result->Message,
						'message'  => $msg,
						'status'   => $result->IsSuccessful,
						'from'     => config('smsirlaravel.line-number'),
						'to'       => $number,
					]);
				}
			} else {
				foreach ( array_combine( $messages, $numbers ) as $message => $number ) {
					SmsirlaravelLogs::create( [
						'response' => $result->Message,
						'message'  => $message,
						'status'   => $result->IsSuccessful,
						'from'     => config('smsirlaravel.line-number'),
						'to'       => $number,
					]);
				}
			}
		}
	}

	/**
	 * this method used in every request to get the token at first.
	 *
	 * @return mixed - the Token for use api
	 */
	public static function getToken()
	{
//		$client     = new Client();
//		$body       = ['UserApiKey'=>config('smsirlaravel.api-key'),'SecretKey'=>config('smsirlaravel.secret-key'),'System'=>'laravel_v_1_4'];
//		$result     = $client->post(config('smsirlaravel.webservice-url').'api/Token',['json'=>$body,'connect_timeout'=>30]);
//		return json_decode($result->getBody(),true)['TokenKey'];

        $postData = array(
            'UserApiKey' => config('smsirlaravel.api-key'),
            'SecretKey' => config('smsirlaravel.secret-key'),
            'System' => 'php_rest_v_1_2'
        );

        $ch = curl_init(config('smsirlaravel.webservice-url').'api/Token');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        ));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, $postData);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result);

        if(is_object($response)){
            $resultVars = get_object_vars($response);
            if(is_array($resultVars)){
                @$IsSuccessful = $resultVars['IsSuccessful'];
                if($IsSuccessful == true){
                    @$TokenKey = $resultVars['TokenKey'];
                    $resp = $TokenKey;
                } else {
                    $resp = false;
                }
            }
        }else{
            $resp = false;
        }

        return $resp;
	}

	/**
	 * this method return your credit in sms.ir (sms credit, not money)
	 *
	 * @return mixed - credit
	 */
	public static function credit()
	{
//		$client     = new Client();
//		$result     = $client->get(config('smsirlaravel.webservice-url').'api/credit',['headers'=>['x-sms-ir-secure-token'=>self::getToken()],'connect_timeout'=>30]);
//		return json_decode($result->getBody(),true)['Credit'];

        $token = self::getToken();
        if($token != false){

            $url = config('smsirlaravel.webservice-url').'api/credit';
            $GetCredit = self::execute($url, $token);

            $object = json_decode($GetCredit);

            if(is_object($object)){
                $array = get_object_vars($object);

                if(is_array($array)){
                    if($array['IsSuccessful'] == true){
                        $result = $array['Credit'];

                    } else {
                        $result = $array['Message'];
                    }
                } else {
                    $result = false;
                }
            } else {
                $result = false;
            }

        } else {
            $result = false;
        }
        return $result;
	}

    /**
     * executes the main method.
     *
     * @param string $url url
     * @param string $token token string
     * @param string $postData
     * @return string Indicates the curl execute result
     */
    private static function execute($url, $token, $postData=[]){


        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'x-sms-ir-secure-token: '.$token
        ));

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        if ($postData != []){
            curl_setopt($ch, CURLOPT_POST, count($postData));
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        }

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

	/**
	 * by this method you can fetch all of your sms lines.
	 *
	 * @return mixed , return all of your sms lines
	 */
	public static function getLines()
	{
//		$client     = new Client();
//		$result     = $client->get(config('smsirlaravel.webservice-url').'api/SMSLine',['headers'=>['x-sms-ir-secure-token'=>self::getToken()],'connect_timeout'=>30]);
//		return json_decode($result->getBody(),true);

        $token = self::getToken();
        if($token != false){

            $url = config('smsirlaravel.webservice-url').'api/SMSLine';
            $GetSmsLines = self::execute($url, $token);

            $object = json_decode($GetSmsLines);

            if(is_object($object)){
                $array = get_object_vars($object);
                if(is_array($array)){
                    if($array['IsSuccessful'] == true){
                        $result = $array['SMSLines'];
                    } else {
                        $result = $array['Message'];
                    }
                } else {
                    $result = false;
                }
            } else {
                $result = false;
            }

        } else {
            $result = false;
        }
        return $result;
	}

	/**
	 * Simple send message with sms.ir account and line number
	 *
	 * @param $messages = Messages - Count must be equal with $numbers
	 * @param $numbers  = Numbers - must be equal with $messages
	 * @param null $sendDateTime = don't fill it if you want to send message now
	 *
	 * @return mixed, return status
	 */
	public static function send($messages,$numbers,$sendDateTime = null)
	{
//		$client     = new Client();
//		$messages = (array)$messages;
//		$numbers = (array)$numbers;
//		if($sendDateTime === null) {
//			$body   = ['Messages'=>$messages,'MobileNumbers'=>$numbers,'LineNumber'=>config('smsirlaravel.line-number')];
//		} else {
//			$body   = ['Messages'=>$messages,'MobileNumbers'=>$numbers,'LineNumber'=>config('smsirlaravel.line-number'),'SendDateTime'=>$sendDateTime];
//		}
//		$result     = $client->post(config('smsirlaravel.webservice-url').'api/MessageSend',['json'=>$body,'headers'=>['x-sms-ir-secure-token'=>self::getToken()],'connect_timeout'=>30]);
//
//		self::DBlog($result,$messages,$numbers);
//
//		return json_decode($result->getBody(),true);

        $token = self::getToken();
        if($token != false){

            if ($sendDateTime === null){
                $postData = array(
                    'Messages' => (array)$messages,
                    'MobileNumbers' => (array)$numbers,
                    'LineNumber' => config('smsirlaravel.line-number'),
                    'CanContinueInCaseOfError' => 'false'
                );
            }else{
                $postData = array(
                    'Messages' => (array)$messages,
                    'MobileNumbers' => (array)$numbers,
                    'LineNumber' => config('smsirlaravel.line-number'),
                    'SendDateTime' => $sendDateTime,
                    'CanContinueInCaseOfError' => 'false'
                );
            }

            $url = config('smsirlaravel.webservice-url').'api/MessageSend';
            $SendMessage = self::execute($url, $token, $postData);
            $object = json_decode($SendMessage);

            self::DBlog($object,(array)$messages,(array)$numbers);

            if(is_object($object)){
                $array = get_object_vars($object);
                if(is_array($array)){
                    $result = $array['Message'];
                } else {
                    $result = false;

                }
            } else {
                $result = false;
            }

        } else {
            $result = false;
        }
        return $result;
	}

	/**
	 * add a person to the customer club contacts
	 *
	 * @param $prefix               = mr, dr, dear...
	 * @param $firstName            = first name of this contact
	 * @param $lastName             = last name of this contact
	 * @param $mobile               = contact mobile number
	 * @param string $birthDay      = birthday of contact, not require
	 * @param string $categotyId    = which category id of your customer club to join this contact?
	 *
	 * @return \Psr\Http\Message\ResponseInterface = $result as json
	 */
	public static function addToCustomerClub($prefix,$firstName,$lastName,$mobile,$birthDay = '',$categoryId = '')
	{
//		$client     = new Client();
//		$body       = ['Prefix'=>$prefix,'FirstName'=>$firstName,'LastName'=>$lastName,'Mobile'=>$mobile,'BirthDay'=>$birthDay,'CategoryId'=>$categoryId];
//		$result     = $client->post(config('smsirlaravel.webservice-url').'api/CustomerClubContact',['json'=>$body,'headers'=>['x-sms-ir-secure-token'=>self::getToken()],'connect_timeout'=>30]);
//		// $res        = json_decode($result->getBody()->getContents(),true);
//
//		self::DBlog($result,"افزودن $firstName $lastName به مخاطبین باشگاه ",$mobile);
//
//		return json_decode($result->getBody(),true);

        $token = self::getToken();
        if($token != false){
            $postData = array(
                'Prefix' => $prefix,
                'FirstName' => $firstName,
                'LastName' => $lastName,
                'Mobile' => $mobile,
                'BirthDay' => $birthDay,
                'CategoryId' => $categoryId
            );

            $url = config('smsirlaravel.webservice-url').'api/CustomerClubContact';
            $AddContactToCustomerClub = self::execute($url, $token, $postData);
            $object = json_decode($AddContactToCustomerClub);
            self::DBlog($object,"افزودن $firstName $lastName به مخاطبین باشگاه ",(array) $mobile);

            if(is_object($object)){
                $array = get_object_vars($object);
                if(is_array($array)){
                    $result = $array['Message'];
                } else {

                    $result = false;
                }
            } else {
                $result = false;
            }

        } else {
            $result = false;
        }
        return $result;
	}

	/**
	 * this method send message to your customer club contacts (known as white sms module)
	 *
	 * @param $messages
	 * @param $numbers
	 * @param null $sendDateTime
	 * @param bool $canContinueInCaseOfError
	 *
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public static function sendToCustomerClub($messages,$numbers,$sendDateTime = null,$canContinueInCaseOfError = true)
	{
//		$client     = new Client();
//		$messages = (array)$messages;
//		$numbers = (array)$numbers;
//		if($sendDateTime !== null) {
//			$body   = ['Messages'=>$messages,'MobileNumbers'=>$numbers,'SendDateTime'=>$sendDateTime,'CanContinueInCaseOfError'=>$canContinueInCaseOfError];
//		} else {
//			$body   = ['Messages'=>$messages,'MobileNumbers'=>$numbers,'CanContinueInCaseOfError'=>$canContinueInCaseOfError];
//		}
//		$result     = $client->post(config('smsirlaravel.webservice-url').'api/CustomerClub/Send',['json'=>$body,'headers'=>['x-sms-ir-secure-token'=>self::getToken()],'connect_timeout'=>30]);
//
//		self::DBlog($result,$messages,$numbers);
//
//		return json_decode($result->getBody(),true);

        $token = self::getToken();
        if($token != false){
            if($sendDateTime !== null) {
                $postData = array(
                    'Messages' => (array)$messages,
                    'MobileNumbers' => (array)$numbers,
                    'SendDateTime' => $sendDateTime,
                    'CanContinueInCaseOfError' => $canContinueInCaseOfError
                );
            } else {
                $postData = array(
                    'Messages' => (array)$messages,
                    'MobileNumbers' => (array)$numbers,
                    'CanContinueInCaseOfError' => $canContinueInCaseOfError
                );
            }

            $url = config('smsirlaravel.webservice-url').'api/CustomerClub/Send';
            $CustomerClubSend = self::execute($url, $token, $postData);
            $object = json_decode($CustomerClubSend);
            self::DBlog($object,(array)$messages,(array)$numbers);

            if(is_object($object)){
                $array = get_object_vars($object);

                if(is_array($array)){

                    $result = $array['Message'];
                } else {
                    $result = false;
                }

            } else {
                $result = false;
            }

        } else {
            $result = false;
        }
        return $result;

	}

	/**
	 * this method add contact to the your customer club and then send a message to him/her
	 *
	 * @param $prefix
	 * @param $firstName
	 * @param $lastName
	 * @param $mobile
	 * @param $message
	 * @param string $birthDay
	 * @param string $categotyId
	 *
	 * @return mixed
	 */
	public static function addContactAndSend($prefix,$firstName,$lastName,$mobile,$message,$birthDay = '',$categoryId = '')
	{
//		$client = new Client();
//		$body   = ['Prefix'=>$prefix,'FirstName'=>$firstName,'LastName'=>$lastName,'Mobile'=>$mobile,'BirthDay'=>$birthDay,'CategoryId'=>$categotyId,'MessageText'=>$message];
//		$result = $client->post(config('smsirlaravel.webservice-url').'api/CustomerClub/AddContactAndSend',['json'=>[$body],'headers'=>['x-sms-ir-secure-token'=>self::getToken()],'connect_timeout'=>30]);
//
//		self::DBlog($result,$message,$mobile);
//
//		return json_decode($result->getBody(),true);

        $token = self::getToken();
        if($token != false){
            $postData = ['Prefix'=>$prefix,'FirstName'=>$firstName,'LastName'=>$lastName,'Mobile'=>$mobile,'BirthDay'=>$birthDay,'CategoryId'=>$categoryId,'MessageText'=>$message];

            $url = config('smsirlaravel.webservice-url').'api/CustomerClub/AddContactAndSend';
            $CustomerClubInsertAndSendMessage = self::execute($url, $token, $postData);
            $object = json_decode($CustomerClubInsertAndSendMessage);
            self::DBlog($object,(array) $message, (array) $mobile);

            if(is_object($object)){
                $array = get_object_vars($object);
                if(is_array($array)){
                    $result = $array['Message'];
                } else {
                    $result = false;
                }
            } else {
                $result = false;
            }

        } else {
            $result = false;
        }
        return $result;
	}

	/**
	 * this method send a verification code to your customer. need active the module at panel first.
	 *
	 * @param $code
	 * @param $number
	 *
	 * @param bool $log
	 *
	 * @return mixed
	 */
	public static function sendVerification($code,$number,$log = false)
	{
//		$client = new Client();
//		$body   = ['Code'=>$code,'MobileNumber'=>$number];
//		$result = $client->post(config('smsirlaravel.webservice-url').'api/VerificationCode',['json'=>$body,'headers'=>['x-sms-ir-secure-token'=>self::getToken()],'connect_timeout'=>30]);
//		if($log) {
//			self::DBlog($result,$code,$number);
//		}
//		return json_decode($result->getBody(),true);

        $token = self::getToken();
        if($token != false){
            $postData = array(
                'Code' => $code,
                'MobileNumber' => $number,
            );

            $url = config('smsirlaravel.webservice-url').'api/VerificationCode';
            $VerificationCode = self::execute($url, $token, $postData);
            $object = json_decode($VerificationCode);

            if($log) {
                self::DBlog($object,(array) $code, (array) $number);
            }

            if(is_object($object)){
                $array = get_object_vars($object);
                if(is_array($array)){
                    $result = $array['Message'];
                } else {
                    $result = false;
                }
            } else {
                $result = false;
            }

        } else {
            $result = false;
        }
        return $result;
	}

	/**
	 * @param array $parameters = all parameters and parameters value as an array
	 * @param $template_id = you must create a template in sms.ir and put your template id here
	 * @param $number = phone number
	 * @return mixed = the result
	 */
	public static function ultraFastSend(array $parameters, $template_id, $number) {
//		$params = [];
//		foreach ($parameters as $key => $value) {
//			$params[] = ['Parameter' => $key, 'ParameterValue' => $value];
//		}
//		$client = new Client();
//		$body   = ['ParameterArray' => $params,'TemplateId' => $template_id,'Mobile' => $number];
//		$result = $client->post(config('smsirlaravel.webservice-url').'api/UltraFastSend',['json'=>$body,'headers'=>['x-sms-ir-secure-token'=>self::getToken()],'connect_timeout'=>30]);
//
//		return json_decode($result->getBody(),true);

        $token = self::getToken();
        if($token != false){
            $params = [];
            foreach ($parameters as $key => $value) {
                $params[] = ['Parameter' => $key, 'ParameterValue' => $value];
            }
            $postData = ['ParameterArray' => $params,'TemplateId' => $template_id,'Mobile' => $number];

            $url = config('smsirlaravel.webservice-url').'api/UltraFastSend';
            $UltraFastSend = self::execute($url, $token, $postData);
            $object = json_decode($UltraFastSend);

            if(is_object($object)){
                $array = get_object_vars($object);
                if(is_array($array)){
                    $result = $array['Message'];
                } else {
                    $result = false;
                }
            } else {
                $result = false;
            }

        } else {
            $result = false;
        }
        return $result;
	}

	/**
	 * this method used for fetch received messages
	 *
	 * @param $perPage
	 * @param $pageNumber
	 * @param $formDate
	 * @param $toDate
	 *
	 * @return mixed
	 */
	public static function getReceivedMessages($perPage,$pageNumber,$formDate,$toDate)
	{
//		$client = new Client();
//		$result = $client->get(config('smsirlaravel.webservice-url')."api/ReceiveMessage?Shamsi_FromDate={$formDate}&Shamsi_ToDate={$toDate}&RowsPerPage={$perPage}&RequestedPageNumber={$pageNumber}",['headers'=>['x-sms-ir-secure-token'=>self::getToken()],'connect_timeout'=>30]);
//
//		return json_decode($result->getBody()->getContents())->Messages;

        $token = self::getToken();
        if($token != false){

            $url = config('smsirlaravel.webservice-url')."api/ReceiveMessage?Shamsi_FromDate={$formDate}&Shamsi_ToDate={$toDate}&RowsPerPage={$perPage}&RequestedPageNumber={$pageNumber}";
            $ReceiveMessageResponseByDate = self::execute($url, $token);

            $object = json_decode($ReceiveMessageResponseByDate);

            if(is_object($object)){
                $array = get_object_vars($object);
                if(is_array($array)){
                    if($array['IsSuccessful'] == true){
                        $result = $array['Messages'];
                    } else {
                        $result = $array['Message'];
                    }
                } else {
                    $result = false;
                }

            } else {
                $result = false;
            }

        } else {
            $result = false;
        }
        return $result;
	}

	/**
	 * this method used for fetch your sent messages
	 *
	 * @param $perPage = how many sms you want to fetch in every page
	 * @param $pageNumber = the page number
	 * @param $formDate = from date
	 * @param $toDate = to date
	 *
	 * @return mixed
	 */
	public static function getSentMessages($perPage,$pageNumber,$formDate,$toDate)
	{
//		$client = new Client();
//		$result = $client->get(config('smsirlaravel.webservice-url')."api/MessageSend?Shamsi_FromDate={$formDate}&Shamsi_ToDate={$toDate}&RowsPerPage={$perPage}&RequestedPageNumber={$pageNumber}",['headers'=>['x-sms-ir-secure-token'=>self::getToken()],'connect_timeout'=>30]);
//
//		return json_decode($result->getBody()->getContents())->Messages;

        $token = self::getToken();
        if($token != false){

            $url = config('smsirlaravel.webservice-url')."api/MessageSend?Shamsi_FromDate={$formDate}&Shamsi_ToDate={$toDate}&RowsPerPage={$perPage}&RequestedPageNumber={$pageNumber}";
            $SentMessageResponseByDate = self::execute($url, $token);

            $object = json_decode($SentMessageResponseByDate);

            if(is_object($object)){
                $array = get_object_vars($object);
                if(is_array($array)){
                    if($array['IsSuccessful'] == true){
                        $result = $array['Messages'];
                    } else {
                        $result = $array['Message'];
                    }
                } else {
                    $result = false;
                }

            } else {
                $result = false;
            }

        } else {
            $result = false;
        }
        return $result;
	}

	/**
	 * @param $mobile = The mobile number of that user who you wanna to delete it
	 *
	 * @return mixed = the result
	 */
	public static function deleteContact($mobile) {
//		$client = new Client();
//		$body   = ['Mobile' => $mobile, 'CanContinueInCaseOfError' => false];
//		$result = $client->post(config('smsirlaravel.webservice-url').'api/CustomerClub/DeleteContactCustomerClub',['json'=>$body,'headers'=>['x-sms-ir-secure-token'=>self::getToken()],'connect_timeout'=>30]);
//
//		return json_decode($result->getBody(),true);

        $token = self::getToken();
        if($token != false){

            $url = config('smsirlaravel.webservice-url').'api/CustomerClub/DeleteContactCustomerClub';
            $body   = ['Mobile' => $mobile, 'CanContinueInCaseOfError' => false];
            $deleteContact = self::execute($url, $token, $body);

            $object = json_decode($deleteContact);

            if(is_object($object)){
                $array = get_object_vars($object);
                if(is_array($array)){
                    if($array['IsSuccessful'] == true){
                        $result = $array['Messages'];
                    } else {
                        $result = $array['Message'];
                    }
                } else {
                    $result = false;
                }

            } else {
                $result = false;
            }

        } else {
            $result = false;
        }
        return $result;
	}

}
