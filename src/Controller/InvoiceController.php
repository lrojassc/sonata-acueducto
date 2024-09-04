<?php

namespace App\Controller;

use App\Form\CreateInvoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InvoiceController extends AbstractController
{
    #[Route('/admin/invoice/custom-form', name: 'admin_invoice_custom_form')]
    public function create(Request $request): Response
    {
        $form = $this->createForm(CreateInvoiceType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /*
            $user_id = $request->request->get('userInvoice');
            $id_subscription = $request->request->get('serviceUser');
            $invoice = $form->getData();
            $invoice->setYearInvoiced(date('Y'));
            $invoice->setStatus('PENDIENTE');
            $invoice->setCreatedAt(new \DateTime('now'));
            $invoice->setUpdatedAt(new \DateTime('now'));

            $invoice->setUser($this->entityManager->getRepository(User::class)->find($user_id));
            $invoice->setSubscription($this->entityManager->getRepository(Subscription::class)->find($id_subscription));

            $this->entityManager->persist($invoice);
            $this->entityManager->flush();
            $this->addFlash('success', 'Factura creada exitosamente');
            return $this->redirectToRoute('create_invoice');
            */
        }
        return $this->render('Invoice/create.html.twig', [
                'form_create_invoice' => $form->createView(),
        ]);
    }
}
