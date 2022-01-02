<?php

namespace App\Data;

use App\Entity\Category;

class SearchData 
{
    /**
     * @var Category[]
     */
    private array $categories = [];

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }
}