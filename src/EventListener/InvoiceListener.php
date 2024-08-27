<?php

namespace App\EventListener;

use App\Entity\Invoice;
use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class InvoiceListener
{

    private EntityManagerInterface $entityManager;

    private RequestStack $requestStack;

    public function __construct(EntityManagerInterface $manager, RequestStack $requestStack) {
        $this->entityManager = $manager;
        $this->requestStack = $requestStack;
    }

    /**
     * @param Invoice $invoice
     *
     * @return void
     */
    public function preUpdate(Invoice $invoice): void
    {
        // Si el estado es true buscamos la factura asociada
        if ($invoice->getStatus() && (int) $invoice->getValue() !== 0) {
            // De esta manera conocemos si se envia desde el formulario directo de la factura
            $paymentOnlyInvoice = true;

            $request = $this->requestStack->getCurrentRequest()->request;
            $postData = $request->all();
            foreach ($postData as $data) {
                if (is_array($data) && isset($data['invoices'])) {
                    // De esta manera damos a conocer que se esta enviando desde el formulario de edicion de usuario donde estan las facturas asociadas
                    $paymentOnlyInvoice = false;
                    $invoicesRequest = $data['invoices'];

                    foreach ($invoicesRequest as $invoiceRequest) {
                        // Comparamos el listado de facturas enviadas en el form con la factura enviada
                        if ($invoiceRequest['hiddenId'] == $invoice->getId()) {
                            // Obtenemos el valor a pagar o false
                            $valuePayment = !empty($invoiceRequest['paymentValue']) ? (int) $invoiceRequest['paymentValue'] : false;
                        }
                    }
                }
            }

            // Si es el pago de una sola factura desde el form de factura paga por este lado
            if ($paymentOnlyInvoice) {
                $valueInvoice = (int) $invoice->getValue();
                $valuePayment = (int) $invoice->getPaymentValueTemporary();
                if ($valuePayment === $valueInvoice) {
                    $invoice->setValue(0);
                    $invoice->setStatus('PAGADA');
                    $invoice->setStatusInvoiceDuplicated('PAGADA');
                } elseif ($valuePayment < $valueInvoice) {
                    $invoice->setValue($invoice->getValue() - $invoice->getPaymentValueTemporary());
                    $invoice->setStatus('PAGO PARCIAL');
                    $invoice->setStatusInvoiceDuplicated('PAGO PARCIAL');
                }
                $invoice->setUpdatedAt(new \DateTime());
            } else {
                if ($valuePayment !== false) {
                    // Seteeamos el valor a pagar en el campo no mapeado de la entidad Invoice
                    $invoice->setPaymentValueTemporary($valuePayment);
                    $valueInvoice = (int) $invoice->getValue();

                    if ($valuePayment === $valueInvoice) {
                        $invoice->setValue(0);
                        $invoice->setStatus('PAGADA');
                        $invoice->setStatusInvoiceDuplicated('PAGADA');

                    } elseif ($valuePayment < $valueInvoice) {
                        $invoice->setValue($valueInvoice - $valuePayment);
                        $invoice->setStatus('PAGO PARCIAL');
                        $invoice->setStatusInvoiceDuplicated('PAGO PARCIAL');

                    }
                    $invoice->setUpdatedAt(new \DateTime());
                }
            }
            // Si el pago tiene un valor, cambiamos el valor de la factura y creamos el pago
            $invoice->setStatus($invoice->getStatusInvoiceDuplicated());
        } else {
            $invoice->setStatus($invoice->getStatusInvoiceDuplicated());
        }
    }

    /**
     * @param Invoice $invoice
     *
     * @return void
     */
    public function postUpdate(Invoice $invoice): void
    {
        // Registramos el pago de la factura
        if ($invoice->getPaymentValueTemporary()) {
            $payment = new Payment();
            $payment->setValue($invoice->getPaymentValueTemporary());
            $payment->setDescription($invoice->getDescription());
            $payment->setMethod('EFECTIVO');
            $payment->setMonthInvoiced($invoice->getMonthInvoiced());
            $payment->setCreatedAt(new \DateTime());
            $payment->setUpdatedAt(new \DateTime());
            $payment->setInvoice($invoice);

            $this->entityManager->persist($payment);
            $this->entityManager->flush();
        }
    }

}