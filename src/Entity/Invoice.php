<?php

namespace App\Entity;

use App\EventListener\InvoiceListener;
use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
#[ORM\EntityListeners([InvoiceListener::class])]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Este campo no puede estar vacio")]
    private ?string $value = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $year_invoiced = null;

    #[ORM\Column(length: 255)]
    private ?string $month_invoiced = null;

    #[ORM\Column(length: 255)]
    private ?string $concept = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Subscription $subscription = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated_at = null;

    /**
     * @var Collection<int, Payment>
     */
    #[ORM\OneToMany(targetEntity: Payment::class, mappedBy: 'invoice')]
    private Collection $payments;

    private ?int $hiddenId = null;

    private ?string $paymentValueTemporary = null;

    private ?string $statusInvoiceDuplicated = null;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getYearInvoiced(): ?string
    {
        return $this->year_invoiced;
    }

    public function setYearInvoiced(string $year_invoiced): static
    {
        $this->year_invoiced = $year_invoiced;

        return $this;
    }

    public function getMonthInvoiced(): ?string
    {
        return $this->month_invoiced;
    }

    public function setMonthInvoiced(string $month_invoiced): static
    {
        $this->month_invoiced = $month_invoiced;

        return $this;
    }

    public function getConcept(): ?string
    {
        return $this->concept;
    }

    public function setConcept(string $concept): static
    {
        $this->concept = $concept;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function setSubscription(?Subscription $subscription): static
    {
        $this->subscription = $subscription;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setInvoice($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getInvoice() === $this) {
                $payment->setInvoice(null);
            }
        }

        return $this;
    }

    public function setHiddenId(int $hiddenId): static
    {
        $this->hiddenId = $hiddenId;
        return $this;
    }

    public function getHiddenId(): ?int
    {
        return $this->hiddenId;
    }

    public function setPaymentValueTemporary(string $paymentValueTemporary): static
    {
        $this->paymentValueTemporary = $paymentValueTemporary;
        return $this;
    }

    public function getPaymentValueTemporary(): ?string
    {
        return $this->paymentValueTemporary;
    }

    public function setStatusInvoiceDuplicated(string $statusInvoiceDuplicated): static
    {
        $this->statusInvoiceDuplicated = $statusInvoiceDuplicated;
        return $this;
    }

    public function getStatusInvoiceDuplicated(): ?string
    {
        return $this->statusInvoiceDuplicated;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getId();
    }
}
