<?php

namespace App\Http\Controllers\App;

use App\Enums\SaleStatus;
use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Services\SaleService;
use App\Services\EsewaPayment;
use App\Services\PaymentService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Exception\NotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SalesController extends Controller
{
    use ResponseTrait;
    function __construct(private SaleService $_saleService) {}

    public function myOrders()
    {
        return $this->responseSuccess($this->_saleService->listOrder(), 'My orders');
    }

    public function details($order)
    {
        $orderModal = $this->_saleService->viewOrder($order);
        if ($orderModal == null) {
            throw new NotFoundException("Order not found");
        }
        return $this->responseSuccess($orderModal, 'Orders Detail');
    }

    public function cancelOrderItem($order, $item)
    {
        if (!$this->_saleService->cancelOrderItem($item)) {
            throw new NotFoundException("Order not found");
        }
        return $this->responseSuccess([], 'Orders Cancelled');
    }

    public function cancelOrder($order)
    {
        if (!$this->_saleService->cancelOrder($order)) {
            throw new NotFoundException("Order not found");
        }
        return $this->responseSuccess([], 'Orders Cancelled');
    }

    public function paymentFailed()
    {
        return $this->responseError([], 'PaymentFailed');
    }

    public function esewaSuccess(Request $request)
    {
        try {
            $esewa = new EsewaPayment();
            $service = new PaymentService();
            $saleModal = $service->makePayment($request, $esewa);
            return $this->responseSuccess(["order" => $saleModal], 'Payment by esewa success');
        } catch (\Exception $ex) {
            return $this->responseError([], $ex->getMessage());
        }
    }

    public function paymentOrder(Sale $sale)
    {
        if ($sale->status == SaleStatus::cancelled->value) {
            return $this->responseError([], 'Cannot initiate payment');
        }
        if ($sale->total_amount <= $sale->payment_amount) {
            return $this->responseError([], 'Payment Completed');
        }
        $orderModal = $this->_saleService->paymentOrder($sale->id);
        if ($orderModal == null) {
            throw new NotFoundException("Order not found");
        }
        if ($orderModal->payment > 0) {
            throw new HttpException(404, "Payment already made");
        }
        $service = new PaymentService();

        $trid = $service->paymentInit($orderModal);

        $esewa = new EsewaPayment();

        $esewa->init($trid, $orderModal->total_amt);

        return $this->responseSuccess([
            'order' => $orderModal,
            'esewa' => $esewa
        ], 'Payment through esewa');
    }
}
