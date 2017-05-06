<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Receipt;
use Auth;

class ReceiptController extends Controller
{
    public function index()
    {
        return view('account.receipts.index')
            ->withReceipts(Auth::user()->receipts()->paginate(25))
            ->withUser(Auth::user());
    }

    /**
     * @return mixed
     */
    public function show(Receipt $receipt)
    {
        /** @var \Barryvdh\DomPDF\PDF $pdf */
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('account.receipts.pdf', [
            'receipt'         => $receipt,
            'user'            => $receipt->user,
        ]);

        return $pdf->stream();
    }
}
