<?php
require 'OAuth.php';
require 'class_db_mysql.php';

class Consulta_ApiSamsung extends bd
{

    protected  $url_base;
    protected  $consumer_key;
    protected  $consumer_secret;
    protected  $url_crearso;
    protected  $url_deleteso;
    protected  $url_getInfoSo;
    protected  $url_lista_usuarios;
    protected  $url;
    protected  $autorizacion;
    protected  $consumer;
    protected  $params;
    protected  $metodo;
    protected  $curl;
    protected  $postdata;
    protected  $response;
    protected  $from;
    protected  $to;
    protected  $token;
    protected  $asc;
    protected  $pac;
    protected  $country;
    protected  $land;
    protected $url_search;
    protected $company;
    protected $IvCreationCheck;
    protected $RequestDate;
    protected $RequestTime;



    public function __construct()
    {
        $this->url_base = "https://latamdev.ipaas.samsung.com/";
        $this->url_crearso = "latam/gcic/CreateSO/1.0/ImportSet";
        $this->url_deleteso = "latam/gcic/DeleteSOParts/1.0/ImportSet";
        $this->url_getInfoSo = "latam/gcic/GetSOInfoAll/1.0/ImportSet";
        $this->url_search='latam/gcic/GetBOMList/1.0/ImportSet';
        $this->url_lista_usuarios = '/User/List';
        $this->consumer_key = '1dd229';
        $this->consumer_secret = '43dd1bb2';

        $this->metodo = "POST";
        $this->params = array();
        $this->postdata = null;
        $this->token="Bearer b0b0c901-cc0f-3468-b5f2-29f4e5eb6adc";
        $this->asc="1123197";
        $this->country='CL';
        $this->land='SP';
        $this->company='C850';


    }

    private function get_url_user()
    {

        $this->url = $this->url_base . $this->url_lista_usuarios;
    }
    private function get_url_marcas()
    {

        $this->url = $this->url_base . $this->url_marcas;
    }

    private function get_url_crearso()
    {

        $this->url = $this->url_base . $this->url_crearso;
    }
    private function get_url_infoso()
    {

        $this->url = $this->url_base . $this->url_getInfoSo;
    }

    private function get_url_deleteSO()
    {

        $this->url = $this->url_base . $this->url_deleteso;
    }

    private function get_url_search()
    {

        $this->url = $this->url_base . $this->url_search;
    }

    private function get_comsumer()
    {
        $this->consumer = new OAuthConsumer($this->consumer_key, $this->consumer_secret, $this->url);

    }

