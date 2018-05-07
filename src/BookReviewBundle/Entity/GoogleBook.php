<?php

namespace BookReviewBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use JMS\Serializer\Annotation as JMS;
use GuzzleHttp;
use GuzzleHttp\Client;

class GoogleBook extends Book
{
    private $googleId;
    private $publisher;
    private $publishedDate;
    private $isbnTen;
    private $isbnThirteen;
    private $pages;
    private $categories;
    private $shortGoogleUrl;

    /**
     * @return mixed
     */
    public function getShortGoogleUrl()
    {
        return $this->shortGoogleUrl;
    }

    /**
     * @param mixed $shortGoogleUrl
     */
    public function setShortGoogleUrl($shortGoogleUrl)
    {
        $this->shortGoogleUrl = $shortGoogleUrl;
    }

    /**
     * @return mixed
     */
    public function getShortUrl()
    {
        return $this->shortUrl;
    }

    /**
     * @param mixed $shortUrl
     */
    public function setShortUrl($shortUrl)
    {
        $this->shortUrl = $shortUrl;
    }

    /**
     * @return mixed
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }

    /**
     * @param mixed $googleId
     */
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;
    }

    /**
     * @return mixed
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * @param mixed $publisher
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;
    }

    /**
     * @return mixed
     */
    public function getPublishedDate()
    {
        return $this->publishedDate;
    }

    /**
     * @param mixed $publishedDate
     */
    public function setPublishedDate($publishedDate)
    {
        $this->publishedDate = $publishedDate;
    }

    /**
     * @return mixed
     */
    public function getIsbnTen()
    {
        return $this->isbnTen;
    }

    /**
     * @param mixed $isbnTen
     */
    public function setIsbnTen($isbnTen)
    {
        $this->isbnTen = $isbnTen;
    }

    /**
     * @return mixed
     */
    public function getIsbnThirteen()
    {
        return $this->isbnThirteen;
    }

    /**
     * @param mixed $isbnThirteen
     */
    public function setIsbnThirteen($isbnThirteen)
    {
        $this->isbnThirteen = $isbnThirteen;
    }

    /**
     * @return mixed
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * @param mixed $pages
     */
    public function setPages($pages)
    {
        $this->pages = $pages;
    }

    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param mixed $categories
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    public function loadJsonData($json){
        $this->setGoogleId($json->id);
        $this->loadVolumeInfo($json->volumeInfo);
        $this->loadVolumeInfoGoogle($json->volumeInfo);

    }

    public function loadVolumeInfoGoogle($volumeInfoJson){
        if(isset($volumeInfoJson->imageLinks))
            $this->setImageName($volumeInfoJson->imageLinks->thumbnail);

        if(isset($volumeInfoJson->publisher))
            $this->setPublisher($volumeInfoJson->publisher);
        if(isset($volumeInfoJson->publishedDate))
            $this->setPublishedDate($volumeInfoJson->publishedDate);


        if(isset($volumeInfoJson->industryIdentifiers)){
            for ($i = 0; $i < count($volumeInfoJson->industryIdentifiers); $i++){
                $indentifier = $volumeInfoJson->industryIdentifiers[$i];

                if ($indentifier->type == 'ISBN_10')
                    $this->setIsbnTen($indentifier->identifier);
                if ($indentifier->type == 'ISBN_13')
                    $this->setIsbnThirteen($indentifier->identifier);
            }
        }

        if(isset($volumeInfoJson->industryIdentifiers[0]))

        if(isset($volumeInfoJson->industryIdentifiers[1]))
            $this->setIsbnThirteen($volumeInfoJson->industryIdentifiers[1]->identifier);

        if(isset($volumeInfoJson->pageCount))
            $this->setPages($volumeInfoJson->pageCount);

        if(isset($volumeInfoJson->categories))
            $this->setCategories(implode("|",$volumeInfoJson->categories));


        $googleBooksUrl = "https://books.google.co.uk/books?id=";
        $this->setShortGoogleUrl($this->shortUrl($googleBooksUrl . $this->googleId));
    }

    /**
     * @param Book $book
     * @return mixed
     */
    public function shortUrl($longUrl)
    {
        $client = new Client();

        $url = "https://www.googleapis.com/urlshortener/v1/url?key=AIzaSyC5IdpPmYuCG21fT2UWIvaamgZOB4etZyk";
        //var_dump($longUrl);
        $response = $client->post($url, [
            GuzzleHttp\RequestOptions::JSON => ['longUrl' => $longUrl]
        ]);

        $jsonObj = json_decode($response->getBody());

        return $jsonObj->id;
    }

}

