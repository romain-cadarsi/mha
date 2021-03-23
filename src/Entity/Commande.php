<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nomPrenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $depart;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $arrivee;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="float")
     */
    private $prixApproximatif;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $effectue;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity=Paiement::class, mappedBy="commande")
     */
    private $paiements;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $paiementMethod;

    /**
     * @ORM\ManyToOne(targetEntity=PromotionnalCode::class)
     */
    private $coupon;

    public function __construct()
    {
        $this->paiements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPrenom(): ?string
    {
        return $this->nomPrenom;
    }

    public function setNomPrenom(string $nomPrenom): self
    {
        $this->nomPrenom = $nomPrenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDepart(): ?string
    {
        return $this->depart;
    }

    public function setDepart(string $depart): self
    {
        $this->depart = $depart;

        return $this;
    }

    public function getArrivee(): ?string
    {
        return $this->arrivee;
    }

    public function setArrivee(string $arrivee): self
    {
        $this->arrivee = $arrivee;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPrixApproximatif(): ?float
    {
        return $this->prixApproximatif;
    }

    public function setPrixApproximatif(float $prixApproximatif): self
    {
        $this->prixApproximatif = $prixApproximatif;

        return $this;
    }

    public function getValidation(): ?bool
    {
        return $this->validation;
    }

    public function setValidation(bool $validation): self
    {
        $this->validation = $validation;

        return $this;
    }

    public function getEffectue(): ?bool
    {
        return $this->effectue;
    }

    public function setEffectue(bool $effectue): self
    {
        $this->effectue = $effectue;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|Paiement[]
     */
    public function getPaiements(): Collection
    {
        return $this->paiements;
    }

    public function addPaiement(Paiement $paiement): self
    {
        if (!$this->paiements->contains($paiement)) {
            $this->paiements[] = $paiement;
            $paiement->setCommande($this);
        }

        return $this;
    }

    public function removePaiement(Paiement $paiement): self
    {
        if ($this->paiements->removeElement($paiement)) {
            // set the owning side to null (unless already changed)
            if ($paiement->getCommande() === $this) {
                $paiement->setCommande(null);
            }
        }

        return $this;
    }

    public function getPaiementMethod(): ?string
    {
        return $this->paiementMethod;
    }

    public function setPaiementMethod(string $paiementMethod): self
    {
        $this->paiementMethod = $paiementMethod;

        return $this;
    }

    public function getCoupon(): ?PromotionnalCode
    {
        return $this->coupon;
    }

    public function setCoupon(?PromotionnalCode $coupon): self
    {
        $this->coupon = $coupon;

        return $this;
    }
}
