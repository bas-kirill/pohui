<?php

require_once "../util/functions.php";

class Book {
    public $isbn;
    public $title;
    public $price;
    public $description;
    public $category;

    public function __construct($isbn, $title, $price, $description, $category) {
        $this->isbn = $isbn;
        $this->title = $title;
        $this->price = $price;
        $this->description = $description;
        $this->category = $category;
    }
}

?>