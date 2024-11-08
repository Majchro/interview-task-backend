<?php

namespace App\Modules\Invoices\Api\Controllers;

use App\Infrastructure\Controller;
use App\Modules\Invoices\Application\Queries\FindInvoiceByIdQuery;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class InvoiceController extends Controller
{
    public function show(string $id, FindInvoiceByIdQuery $query): JsonResponse
    {
        try {
            $invoice = $query->execute($id);

            return response()->success($invoice);
        } catch (ModelNotFoundException) {
            return response()->error('Invoice not found', SymfonyResponse::HTTP_NOT_FOUND);
        }
    }
}
