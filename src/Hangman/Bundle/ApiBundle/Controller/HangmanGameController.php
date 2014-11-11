<?php

namespace Hangman\Bundle\ApiBundle\Controller;

use Hangman\Bundle\DatastoreBundle\Exception\InvalidCharacterGuessedException;
use Hangman\Bundle\GameBundle\Service\GameService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouterInterface;

class HangmanGameController
{
    /**
     * @var GameService
     */
    private $gameService;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param GameService $gameService
     * @param RouterInterface $router
     */
    public function __construct(GameService $gameService, RouterInterface $router)
    {
        $this->gameService = $gameService;
        $this->router = $router;
    }
    
    /**
     * @return Response
     */
    public function startAction()
    {
        $gameData = $this->gameService->startNewGame();

        return new JsonResponse(
            $gameData,
            201,
            ['Location' => $this->router->generate('hangman_api_game', ['gameId' => $gameData->id])]
        );
    }

    /**
     * @return Response
     */
    public function viewAction()
    {
        return new JsonResponse($this->gameService->startNewGame());
    }

    /**
     * @param integer $gameId
     * @param Request $request
     * @return JsonResponse
     *
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function guessAction($gameId, Request $request)
    {
        try {
            $character = $this->getParameterFromRequest($request, 'character');

            return new JsonResponse(
                $this->gameService->guess($gameId, $character)
            );
        } catch (ResourceNotFoundException $exception) {
            throw new NotFoundHttpException('Game with id ' . $gameId . ' does not exist.');
        } catch (InvalidCharacterGuessedException $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * Convenience method for retrieving parameters from Request for both Content-Types:
     * - application/json
     * - application/x-www-form-urlencoded
     *
     * In this way, we can easily support both json stream input, as well as regular
     * POST/PUT parameters in the request payload.
     *
     * @param Request $request
     * @param string $parameter
     * @return mixed
     */
    protected function getParameterFromRequest(Request $request, $parameter)
    {
        if ($request->getContentType() === 'json') {
            $jsonPayload = json_decode($request->getContent());

            return $jsonPayload->{$parameter};
        }

        return $request->get($parameter);
    }
}
