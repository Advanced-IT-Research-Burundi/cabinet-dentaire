<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Entreprise;
use App\Models\Invoice;
use App\Models\ObrPointer;
use App\Models\ObrRequestBody;
use Illuminate\Support\Facades\Http;

class SendInvoiceToOBR extends Controller
{
    //
    //private string $baseUrl = 'http://41.79.226.28:8345/ebms_api/';
    //private string $baseUrl = 'https://ebms.obr.gov.bi:8443/ebms_api/';
    //private string $baseUrl = 'https://ebms.obr.gov.bi:9443/ebms_api/';
    private string $baseUrl ;

    public function __construct()
    {
        // Check if the syncronize is enabled if not prevent calling the constructor
        $this->baseUrl = env('OBR_PRODUCTION', false) == true ? 'https://ebms.obr.gov.bi:8443/ebms_api/' : 'https://ebms.obr.gov.bi:9443/ebms_api/';
    }

    public function addStockMovement($data){
        $token = $this->getToken();
        // Item
        $data = array_merge(
            [
                "system_or_device_id" => env('OBR_USERNAME'),
            ],
            $data
        );
        $req = Http::withToken($token)->acceptJson()->post($this->baseUrl . 'AddStockMovement/', $data);
        // dd();
        return $req->body();
    }
    public function checkTin(string $tp_TIN)
    {
        $token = $this->getToken();
        // Enlevement des espaces
        $tp_TIN = trim($tp_TIN);
        $req = Http::withToken($token)->acceptJson()->post($this->baseUrl . 'checkTIN/', [
            'tp_TIN' => $tp_TIN
        ]);

        return json_decode($req->body());
    }


    public function cancelInvoice($invoice_signature, $motif)
    {
        $token = $this->getToken();
        $invoice_signature = trim($invoice_signature);

        $arrayString =  explode( '/', $invoice_signature);
        $invoice_id = end($arrayString);

        ObrRequestBody::create([
            'invoice_id' =>  $invoice_id ,
            'request_body' => json_encode([
                "invoice_identifier" => $invoice_signature,
                "cn_motif" => $motif
            ]),
        ]);
        $req = Http::withToken($token)->acceptJson()->post($this->baseUrl . 'cancelInvoice/', [
            "invoice_identifier" => $invoice_signature,
            "cn_motif" => $motif
        ]);
        $response = json_decode($req->body());
        ObrPointer::create([
            'order_id' =>   $invoice_id ,
            'invoice_signature' => $invoice_signature,
            'status' => $response->success ?? "",
            'electronic_signature' => $invoice_signature,
            'msg' =>  $response->msg ?? "",
            'result' => "X",
        ]);
        return $response;
    }


    public function addInvoice($invoince)
    {
        $token = $this->getToken();
        // https://ebms.obr.gov.bi:9443/ebms_api
        ObrRequestBody::create([
            'invoice_id' => $invoince['invoice_id'],
            'request_body' => json_encode($invoince),
        ]);
        //dd($order);
        $req = Http::withToken($token)->acceptJson()->post($this->baseUrl . 'addInvoice_confirm/', $invoince);
        $response = json_decode($req->body());

        if($response->success || $response->msg  == "Une facture avec le même numéro existe déjà."){
            // Modififier la facture
            $order = Invoice::find($invoince['invoice_id']);
            $order->is_sent_to_obr = 1;
            $order->is_sent_at = now();
            $order->save();
        }


        ObrPointer::create([
            'invoice_id' =>   $invoince['invoice_id'] ,
            'invoice_signature' => $invoince['invoice_identifier'],
            'status' => $response->success ?? "",
            'electronic_signature' => $response->electronic_signature ?? "",
            'msg' =>  $response->msg ?? "",
            'result' => json_encode($response->result),
        ]);
        return $response;
    }


    public static function getInvoiceSignature($invoice_number, $created_at)
    {
        $company = Company::latest()->first();
        //$invoice_number = getInvoiceNumber($invoince_id);
        $d = date_create($created_at);
        $date_facturation = date_format($d, 'YmdHis');

        $invoice_signature = $company->tp_TIN . "/" . env('OBR_USERNAME')
        . "/" . $date_facturation . "/" . $invoice_number;

        return $invoice_signature;

    }

    // Get Invoince

    public function getInvoice($invoice_signature)
    {
        $token = $this->getToken();
        $req = Http::withToken($token)->acceptJson()->post($this->baseUrl . 'getInvoice/', [
            'invoice_signature' => $invoice_signature
        ]);
        $response = json_decode($req->body());
        $success = $response->success;
        $message = $response->msg;

        return  $response;
    }

    // Generation du TOken
    public function getToken()
    {
        try {
            $req = Http::acceptJson()->post($this->baseUrl . 'login/', [
                'username' => env('OBR_USERNAME'),
                'password' => env('OBR_PASSWORD')
            ]);
            $response = json_decode($req->body());
            $success = $response->success;
            $message = $response->msg;
            $token = "";
            if ($success) {
                return $response->result->token;
            }
            return [
                'succees' => false,
                'response' => $req->body(),
                "data" => [
                    'username' => env('OBR_USERNAME'),
                    'password' => env('OBR_PASSWORD') ,
                    'url' => $this->baseUrl
                    ]
                ];
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage(), $e->getCode());
            }
        }



    }


