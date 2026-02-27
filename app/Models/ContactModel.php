<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ContactModel
 *
 * Manages database operations for the `contacts` table.
 */
class ContactModel extends Model
{
    protected $table      = 'contacts';
    protected $primaryKey = 'id';

    // Only these fields can be inserted or updated
    protected $allowedFields = ['name', 'email', 'message'];

    // Return rows as associative arrays
    protected $returnType = 'array';

    // Automatically set created_at on insert
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
