<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Xendit\Configuration;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\Invoice\Invoice;
use Xendit\Invoice\InvoiceApi;
use App\Models\User;

class PaymentController extends Controller
{
    public function __construct()
    {
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
    }

    public function createInvoice(Request $request)
    {

        $validatedData = $request->validate([
            'barang_id' => 'required|integer|exists:barangs,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $barang = Barang::find($validatedData['barang_id']);

        $user = Auth::user();
        $totalAmount = $barang->harga * $validatedData['quantity'];

        $createdInvoice = new CreateInvoiceRequest([
            'external_id' => 'Inv ' . rand(),
            'amount' => $totalAmount,
            'payer_email' => $user->email,
            'description' => 'Invoice for user ' . $user->name,
        ]);

        $apiIstance = new InvoiceApi();
        $generateInvoice = $apiIstance->createInvoice($createdInvoice);

        return dd($generateInvoice);
    }
}
