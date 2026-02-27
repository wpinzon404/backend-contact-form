<?php

namespace App\Controllers;

use App\Models\ContactModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class ContactController extends ResourceController
{
    protected $format    = 'json';
    protected $modelName = ContactModel::class;

    private const RULES = [
        'name'    => 'required|min_length[2]|max_length[100]',
        'email'   => 'required|valid_email|max_length[150]',
        'message' => 'required|min_length[10]',
    ];

    private const MESSAGES = [
        'name' => [
            'required'   => 'Name is required.',
            'min_length' => 'Name must be at least 2 characters.',
            'max_length' => 'Name cannot exceed 100 characters.',
        ],
        'email' => [
            'required'    => 'Email is required.',
            'valid_email' => 'Please enter a valid email address.',
            'max_length'  => 'Email cannot exceed 150 characters.',
        ],
        'message' => [
            'required'   => 'Message is required.',
            'min_length' => 'Message must be at least 10 characters.',
        ],
    ];

    public function index(): ResponseInterface
    {
        $contacts = $this->model->orderBy('created_at', 'DESC')->findAll();

        return $this->respond([
            'status' => 'success',
            'total'  => count($contacts),
            'data'   => $contacts,
        ]);
    }

    public function show($id = null): ResponseInterface
    {
        $contact = $this->model->find($id);

        if ($contact === null) {
            return $this->contactNotFound($id);
        }

        return $this->respond(['status' => 'success', 'data' => $contact]);
    }

    public function create(): ResponseInterface
    {
        if (!$this->isContactValid()) {
            return $this->validationFailed();
        }

        $id = $this->model->insert($this->sanitize($this->getRequestData()), true);

        if (!$id) {
            return $this->respond([
                'status'  => 'error',
                'message' => 'Failed to save contact. Please try again.',
            ], 500);
        }

        return $this->respond([
            'status'  => 'success',
            'message' => 'Contact saved successfully.',
            'data'    => $this->model->find($id),
        ], 201);
    }

    public function update($id = null): ResponseInterface
    {
        if ($this->model->find($id) === null) {
            return $this->contactNotFound($id);
        }

        if (!$this->isContactValid()) {
            return $this->validationFailed();
        }

        $this->model->update($id, $this->sanitize($this->getRequestData()));

        return $this->respond([
            'status'  => 'success',
            'message' => 'Contact updated successfully.',
            'data'    => $this->model->find($id),
        ]);
    }

    public function delete($id = null): ResponseInterface
    {
        if ($this->model->find($id) === null) {
            return $this->contactNotFound($id);
        }

        $this->model->delete($id);

        return $this->respond([
            'status'  => 'success',
            'message' => "Contact {$id} deleted successfully.",
        ]);
    }

    public function preflight(): ResponseInterface
    {
        return $this->response->setStatusCode(204);
    }

    private function getRequestData(): array
    {
        return $this->request->getJSON(true) ?? $this->request->getPost() ?? [];
    }

    private function isContactValid(): bool
    {
        return $this->validate(self::RULES, self::MESSAGES);
    }

    private function sanitize(array $data): array
    {
        return [
            'name'    => esc(trim($data['name'])),
            'email'   => esc(trim($data['email'])),
            'message' => esc(trim($data['message'])),
        ];
    }

    private function contactNotFound(mixed $id): ResponseInterface
    {
        return $this->respond([
            'status'  => 'error',
            'message' => "Contact with ID {$id} not found.",
        ], 404);
    }

    private function validationFailed(): ResponseInterface
    {
        return $this->respond([
            'status'  => 'error',
            'message' => 'Validation failed.',
            'errors'  => $this->validator->getErrors(),
        ], 422);
    }
}
