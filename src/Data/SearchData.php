<?php

namespace App\Data;

use App\Entity\Category;

class SearchData 
{
    /**
     * @var int;
     */
    private int $page = 1;

    /**
     * @var Category[]
     */
    private array $categories = [];

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;
        
        return $this;
    }

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