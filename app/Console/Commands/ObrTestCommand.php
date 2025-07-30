<?php

namespace App\Console\Commands;

use App\Http\Controllers\SendInvoiceToOBR;
use App\Models\Invoice;
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
    public function handle()
    {
        $obr = new SendInvoiceToOBR();

        $order = Invoice::latest()->first();
        dump($order->obr_order_format);

        $resp = $obr->addInvoice($order->obr_order_format);

        dump($resp);
    }
}
