<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\ShowInvoicesType;
use Sonata\AdminBundle\Controller\CRUDController;

final class InvoiceAdminController extends CRUDController
{

    #[Route('/show/invoices', name: 'admin.user.show_invoices', methods: ['GET'])]
    public function showInvoice()
    {
        $form = $this->createForm(ShowInvoicesType::class);
        return $this->render('Invoice/show_invoice.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