    private function get_autorizacion()
    {

        $this->get_comsumer();
        $request_auth = OAuthRequest::from_consumer_and_token($this->consumer, null, $this->metodo, $this->url, $this->params);

        $request_auth->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), $this->consumer, null);

        $this->autorizacion = $request_auth->to_header();

    }

    private function get_pac(){
        $date=getdate();
     
     
        $this->pac=$this->asc.$date['year'].$date['mon'].$date['mday'].$date['hours'].$date['minutes'].$date['seconds'];
           
        $this->RequestDate=$date['year'].$date['mon'].$date['mday'];

        $this->RequestTime=$date['hours'].$date['minutes'].$date['seconds'];

        return $this->pac;

    }
    public function get_boy_getInfo_SO($noOrden){

        $this->postdata=json_encode([
        'IvSvcOrderNo'=>'4100173159',
        'IvAscJobNo'=>'27274847',   
                
                
        'IsCommonHeader'=>  
        ['Company'=>$this->company,
        'AscCode'=>$this->asc,
        'Lang'=> $this->land,
        'Country'=> $this->country,
        'Pac'=> $this->pac
        ]

    ]);

    }

    public function get_boy_delete_SO($noOrden){

        $this->postdata=json_encode(['IsCommonHeader'=>
           
        ['Company'=>$this->company,
        'AscCode'=>$this->asc,
        'Lang'=> $this->land,
        'Country'=> $this->country,
        'Pac'=> $this->pac
        ],
        'IvSvcOrderNo' => '4100173159',
        'IvPartsCode'=> '',
        'IvSeq' =>''

    ]);

    }

    private function get_boy_so(){

/* 
        $this->postdata='{
            "IsModelInfo": {
                "Model": "SGH-E215UKLCHE",
                "ModelVersion": "CN02",
                "SerialNo": "RS3AA89599E",
                "IMEI": "353869010215151",
                "PurchaseDate":"20190922",
                "Carrier": "",
                "Dealercode": "",
                "PurchasePlace": "27",
                "LocalInvoiceNo": "",
                "Accessory": "BATTERY",
                "DefectDesc": "Defect Description",
                "Remark": "REMARK",
                "CustComment": "COMMENT",
                "WtyException": "VOID1",
                "BosFlag": "Y",
                "PurchaseDt": "20190922",
                "CertiNo": ""
            },
            "IsHeaderInfo": {
                "AscJobNo": "202787",
                "CollectionCenter": "",
                "CollectionRefNo": "",
                "ADHPackConfirm": ""
            },
            "IsCommonHeader": {
                "Company": "'.$this->company.'",
                "AscCode": "'.$this->asc.'",
                "Lang": "'.$this->land.'",
                "Country": "'.$this->country.'",
                "Pac": "'.$this->pac.'"
            },
            "IsBpInfo": {
                "CustomerCode": "6400003520",
                "Addrnumber": ""
            },
            "IsJobInfo": {
                "SymCode1": "01",
                "SymCode2": "01",
                "SymCode3": "05",
                "SvcType": "PS",
                "ServiceDate": "20190922",
                "ServiceTime": "105021",
                "SOComment": "COMMENT",
                "Engineer": "",
                "TechId": "Steve Yoo",
                "ContactPreference": "ES",
                "EngineerName": "Steve Km",
                "QueueTokenNo": "",
                "SymCode4": "02",
                "B2BSvc": "1",
                "WtyType": "OW",
                "StRepairReason": "02",
                "RefRemark": ""
            },
            "IsBpDetail": {
                "CustFirstName": "Pamela",
                "CustLastName": "Reyes",
                "CustHomePhone": "91245678",
                "CustOfficePhone": "91356894",
                "CustMobilePhone": "98765432",
                "CustEmail": "",
                "CustAddrStreet2": "GURGAON",
                "CustAddrStreet1": "1",
                "CustCity": "GURGAON",
                "CustState": "7",
                "CustZipcode": "122001",
                "Country": "'.$this->country.'"
            },
            "IvCreationCheck": "'.$this->RequestDate.$this->RequestTime.'",
            "IsDateInfo": {
                "UntRecvDate": "",
                "UntRecvTime": "",
                "FirstAppDate": "",
                "FirstAppTime": "",
                "RequestDate": "20190922",
                "RequestTime": "155813",
                "RepairRcvDt": "",
                "RepairRcvTm": ""
            },
            "IsWtyInfo": {
                "WtyConsType": "7"
            },
            "IsExtdInfo": {
                "AlternativeMobile": "",
                "EwpAttachWarningSkip": "X"
            }
        }'; */


        $Request_array=['IsModelInfo'=>
        ['Model'=>'SM-A105M',
         'ModelVersion'=>'CN02',
         'SerialNo'=> '',
         'IMEI'=> '359310106361280',
         'PurchaseDate'=> '20190922',
         'Carrier' => '',
         'Dealercode'=> '',
         'PurchasePlace' =>'27',
         'LocalInvoiceNo'=> '',
         'Accessory'=> 'BATTERY',
         'DefectDesc'=> 'Defect Description',
         'Remark'=> 'REMARK',
         'CustComment'=> 'COMMENT',
         'WtyException'=> 'VOID1',
         'BosFlag'=> 'Y',
         'PurchaseDt'=> '20190922',
         'CertiNo'=> ''
        ],
        
        'IsHeaderInfo'=>
        ['AscJobNo'=>'27274849',
        'CollectionCenter'=>'',
        'CollectionRefNo'=> '',
        'ADHPackConfirm'=> ''
        ],

        'IsCommonHeader'=>
        ['Company'=>$this->company,
        'AscCode'=>$this->asc,
        'Lang'=> $this->land,
        'Country'=> $this->country,
        'Pac'=> $this->pac
        ],

        'IsBpInfo'=>
        ['CustomerCode'=>'6400003520',
        'Addrnumber'=>''
        ],

        'IsJobInfo'=>
        ['SymCode1'=>'01',
         'SymCode2'=>'01',
         'SymCode3'=>'05',
         'SvcType'=> 'PS',
         'ServiceDate'=> '20190922',
         'Engineer' => '',
         'TechId'=> '',
         'ContactPreference' =>'ES',
         'EngineerName'=> 'Steve Km',
         'QueueTokenNo'=> '',
         'SymCode4'=> '02',
         'B2BSvc'=> '1',
         'WtyType'=> 'OW',
         'StRepairReason'=> '02',
         'RefRemark'=> ''
        ],

        'IsBpDetail'=>
        ['CustFirstName'=>'Pamela',
         'CustLastName'=>'Reyes',
         'CustHomePhone'=> '91245678',
         'CustOfficePhone'=> '91356894',
         'CustMobilePhone'=> '98765432',
         'CustEmail' => '',
         'CustAddrStreet2' =>'GURGAON',
         'CustAddrStreet1'=> '1',
         'CustCity'=> 'GURGAON',
         'CustState'=> '7',
         'CustZipcode'=> '122001',
         'Country'=> $this->country,
    
        ],

        'IvCreationCheck' =>$this->RequestDate.$this->RequestTime,

        'IsDateInfo'=>
        ['UntRecvDate'=>'',
        'UntRecvTime'=>'',
        'FirstAppDate'=> '',
        'FirstAppTime'=> '',
        'RequestDate'=> '20190922',
        'RequestTime'=> '155813',
        'RepairRcvDt'=> '',
        'RepairRcvTm'=> '',
        ],

        'IsWtyInfo'=>
        ['WtyConsType'=>'7',
        ],

        'IsExtdInfo'=>
        ['AlternativeMobile'=>'',
        'EwpAttachWarningSkip'=>'X'
        ],

    
    
    ];
    $this->postdata=json_encode($Request_array);

    echo '<br>';
    echo '<br>';
  print_r($this->postdata);
    echo '<br>';
    echo '<br>';
 
       

    
    

   // return $this->postdata;

  /*   $this->postdata=json_encode($Request_array, JSON_PRESERVE_ZERO_FRACTION);
 
        echo '<br>';
        echo '<br>';



      


       $this->postdata= '{ "IsModelInfo": { "Model": "SGH-E215UKLCHE", "ModelVersion": "CN02", "SerialNo": "RS3AA89599E", "IMEI": "353869010215151", "PurchaseDate":"20190922", "Carrier": "", "Dealercode": "", "PurchasePlace": "27", "LocalInvoiceNo": "", "Accessory": "BATTERY", "DefectDesc": "Defect Description", "Remark": "REMARK", "CustComment": "COMMENT", "WtyException": "VOID1", "BosFlag": "Y", "PurchaseDt": "20190922", "CertiNo": "" }, "IsHeaderInfo": { "AscJobNo": "202787", "CollectionCenter": "", "CollectionRefNo": "", "ADHPackConfirm": "" }, "IsCommonHeader": { "Company": "C850", "AscCode": "1123197", "Lang": "SP", "Country": "CL", "Pac": "11231972020124123522" }, "IsBpInfo": { "CustomerCode": "6400003520", "Addrnumber": "" }, "IsJobInfo": { "SymCode1": "01", "SymCode2": "01", "SymCode3": "05", "SvcType": "PS", "ServiceDate": "20190922", "ServiceTime": "105021", "SOComment": "COMMENT", "Engineer": "", "TechId": "Steve Yoo", "ContactPreference": "ES", "EngineerName": "Steve Km", "QueueTokenNo": "", "SymCode4": "02", "B2BSvc": "1", "WtyType": "OW", "StRepairReason": "02", "RefRemark": "" }, "IsBpDetail": { "CustFirstName": "Pamela", "CustLastName": "Reyes", "CustHomePhone": "91245678", "CustOfficePhone": "91356894", "CustMobilePhone": "98765432", "CustEmail": "", "CustAddrStreet2": "GURGAON", "CustAddrStreet1": "1", "CustCity": "GURGAON", "CustState": "7", "CustZipcode": "122001", "Country": "CL" }, "IvCreationCheck": "2020124123522", "IsDateInfo": { "UntRecvDate": "", "UntRecvTime": "", "FirstAppDate": "", "FirstAppTime": "", "RequestDate": "20190922", "RequestTime": "155813", "RepairRcvDt": "", "RepairRcvTm": "" }, "IsWtyInfo": { "WtyConsType": "7" }, "IsExtdInfo": { "AlternativeMobile": "", "EwpAttachWarningSkip": "X" } }';

        
       print_r($this->postdata);
 */
       echo '<br>';
       echo '<br>';

    }

    private function get_boy_search(){

        $this->postdata='{
            "IsCommonHeader": {
                "Company": "'.$this->company.'",
                "AscCode": "'.$this->asc.'",
                "Lang": "'.$this->land.'",
                "Country":"'.$this->country.'",
                "Pac":"'.$this->pac.'"
            },
            "IvModel": "",
            "IvSerialNo": "",
            "IvVersion": "",
            "IvJobFlag": ""
        }';


    }

    private function get_curl_request()
    {
        
     
        

        $this->curl = curl_init();

        curl_setopt_array($this->curl, array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30, 
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $this->postdata,


            CURLOPT_HTTPHEADER => array(
            "asccode:" . $this->asc . "",
            "authorization: " . $this->token . "",
            "cache-control: no-cache",
            "company: C850",
            "country: Cl",
            "lang: SP",
            "pac: " . $this->pac . " ",
                "cache-control: no-cache",
                "content-type: application/json"
              

            ),
        ));

        $this->response = curl_exec($this->curl);
        $err = curl_error($this->curl);

        curl_close($this->curl);

        if ($err) {
         //   echo "cURL Error #:" . $err;
          } else {
            echo  $this->response;
          }

       
