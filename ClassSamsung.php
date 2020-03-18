<?php
require 'OAuth.php';
require 'class_db_mysql.php';

class Consulta_ApiSamsung extends bd
{

    protected $url_base;
    protected $consumer_key;
    protected $consumer_secret;
    protected $url_crearso;
    protected $url_changeSo;
    protected $url_deleteso;
    protected $url_getInfoSo;
    protected $url_getSOInfoAll;
    protected $url_lista_usuarios;
    protected $url_getBomList;
    protected $url_AddSOAttachFile;
    protected $url_GetEngineerList;
    protected $url_getEginnerInfo;
    protected $url_CheckWarranty;
    protected $url_GetWarrantyList;
    protected $url_ChangeSODeliveryInfo;
    protected $url_ModifyEngineerInfo;
    protected $url_GetPartsInfo;
    protected $url_GetModellist;
    protected $url;
    protected $autorizacion;
    protected $consumer;
    protected $params;
    protected $metodo;
    protected $curl;
    protected $postdata;
    protected $response;
    protected $from;
    protected $to;
    protected $token;
    protected $asc;
    protected $pac;
    protected $country;
    protected $land;
    protected $url_search;
    protected $company;
    protected $IvCreationCheck;
    protected $RequestDate;
    protected $RequestTime;
    protected $filesize;
    protected $imgBase64;

