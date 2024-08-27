<?php

namespace App\EventListener;

use App\Entity\Invoice;
use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Param;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserSaveListener
{

    private EntityManagerInterface $entityManager;

    private UserPasswordHasherInterface $passwordEncoder;

    private $monthlyInvoiceValue;

    public function __construct(EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHasher, $monthlyInvoiceValue) {
        $this->entityManager = $manager;
        $this->passwordEncoder = $passwordHasher;
        $this->monthlyInvoiceValue = $monthlyInvoiceValue;
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function prePersist(User $user): void
    {
        $user->setPaidSubscription('DEBE');
        $user->setPassword($this->passwordEncoder->hashPassword($user, $user->getDocumentNumber()));
        $user->setRoles(['ROLE_USER']);
        $user->setCreatedAt(new \DateTime());
        $user->setUpdatedAt(new \DateTime());
    }

    /**
     * @param User $user
     *
     * @return void
     */
    public function postPersist(User $user): void
    {
        $subscription = new Subscription();
        $subscription->setUser($user);
        $subscription->setService('Residencial 1');
        $subscription->setStatus('ACTIVO');
        $subscription->setCreatedAt(new \DateTime('now'));
        $subscription->setUpdatedAt(new \DateTime('now'));

        // Crear factura asociada a la adquisicion del servicio
        $invoice = new Invoice();
        $invoice->setUser($user);
        $invoice->setValue(10000);
        $invoice->setDescription('Suscripción al servicio de acueducto');
        $invoice->setYearInvoiced(date('Y'));
        $invoice->setMonthInvoiced(date('m'));
        $invoice->setConcept('SUSCRIPCION');
        $invoice->setStatus('PENDIENTE');
        $invoice->setSubscription($subscription);
        $invoice->setCreatedAt(new \DateTime('now'));
        $invoice->setUpdatedAt(new \DateTime('now'));

        // Almacenar informacion en la base de datos
        $user->addSubscription($subscription);
        $user->addInvoice($invoice);

        $this->entityManager->persist($invoice);
        $this->entityManager->persist($subscription);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

}