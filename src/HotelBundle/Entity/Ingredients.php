<?php

namespace HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ingredients
 *
 * @ORM\Table(name="ingredients", indexes={@ORM\Index(name="product_id", columns={"product_id"})})
 * @ORM\Entity
 */
class Ingredients
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="product_ingredient_id", type="integer", nullable=false)
     */
    private $productIngredientId;

    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="float", precision=10, scale=0, nullable=false)
     */
    private $quantity;

    /**
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * })
     */
    private $product;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set productIngredientId
     *
     * @param integer $productIngredientId
     * @return Ingredients
     */
    public function setProductIngredientId($productIngredientId)
    {
        $this->productIngredientId = $productIngredientId;

        return $this;
    }

    /**
     * Get productIngredientId
     *
     * @return integer 
     */
    public function getProductIngredientId()
    {
        return $this->productIngredientId;
    }

    /**
     * Set quantity
     *
     * @param float $quantity
     * @return Ingredients
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return float 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set product
     *
     * @param \HotelBundle\Entity\Product $product
     * @return Ingredients
     */
    public function setProduct(\HotelBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \HotelBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }
}
