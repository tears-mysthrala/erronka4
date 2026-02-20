<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Controllers\Web;

use ZabalaGailetak\HrPortal\Database\Database;
use ZabalaGailetak\HrPortal\Http\Request;
use ZabalaGailetak\HrPortal\Http\Response;
use ZabalaGailetak\HrPortal\Models\Document;

/**
 * Web Document Controller
 * Handles server-side rendered pages for documents
 */
class WebDocumentController
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
        $this->uploadDir = ($_SERVER['DOCUMENT_ROOT'] ?? __DIR__ . '/../../../public') . '/storage/documents/';
        
        // Ensure upload directory exists
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    /**
     * Get authenticated user from session or JWT
     */
    private function getUser(Request $request): ?array
    {
        // First check session (web login)
        $user = $_SESSION['user'] ?? null;
        if ($user) {
            return $user;
        }
        
        // Then check JWT (API login via request attribute set by AuthenticationMiddleware)
        $jwtUser = $request->getAttribute('user');
        if ($jwtUser) {
            return $jwtUser;
        }
        
        return null;
    }

    /**
     * List documents view
     * GET /documents
     */
    public function index(Request $request): Response
    {
        $user = $this->getUser($request);
        if (!$user) {
            return Response::redirect('/login');
        }

        $category = $request->getQuery('category');
        $tab = $request->getQuery('tab') ?? 'personal';
        
        // Get personal documents
        $personalQuery = 'SELECT d.*, u.email as uploaded_by_email, u.first_name, u.last_name
                          FROM documents d 
                          JOIN users u ON d.uploaded_by = u.id
                          WHERE d.is_archived = FALSE AND d.employee_id = ?';
        $personalParams = [$user['id']];
        
        if ($category) {
            $personalQuery .= ' AND d.type = ?';
            $personalParams[] = $category;
        }
        
        $personalQuery .= ' ORDER BY d.created_at DESC';
        
        $stmt = $this->db->prepare($personalQuery);
        $stmt->execute($personalParams);
        $personalDocs = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Get public documents
        $publicQuery = 'SELECT d.*, u.email as uploaded_by_email, u.first_name, u.last_name
                        FROM documents d 
                        JOIN users u ON d.uploaded_by = u.id
                        WHERE d.is_archived = FALSE AND d.employee_id IS NULL';
        $publicParams = [];
        
        if ($category) {
            $publicQuery .= ' AND d.type = ?';
            $publicParams[] = $category;
        }
        
        $publicQuery .= ' ORDER BY d.created_at DESC';
        
        $stmt = $this->db->prepare($publicQuery);
        $stmt->execute($publicParams);
        $publicDocs = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        // Convert to Document objects
        $personalDocuments = array_map(function ($row) {
            $document = Document::fromDatabase($row);
            $data = $document->toArray();
            $data['uploaded_by_name'] = trim($row['first_name'] . ' ' . $row['last_name']);
            return $data;
        }, $personalDocs);
        
        $publicDocuments = array_map(function ($row) {
            $document = Document::fromDatabase($row);
            $data = $document->toArray();
            $data['uploaded_by_name'] = trim($row['first_name'] . ' ' . $row['last_name']);
            return $data;
        }, $publicDocs);
        
        // Get document categories
        $categories = [
            'contract' => ['eu' => 'Kontratuak', 'es' => 'Contratos'],
            'nif' => ['eu' => 'NIF/NIE', 'es' => 'NIF/NIE'],
            'payroll' => ['eu' => 'Nominak', 'es' => 'Nóminas'],
            'certificate' => ['eu' => 'Ziurtagiriak', 'es' => 'Certificados'],
            'other' => ['eu' => 'Beste batzuk', 'es' => 'Otros']
        ];
        
        return $this->renderView('documents/index', [
            'user' => $user,
            'personal_documents' => $personalDocuments,
            'public_documents' => $publicDocuments,
            'categories' => $categories,
            'selected_category' => $category,
            'active_tab' => $tab
        ]);
    }

    /**
     * Upload document form
     * GET /documents/upload
     */
    public function uploadForm(Request $request): Response
    {
        $user = $this->getUser($request);
        if (!$user || ($user['role'] !== 'ADMIN' && $user['role'] !== 'RRHH_MGR')) {
            $_SESSION['flash_error'] = 'Access denied';
            return Response::redirect('/documents');
        }

        // Get employees for dropdown
        $stmt = $this->db->query(
            'SELECT e.id, e.first_name, e.last_name, u.email 
             FROM employees e 
             JOIN users u ON e.user_id = u.id 
             WHERE e.is_active = TRUE
             ORDER BY e.last_name, e.first_name'
        );
        $employees = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $categories = [
            'contract' => ['eu' => 'Kontratuak', 'es' => 'Contratos'],
            'nif' => ['eu' => 'NIF/NIE', 'es' => 'NIF/NIE'],
            'payroll' => ['eu' => 'Nominak', 'es' => 'Nóminas'],
            'certificate' => ['eu' => 'Ziurtagiriak', 'es' => 'Certificados'],
            'other' => ['eu' => 'Beste batzuk', 'es' => 'Otros']
        ];
        
        return $this->renderView('documents/upload', [
            'user' => $user,
            'employees' => $employees,
            'categories' => $categories
        ]);
    }

    /**
     * Upload document
     * POST /documents/upload
     */
    public function upload(Request $request): Response
    {
        $user = $this->getUser($request);
        if (!$user || ($user['role'] !== 'ADMIN' && $user['role'] !== 'RRHH_MGR')) {
            $_SESSION['flash_error'] = 'Access denied';
            return Response::redirect('/documents');
        }

        $data = $request->getParsedBody();
        $file = $_FILES['file'] ?? null;
        
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash_error'] = 'Error al subir el archivo / File upload error';
            return Response::redirect('/documents/upload');
        }
        
        // Validate file size
        if ($file['size'] > self::MAX_FILE_SIZE) {
            $_SESSION['flash_error'] = 'El archivo es demasiado grande (max 10MB) / File too large';
            return Response::redirect('/documents/upload');
        }
        
        // Validate file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, self::ALLOWED_TYPES)) {
            $_SESSION['flash_error'] = 'Tipo de archivo no permitido / File type not allowed';
            return Response::redirect('/documents/upload');
        }
        
        // Validate MIME type using finfo (security fix - don't trust client-provided MIME)
        if (!extension_loaded('fileinfo')) {
            $_SESSION['flash_error'] = 'Server configuration error: fileinfo extension required';
            return Response::redirect('/documents/upload');
        }
        
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $realMimeType = $finfo->file($file['tmp_name']);
        
        if (!in_array($realMimeType, self::ALLOWED_MIME_TYPES)) {
            $_SESSION['flash_error'] = 'Invalid file content type detected / Tipo de archivo inválido detectado';
            return Response::redirect('/documents/upload');
        }
        
        // Generate unique filename
        $filename = uniqid('doc_') . '_' . time() . '.' . $extension;
        $filePath = $this->uploadDir . $filename;
        
        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            $_SESSION['flash_error'] = 'Error al guardar el archivo / Error saving file';
            return Response::redirect('/documents/upload');
        }
        
        // Calculate checksum
        $checksum = hash_file('sha256', $filePath);
        
        // Determine employee_id (null for public documents)
        $employeeId = $data['is_public'] === '1' ? null : ($data['employee_id'] ?? null);
        
        try {
            $stmt = $this->db->prepare(
                'INSERT INTO documents (
                    employee_id, type, filename, original_filename, file_path, mime_type, 
                    file_size, checksum, description, uploaded_by
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            
            $stmt->execute([
                $employeeId,
                $data['type'],
                $filename,
                $file['name'],
                $filePath,
                $realMimeType, // Use server-detected MIME type
                $file['size'],
                $checksum,
                $data['description'] ?? null,
                $user['id']
            ]);
            
            $_SESSION['flash_success'] = 'Documento subido correctamente / Document uploaded successfully';
            return Response::redirect('/documents');
        } catch (\PDOException $e) {
            // Delete file if database insert fails
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $_SESSION['flash_error'] = 'Error al guardar en base de datos / Database error: ' . $e->getMessage();
            return Response::redirect('/documents/upload');
        }
    }

    /**
     * Download document
     * GET /documents/{id}/download
     */
    public function download(Request $request, string $id): Response
    {
        $user = $this->getUser($request);
        if (!$user) {
            return Response::redirect('/login');
        }

        // Get document
        $query = 'SELECT * FROM documents WHERE id = ? AND is_archived = FALSE';
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$row) {
            $_SESSION['flash_error'] = 'Documento no encontrado / Document not found';
            return Response::redirect('/documents');
        }
        
        // Check access permissions
        if ($row['employee_id'] !== null && $row['employee_id'] !== $user['id']) {
            if ($user['role'] !== 'ADMIN' && $user['role'] !== 'RRHH_MGR') {
                $_SESSION['flash_error'] = 'Acceso denegado / Access denied';
                return Response::redirect('/documents');
            }
        }
        
        $document = Document::fromDatabase($row);
        
        // Serve file
        if (!file_exists($document->filePath)) {
            $_SESSION['flash_error'] = 'Archivo no encontrado / File not found';
            return Response::redirect('/documents');
        }
        
        header('Content-Type: ' . $document->mimeType);
        header('Content-Disposition: attachment; filename="' . $document->originalFilename . '"');
        header('Content-Length: ' . $document->fileSize);
        readfile($document->filePath);
        exit;
    }

    /**
     * Delete/Archive document with physical file cleanup
     * POST /documents/{id}/delete
     */
    public function delete(Request $request, string $id): Response
    {
        $user = $this->getUser($request);
        if (!$user || ($user['role'] !== 'ADMIN' && $user['role'] !== 'RRHH_MGR')) {
            $_SESSION['flash_error'] = 'Access denied';
            return Response::redirect('/documents');
        }

        try {
            // Get document info before archiving (to delete physical file)
            $stmt = $this->db->prepare('SELECT file_path FROM documents WHERE id = ?');
            $stmt->execute([$id]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$row) {
                $_SESSION['flash_error'] = 'Documento no encontrado / Document not found';
                return Response::redirect('/documents');
            }
            
            $filePath = $row['file_path'];
            
            // Archive the document (soft delete)
            $stmt = $this->db->prepare(
                'UPDATE documents SET is_archived = TRUE, archived_at = NOW(), file_path = NULL WHERE id = ?'
            );
            $stmt->execute([$id]);
            
            // Remove physical file to save storage space
            if ($filePath && file_exists($filePath)) {
                if (!unlink($filePath)) {
                    error_log("[DOCUMENT] Failed to delete physical file: $filePath");
                }
            }
            
            $_SESSION['flash_success'] = 'Documento archivado / Document archived';
        } catch (\PDOException $e) {
            $_SESSION['flash_error'] = 'Error al archivar documento / Error archiving document';
        }
        
        return Response::redirect('/documents');
    }

    /**
     * Render a view template
     */
    private function renderView(string $view, array $data = []): Response
    {
        extract($data);
        ob_start();
        require __DIR__ . '/../../../public/views/' . $view . '.php';
        $content = ob_get_clean();
        return new Response($content);
    }
}
