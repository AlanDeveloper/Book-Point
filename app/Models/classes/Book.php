<?php

namespace MyApp\Models\classes;

class Book {

    private $name;
    private $author;
    private $price;
    private $image;
    private $amount;
    private $synopsis;
    private $genre;
    private $language;
    private $id;

    public function __construct($name, $author, $price, $image, $amount, $synopsis, $genre, $language)
    {
        $this->name = $name;
        $this->author = $author;
        $this->price = $price;
        $this->image = $image;
        $this->amount = $amount;
        $this->synopsis = $synopsis;
        $this->genre = $genre;
        $this->language = $language;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getFormatedPrice()
    {
        $number = $this->getPrice();
        $number = number_format($number, 2, ",", ".");
        return $number;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getSynopsis()
    {
        return $this->synopsis;
    }

    public function setSynopsis($synopsis)
    {
        $this->synopsis = $synopsis;
    }

    public function getGenre()
    {
        return $this->genre;
    }

    public function setGenre($genre)
    {
        $this->genre = $genre;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
}