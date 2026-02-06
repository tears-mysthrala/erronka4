<?php

declare(strict_types=1);

namespace ZabalaGailetak\HrPortal\Models;

/**
 * Document Model
 * Represents employee documents
 */
class Document
{
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $employeeId,
        public readonly string $type,
        public readonly string $filename,
        public readonly string $originalFilename,
        public readonly string $filePath,
        public readonly string $mimeType,
        public readonly int $fileSize,
        public readonly ?string $checksum,
        public readonly ?string $description,
        public readonly bool $isArchived,
        public readonly ?string $archivedAt,
        public readonly string $uploadedBy,
        public readonly ?string $createdAt,
        public readonly ?string $updatedAt
    ) {
    }

    /**
     * Check if document is public (no employee_id)
     */
    public function isPublic(): bool
    {
        return $this->employeeId === null;
    }

    /**
     * Get file extension
     */
    public function getExtension(): string
    {
        return strtoupper(pathinfo($this->originalFilename, PATHINFO_EXTENSION));
    }

    /**
     * Get category name in Basque
     */
    public function getCategoryNameBasque(): string
    {
        return match ($this->type) {
            'contract' => 'Kontratuak',
            'nif' => 'NIF/NIE',
            'payroll' => 'Nominak',
            'certificate' => 'Ziurtagiriak',
            'other' => 'Beste batzuk',
            default => 'Beste batzuk'
        };
    }

    /**
     * Get category name in Spanish
     */
    public function getCategoryNameSpanish(): string
    {
        return match ($this->type) {
            'contract' => 'Contratos',
            'nif' => 'NIF/NIE',
            'payroll' => 'Nóminas',
            'certificate' => 'Certificados',
            'other' => 'Otros',
            default => 'Otros'
        };
    }

    /**
     * Format file size
     */
    public function getFileSizeFormatted(): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->fileSize;
        $unit = 0;
        
        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }
        
        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Get icon class based on file type
     */
    public function getIconClass(): string
    {
        $extension = strtolower($this->getExtension());
        return match ($extension) {
            'pdf' => 'bi-file-earmark-pdf',
            'doc', 'docx' => 'bi-file-earmark-word',
            'xls', 'xlsx' => 'bi-file-earmark-excel',
            'jpg', 'jpeg', 'png', 'gif' => 'bi-file-earmark-image',
            'zip', 'rar' => 'bi-file-earmark-zip',
            default => 'bi-file-earmark'
        };
    }

    /**
     * Get color based on category
     */
    public function getCategoryColor(): string
    {
        return match ($this->type) {
            'contract' => '#667EEA',
            'nif' => '#F59E0B',
            'payroll' => '#06B6D4',
            'certificate' => '#10B981',
            'other' => '#6B7280',
            default => '#6B7280'
        };
    }

    /**
     * Convert to array for API responses
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employeeId,
            'type' => $this->type,
            'category_name_eu' => $this->getCategoryNameBasque(),
            'category_name_es' => $this->getCategoryNameSpanish(),
            'filename' => $this->filename,
            'original_filename' => $this->originalFilename,
            'file_path' => $this->filePath,
            'mime_type' => $this->mimeType,
            'file_size' => $this->fileSize,
            'file_size_formatted' => $this->getFileSizeFormatted(),
            'extension' => $this->getExtension(),
            'checksum' => $this->checksum,
            'description' => $this->description,
            'is_archived' => $this->isArchived,
            'is_public' => $this->isPublic(),
            'archived_at' => $this->archivedAt,
            'uploaded_by' => $this->uploadedBy,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'icon_class' => $this->getIconClass(),
            'category_color' => $this->getCategoryColor()
        ];
    }

    /**
     * Create from database row
     */
    public static function fromDatabase(array $row): self
    {
        return new self(
            id: $row['id'] ?? null,
            employeeId: $row['employee_id'] ?? null,
            type: $row['type'],
            filename: $row['filename'],
            originalFilename: $row['original_filename'] ?? $row['filename'],
            filePath: $row['file_path'],
            mimeType: $row['mime_type'],
            fileSize: (int) $row['file_size'],
            checksum: $row['checksum'] ?? null,
            description: $row['description'] ?? null,
            isArchived: (bool) ($row['is_archived'] ?? false),
            archivedAt: $row['archived_at'] ?? null,
            uploadedBy: $row['uploaded_by'],
            createdAt: $row['created_at'] ?? null,
            updatedAt: $row['updated_at'] ?? null
        );
    }
}