//print_r($this->postdata);
        return $this->response;

    }

    public function crearso_samsung()
    {
        $this->get_url_crearso();
        $this->get_autorizacion();
        $pac=$this->get_pac();
        $this->get_url_crearso();
        $this->get_boy_so();

       
       //print_r($this->postdata);
        $respuesta = $this->get_curl_request();
        $this->get_boy_so();
       // print_r($this->postdata);

        //echo $this->url;
        return $respuesta;

        

    }

    public function deleteso_samsung()
    {
        $this->get_url_deleteso();
        $this->get_autorizacion();
        $pac=$this->get_pac();
        
        $noOrden='4100170324';
       
        $this->get_boy_delete_SO($noOrden);

       
       print_r($this->postdata);
        $respuesta = $this->get_curl_request();
        
       // print_r($this->postdata);

        //echo $this->url;
        return $respuesta;

        

    }

    public function getInfo_samsung()
    {
        $this->get_url_infoso();
        $this->get_autorizacion();
        $pac=$this->get_pac();
        
        $noOrden='4100170324';
       
        $this->get_boy_getInfo_SO($noOrden);

       
       print_r($this->postdata);
        $respuesta = $this->get_curl_request();
        
       // print_r($this->postdata);

        //echo $this->url;
        return $respuesta;

        

    }


    public function request_lista_usuario()
    {
        $this->get_url_user();
        $this->get_autorizacion();
        $respuesta = $this->get_curl_request();
        

        return $respuesta;

    }



    public function request_marcas_usuarios($lista_usuarios)
    {
        $this->get_url_marcas();
        $this->get_autorizacion();
        $this->postdata = '{"Range":"' . $lista_usuarios . '","from":"' . $this->from . '","to":"' . $this->to. '","includeAll":0}';

        $respuestaMarcas = $this->get_curl_request();
//print_r( $respuestaMarcas);

        return $respuestaMarcas;

    }

    private function update_hours($hour)
    {

        $hour = str_replace('\'', '', $hour);

        //echo $hour;
        $ahoras = explode(":", $hour);

        $segundos = ($ahoras[1] * 100) / 60;

        $newHora = intval($ahoras[0]) . '.' . intval($segundos);

        return $newHora;

    }

    private function change_date($date_iso){

        $aDatey=substr($date_iso,0,4);
        $aDatem=substr($date_iso,4,2);
        $aDated=substr($date_iso,6,2);
         
        $nDate=$aDatey.'-'.$aDatem.'-'.$aDated;
        return $nDate;

    }

    public function set_marcas($from,$to)
    {
        
        $this->from=$from;
        $this->to=$to;
       
        parent::__construct();

        $response_lista = $this->request_lista_usuario();

        $resultados = json_decode($response_lista, true);
        $resultados2 = array_chunk($resultados, 50);
        foreach ($resultados2 as $resul) {

            foreach ($resul as $rel) {
                // echo $rel['Identifier'];
                //echo '<br>';

                $perso[] = $rel['Identifier'];

                if ($rel === end($resul)) {

                    $separado_por_comas = implode(",", $perso);

                    $jgrupos_marcas = $this->request_marcas_usuarios($separado_por_comas);
                    echo  $this->postdata ;
                    $gruposMarcas = json_decode($jgrupos_marcas, true);
                    foreach ($gruposMarcas as $marca) {

                        echo $marca['identifier'] . '__' . $marca['begins'] . '__' . $marca['worked_hours'] . '__' . $this->update_hours($marca['worked_hours']). '__' . $this->change_date($marca['begins']);
                        echo '<br>';

                        $fecha_m=$this->change_date($marca['begins']);
                        $horas_m=$this->update_hours($marca['worked_hours']);

                        $sql= "SELECT * FROM Libro_asistencia WHERE fecha='".$fecha_m."' AND RUT='". $marca['identifier']."' ";

                     
                        $queryfind=bd::query($sql);
                        $resultadosfind=bd::fetch_array($queryfind);
                        print_r($resultadosfind);
                        
                        if(empty($resultadosfind[1])){

                        $sqlInsert="INSERT INTO Libro_asistencia
                        (fecha, rut, horas_trabajadas)
                        VALUES('".$fecha_m."','".$marca['identifier'] ."', '".$horas_m."');";
                         bd::query($sqlInsert); 
                    }
                    }
                    $separado_por_comas='';
                    $perso=null;  
                }

            }
            echo '<br>';
            echo '<br>';
        }

    }

}

$lista_usuarios = "262744876";
$from="20191218050000";
$to="20191218235900";

$request = new Consulta_ApiSamsung;
//$request->request_lista_usuario();
//$request->request_marcas_usuarios($lista_usuarios);
$response=$request->crearso_samsung();
$response=$request->deleteso_samsung();

$response=$request->getInfo_samsung();


//print_r($response);



