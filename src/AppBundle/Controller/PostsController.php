<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


class PostsController extends Controller
{
    /**
     * @Route("/{_locale}/posts/refresh", name="postsRefresh")
     */
    public function refreshPosts(Request $request) {
        $fetchFeedPosts = $this->get('app.services.fetchFeedPosts');
        $fetchFeedPosts->fetchAllPosts();

        return new Response("ok");
    }

    /**
     * @Route("/{_locale}/posts/getSingle", name="postsGetSingle")
     * @Method("POST")
     */
    public function getSinglePost(Request $request) {
        $id = $request->request->get('id');

        $getFeedPosts = $this->get('app.services.getFeedPosts');
        $content = $getFeedPosts->getSinglePost($id, $request->getLocale());

        return new JsonResponse($content);
    }

    /**
     * @Route("/{_locale}/posts/markSingleAsRead", name="postsMarkSingleAsRead")
     * @Method("POST")
     */
    public function markSinglePostAsRead(Request $request) {
        $id = $request->request->get('id');

        $getFeedPosts = $this->get('app.services.getFeedPosts');
        $content = $getFeedPosts->markSinglePostAsRead($id);

        return new Response("ok");
    }
}
