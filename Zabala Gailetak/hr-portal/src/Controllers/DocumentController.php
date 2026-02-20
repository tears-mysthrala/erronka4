<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Controllers;

use ZabalaGailetak\HrPortal\Database\Database;
use ZabalaGailetak\HrPortal\Http\Request;
use ZabalaGailetak\HrPortal\Http\Response;
use ZabalaGailetak\HrPortal\Models\Document;

/**
 * Document Controller
 * Handles document upload, download, and management
 */
class DocumentController
{
    private const MAX_FILE_SIZE = 10485760; // 10MB
    private const ALLOWED_TYPES = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
    
    // MIME type whitelist for security
    private const ALLOWED_MIME_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'image/jpeg',
        'image/png'
    ];
    
    // Dynamic upload directory - uses DOCUMENT_ROOT for InfinityFree compatibility
    private string $uploadDir;

    public function __construct(
        private readonly Database $db
    ) {
        // Use DOCUMENT_ROOT for InfinityFree open_basedir compatibility
        // This ensures we stay within the htdocs directory
        $this->uploadDir = ($_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/../../public') . '/storage/documents/';
        
        // Ensure upload directory exists
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    /**
     * Get my documents with pagination
     * GET /api/documents/my
     */
    public function getMyDocuments(Request $request): Response
    {
        $user = $request->getAttribute('user');
        if (!$user) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        $category = $request->getQuery('category');
        $page = (int) ($request->getQuery('page', 1));
        $limit = (int) ($request->getQuery('limit', 20));
        
        // Validate pagination
        if ($page < 1) $page = 1;
        if ($limit < 1 || $limit > 100) $limit = 20;
        $offset = ($page - 1) * $limit;

        // Get total count
        $countQuery = 'SELECT COUNT(*) FROM documents 
                       WHERE is_archived = FALSE AND employee_id = ?';
        $countParams = [$user['id']];
        
        if ($category) {
            $countQuery .= ' AND type = ?';
            $countParams[] = $category;
        }
        
        $countStmt = $this->db->prepare($countQuery);
        $countStmt->execute($countParams);
        $totalCount = (int) $countStmt->fetchColumn();

        // Get paginated documents
        $query = 'SELECT d.*, u.email as uploaded_by_email 
                  FROM documents d 
                  JOIN users u ON d.uploaded_by = u.id
                  WHERE d.is_archived = FALSE 
                  AND d.employee_id = ?';
        $params = [$user['id']];

        // Filter by category
        if ($category) {
            $query .= ' AND d.type = ?';
            $params[] = $category;
        }

        $query .= ' ORDER BY d.created_at DESC LIMIT ? OFFSET ?';
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $documents = array_map(function ($row) {
            $document = Document::fromDatabase($row);
            $data = $document->toArray();
            $data['uploaded_by_email'] = $row['uploaded_by_email'];
            return $data;
        }, $rows);

        return Response::json([
            'success' => true,
            'data' => $documents,
            'count' => count($documents),
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $totalCount,
                'total_pages' => (int) ceil($totalCount / $limit)
            ]
        ]);
    }

    /**
     * Get public documents with pagination
     * GET /api/documents/public
     */
    public function getPublicDocuments(Request $request): Response
    {
        $user = $request->getAttribute('user');
        if (!$user) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        $category = $request->getQuery('category');
        $page = (int) ($request->getQuery('page', 1));
        $limit = (int) ($request->getQuery('limit', 20));
        
        // Validate pagination
        if ($page < 1) $page = 1;
        if ($limit < 1 || $limit > 100) $limit = 20;
        $offset = ($page - 1) * $limit;
        
        // Get total count
        $countQuery = 'SELECT COUNT(*) FROM documents 
                       WHERE is_archived = FALSE AND employee_id IS NULL';
        $countParams = [];
        
        if ($category) {
            $countQuery .= ' AND type = ?';
            $countParams[] = $category;
        }
        
        $countStmt = $this->db->prepare($countQuery);
        $countStmt->execute($countParams);
        $totalCount = (int) $countStmt->fetchColumn();

        $query = 'SELECT d.*, u.email as uploaded_by_email 
                  FROM documents d 
                  JOIN users u ON d.uploaded_by = u.id
                  WHERE d.is_archived = FALSE 
                  AND d.employee_id IS NULL';
        $params = [];

        // Filter by category
        if ($category) {
            $query .= ' AND d.type = ?';
            $params[] = $category;
        }

        $query .= ' ORDER BY d.created_at DESC LIMIT ? OFFSET ?';
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $documents = array_map(function ($row) {
            $document = Document::fromDatabase($row);
            $data = $document->toArray();
            $data['uploaded_by_email'] = $row['uploaded_by_email'];
            return $data;
        }, $rows);

        return Response::json([
            'success' => true,
            'data' => $documents,
            'count' => count($documents),
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $totalCount,
                'total_pages' => (int) ceil($totalCount / $limit)
            ]
        ]);
    }

    /**
     * List documents with pagination
     * GET /api/documents
     */
    public function index(Request $request): Response
    {
        $user = $request->getAttribute('user');
        if (!$user) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        $category = $request->getQuery('category');
        $isPublic = $request->getQuery('public');
        $page = (int) ($request->getQuery('page', 1));
        $limit = (int) ($request->getQuery('limit', 20));
        
        // Validate pagination
        if ($page < 1) $page = 1;
        if ($limit < 1 || $limit > 100) $limit = 20;
        $offset = ($page - 1) * $limit;

        // Build count query
        $countQuery = 'SELECT COUNT(*) FROM documents d 
                       WHERE d.is_archived = FALSE AND (';
        $countParams = [];

        // Users can see their own documents + public documents
        if ($user['role'] === 'admin' || $user['role'] === 'hr_manager') {
            $countQuery .= '1=1'; // Admin sees all
        } else {
            $countQuery .= 'd.employee_id = ? OR d.employee_id IS NULL';
            $countParams[] = $user['id'];
        }

        $countQuery .= ')';

        // Filter by category
        if ($category) {
            $countQuery .= ' AND d.type = ?';
            $countParams[] = $category;
        }

        // Filter by public/private
        if ($isPublic !== null) {
            if ($isPublic === 'true' || $isPublic === '1') {
                $countQuery .= ' AND d.employee_id IS NULL';
            } else {
                $countQuery .= ' AND d.employee_id IS NOT NULL';
            }
        }
        
        $countStmt = $this->db->prepare($countQuery);
        $countStmt->execute($countParams);
        $totalCount = (int) $countStmt->fetchColumn();

        // Build data query
        $query = 'SELECT d.*, u.email as uploaded_by_email 
                  FROM documents d 
                  JOIN users u ON d.uploaded_by = u.id
                  WHERE d.is_archived = FALSE AND (';
        $params = [];

        // Users can see their own documents + public documents
        if ($user['role'] === 'admin' || $user['role'] === 'hr_manager') {
            $query .= '1=1'; // Admin sees all
        } else {
            $query .= 'd.employee_id = ? OR d.employee_id IS NULL';
            $params[] = $user['id'];
        }

        $query .= ')';

        // Filter by category
        if ($category) {
            $query .= ' AND d.type = ?';
            $params[] = $category;
        }

        // Filter by public/private
        if ($isPublic !== null) {
            if ($isPublic === 'true' || $isPublic === '1') {
                $query .= ' AND d.employee_id IS NULL';
            } else {
                $query .= ' AND d.employee_id IS NOT NULL';
            }
        }

        $query .= ' ORDER BY d.created_at DESC LIMIT ? OFFSET ?';
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $documents = array_map(function ($row) {
            $document = Document::fromDatabase($row);
            $data = $document->toArray();
            $data['uploaded_by_email'] = $row['uploaded_by_email'];
            return $data;
        }, $rows);

        return Response::json([
            'success' => true,
            'data' => $documents,
            'count' => count($documents),
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $totalCount,
                'total_pages' => (int) ceil($totalCount / $limit)
            ]
        ]);
    }

    /**
     * Get specific document details
     * GET /api/documents/{id}
     */
    public function show(Request $request, string $id): Response
    {
        $user = $request->getAttribute('user');
        if (!$user) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        $query = 'SELECT d.*, u.email as uploaded_by_email 
                  FROM documents d 
                  JOIN users u ON d.uploaded_by = u.id
                  WHERE d.id = ?';
        $params = [$id];

        // Check access permissions
        if ($user['role'] !== 'admin' && $user['role'] !== 'hr_manager') {
            $query .= ' AND (d.employee_id = ? OR d.employee_id IS NULL)';
            $params[] = $user['id'];
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            return Response::json(['error' => 'Document not found'], 404);
        }

        $document = Document::fromDatabase($row);
        $data = $document->toArray();
        $data['uploaded_by_email'] = $row['uploaded_by_email'];

        return Response::json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Upload new document
     * POST /api/documents/upload
     */
    public function upload(Request $request): Response
    {
        $user = $request->getAttribute('user');
        if (!$user || ($user['role'] !== 'admin' && $user['role'] !== 'hr_manager')) {
            return Response::json(['error' => 'Forbidden'], 403);
        }

        $files = $_FILES ?? [];
        if (!isset($files['file'])) {
            return Response::json(['error' => 'No file uploaded'], 400);
        }

        $file = $files['file'];
        $data = $_POST;

        // Validate file
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return Response::json(['error' => 'File upload error'], 400);
        }

        if ($file['size'] > self::MAX_FILE_SIZE) {
            return Response::json([
                'error' => 'File size exceeds maximum allowed (10MB)'
            ], 400);
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, self::ALLOWED_TYPES)) {
            return Response::json([
                'error' => 'File type not allowed. Allowed types: ' . implode(', ', self::ALLOWED_TYPES)
            ], 400);
        }

        // Validate MIME type using finfo (security fix - don't trust client-provided MIME)
        if (!extension_loaded('fileinfo')) {
            return Response::json(['error' => 'Server configuration error: fileinfo extension required'], 500);
        }
        
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $realMimeType = $finfo->file($file['tmp_name']);
        
        if (!in_array($realMimeType, self::ALLOWED_MIME_TYPES)) {
            return Response::json([
                'error' => 'Invalid file content type detected. File may be corrupted or unsafe.'
            ], 400);
        }

        // Validate required fields
        if (!isset($data['type']) || !in_array($data['type'], ['contract', 'nif', 'payroll', 'certificate', 'other'])) {
            return Response::json(['error' => 'Invalid document type'], 400);
        }

        // Generate unique filename
        $uniqueFilename = uniqid() . '_' . time() . '.' . $extension;
        $uploadPath = $this->uploadDir . $uniqueFilename;

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return Response::json(['error' => 'Failed to save file'], 500);
        }

        // Calculate checksum
        $checksum = hash_file('sha256', $uploadPath);

        try {
            $stmt = $this->db->prepare(
                'INSERT INTO documents (
                    employee_id, type, filename, original_filename,
                    file_path, mime_type, file_size, checksum,
                    description, uploaded_by
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );

            $stmt->execute([
                $data['employee_id'] ?? null,
                $data['type'],
                $uniqueFilename,
                $file['name'],
                $uploadPath,
                $realMimeType, // Use server-detected MIME type, not client-provided
                $file['size'],
                $checksum,
                $data['description'] ?? null,
                $user['id']
            ]);

            // Get last inserted ID (MySQL compatible)
            $documentId = $this->db->lastInsertId();
            
            // Fetch the created record
            $stmt = $this->db->prepare(
                'SELECT d.*, u.email as uploaded_by_email 
                 FROM documents d 
                 JOIN users u ON d.uploaded_by = u.id
                 WHERE d.id = ?'
            );
            $stmt->execute([$documentId]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            $document = Document::fromDatabase($row);

            return Response::json([
                'success' => true,
                'message' => 'Document uploaded successfully',
                'data' => $document->toArray()
            ], 201);
        } catch (\PDOException $e) {
            // Clean up file on database error
            if (file_exists($uploadPath)) {
                unlink($uploadPath);
            }
            throw $e;
        }
    }

    /**
     * Download document
     * GET /api/documents/{id}/download
     */
    public function download(Request $request, string $id): Response
    {
        $user = $request->getAttribute('user');
        if (!$user) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        $query = 'SELECT * FROM documents WHERE id = ?';
        $params = [$id];

        // Check access permissions
        if ($user['role'] !== 'admin' && $user['role'] !== 'hr_manager') {
            $query .= ' AND (employee_id = ? OR employee_id IS NULL)';
            $params[] = $user['id'];
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            return Response::json(['error' => 'Document not found'], 404);
        }

        $document = Document::fromDatabase($row);
        $filePath = $document->filePath;

        if (!file_exists($filePath)) {
            return Response::json(['error' => 'File not found on disk'], 404);
        }

        // Set headers for file download
        header('Content-Type: ' . $document->mimeType);
        header('Content-Disposition: attachment; filename="' . $document->originalFilename . '"');
        header('Content-Length: ' . $document->fileSize);
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: 0');

        // Output file
        readfile($filePath);
        exit;
    }

    /**
     * Delete document (Admin or owner only) - Archives and optionally removes physical file
     * DELETE /api/documents/{id}
     */
    public function delete(Request $request, string $id): Response
    {
        $user = $request->getAttribute('user');
        if (!$user) {
            return Response::json(['error' => 'Unauthorized'], 401);
        }

        $query = 'SELECT * FROM documents WHERE id = ?';
        $params = [$id];

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            return Response::json(['error' => 'Document not found'], 404);
        }

        // Check permissions: Admin or document uploader
        if ($user['role'] !== 'admin' && $row['uploaded_by'] !== $user['id']) {
            return Response::json(['error' => 'Forbidden'], 403);
        }

        $document = Document::fromDatabase($row);
        $filePath = $document->filePath;

        // Archive the document (soft delete)
        $stmt = $this->db->prepare(
            'UPDATE documents SET is_archived = TRUE, archived_at = NOW() WHERE id = ?'
        );
        $stmt->execute([$id]);

        // Remove physical file to save storage space
        // The database record is kept for audit purposes
        if (file_exists($filePath)) {
            if (!unlink($filePath)) {
                // Log the error but don't fail the operation
                error_log("[DOCUMENT] Failed to delete physical file: $filePath");
            } else {
                // Update the record to indicate file has been removed
                $stmt = $this->db->prepare(
                    'UPDATE documents SET file_path = NULL WHERE id = ?'
                );
                $stmt->execute([$id]);
            }
        }

        return Response::json([
            'success' => true,
            'message' => 'Document archived successfully'
        ]);
    }

    /**
     * Get document categories
     * GET /api/documents/categories
     */
    public function categories(Request $request): Response
    {
        $categories = [
            [
                'value' => 'contract',
                'label_eu' => 'Kontratuak',
                'label_es' => 'Contratos',
                'color' => '#667EEA'
            ],
            [
                'value' => 'nif',
                'label_eu' => 'NIF/NIE',
                'label_es' => 'NIF/NIE',
                'color' => '#F59E0B'
            ],
            [
                'value' => 'payroll',
                'label_eu' => 'Nominak',
                'label_es' => 'Nóminas',
                'color' => '#06B6D4'
            ],
            [
                'value' => 'certificate',
                'label_eu' => 'Ziurtagiriak',
                'label_es' => 'Certificados',
                'color' => '#10B981'
            ],
            [
                'value' => 'other',
                'label_eu' => 'Beste batzuk',
                'label_es' => 'Otros',
                'color' => '#6B7280'
            ]
        ];

        return Response::json([
            'success' => true,
            'data' => $categories
        ]);
    }
}
