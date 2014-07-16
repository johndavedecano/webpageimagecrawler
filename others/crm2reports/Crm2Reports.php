<?php
class Crm2Reports
{
    protected $AUTH_TOKEN = '3f19248351f4ea8f6f4eb2372529f550';

    protected $BASE_URI = '';

    protected $ZOHO_EMAIL = 'liana@wolfdene.com.au';

    protected $ZOHO_TABLE = '';

    protected $ZOHO_DBASE = 'Zoho CRM Reports';

    protected $ZOHO_ACTION = 'ADDROW';

    protected $ZOHO_OUTPUT_FORMAT = 'JSON';

    protected $ZOHO_ERROR_FORMAT = 'JSON';

    protected $ZOHO_API_VERSION = '1.0';

    protected $USER_AGENT = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13';
    
    public function __construct($category = 'Saratoga')
    {
        if($category == "Saratoga")
        {
            $this->ZOHO_TABLE = 'StockList_Saratoga';
        }else{
            $category = "Alarah";
            $this->ZOHO_TABLE = "StockList_Alarah";
        }
        $this->BASE_URI = "https://crm.zoho.com/crm/private/json/Products/getSearchRecords?authtoken=".$this->AUTH_TOKEN."&scope=crmapi&selectColumns=All&searchCondition=(Product%20Category|=|".$category.")";
    }

    private function request($url = false)
    {

        $request_url = ($url == false)?$this->BASE_URI:$url;
        return file_get_contents($request_url);
    }

    public function run()
    {
        $response = $this->object_to_array(json_decode($this->request()));
        $products = $response['response']['result']['Products']['row'];
        $flattend = array();
        $counter = 0;
        foreach($products as $key => $val)
        {
            foreach($val['FL'] as $k => $v)
            {
                $flattend[$counter][$v['val']] = $v['content'];
            }
            $flattend[$counter]['ZOHO_ACTION'] = $this->ZOHO_ACTION;
            $flattend[$counter]['ZOHO_OUTPUT_FORMAT'] = $this->ZOHO_OUTPUT_FORMAT;
            $flattend[$counter]['ZOHO_ERROR_FORMAT'] = $this->ZOHO_ERROR_FORMAT;
            $flattend[$counter]['ZOHO_API_VERSION'] = $this->ZOHO_API_VERSION;
            $flattend[$counter]['authtoken'] = $this->AUTH_TOKEN;
            $counter++;
        }

        // EXPORT TO REPORTS
        foreach($flattend as $report)
        {
            try
            {
				// DELETE EXISTING ROW
				$this->delete_row($report['PRODUCTID']);
				// CONTINUE REQUEST
				$opturl = 'https://reportsapi.zoho.com/api/'.$this->ZOHO_EMAIL.'/'.$this->ZOHO_DBASE.'/'.$this->ZOHO_TABLE;
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => $opturl,
					CURLOPT_USERAGENT => $this->USER_AGENT,
					CURLOPT_POST => 1,
					CURLOPT_POSTFIELDS => $report
				));
				$resp = curl_exec($curl);
				$this->log($resp);
				curl_close($curl);
            }catch(Exception $e)
            {
                $this->log($e->getMessage());
            }
        }
    }
	
	private function delete_row($productid)
	{
		$opturl = 'https://reportsapi.zoho.com/api/'.$this->ZOHO_EMAIL.'/'.$this->ZOHO_DBASE.'/'.$this->ZOHO_TABLE;
		$params['ZOHO_ACTION'] = 'DELETE';
		$params['ZOHO_CRITERIA'] = "('PRODUCTID' = '$productid')";
        $params['ZOHO_OUTPUT_FORMAT'] = $this->ZOHO_OUTPUT_FORMAT;
        $params['ZOHO_ERROR_FORMAT'] = $this->ZOHO_ERROR_FORMAT;
        $params['ZOHO_API_VERSION'] = $this->ZOHO_API_VERSION;
        $params['authtoken'] = $this->AUTH_TOKEN;
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $opturl,
			CURLOPT_USERAGENT => $this->USER_AGENT,
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => $params
		));
		$resp = curl_exec($curl);
		$this->log($resp);
		curl_close($curl);
	}

    private function object_to_array($obj) {
        if(is_object($obj)) $obj = (array) $obj;
        if(is_array($obj)) {
            $new = array();
            foreach($obj as $key => $val) {
                $new[$key] = $this->object_to_array($val);
            }
        }
        else $new = $obj;
        return $new;       
    }

    private function log($text)
    {
		$filename = "response.log";
		$fh = fopen($filename, "a") or die("Could not open log file.");
		fwrite($fh, date("d-m-Y, H:i")." - $text\n") or die("Could not write file!");
		fclose($fh);
    }
}