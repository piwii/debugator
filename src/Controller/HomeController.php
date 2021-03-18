<?php

namespace App\Controller;

use App\Services\Debugator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController
{
    private Debugator $debugator;
    private string $slackToken;

    public function __construct(Debugator $debugator, string $slackToken)
    {
        $this->debugator = $debugator;
        $this->slackToken = $slackToken;
    }

    /**
     * @route("/help", name="help")
     */
    public function help(): Response
    {
        return new Response('help');
    }

    /**
     * @route("/", methods={"GET","POST"}, name="command")
     */
    public function execCommand(Request $request): Response
    {
        if ($request->get('token') !== $this->slackToken) {
            return new Response(json_encode('Unauthorized : bad slack toekn'), Response::HTTP_UNAUTHORIZED);
        }

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
