<?php
namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\Note;
use PDO;

class NotesController extends Controller
{
    private PDO $connection;
    private Note $note;

    public function __construct()
    {
        parent::__construct();
        $this->connection = Connection::createConnection();
        $this->note = Note::createFromConnection($this->connection);
    }

    public function index()
    {
        $this->response->setHeader("Access-Control-Allow-Origin", "*");
        $this->response->setParam("notes", $this->note->findAllArray());
        $this->sendResponse();
    }

    public function get(array $args)
    {
        $this->response->setHeader("Access-Control-Allow-Origin", "*");
        $this->response->setParam(
            "notes",
            $this->note->findByIdArray($args['id'])
        );
        $this->sendResponse();
    }

    public function save()
    {
        $this->response->setHeader("Access-Control-Allow-Origin", "*");

        $data = $this->request->getData();
        $title = filter_var($data['title'], FILTER_SANITIZE_STRING);
        $body = filter_var($data['body'], FILTER_SANITIZE_STRING);

        $this->note
            ->setTitle($title)
            ->setBody($body)
            ->save();

        $this->response->setParam("note", [
            "title" => $title,
            "body" => $body,
        ]);

        $this->sendResponse(201);
    }

    public function cors()
    {
        $this->response->setHeader("Access-Control-Allow-Origin", "*");
        $this->request->acceptCORS("PUT, DELETE");
    }

    public function update(array $args)
    {
        $this->response->setHeader("Access-Control-Allow-Origin", "*");
        $this->response->setHeader("Access-Control-Allow-Methods", "PUT");

        $data = $this->request->getData();
        $title = filter_var($data['title'], FILTER_SANITIZE_STRING);
        $body = filter_var($data['body'], FILTER_SANITIZE_STRING);

        $selectedNote = $this->note->findById($args['id']);
        $selectedNote
            ->setConnection($this->connection)
            ->setTitle($title)
            ->setBody($body)
            ->update();

        $this->response->setParam("notes", [
            "title" => $title,
            "body" => $body,
        ]);
        $this->sendResponse();
    }

    public function delete($args)
    {
        $this->response->setHeader("Access-Control-Allow-Origin", "*");
        $this->response->setHeader("Access-Control-Allow-Methods", "DELETE");
        $deletedNote = $this->note->findByIdArray($args['id']);
        $this->note->setId($args['id'])->destroy();
        $this->response->setParam("note", $deletedNote);
        $this->sendResponse();
    }
}
