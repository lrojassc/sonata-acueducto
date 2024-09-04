<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;

final class PaymentAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('value')
            ->add('method')
            ->add('month_invoiced')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id', null , [
                'label' => 'No. Pago'
            ])
            ->add('value', null, [
                'label' => 'Valor Pagado'
            ])
            ->add('description', null, [
                'label' => 'Descripción'
            ])
            ->add('method', null, [
                'label' => 'Metodo'
            ])
            ->add('month_invoiced', null, [
                'label' => 'Mes Pagado',
            ])
            ->add('invoice.description', null, [
                'label' => 'Descripción Factura'
            ])
            ->add('invoice.concept', null, [
                'label' => 'Concepto'
            ])
            ->add('invoice.user.name', null, [
                'label' => 'Suscriptor'
            ])
            ->add('invoice.subscription.service', null, [
                'label' => 'Servicio'
            ])
            ->add('created_at', null, [
                'label' => 'Fecha de Pago',
            ])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'label' => 'Acciones',
                'actions' => [
                    'show' => [],
                    'report-payment' => [
                        'template' => '/Actions/payment_report_action.html.twig'
                    ]
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('id')
            ->add('value')
            ->add('description')
            ->add('method')
            ->add('month_invoiced')
            ->add('created_at')
            ->add('updated_at')
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('value', null, [
                'label' => 'Valor Pagado'
            ])
            ->add('description', null, [
                'label' => 'Descripción'
            ])
            ->add('method', null, [
                'label' => 'Metodo'
            ])
            ->add('month_invoiced', null, [
                'label' => 'Mes Pagado',
            ])
            ->add('invoice.user.name', null, [
                'label' => 'Suscriptor'
            ])
            ->add('invoice.subscription.service', null, [
                'label' => 'Servicio'
            ])
            ->add('invoice.id', null, [
                'label' => 'No. Factura',
            ])
        ;
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->remove('create');
        $collection->remove('edit');
        $collection->add('report-payment', $this->getRouterIdParameter().'/report-payment');
    }
}
