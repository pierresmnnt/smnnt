<?php

namespace App\Data;

use App\Entity\Category;

class SearchData 
{
    /**
     * @var int page;
     */
    private int $page = 1;

    /**
     * @var Category
     */
    private $category = null;

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
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category): self
    {
        $this->category = $category;

        return $this;
    }
}