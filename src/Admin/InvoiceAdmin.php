<?php

declare(strict_types=1);

namespace App\Admin;

use App\Form\DataTransformer\StringToBooleanTransformer;
use Knp\Menu\ItemInterface;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\RequestStack;

final class InvoiceAdmin extends AbstractAdmin
{

    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('value')
            ->add('description')
            ->add('year_invoiced')
            ->add('month_invoiced')
            ->add('concept')
            ->add('status')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id', null, [
                'label' => 'No. Factura'
            ])
            ->add('value', null, [
                'label' => 'Valor',
            ])
            ->add('description', null, [
                'label' => 'Descripcion'
            ])
            ->add('year_invoiced', null, [
                'label' => 'Año'
            ])
            ->add('month_invoiced', null, [
                'label' => 'Mes'
            ])
            ->add('concept', null, [
                'label' => 'Concepto'
            ])
            ->add('status', null, [
                'label' => 'Estado',
            ])
            ->add('user.name', null, [
                'label' => 'Usuario'
            ])
            ->add('subscription.service', null, [
                'label' => 'Servicio'
            ])
            ->add('user.address', null, [
                'label' => 'Dirección'
            ])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $invoice = $this->getSubject();

        $invoice->setHiddenId($invoice->getId());
        $invoice->setStatusInvoiceDuplicated($invoice->getStatus());

        $statusInvoice = $invoice->getStatusInvoiceDuplicated();

        $color = 'red';
        $isPaid = false;
        if ($statusInvoice === 'PAGADA') {
            $color = 'green';
            $isPaid = true;
        } elseif ($statusInvoice === 'PAGO PARCIAL') {
            $color = 'orange';
        } elseif ($statusInvoice === 'INACTIVO') {
            $color = 'black';
            $isPaid = true;
        }

        $form
            ->add('status', CheckboxType::class, [
                'label' => '¿Pagar?',
                'data' => false,
                'required' => false,
                // De pronto intentar agregar un html con attr
            ])
            ->add('hiddenId', null, [
                'label' => 'ID',
                'attr' => ['readonly' => true],
            ])
            ->add('value', null, [
                'label' => 'Valor Factura',
                'required' => false,
                'attr' => $isPaid ? ['readonly' => true] : [],
            ])
            ->add('paymentValue', null, [
                'label' => 'Valor a Pagar',
                'mapped' => false,  // Indica que no está mapeado a ninguna propiedad de la entidad
                'required' => false,
                'attr' => $isPaid ? ['readonly' => true] : [],
            ])
            ->add('description', null, [
                'label' => 'Descripcion',
                'attr' => ['readonly' => true]
            ])
            ->add('year_invoiced', null, [
                'label' => 'Año Factura',
                'required' => false,
                'attr' => ['readonly' => true]
            ])
            ->add('month_invoiced', null, [
                'label' => 'Mes Factura',
                'required' => false,
                'attr' => ['readonly' => true]
            ])
            ->add('concept', null, [
                'label' => 'Concepto',
                'required' => false,
                'attr' => ['readonly' => true]
            ])
            ->add('subscription.service', null, [
                'label' => 'Servicio',
                'attr' => ['readonly' => true]
            ])
            ->add('statusInvoiceDuplicated', null, [
                'label' => 'Estado',
                'attr' =>  ['readonly' => true, 'style' => 'color: ' . $color . '; font-weight: bold;']
            ])
        ;

        $form->getFormBuilder()
            ->get('status')
            ->addModelTransformer(new StringToBooleanTransformer());
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('value')
            ->add('description')
            ->add('year_invoiced')
            ->add('month_invoiced')
            ->add('concept')
            ->add('status')
            ->add('created_at')
            ->add('updated_at')
            ->add('user.name')
            ->add('subscription.service')
        ;
    }

    public function preUpdate($object): void
    {
        $request = $this->requestStack->getCurrentRequest()->request;
        $postData = $request->all();
        foreach ($postData as $data) {
            if (!empty($data['paymentValue'])) {
                $object->setPaymentValueTemporary($data['paymentValue']);
            }
        }
    }

    protected function configureTabMenu(ItemInterface $menu, string $action, ?AdminInterface $childAdmin = NULL): void
    {
        parent::configureTabMenu($menu, $action, $childAdmin); // TODO: Change the autogenerated stub
        $menu->addChild('Custom Form', [
            'route' => 'admin_invoice_custom_form',
        ])->setLabel('Custom Invoice Form');
    }

}
