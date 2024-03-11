<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public function tutor() {
        return $this->belongsTo(User::class, 'assigned_tutor_id', 'id');
    }

    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function ticket_statuses() {
        return $this->belongsToMany(TicketStatus::class);
    }

}
