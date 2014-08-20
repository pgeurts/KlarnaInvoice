<?php
namespace Payum\Klarna\Invoice\Action\Api;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Klarna\Invoice\Request\Api\Activate;

class ActivateAction extends BaseApiAwareAction
{
    /**
     * {@inheritDoc}
     *
     * @param Activate $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        $klarna = $this->createKlarna();

        try {
            $result = $klarna->activate($details['rno'], $details['osr'], $details['activation_flags']);

            $details['risk_status'] = $result[0];
            $details['invoice_number'] = $result[1];
        } catch (\KlarnaException $e) {
            $details['error_code'] = $e->getCode();
            $details['error_message'] = $e->getMessage();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Activate &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}