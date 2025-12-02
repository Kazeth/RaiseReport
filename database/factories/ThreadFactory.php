<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Thread>
 */
class ThreadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $incidents = [
            'Perundungan siswa',
            'Kekerasan verbal',
            'Penindasan sekolah',
            'Intimidasi lingkungan',
            'Pelecehan teman sebaya',
            'Pengucilan sosial',
            'Perundungan online',
            'Kekerasan fisik ringan'
        ];

        // list lokasi
        $locations = [
            'Jakarta',
            'Bandung',
            'Surabaya',
            'Yogyakarta',
            'Medan',
            'Semarang',
            'Denpasar',
            'Makassar'
        ];

        $statuses = [
            'Rejected',
            'Pending',
            'Approved'
        ];

        // generate kejadian + lokasi
        $incident = $this->faker->randomElement($incidents);
        $extraWords = $this->faker->words(rand(1, 3), true);
        $location = $this->faker->randomElement($locations);
        $status = $this->faker->randomElement($statuses);

        // threadName: Contoh → "Perundungan siswa kelas 8 di Bandung"
        $threadName = ucfirst($incident . " di $location");

        $templates = [
            "Kejadian $incident terjadi di wilayah $location. Korban disebut mengalami perlakuan tidak menyenangkan dari pelaku, sehingga memicu reaksi warga sekitar. Saksi menyampaikan bahwa korban tampak ketakutan dan membutuhkan pendampingan lebih lanjut.",

            "Sebuah laporan mengenai $incident muncul dari daerah $location. Peristiwa ini bermula ketika korban mengalami tindakan yang membuatnya merasa terancam. Warga sekitar menyebut kejadian ini sudah beberapa kali terjadi dan berharap adanya tindakan tegas.",

            "Di $location, kasus $incident kembali mencuat. Korban mengaku mendapatkan tekanan fisik maupun emosional dari pelaku. Beberapa saksi mata melihat korban dalam kondisi tidak stabil dan meminta pihak berwenang segera turun tangan.",

            "Kasus $incident dilaporkan oleh warga di sekitar $location. Insiden ini menarik perhatian publik setelah korban terlihat mengalami gangguan emosional akibat perlakuan pelaku. Masyarakat berharap kejadian seperti ini dapat segera ditangani.",

            "Laporan mengenai $incident datang dari $location. Korban disebut mengalami pengalaman tidak menyenangkan yang membuatnya mengalami ketakutan mendalam. Saksi berharap pihak terkait dapat menindaklanjuti laporan demi keamanan bersama."
        ];

        $threadContent = $templates[array_rand($templates)];


        return [
            'userId' => User::inRandomOrder()->first()->id,
            'threadName' => $threadName,
            'threadContent' => $threadContent,
            'threadStatus' => $status,
            'threadUpvote' => ($status == 'Approved' ? $this->faker->numberBetween(0, 20) : 0),

        ];
    }
}
