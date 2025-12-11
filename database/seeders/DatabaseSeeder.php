<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\News;
use App\Models\Teacher;
use App\Models\Facility;
use App\Models\Gallery;
use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'fullname' => 'Administrator',
            'email' => 'admin@baabussalaam.sch.id',
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Create Editor User
        User::create([
            'username' => 'editor',
            'password' => Hash::make('editor123'),
            'fullname' => 'Editor',
            'email' => 'editor@baabussalaam.sch.id',
            'role' => 'editor',
            'status' => 'active',
        ]);

        // Create Sample Teachers
        Teacher::create([
            'name' => 'Dr. Ahmad Fauzi, M.Pd',
            'position' => 'Kepala Sekolah',
            'qualifications' => 'S.Pd, M.Pd, Dr. Pendidikan Islam',
            'experience' => 'Pengalaman 15 Tahun',
            'email' => 'kepala@baabussalaam.sch.id',
            'phone' => '08123456789',
            'category' => 'principal',
        ]);

        Teacher::create([
            'name' => 'Siti Aminah, S.Pd',
            'position' => 'Guru Matematika',
            'qualifications' => 'S.Pd Matematika',
            'experience' => 'Pengajar sejak 2015',
            'email' => 'siti.aminah@baabussalaam.sch.id',
            'phone' => '08123456787',
            'category' => 'teacher',
        ]);

        // Create Sample Facilities
        Facility::create([
            'name' => 'Ruang Kelas',
            'description' => 'Ruang kelas nyaman ber-AC dengan kapasitas 30 siswa, dilengkapi LCD proyektor dan sound system.',
            'features' => 'Full AC,Proyektor LCD,Sound System',
            'category' => 'class',
        ]);

        Facility::create([
            'name' => 'Laboratorium IPA',
            'description' => 'Laboratorium IPA modern dengan peralatan lengkap untuk praktik Fisika, Kimia, dan Biologi.',
            'features' => 'Alat praktik lengkap,Kapasitas 30 siswa,Safety equipment',
            'category' => 'lab',
        ]);

        // Create Sample Pages
        Page::create([
            'title' => 'Tentang Kami',
            'slug' => 'about',
            'content' => '<h2>Selamat Datang di SMP Islam Baabussalaam</h2><p>SMP Islam Baabussalaam adalah sekolah unggulan yang berkomitmen untuk memberikan pendidikan terbaik dengan mengintegrasikan nilai-nilai Islami dalam setiap aspek pembelajaran.</p>',
            'status' => 'published',
            'is_system' => true,
        ]);

        Page::create([
            'title' => 'Program Akademik',
            'slug' => 'akademik',
            'content' => '<h2>Program Akademik Unggulan</h2><p>Kami menyediakan berbagai program akademik yang dirancang untuk mengembangkan potensi siswa secara maksimal.</p>',
            'status' => 'published',
            'is_system' => true,
        ]);

        // Create Sample News
        News::create([
            'title' => 'SMP Baabussalaam Raih Juara OSN 2023',
            'content' => 'Siswa SMP Baabussalaam berhasil meraih medali emas dalam Olimpiade Sains Nasional tingkat provinsi. Muhammad Alif dari kelas 9A berhasil meraih medali emas dalam Olimpiade Sains Nasional tingkat provinsi...',
            'excerpt' => 'Muhammad Alif dari kelas 9A berhasil meraih medali emas dalam Olimpiade Sains Nasional tingkat provinsi...',
            'category' => 'achievement',
            'author_id' => 1,
            'status' => 'published',
        ]);
    }
}
