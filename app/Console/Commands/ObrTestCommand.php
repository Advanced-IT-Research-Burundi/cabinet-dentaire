<?php

namespace App\Console\Commands;

use App\Http\Controllers\SendInvoiceToOBR;
use App\Models\Invoice;
use App\Models\MouvementStock;
use App\Models\StockMovement;
use Illuminate\Console\Command;

class ObrTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:obr-test-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test OBR';

    /**
     * Execute the console command.
     */
    public function handle( $invoice_id = null)
    {
        if($invoice_id){
            $invoice = Invoice::find($invoice_id);
            $obr = new SendInvoiceToOBR();
            $resp = $obr->addInvoice($invoice->obr_order_format);
            dump($resp);
            return;
        }
        $obr = new SendInvoiceToOBR();

        $mouvememts = MouvementStock::whereNull('is_send_to_obr')->get();
        foreach ($mouvememts as $mouvement) {
            $resp =  $obr->addStockMovement($mouvement);
            dump($resp);
        }

        $orders = Invoice::where('is_sent_to_obr', false)->get();
        foreach ($orders as $order) {

           $resp = $obr->addInvoice($order->obr_order_format);
            dump($resp);
        }

        // update invoice status

        $ordersCanceleds = Invoice::where('is_canceled', 1)
                ->whereNull('canced_to_obr_at')
                  ->get();

        foreach ($ordersCanceleds as $order) {
           $resp = $obr->cancelInvoice($order->invoice_identifier, $order->cn_motif);
           dump($resp);
           if($resp->success){
            $order->update([
                'canced_to_obr_at' => now(),
            ]);
           }
        }


    }
}
