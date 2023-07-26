<?php

$host = $_SERVER["DOCUMENT_ROOT"];
require_once $host . "/util/functions.php";

class Book {
    public $isbn;
    public $title;
    public $price;
    public $description;
    public $category;
    public $position;

    public function __construct($isbn, $title, $price, $description, $category, $position) {
        $this->isbn = $isbn;
        $this->title = $title;
        $this->price = $price;
        $this->description = $description;
        $this->category = $category;
        $this->position = $position;
    }
}

?>