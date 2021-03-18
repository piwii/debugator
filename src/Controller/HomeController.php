<?php

namespace App\Controller;

use App\Repository\DeveloperRepository;
use App\Services\Debugator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController
{
    private Debugator $debugator;

    public function __construct(Debugator $debugator)
    {
        $this->debugator = $debugator;
    }

    /**
     * @route("/help", name="help")
     */
    public function help(): Response
    {
        return new Response('help');
    }

    /**
     * @route("/", methods="POST", name="command")
     */
    public function execCommand(Request $request): Response
    {
        $arg = explode(' ', $request->get('text'));
        $response = $this->debugator->{$arg[0]}(...$arg);

        return new Response(json_encode([
            'text' => $response,
            'mrkdwn' => true,
        ]),
        Response::HTTP_OK,
        [
            'Content-Type' => 'application/json'
        ]);
    }
}
