<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\CollectionType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class UserAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id', null, ['label' => 'Código'])
            ->add('name', null, ['label' => 'Nombre completo'])
            ->add('document_number', null, ['label' => 'Número de documento'])
            ->add('paid_subscription', null, ['label' => 'Estado Suscripcion'])
            ->add('full_payment', null, ['label' => 'Pago servicio completo'])
            ->add('address', null, ['label' => 'Direccion'])
            ->add('city', null, ['label' => 'Ciudad'])
            ->add('status', null, ['label' => 'Estado usuario'])
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id', null, ['label' => 'Código'])
            ->addIdentifier('name', null, ['label' => 'Nombre'])
            ->add('document_number', null, ['label' => 'Numero de documento'])
            ->add('phone_number', null, ['label' => 'Telefono'])
            ->add('paid_subscription', null, ['label' => 'Estado Suscripción'])
            ->add('full_payment', null, ['label' => 'Paga servicio completo'])
            ->add('address', null, ['label' => 'Dirección'])
            ->add('status')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'edit' => []
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('Información del usuario', ['class' => 'col-md-3'])
                ->add('name', null, ['label' => 'Nombre completo'])
                ->add('document_type', ChoiceType::class, [
                    'label' => 'Tipo de documento',
                    'choices' => [
                        'CC' => 'CC',
                        'TI' => 'TI',
                        'NIT' => 'NIT',
                    ]
                ])
                ->add('document_number', null, ['label' => 'Número de documento'])
                ->add('email', null, ['label' => 'Correo'])
                ->add('phone_number', null, ['label' => 'Teléfono'])
                ->add('full_payment', ChoiceType::class, [
                    'label' => '¿Paga Servicio Completo?',
                    'choices' => [
                        'SI' => 'SI',
                        'PAGA LA MITAD' => 'MITAD',
                    ]
                ])
                ->add('address', null, ['label' => 'Dirección'])
                ->add('city', null, ['label' => 'Ciudad'])
                ->add('municipality', null, ['label' => 'Municipio'])
                ->add('status', ChoiceType::class, [
                    'label' => 'Estado Del Suscriptor',
                    'required' => true,
                    'choices' => [
                        'ACTIVO' => 'ACTIVO',
                        'SUSPENDIDO' => 'SUSPENDIDO',
                    ]
                ])
            ->end()
            ->with('Facturas', ['class' => 'col-md-9'])
                ->add('invoices', CollectionType::class, [
                    'label' => false,
                ], [
                    'edit' => 'inline',
                    'inline' => 'table',
                    'delete' => false,
                ])
            ->end()
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->with('Información del usuario', ['class' => 'col-md-3'])
                ->add('id', null, ['label' => 'Código'])
                ->add('name', null, ['label' => 'Nombre completo'])
                ->add('document_type', null, ['label' => 'Tipo de documento'])
                ->add('document_number', null, ['label' => 'Número de documento'])
                ->add('email', null, ['label' => 'Correo'])
                ->add('phone_number', null, ['label' => 'Telefono'])
                ->add('paid_subscription', null, ['label' => 'Estado Suscripción'])
                ->add('full_payment', null, ['label' => 'Paga servicio completo'])
                ->add('address', null, ['label' => 'Dirección'])
                ->add('city', null, ['label' => 'Ciudad'])
                ->add('municipality', null, ['label' => 'Municipio'])
                ->add('status', null, ['label' => 'Status'])
            ->end()
            ->with('Facturas Asociadas', ['class' => 'col-md-9'])
                ->add('invoices', null, [
                    'label' => 'Facturas',
                    'template' => 'User/invoices_by_user.html.twig',
                ])
            ->end()
        ;
    }



}
