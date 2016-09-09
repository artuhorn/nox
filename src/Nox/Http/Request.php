<?php

namespace Nox\Http;


use Nox\Core\Hash;
use Nox\Helpers\TSingleton;

class Request extends Hash
{
    use TSingleton;
    
    /** @var string */
    public $url;
    
    /** @var Hash */
    public $post;

    /** @var Hash */
    public $get;
    
    /** @var Hash */
    public $params;
    
    public function init()
    {
        $this->url = $_SERVER['REQUEST_URI'];
        
        $this->post = new Hash();
        $this->post->fromArray($_POST);
        
        $this->get = new Hash();
        $this->get->fromArray($_GET);

        $this->params = new Hash();
    }

}
