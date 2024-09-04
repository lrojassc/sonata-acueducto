<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\CreateInvoiceType;
use App\Form\ShowInvoicesType;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class InvoiceAdminController extends CRUDController
{

    #[Route('/show/invoices', name: 'show_invoices')]
    public function showInvoice()
    {
        $form = $this->createForm(ShowInvoicesType::class);
        return $this->render('Invoice/show_invoice.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
