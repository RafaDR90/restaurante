<?php

namespace App\Entity;

use App\Repository\PedidosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PedidosRepository::class)]
class Pedidos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column]
    private ?int $enviado = null;

    #[ORM\ManyToOne(inversedBy: 'pedidos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Restaurante $restaurante = null;

    #[ORM\OneToMany(targetEntity: DatosPedido::class, mappedBy: 'pedido')]
    private Collection $datosPedidos;

    public function __construct()
    {
        $this->datosPedidos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getEnviado(): ?int
    {
        return $this->enviado;
    }

    public function setEnviado(int $enviado): static
    {
        $this->enviado = $enviado;

        return $this;
    }

    public function getRestaurante(): ?Restaurante
    {
        return $this->restaurante;
    }

    public function setRestaurante(?Restaurante $restaurante): static
    {
        $this->restaurante = $restaurante;

        return $this;
    }

    /**
     * @return Collection<int, DatosPedido>
     */
    public function getDatosPedidos(): Collection
    {
        return $this->datosPedidos;
    }

    public function addDatosPedido(DatosPedido $datosPedido): static
    {
        if (!$this->datosPedidos->contains($datosPedido)) {
            $this->datosPedidos->add($datosPedido);
            $datosPedido->setPedido($this);
        }

        return $this;
    }

    public function removeDatosPedido(DatosPedido $datosPedido): static
    {
        if ($this->datosPedidos->removeElement($datosPedido)) {
            // set the owning side to null (unless already changed)
            if ($datosPedido->getPedido() === $this) {
                $datosPedido->setPedido(null);
            }
        }

        return $this;
    }
}
