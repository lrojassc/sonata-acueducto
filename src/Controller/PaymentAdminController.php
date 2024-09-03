<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Payment;
use Dompdf\Dompdf;
use Dompdf\Options;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Response;

final class PaymentAdminController extends CRUDController
{

    public function reportPaymentAction(Payment $payment): Response
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);

        $html = $this->renderView('pdf/payment_report.html.twig', [
            'payment' => $payment
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('letter');
        $dompdf->render();
        $output = $dompdf->output();

        $response = new Response($output);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'inline; filename="Pago"' . $payment->getId() . '".pdf"');
        return $response;
    }
}
