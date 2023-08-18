<?php

namespace App\Service;

use DateTime;
use DateTimeZone;
use Symfony\Component\Uid\Uuid;

class MessageFileManager
{
    private string $dataFilePath;

    public function __construct(string $dataFilePath = null)
    {
        $this->dataFilePath = $dataFilePath ?? __DIR__ . '/../../data/messages.json';
    }

    private function writeData(array $data): void
    {
        file_put_contents($this->dataFilePath, json_encode($data));
    }

    private function readData(): array
    {
        $data = [];
        if (file_exists($this->dataFilePath)) {
            $data = json_decode(file_get_contents($this->dataFilePath), true);
        }
        return $data;
    }

    public function addMessage(string $message): string
    {
        $uuid = Uuid::v4()->toHex();
        $createdAt = (new DateTime())->format('Y-m-d H:i:s');
        
        $data = $this->readData();
        $data[$uuid] = ['uuid' => $uuid, 'message' => $message, 'createdAt' => $createdAt];
        $this->writeData($data);

        return $uuid;
    }

    public function getMessage(string $uuid): ?array
    {
        $data = $this->readData();
        
        return $data[$uuid] ?? null;
    }

    public function getAllMessages(): array
    {
        $data = $this->readData();
        
        return $data;
    }
}
