<?php

namespace App\Entity;

use App\Repository\ProductosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductosRepository::class)]
class Productos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    #[ORM\Column]
    private ?float $peso = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\ManyToOne(inversedBy: 'productos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorias $categoria = null;

    #[ORM\OneToMany(targetEntity: DatosPedido::class, mappedBy: 'producto')]
    private Collection $datosPedidos;

    public function __construct()
    {
        $this->datosPedidos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getPeso(): ?float
    {
        return $this->peso;
    }

    public function setPeso(float $peso): static
    {
        $this->peso = $peso;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getCategoria(): ?Categorias
    {
        return $this->categoria;
    }

    public function setCategoria(?Categorias $categoria): static
    {
        $this->categoria = $categoria;

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
            $datosPedido->setProducto($this);
        }

        return $this;
    }

    public function removeDatosPedido(DatosPedido $datosPedido): static
    {
        if ($this->datosPedidos->removeElement($datosPedido)) {
            // set the owning side to null (unless already changed)
            if ($datosPedido->getProducto() === $this) {
                $datosPedido->setProducto(null);
            }
        }

        return $this;
    }
}
