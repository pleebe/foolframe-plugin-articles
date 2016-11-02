<?php

namespace Foolz\FoolFuuka\Controller\Api;

use Foolz\FoolFrame\Plugins\Articles\Model\Articles as A;
use Foolz\FoolFrame\Plugins\Articles\Model\ArticlesArticleNotFoundException;

class Articles extends \Foolz\FoolFuuka\Controller\Api\Chan
{
    /**
     * @var A
     */
    protected $articles;

    public function before()
    {
        parent::before();
        $this->articles = new A($this->getContext());
    }

    public function get_articles()
    {
        $slug = $this->getQuery('slug');
        if (!$slug) {
            $response = $this->articles->getAll();
            return $this->response->setData($response);
        }

        try {
            $article = $this->articles->getBySlug($slug);
        } catch (ArticlesArticleNotFoundException $e) {
            return $this->response->setData(['error' => _i('No such article.')])->setStatusCode(404);
        }

        $response = [
            'id'=> $article['id'],
            'slug'=> $article['slug'],
            'title' => $article['title'],
            'url' => $article['url'],
            'content_raw' => $article['content'],
            'content_formatted' => \Foolz\FoolFrame\Model\Markdown::parse($article['content']),
            'hidden' => $article['hidden'],
            'top' => $article['top'],
            'bottom' =>$article['bottom'],
            'timestamp' => $article['timestamp']
        ];

        return $this->response->setData($response);
    }
}