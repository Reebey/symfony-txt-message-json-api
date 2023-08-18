<?php

namespace App\Controller;

use App\Service\MessageFileManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    private MessageFileManager $messageFileManager;

    public function __construct(MessageFileManager $messageFileManager)
    {
        $this->messageFileManager = $messageFileManager;
    }
    
    #[Route('/messages', name: 'message_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $messages = $this->messageFileManager->getAllMessages();

        // Determine sort criteria
        $defaultSortBy = 'createdAt';
        $sortBy = $request->query->get('sort_by', $defaultSortBy);
        $validSortBy = in_array($sortBy, ['uuid', 'message', 'createdAt']) ? $sortBy : $defaultSortBy;

        // Determine sort order
        $defaultOrderBy = 'asc';
        $sortOrder = $request->query->get('order_by', $defaultOrderBy);
        $validSortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : $defaultOrderBy;

        // Sort
        usort($messages, function ($a, $b) use ($validSortBy, $validSortOrder) {
            $comparison = strcmp($a[$validSortBy], $b[$validSortBy]);
            return ($validSortOrder === 'asc') ? $comparison : -$comparison;
        });

        return new Response(json_encode($messages, true), Response::HTTP_OK);
    }

    #[Route('/messages/{uuid}', name: 'message_show', methods: ['GET'])]
    public function show(string $uuid): Response
    {
        $message = $this->messageFileManager->getMessage($uuid);
        
        if (is_null($message)) {
            return new Response('Message not found.', Response::HTTP_NOT_FOUND);
        }
        return new Response(json_encode($message, true), Response::HTTP_OK);
    }

    #[Route('/messages', name: 'message_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $message = $request->getContent();
        $uuid = $this->messageFileManager->addMessage($message);
        return new Response($uuid, Response::HTTP_CREATED);
    }
}