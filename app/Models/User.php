<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

<<<<<<< HEAD
    // =======================================================
    // 🔑 PERBAIKAN 1: DEFINISI ROLE CONSTANTS
    // Konstanta ini digunakan di LoginController untuk pengecekan role
    // =======================================================
=======
    /**
     * Role constants
     */
>>>>>>> e6f494f (Initial commit lokal sebelum sinkron dengan GitHub)
    const ROLE_ADMIN = 'admin';
    const ROLE_CUSTOMER = 'pelanggan';

    /**
     * The attributes that are mass assignable.
     *
<<<<<<< HEAD
     * @var list<string>
=======
     * @var array<int, string>
>>>>>>> e6f494f (Initial commit lokal sebelum sinkron dengan GitHub)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
<<<<<<< HEAD
        'role', // 👈 PERBAIKAN 2: Tambahkan 'role' ke fillable
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
=======
        'role',   // penting jika ingin role disimpan otomatis
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
>>>>>>> e6f494f (Initial commit lokal sebelum sinkron dengan GitHub)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
<<<<<<< HEAD
     * Get the attributes that should be cast.
=======
     * The attributes that should be cast.
>>>>>>> e6f494f (Initial commit lokal sebelum sinkron dengan GitHub)
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
<<<<<<< HEAD
            'password' => 'hashed',
        ];
    }
}
=======

            // password akan otomatis di-hash saat disimpan
            'password' => 'hashed',  
        ];
    }
}
>>>>>>> e6f494f (Initial commit lokal sebelum sinkron dengan GitHub)
