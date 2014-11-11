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

class HangmanGameController
{
    /**
     * @var GameService
     */
    private $gameService;

    /**
     * @param GameService $gameService
     */
    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }
    
    /**
     * @return Response
     */
    public function startAction()
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
