<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class PostsController extends Controller
{
    /**
     * @Route("/{_locale}/posts/refresh", name="postsRefresh")
     */
    public function refreshPosts(Request $request)
    {
        $fetchFeedPosts = $this->get('app.services.fetchFeedPosts');

        $fetchFeedPosts->fetchAllPosts();

        return new Response("ok");
    }
}
