<?php

namespace App\Modules\Invoices\Api\Controllers;

use App\Infrastructure\Controller;
use App\Modules\Invoices\Application\Commands\SetInvoiceAsRejectedCommand;
use App\Modules\Invoices\Domain\Exceptions\InvoiceIsNotApprovableException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class RejectInvoiceController extends Controller
{
    public function __invoke(string $id, SetInvoiceAsRejectedCommand $command): JsonResponse
    {
        try {
            $command->execute($id);

            return response()->success('Invoice rejected');
        } catch (ModelNotFoundException) {
            return response()->error('Invoice not found', SymfonyResponse::HTTP_NOT_FOUND);
        } catch (InvoiceIsNotApprovableException $exception) {
            return response()->error($exception->getMessage(), SymfonyResponse::HTTP_BAD_REQUEST);
        }
    }
}
