<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Payment;
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
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'barang_id' => 'required|integer|exists:barangs,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $barang = Barang::find($validatedData['barang_id']);
            $user = Auth::user();
            $totalAmount = $barang->harga * $validatedData['quantity'];
            $no_transaction = 'Inv - ' . rand();

            $order = new Payment();
            $order->no_transaction = $no_transaction;
            $order->external_id = $no_transaction;
            $order->item_name = $barang->name;
            $order->quantity = $validatedData['quantity'];
            $order->harga = $barang->harga;
            $order->grand_total = $totalAmount;
            $order->save();

            $createdInvoice = new CreateInvoiceRequest([
                'external_id' => $no_transaction,
                'amount' => $totalAmount,
                'payer_email' => $user->email,
                'description' => 'Invoice for user ' . $user->name,
            ]);

            $apiInstance = new InvoiceApi();
            $generateInvoice = $apiInstance->createInvoice($createdInvoice);

            return dd($generateInvoice);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