    public function __construct()
    {
        $this->url_base = "https://latamdev.ipaas.samsung.com/";
        $this->url_crearso = "latam/gcic/CreateSO/1.0/ImportSet";
        $this->url_changeSo = "latam/gcic/ChangeSO/1.0/ImportSet";
        $this->url_deleteso = "latam/gcic/DeleteSOParts/1.0/ImportSet";
        $this->url_getInfoSo = "latam/gcic/GetSOInfoAll/1.0/ImportSet";
        $this->url_getBomList = 'latam/gcic/GetBOMList/1.0/ImportSet';
        $this->url_AddSOAttachFile = "latam/gcic/AddSOAttachFile/1.0/ImportSet";
        $this->url_getEginnerInfo = "latam/gcic/GetEngineerInfo/1.0/ImportSet";
        $this->url_GetEngineerList = "latam/gcic/GetEngineerList/1.0/ImportSet";
        $this->url_CheckWarranty = "latam/gcic/CheckWarranty/1.0/ImportSet";
        $this->url_GetWarrantyList = "latam/gcic/GetWarrantyList/1.0/ImportSet";
        $this->url_ChangeSODeliveryInfo = "latam/gcic/ChangeSODeliveryInfo/1.0/ImportSet";
        $this->url_ModifyEngineerInfo="latam/gcic/ModifyEngineerInfo/1.0/ImportSet";
        $this->url_GetPartsInfo="latam/gcic/GetPartsInfo/1.0/ImportSet";
        $this->url_GetModellist="latam/gcic/GetModellist/1.0/ImportSet";
        $this->url_lista_usuarios = '/User/List';
        $this->consumer_key = '1dd229';
        $this->consumer_secret = '43dd1bb2';

        $this->metodo = "POST";
        $this->params = array();
        $this->postdata = null;
        $this->token = "Bearer b0b0c901-cc0f-3468-b5f2-29f4e5eb6adc";
        $this->asc = "1123197";
        $this->country = 'CL';
        $this->land = 'SP';
        $this->company = 'C850';
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

    private function get_url_changeso()
    {

        $this->url = $this->url_base . $this->url_changeSo;
    }
    private function get_url_infoso()
    {

        $this->url = $this->url_base . $this->url_getInfoSo;
    }

    private function get_url_deleteSO()
    {

        $this->url = $this->url_base . $this->url_deleteso;
    }
    private function get_url_getBomList()
    {

        $this->url = $this->url_base . $this->url_getBomList;
    }

    private function get_url_search()
    {

        $this->url = $this->url_base . $this->url_search;
    }
    private function get_url_AddSOAttachFile()
    {

        $this->url = $this->url_base . $this->url_AddSOAttachFile;
    }
    private function get_url_GetEngineerInfo()
    {

        $this->url = $this->url_base . $this->url_getEginnerInfo;
    }

    private function get_url_GetEngineerList()
    {

        $this->url = $this->url_base . $this->url_GetEngineerList;
    }
    private function get_url_CheckWarranty()
    {

        $this->url = $this->url_base . $this->url_CheckWarranty;
    }
    private function get_url_GetWarrantyList()
    {

        $this->url = $this->url_base . $this->url_GetWarrantyList;
    }
    private function get_url_ChangeSODeliveryInfo()
    {

        $this->url = $this->url_base . $this->url_ChangeSODeliveryInfo;
    }

    private function get_url_ModifyEngineerInfo()
    {

        $this->url = $this->url_base . $this->url_ModifyEngineerInfo;
    }

    private function get_url_GetPartsInfo()
    {

        $this->url = $this->url_base . $this->url_GetPartsInfo;
    }

    private function get_url_GetModellist()
    {

        $this->url = $this->url_base . $this->url_GetModellist;
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

    private function get_pac()
    {
        $date = getdate();

        $day = str_pad($date['mday'], 2, "0", STR_PAD_LEFT);
        $mes = str_pad($date['mon'], 2, "0", STR_PAD_LEFT);

        $this->pac = $this->asc . $date['year'] . $mes . $day . $date['hours'] . $date['minutes'] . $date['seconds'];

        $this->RequestDate = $date['year'] . $mes . $day;

        $this->RequestTime = $date['hours'] . $date['minutes'] . $date['seconds'];

        return $this->pac;
    }
    public function get_body_getInfo_SO($noOrden)
    {

        $this->postdata = json_encode([
            'IvSvcOrderNo' => '4100173574',
            'IvAscJobNo' => '28475960',

            'IsCommonHeader' =>
            [
                'Company' => $this->company,
                'AscCode' => $this->asc,
                'Lang' => $this->land,
                'Country' => $this->country,
                'Pac' => $this->pac,
            ],

        ]);
    }

    public function get_boy_delete_SO($noOrden)
    {

        $this->postdata = json_encode([
            'IsCommonHeader' =>

            [
                'Company' => $this->company,
                'AscCode' => $this->asc,
                'Lang' => $this->land,
                'Country' => $this->country,
                'Pac' => $this->pac,
            ],
            'IvSvcOrderNo' => '4100173159',
            'IvPartsCode' => '',
            'IvSeq' => '',

        ]);
    }

    public function set_traspaso()
    {

        $this->postdata = json_encode([

            'id' => '235678',
            'BodegaOrigen ' => '1000',
            'SubBodegaOrigen' => '410',
            'BodegaDestino' => '1000',
            'SubBodegaDestino ' => '410',

            'Lineas' => [
                [
                    'SKU' => 'P001109P0001',
                    'Cantidad' => '1',

                ], [
                    'SKU' => 'P001109P0005',
                    'Cantidad' => '1',
                ],
            ],

        ]);
    }

    private function get_body_so()
    {

        echo $this->RequestTime;

        $Request_array = [
            'IsModelInfo' =>
            [
                'Model' => 'SGH-E215L',
                'ModelVersion' => 'CN02',
                'SerialNo' => 'RUWQ905142R',
                'IMEI' => '355468020639183',
                'PurchaseDate' => '20190927',
                'Carrier' => '',
                'Dealercode' => '',
                'PurchasePlace' => '27',
                'LocalInvoiceNo' => '',
                'Accessory' => 'bluetooth',
                'DefectDesc' => 'cosmetic',
                'Remark' => 'REMARK',
                'CustComment' => 'COMMENT',
                'WtyException' => 'VOID1',
                'BosFlag' => 'Y',
                'PurchaseDt' => '20190927',
                'CertiNo' => '',
            ],

            'IsHeaderInfo' =>
            [
                'AscJobNo' => '28475962',
                'CollectionCenter' => '',
                'CollectionRefNo' => '',
                'ADHPackConfirm' => '',
            ],

            'IsCommonHeader' =>
            [
                'Company' => $this->company,
                'AscCode' => $this->asc,
                'Lang' => $this->land,
                'Country' => $this->country,
                'Pac' => $this->pac,
            ],

            'IsBpInfo' =>
            [
                'CustomerCode' => '6400003520',
                'Addrnumber' => '',
            ],

            'IsJobInfo' =>
            [
                'SymCode1' => '01',
                'SymCode2' => '01',
                'SymCode3' => '05',
                'SvcType' => 'PS',
                'ServiceDate' => '20190927',
                'Engineer' => '',
                'TechId' => '',
                'ContactPreference' => 'ES',
                'EngineerName' => 'Steve Km',
                'QueueTokenNo' => '',
                'SymCode4' => '02',
                'B2BSvc' => '1',
                'WtyType' => 'OW',// OW 
                'StRepairReason' => '01',//02
                'RefRemark' => '',
            ],

            'IsBpDetail' =>
            [
                'CustFirstName' => 'Pamela',
                'CustLastName' => 'Reyes',
                'CustHomePhone' => '91245678',
                'CustOfficePhone' => '91356894',
                'CustMobilePhone' => '98765432',
                'CustEmail' => '',
                'CustAddrStreet2' => 'GURGAON',
                'CustAddrStreet1' => '1',
                'CustCity' => 'GURGAON',
                'CustState' => '7',
                'CustZipcode' => '122001',
                'Country' => $this->country,

            ],

            'IvCreationCheck' => $this->RequestDate . $this->RequestTime,

            'IsDateInfo' =>
            [
                'UntRecvDate' => '',
                'UntRecvTime' => '',
                'FirstAppDate' => '',
                'FirstAppTime' => '',
                'RequestDate' =>  '20200311', //$this->RequestDate, //'20190922',
                'RequestTime' => '214639',//'155813',
                'RepairRcvDt' => '',
                'RepairRcvTm' => '',
            ],

            'IsWtyInfo' =>
            [
                'WtyConsType' => '7',
            ],

            'IsExtdInfo' =>
            [
                'AlternativeMobile' => '',
                'EwpAttachWarningSkip' => 'X',
            ],

        ];
        $this->postdata = json_encode($Request_array);

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

    private function get_body_changeSo()
    {

        $Request_array = [
            'IsModelInfo' =>
            [
                'Model' => 'SGH-E256',
                'ModelVersion' => 'CN02',
                'SerialNo' => 'R1XQ185046T',
                'IMEI' => '354004012492249',
                'PurchaseDate' => '20190927',
                'Carrier' => '4027',
                'Dealercode' => '',
                'PurchasePlace' => '27',
                'LocalInvoiceNo' => '',
                'Accessory' => 'TOUCH', //TOUCH BATTERY
                'DefectDesc' => 'FALLA DE TOUCH',
                'Remark' => 'REMARK',
                'CustComment' => 'COMMENT',
                'WtyException' => 'VOID2',//VOID1
                'BosFlag' => 'Y',
            ],

            'IsCommonHeader' =>
            [
                'Company' => $this->company,
                'AscCode' => $this->asc,
                'Lang' => $this->land,
                'Country' => $this->country,
                'Pac' => $this->pac,
            ],

            'IsShipInfo' =>
            [
                'UnitReceivedDate' => '20200208',
                'UnitReceivedTime' => '100233',
                'UnitSendToAscDate' => '',
                'UnitSendToAscTime' => '',
                'UnitShipFromAscDate' => '',
                'UnitShipFromAscTime' => '',
                'UnitSendToCustDate' => '',
                'UnitSendToCustTime' => '',
                'ShipCarrier' => 'SHC001',
                'ShipOutTrackingNo' => '1942064',
                'ShipInTrackingNo' => '1942064',
                'RepairReqDate' => '20200305', //
                'ShopGiDate' => '',
                'ShopGrDate' => '',
                'CustReturnDate' => '',
                'RepairReqTime' => '',
                'ShopGiTime' => '',
                'ShopGrTime' => '',
                'CustReturnTime' => '',
            ],

            'IsBpInfo' =>
            [
                'CustomerCode' => '6400003520',
                'CustHomePhone' => '91234569',
                'CustMobilePhone' => '91234569',
                'CustAddrStreet1' => '999 ANY  Street',
            ],

            'IsBpDetail' =>
            [
                'CustFirstName' => 'Pamela',
                'CustLastName' => 'Reyes',
                'CustHomePhone' => '91245678',
                'CustOfficePhone' => '91356894',
                'CustMobilePhone' => '98765432',
                'CustEmail' => '',
                'CustAddrStreet2' => 'GURGAON',
                'CustAddrStreet1' => '1',
                'CustCity' => 'GURGAON',
                'CustState' => '7',
                'CustZipcode' => '122001',
                'Country' => $this->country,

            ],

            'IsHeaderInfo' =>
            [
                'SvcOrderNo' => '4100173575',
                'AscJobNo' => '28475961',
                'CollectionCenter' => '',
                'WtyFlag' => 'X',
                'CollectionRefNo' => '',
            ],

            'IsJobInfo' =>
            [
                'SymCode1' => '01',
                'SymCode2' => '01',
                'SymCode3' => '05',
                'SvcType' => 'PS',
                'StRepairReason' => '',
                'Status' => 'ST040',
                'StReason' => 'HG005',
                'ChangedDate' => '',
                'ChangedTime' => '',
                'SOComment' => '',
                'UnitLocation' => '',
                'NewModel' => '',
                'NewSerialNo' => '',
                'NewIMEI' => '',
                'NewFirmware' => '',
                'Engineer' => '6486400431', // 6486400431
                'TechId' => '',
                'ContactPreference' => '',
                'EngineerName' => '',
                'DupProblem' => 'Y',
                'SupportFlag' => 'Y',
                'ResourceType' => '',
                'RedoFeedback' => 'test',
                'RedoReason' => '',
                'QueueTokenNo' => '',
                'SymCode4' => '02',
                'B2BSvc' => '1',
                'WtyType' => 'OW',
                'SubSvcType' => '',
                'StRepairReason' => '01',
                'RefRemark' => '',
            ],

            'IsDateInfo' =>
            [
                'RequestDate' => '20200227',//$this->RequestDate,
                'RequestTime' => '214639',//$this->RequestTime
                'UnitRcvDate' => '20200227',//$this->RequestDate
                'UnitRcvTime' => '214639',//$this->RequestTime
                'FirstAppDate' => '',
                'FirstAppTime' => '',
                'LastAppDate' => '',
                'LastAppTime' => '',
                'LastAppDate' => '',
                'FirstVisitDate' => '',
                'FirstVisitTime' => '',
                'LastVisitDate' => '',
                'LastVisitTime' => '',
                'EngAssignDate' => '',
                'EngAssignTime' => '',
                'CompleteDate' => '',
                'CompleteTime' => '',
                'GoodsDeliveryDate' => '20200305',//
                'GoodsDeliveryTime' => '120000',//
                'EstRepairDate' => '',
                'CallAttempt1Date' => '',
                'CallAttempt1Time' => '',
                'CallAttempt2Date' => '',
                'CallAttempt2Time' => '',
                'CallAttempt3Date' => '',
                'CallAttempt3Time' => '',
                'RepairRcvDt' => '',
                'RepairRcvTm' => '',
            ],

            'IsWtyInfo' =>
            [
                'LaborType' => 'MN', // MN
                'WtyRepairType' => 'PS',//OW PS
                'IrisCondition' => '1', //1 3
                'IrisDefect' => 'A', //A B
                'IrisRepair' => 'X01',//01 X01
                'IrisSymption' => 'T22',// T21 HA1
                'DefectBlock' => '1A03',//104A 1A03
                'WtyRepairDesc' => '',
                'GasCharge' => '',
                'Distance' => '',
                'TransportationAmount' => '',
                'OthersAmount' => '',
                'OthersRemark' => 'comment',
                'WtyConsType' => '',
                'SvcIndicator' => '',
                'AuthNo' => '',
            ],
            'IvCreationCheck' => $this->RequestDate . $this->RequestTime,

            /*    'ItPartsInfo'=>
            [['SeqNo'=>'',
            'PartsNo'=>'',
            'PartsQty'=> '',
            'InvoiceNo'=> '',
            'WtyType'=> '',
            'PartStatus' => '',
            'RepairLocation' =>'',
            'DefectSerialNo'=> '',
            'PoNo'=> '',
            'InvoiceItemNo'=> '',

            ]],  */

            'ItPartsInfoOld' =>
            [[
                'SeqNo' => '',
                'PartsNo' => '',
                'DefectSerialNo' => '',
            ]],

            'IsExtdInfo' =>
            [
                'AlternativeMobile' => '',
                'EwpAttachWarningSkip' => 'X',
            ],

        ];
        $this->postdata = json_encode($Request_array);

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

    private function get_body_ChangeSoDelivery(){

       $this->postdata= json_encode([
        "IsInputValue"=>[
            'RepairReqDate' => '20200305',//$this->RequestDate,
            'RepairReqTime' => '214640',//$this->RequestTime
            'ShopGiDate' => '20200227',//$this->RequestDate
            'ShopGiTime' => '214639',//$this->RequestTime
            'UnitReceivedDate' => '20200227',
            'UnitReceivedTime' => '214639',
            'UnitSendToAscDate' => '',
            'UnitSendToAscTime' => '',
            'ShipInTrackingNo' => '',
            'UnitShipFromAscDate' => '',
            'UnitShipFromAscTime' => '',
            'UnitSendToCustDate' => '',
            'UnitSendToCustTime' => '',
            'ShipOutTrackingNo' => '',
            'ShipCarrier' => '',
            'ShopGrDate' => '',
            'ShopGrTime' => '',
            'CustReturnDate' => '',
            'CustReturnTime' => ''
        ], 'IsCommonHeader' =>
        [
            'Company' => $this->company,
            'AscCode' => $this->asc,
            'Lang' => $this->land,
            'Country' => $this->country,
            'Pac' => $this->pac,
        ],
        "IvObjectId"=>"4100173575",
        "IvLangu"=>"E"


    ]);




    }

    private function get_body_getBomList()
    {

        $this->postdata = '{
            "IsCommonHeader": {
                "Company": "' . $this->company . '",
                "AscCode": "' . $this->asc . '",
                "Lang": "' . $this->land . '",
                "Country":"' . $this->country . '",
                "Pac":"' . $this->pac . '"
            },
            "IvModel": "",
            "IvSerialNo": "",
            "IvVersion": "",
            "IvJobFlag": ""
        }';
    }

    private function get_body_AddSOAttachFile()
    {
       //$this->imgBase64='';

        $this->postdata = json_encode([
            'IsCommonHeader' =>

            [
                'Company' => $this->company,
                'AscCode' => $this->asc,
                'Lang' => $this->land,
                'Country' => $this->country,
                'Pac' => $this->pac,
            ],
            'IvDocClass' => '',
            'IvFileName' => 'prueba4.jpg',
            'IvFileSize' => $this->filesize,
            'IvFileStream' => $this->imgBase64,
            'IvSvcOrderNo' => '4100173159',
            'IvDesc' => 'ATT01',

        ]);

       

        $this->postdata=' {
            "IsCommonHeader":{
               "Company":"'.$this->company.'",
               "AscCode":"'.$this->asc.'",
               "Lang":"'.$this->land.'",
               "Country":"'.$this->country.'",
               "Pac":"'.$this->pac.'"
            },
            "IvDocClass":"",
            "IvFileName":"FILE__3.JPG",
            "IvFileSize":"'.$this->filesize.'",
            "IvFileStream":"'.$this->imgBase64.'",
            "IvSvcOrderNo":"4100173159",
            "IvDesc":"ATT99"
         }';
       
        
    }

    private function get_body_ModifyEngineerInfo()
    {

        $this->postdata = json_encode([
            'IsCommonHeader' =>

            [
                'Company' => $this->company,
                'AscCode' => $this->asc,
                'Lang' => $this->land,
                'Country' => $this->country,
                'Pac' => $this->pac,
            ],

            'IsEngrAddr' =>

            [
                'NameFirst' => "Carlos",
                'NameLast' => "Galeano",
                'OfficePhone' => "",
                'MobilePhone' => "",
                'Fax' => "",
                'OfficePhoneExt' => "",
                'Street1' => "",
                'Street2' => "",
                'Street3' => "",
                'City' => "",
                'District' => "",
                'RegionCode' => "",
                'Country' => "CL",
                'PostCode' => "",
                'Email' => "",
            ],
            'IsEngrInfo' =>

            [
                'AscEngineer' => "Carlos Galeano",
                'WorkStatus' => "W",
                'EngType' => "A",
                'Capacity' => "50",
                'RepairTime' => "10",
                'HieredDay' => "20191201",
                'ResignDay' => "",
                'ResignCode' => "",
                'Qualified' => ""
            ],
           
            "IvSupervisor" => "",
            "IvSupervisorFlag" => "N",
            "IvEngineer" => "6486400431"
        ]);
    }

    
    private function get_body_GetEngineerInfo()
    {

        $this->postdata = json_encode([
            'IsCommonHeader' =>

            [
                'Company' => $this->company,
                'AscCode' => $this->asc,
                'Lang' => $this->land,
                'Country' => $this->country,
                'Pac' => $this->pac,
            ],
            "IvEngineer" => "6486400431",
        ]);
    }

    private function get_body_GetEngineerList()
    {

        $this->postdata = json_encode([
            'IsCommonHeader' =>

            [
                'Company' => $this->company,
                'AscCode' => $this->asc,
                'Lang' => $this->land,
                'Country' => $this->country,
                'Pac' => $this->pac,
            ],
            "IvEngineer" => "6486400431",
            "IvWorkStatus" => "W",
        ]);
    }

    private function get_body_GetPartsInfo()
    {

        $this->postdata = json_encode([
            'IsCommonHeader' =>

            [
                'Company' => $this->company,
                'AscCode' => $this->asc,
                'Lang' => $this->land,
                'Country' => $this->country,
                'Pac' => $this->pac,
            ],
            "IvPartsNo" => "GH42-06007A",
            
        ]);
    }

    private function get_body_GetModellist()
    {

        $this->postdata = json_encode([
            'IsCommonHeader' =>

            [
                'Company' => $this->company,
                'AscCode' => $this->asc,
                'Lang' => $this->land,
                'Country' => $this->country,
                'Pac' => $this->pac,
            ],
            "IvFromChgdate"=>  "20200101",
            "IvToChgdate"=>  "20200312",
            "IvCicPrcd"=> "HHP"
            
        ]);
    }


    private function get_body_CheckWarranty()
    {

        $this->postdata = json_encode([
            'IsCommonHeader' =>

            [
                'Company' => $this->company,
                'AscCode' => $this->asc,
                'Lang' => $this->land,
                'Country' => $this->country,
                'Pac' => $this->pac,
            ],
            "IvCustomerCode" => "",
            "IvIMEI" => "354961011328718",
            "IvModel" => "SGH-C516DSACHE",
            "IvSvcOrderNo" => "4100173531",
            "IvProductDate" => "",
            "IvPurchaseDate" => "20190922",
            "IvSerialNo" => "R3TP931643B",
            "IvSvcType" => "PS",
            "IvWtyException" => "",
        ]);
    }

    private function get_body_GetWarrantyList()
    {

        $this->postdata = json_encode([
            'IsCommonHeader' =>

            [
                'Company' => $this->company,
                'AscCode' => $this->asc,
                'Lang' => $this->land,
                'Country' => $this->country,
                'Pac' => $this->pac,
            ],
            "IvCreateDateFrom" => "20200129",
            "IvFiscalMonth" => "",
            "IvAscJobNo" => "27274847",
            "IvIMEI" => "359048082905692",
            "IvSerialNo" => "R58J5543BJL",
            "IvWtyStatus" => "10",
            "IvSvcType" => "PS",
            "IvWtySymptomCode" => "",
            "IvCreateDateTo" => "",
            "IvSvcOrderNo" => "20190922",
            "IvWtyBillNo" => "",

        ]);
    }

    public function resize_image($file, $w, $h, $crop = false)
    {
        putenv('GDFONTPATH=' . realpath('.'));

        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width - ($width * abs($r - $w / $h)));
            } else {
                $height = ceil($height - ($height * abs($r - $w / $h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w / $h > $r) {
                $newwidth = $h * $r;
                $newheight = $h;
            } else {
                $newheight = $w / $r;
                $newwidth = $w;
            }
        }

        print_r($file);
        $src = imagecreatefromjpeg($file);
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        return $dst;
    }

    public function transforme_imagen()
    {

        // Nombre de la imagen
        $path = 'a51.jpg';

       // header('Content-Type: image/jpeg');

       // $this->resize_image($path,150,70);
     
       $salida= array(); 
       //exec("python contar.py '".$path."'",$salida);
       exec("python imagenresize.py '".$path."'",$salida);
       //echo $salida[0];

       $path=$salida[0];
        // Extensión de la imagen
        $type = pathinfo($path, PATHINFO_EXTENSION);

        // Cargando la imagen
        $data = file_get_contents($path);

        // Decodificando la imagen en base64
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $this->imgBase64 = base64_encode($data);

         //echo $this->imgBase64;

        $this->base64ToImage($base64, 'a58.jpg');

        /* $image = new Imagick('a53.jpg');
        $image->setOption('jpeg:size', '800x532');
        $image->readImage('foo.jpg');   */

        $bytes=filesize($path);

        $tamañoKB = number_format($bytes / 1024, 2) ;
        $this->filesize=strval(round($tamañoKB));
    }

    public function base64ToImage($base64_string, $output_file)
    {
        $file = fopen($output_file, "wb");

        $data = explode(',', $base64_string);

        fwrite($file, base64_decode($data[1]));
        fclose($file);

        return $output_file;
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
                "content-type: application/json",

            ),
        ));

