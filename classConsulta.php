<?php
//require 'OAuth.php';
require 'class_bd.php';

class Consulta_Api extends bd
{

    protected  $url_traspaso;

    protected  $metodo;
    protected  $url;
    protected  $curl;
    protected  $postdata;
    protected  $response;





    public function __construct()
    {

        $this->url_traspaso = "http://192.168.80.70:80/webservices/logyservice/inventory/movements/transfer";


        $this->metodo = "POST";
        $this->params = array();
        $this->postdata = null;
    }


    private function get_url_traspaso()
    {

        $this->url = $this->url_traspaso;
    }


    public function get_body_traspaso($item)
    {


        $sku[] = ['SKU' => strtoupper($item['SKU']), "Cantidad" => $item['qty']];
        //$sku[]=['SKU'=>'P001109P0005',"Cantidad"=> "1"];

        $this->postdata = json_encode([

            'id' => $item['gti'],
            'BodegaOrigen' => $item['Bodega_ID'],
            'SubBodegaOrigen' => $item['SubBodega_ID'],
            'BodegaDestino' => $item['Bodega_ID_REC'],
            'SubBodegaDestino' => $item['SubBodega_ID_REC'],

            'Lineas' =>
            $sku

        ]);
    }

    public function get_body_traspaso_test()
    {


        $sku[] = ['SKU' => 'P016026P0004', "Cantidad" => 4];
        //  $sku[] = ['SKU' => 'P004026P0017', "Cantidad" =>4];
        //$sku[]=['SKU'=>'P001109P0005',"Cantidad"=> "1"];

        $this->postdata = json_encode([

            'id' => '46102',
            'BodegaOrigen' => '1000',
            'SubBodegaOrigen' => '400',
            'BodegaDestino' => '1000',
            'SubBodegaDestino' => '410',

            'Lineas' =>
            $sku

        ]);
    }

    public function set_response_traspaso($item, $response)
    {

        /*    $response='{
        "id": 1927,
        "code": 400, 
        "message": "Transferencia prueba."
    }';
 */


        $resp = json_decode($response, true);


        if ($resp['code'] === 200) {

            $sql_response = "update Transferencia_Header set Doc_WS_ID = '" . $resp['id'] . "', dt_ws_id = getdate()  where gti = '" . $item['gti'] . "' ";
            $respuesta = bd::free_result($sql_response);
            // echo $respuesta;

        }

        if ($resp['code'] === 400) {

            $sql_response = "update Transferencia_Header set Error_WS_ID  = '" . $resp['message'] . "', dt_ws_id = getdate()  where gti = '" . $item['gti'] . "' ";
            $respuesta = bd::free_result($sql_response);
            // echo $respuesta;

        }
    }

    public function get_traspa_pendientes()
    {

        $sql = "select a.gti,  b.Bodega_ID,b.SubBodega_ID, b.Bodega_ID_REC, b.SubBodega_ID_REC,
        b.SKU,sum(b.Qty) as qty
        from Transferencia_Header a, Transaccion_TX b
        where a.GTI = b.Doc_ID_Transaccion
        and b.Bodega_ID = 1000  and a.Doc_WS_ID is null 
        and b.SubBodega_ID <> b.SubBodega_ID_REC
        and b.sku like 'p%'
        group by a.gti,  b.Bodega_ID,b.SubBodega_ID, b.Bodega_ID_REC, b.SubBodega_ID_REC, b.SKU
        ";

        bd::__construct();
        $resul = bd::fetch_array($sql);

        return $resul;
    }


    private function get_boy_search()
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
                "cache-control: no-cache",
                "content-type: application/json",
                "x-api-key: khcjsdfuivh894ubfe783b87rcb3487f8w7f3cbhbf9w832jine9wuh"


            ),
        ));

        $this->response = curl_exec($this->curl);
        $err = curl_error($this->curl);



        // Comprobar el código de estado HTTP
     
        if (!curl_errno($this->curl)) {
            switch ($http_code = curl_getinfo($this->curl, CURLINFO_HTTP_CODE)) {
                case 200:  # OK
                    break;
                default:
                    echo 'Código HTTP inesperado: ', $http_code, "\n";
            }
            echo $http_code;
        }

        curl_close($this->curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo  $this->response;
        }


        //print_r($this->postdata);
        return $this->response;
    }





    public function setTraspaso()
    {
        $this->get_url_traspaso();

        $resuls = $this->get_traspa_pendientes();

        foreach ($resuls as $item) {



            echo "==============Request===================";
            echo "<br>";

            $this->get_body_traspaso($item);

            print_r($this->postdata);

            echo "<br>";
            echo "============Response====================";
            echo "<br>";

            $response = $this->get_curl_request();



            $this->set_response_traspaso($item, $response);

            echo "<br>";
        }


        return $response;
    }


    public function setTraspaso_test()
    {
        $this->get_url_traspaso();

        //$resuls = $this->get_traspa_pendientes();





        echo "==============Request===================";
        echo "<br>";

        $this->get_body_traspaso_test();

        print_r($this->postdata);

        echo "<br>";
        echo "============Response====================";
        echo "<br>";

        $response = $this->get_curl_request();



        //$this->set_response_traspaso($item, $response);

        echo "<br>";



        return $response;
    }


    public function request_lista_usuario()
    {
        $this->get_url_user();
        $this->get_autorizacion();
        $respuesta = $this->get_curl_request();


        return $respuesta;
    }
}



$request = new Consulta_Api;

//$response = $request->setTraspaso();


$response = $request->setTraspaso_test();