        $this->response = curl_exec($this->curl);
        $err = curl_error($this->curl);

        curl_close($this->curl);

        if ($err) {
            //   echo "cURL Error #:" . $err;
        } else {
            echo $this->response;
        }

        //print_r($this->postdata);
        return $this->response;
    }

    public function crearso_samsung()
    {
        $this->get_url_crearso();
        $this->get_autorizacion();
        $pac = $this->get_pac();
        $this->get_url_crearso();
        $this->get_body_so();

        //print_r($this->postdata);
        $respuesta = $this->get_curl_request();
        // $this->get_boy_so();
        // print_r($this->postdata);

        //echo $this->url;
        return $respuesta;
    }

    public function getBomList()
    {
        $this->get_url_getBomList();
        $this->get_autorizacion();
        $pac = $this->get_pac();
        $this->get_body_getBomList();

        print_r($this->postdata);
        $respuesta = $this->get_curl_request();

        // print_r($this->postdata);

        //echo $this->url;
        return $respuesta;
    }

    public function changeso_samsung()
    {
        $this->get_url_changeSo();
        $this->get_autorizacion();
        $pac = $this->get_pac();

        $this->get_body_changeSo();

        //print_r($this->postdata);
        $respuesta = '';
        $respuesta = $this->get_curl_request();
        //$this->get_boy_so();
        // print_r($this->postdata);

        //echo $this->url;
        return $respuesta;
    }

    public function ChangeSODeliveryInfo(){

     $this->get_url_ChangeSODeliveryInfo();
     $this->get_autorizacion();
     $this->get_body_ChangeSoDelivery();
     $pac = $this->get_pac();
     print_r($this->postdata);

     echo "<br>";
     echo "<br>";
     $respuesta = $this->get_curl_request();


    }

    public function deleteso_samsung()
    {
        $this->get_url_deleteso();
        $this->get_autorizacion();
        $pac = $this->get_pac();

        $noOrden = '4100170324';

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
        $pac = $this->get_pac();

        $noOrden = '4100170324';

        $this->get_body_getInfo_SO($noOrden);

        print_r($this->postdata);
        $respuesta = $this->get_curl_request();

        //$this->set_traspaso();

        print_r($this->postdata);

        // print_r($this->postdata);

        //echo $this->url;
        return $respuesta;
    }

    public function addfileSO()
    {
        $this->get_url_AddSOAttachFile();
        $this->get_autorizacion();
        $pac = $this->get_pac();

        $noOrden = '4100170324';

        //$this->get_boy_getInfo_SO($noOrden);

        $this->get_body_AddSOAttachFile();



        print_r($this->postdata);

        echo "<br>";

        $consulta=json_decode($this->postdata);

        //print_r($consulta);
        $respuesta = $this->get_curl_request();

        //$this->set_traspaso();

        //print_r($this->postdata);

        // print_r($this->postdata);

        //echo $this->url;
        return $respuesta;
    }

    public function getEnginnerInfo()
    {
        $this->get_url_GetEngineerInfo();
        $this->get_autorizacion();
        $pac = $this->get_pac();

        $this->get_body_GetEngineerInfo();

        print_r($this->postdata);
        $respuesta = $this->get_curl_request();

        return $respuesta;
    }

    public function GetEngineerList()
    {
        $this->get_url_GetEngineerList();
        $this->get_autorizacion();
        $pac = $this->get_pac();

        $this->get_body_GetEngineerList();

        print_r($this->postdata);
        $respuesta = $this->get_curl_request();

        //echo $this->url;
        return $respuesta;
    }

    public function GetModellist()
    {
        $this->get_url_GetModellist();
        $this->get_autorizacion();
        $pac = $this->get_pac();

        $this->get_body_GetModellist();

        print_r($this->postdata);
        $respuesta = $this->get_curl_request();

        //echo $this->url;
        return $respuesta;
    }

    public function ModifyEngineerInfo()
    {
        $this->get_url_ModifyEngineerInfo();
        $this->get_autorizacion();
        $pac = $this->get_pac();
        echo $this->get_url_ModifyEngineerInfo();
        $this->get_body_ModifyEngineerInfo();

        print_r($this->postdata);
        $respuesta = $this->get_curl_request();

        //echo $this->url;
        return $respuesta;
    }

    public function GetPartsInfo()
    {
        $this->get_url_GetPartsInfo();
        $this->get_autorizacion();
        $pac = $this->get_pac();
       
        $this->get_body_GetPartsInfo();

        print_r($this->postdata);
        $respuesta = $this->get_curl_request();

        //echo $this->url;
        return $respuesta;
    }


    public function CheckWarranty()
    {
        $this->get_url_CheckWarranty();
        $this->get_autorizacion();
        $pac = $this->get_pac();

        $this->get_body_CheckWarranty();
        echo "<br>";
        echo "<br>";

        print_r($this->postdata);
        echo "<br>";
        echo "<br>";
        $respuesta = $this->get_curl_request();

        //echo $this->url;
        return $respuesta;
    }

    public function GetWarrantyList()
    {
        $this->get_url_GetWarrantyList();
        $this->get_autorizacion();
        $pac = $this->get_pac();

        $this->get_body_GetWarrantyList();
        echo "<br>";
        echo "<br>";

        print_r($this->postdata);
        echo "<br>";
        echo "<br>";
        $respuesta = $this->get_curl_request();

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
        $this->postdata = '{"Range":"' . $lista_usuarios . '","from":"' . $this->from . '","to":"' . $this->to . '","includeAll":0}';

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

    private function change_date($date_iso)
    {

        $aDatey = substr($date_iso, 0, 4);
        $aDatem = substr($date_iso, 4, 2);
        $aDated = substr($date_iso, 6, 2);

        $nDate = $aDatey . '-' . $aDatem . '-' . $aDated;
        return $nDate;
    }

    public function set_marcas($from, $to)
    {

        $this->from = $from;
        $this->to = $to;

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
                    echo $this->postdata;
                    $gruposMarcas = json_decode($jgrupos_marcas, true);
                    foreach ($gruposMarcas as $marca) {

                        echo $marca['identifier'] . '__' . $marca['begins'] . '__' . $marca['worked_hours'] . '__' . $this->update_hours($marca['worked_hours']) . '__' . $this->change_date($marca['begins']);
                        echo '<br>';

                        $fecha_m = $this->change_date($marca['begins']);
                        $horas_m = $this->update_hours($marca['worked_hours']);

                        $sql = "SELECT * FROM Libro_asistencia WHERE fecha='" . $fecha_m . "' AND RUT='" . $marca['identifier'] . "' ";

                        $queryfind = bd::query($sql);
                        $resultadosfind = bd::fetch_array($queryfind);
                        print_r($resultadosfind);

                        if (empty($resultadosfind[1])) {

                            $sqlInsert = "INSERT INTO Libro_asistencia
                        (fecha, rut, horas_trabajadas)
                        VALUES('" . $fecha_m . "','" . $marca['identifier'] . "', '" . $horas_m . "');";
                            bd::query($sqlInsert);
                        }
                    }
                    $separado_por_comas = '';
                    $perso = null;
                }
            }
            echo '<br>';
            echo '<br>';
        }
    }
}

$lista_usuarios = "262744876";
$from = "20191218050000";
$to = "20191218235900";

$request = new Consulta_ApiSamsung;
//$request->request_lista_usuario();
//$request->request_marcas_usuarios($lista_usuarios);

/* $response=$request->crearso_samsung();
$response=$request->deleteso_samsung();

$response=$request->getInfo_samsung();

$response=$request->changeso_samsung();
$response=$request->getBomList(); */

//$response=$request->getInfo_samsung();

//$request->transforme_imagen();
//$response = $request->addfileSO();

//$response = $request->changeso_samsung();
//$request->getEnginnerInfo();

//$response=$request->changeso_samsung();

$response=$request->ChangeSODeliveryInfo();

//$response=$request->GetPartsInfo();

//$response=$request->GetModellist();

//$request->GetEngineerList();
//$request->CheckWarranty();

//$request->GetWarrantyList();

//$response=$request->crearso_samsung();

//$response=$request->transforme_imagen();

//print_r($response);

//$response=$request->changeso_samsung();

//$response=$request->crearso_samsung();

//$response=$request->getBomList();

//$response=$request->getInfo_samsung();