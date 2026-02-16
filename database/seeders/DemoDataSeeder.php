<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Salon;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Review;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ==========================================
        // KONFIGURACIJA PODATAKA (Možete menjati ovde)
        // ==========================================
        
        $adminData = [
            'name' => 'Admin User',
            'email' => 'admin@haircare.com',
            'password' => 'password',
            'role' => 'admin',
            'phone' => '0601234567'
        ];

        // Imena za vlasnike salona
        $ownerNames = ['Marko Petrović', 'Jelena Janković', 'Nikola Nikolić'];
        
        // Imena za klijente
        $clientNames = ['Ana Anić', 'Petar Perić', 'Milica Milić', 'Dragan Dragić', 'Sofija Sofić'];

        // Saloni
        $salonsData = [
            [
                'name' => 'Studio Lepote SJAJ',
                'location' => 'Bulevar Kralja Aleksandra 123, Beograd',
                'description' => 'Ekskluzivan salon u srcu grada. Nudimo sve vrste frizerskih usluga sa najkvalitetnijim preparatima.',
                'image_url' => 'https://placehold.co/600x400/png?text=Salon+SJAJ', // PROMENITI SLIKU OVDE
                'type' => 'unisex',
                'status' => 'approved'
            ],
            [
                'name' => 'Berbernica BRKA',
                'location' => 'Knez Mihailova 5, Beograd',
                'description' => 'Tradicionalna berbernica za modernog muškarca. Brijanje, šišanje i negovanje brade.',
                'image_url' => 'https://placehold.co/600x400/png?text=Berbernica+BRKA', // PROMENITI SLIKU OVDE
                'type' => 'male',
                'status' => 'approved'
            ],
            [
                'name' => 'Frizerski Salon DAMA',
                'location' => 'Njegoševa 45, Beograd',
                'description' => 'Salon samo za dame. Specijalizovani za kolorizaciju i svečane frizure.',
                'image_url' => 'https://placehold.co/600x400/png?text=Salon+DAMA', // PROMENITI SLIKU OVDE
                'type' => 'female',
                'status' => 'approved'
            ]
        ];

        // Usluge po tipu salona (uprošćeno)
        $servicesMap = [
            'unisex' => [
                ['name' => 'Muško šišanje', 'price' => 1200, 'duration' => 30],
                ['name' => 'Žensko šišanje', 'price' => 1500, 'duration' => 45],
                ['name' => 'Feniranje', 'price' => 800, 'duration' => 30],
                ['name' => 'Pramenovi', 'price' => 4500, 'duration' => 120],
            ],
            'male' => [
                ['name' => 'Klasično šišanje', 'price' => 1000, 'duration' => 30],
                ['name' => 'Uređivanje brade', 'price' => 800, 'duration' => 20],
                ['name' => 'Kraljevski tretman', 'price' => 2500, 'duration' => 60],
            ],
            'female' => [
                ['name' => 'Šišanje', 'price' => 1800, 'duration' => 45],
                ['name' => 'Svečana punđa', 'price' => 3000, 'duration' => 60],
                ['name' => 'Balayage', 'price' => 6000, 'duration' => 180],
                ['name' => 'Keratin tretman', 'price' => 5000, 'duration' => 120],
            ]
        ];

        // Recenzije komentari
        $reviewComments = [
            'Odlična usluga, sve preporuke!',
            'Jako sam zadovoljan frizurom.',
            'Ambijent je prelep, a osoblje ljubazno.',
            'Malo se duže čeka, ali vredi.',
            'Najbolji salon u gradu!'
        ];

        // ==========================================
        // BRISANJE POSTOJEĆIH PODATAKA
        // ==========================================
        $this->command->info('Brisanje postojećih podataka...');
        
        Schema::disableForeignKeyConstraints();
        
        Review::truncate();
        Appointment::truncate();
        Service::truncate();
        // SalonImage model missing or table logic simple enough to skip explicit model usage if desired, 
        // but assuming we rely on cascades or manual truncate if table exists. 
        // Let's check table existence safely or just truncate tables known from migration analysis.
        DB::table('salon_images')->truncate(); 
        DB::table('messages')->truncate();
        DB::table('notifications')->truncate();
        Salon::truncate();
        User::truncate();
        
        Schema::enableForeignKeyConstraints();

        // ==========================================
        // KREIRANJE KORISNIKA
        // ==========================================
        $this->command->info('Kreiranje korisnika...');

        $passwordHash = Hash::make('password'); // Svi imaju istu šifru 'password'
        $createdUsers = [];
        $logins = [];

        // 1. Admin
        $admin = User::create([
            'name' => $adminData['name'],
            'email' => $adminData['email'],
            'password' => $passwordHash,
            'role' => $adminData['role'],
            'phone' => $adminData['phone'],
            'email_verified_at' => now(),
        ]);
        $logins[] = "ADMIN: {$adminData['email']} / password";

        // 2. Vlasnici Salona
        $owners = [];
        foreach ($ownerNames as $index => $name) {
            $email = 'owner' . ($index + 1) . '@haircare.com';
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => $passwordHash,
                'role' => 'salon_owner',
                'phone' => '061' . rand(1000000, 9999999),
                'email_verified_at' => now(),
            ]);
            $owners[] = $user;
            $logins[] = "VLASNIK: {$email} / password";
        }

        // 3. Klijenti
        $clients = [];
        foreach ($clientNames as $index => $name) {
            $email = 'client' . ($index + 1) . '@haircare.com';
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => $passwordHash,
                'role' => 'client',
                'phone' => '062' . rand(1000000, 9999999),
                'email_verified_at' => now(),
                'interests' => ['haircut', 'styling'], // Example interests
            ]);
            $clients[] = $user;
            $logins[] = "KLIJENT: {$email} / password";
        }

        // ==========================================
        // KREIRANJE SALONA I USLUGA
        // ==========================================
        $this->command->info('Kreiranje salona, slika i usluga...');

        $salons = [];
        $allServices = [];

        foreach ($salonsData as $index => $data) {
            // Dodeljujemo salone vlasnicima u krug
            $owner = $owners[$index % count($owners)];

            $salon = Salon::create([
                'owner_id' => $owner->id,
                'name' => $data['name'],
                'location' => $data['location'],
                'description' => $data['description'],
                'image_url' => $data['image_url'], // Glavna slika
                'type' => $data['type'],
                'status' => $data['status'],
                // 'average_rating' i 'reviews_count' će se ažurirati kasnije
            ]);
            
            // Dodajemo dodatne slike za galeriju
            DB::table('salon_images')->insert([
                [
                    'salon_id' => $salon->id,
                    'image_url' => 'https://placehold.co/600x400/png?text=' . urlencode($salon->name . ' Enterijer'),
                    'alt_text' => $salon->name . ' Enterijer',
                    'order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'salon_id' => $salon->id,
                    'image_url' => 'https://placehold.co/600x400/png?text=' . urlencode($salon->name . ' Detalj'),
                    'alt_text' => $salon->name . ' Detalj',
                    'order' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);

            $salons[] = $salon;

            // Kreiranje usluga za ovaj salon
            $servicesList = $servicesMap[$salon->type] ?? $servicesMap['unisex'];
            foreach ($servicesList as $sData) {
                $service = Service::create([
                    'salon_id' => $salon->id,
                    'name' => $sData['name'],
                    'description' => 'Profesionalna usluga ' . strtolower($sData['name']),
                    'duration_minutes' => $sData['duration'],
                    'price' => $sData['price'],
                    'is_active' => true,
                ]);
                $allServices[] = $service;
            }
        }

        // ==========================================
        // KREIRANJE ZAKAZIVANJA I RECENZIJA
        // ==========================================
        $this->command->info('Kreiranje zakazivanja i recenzija...');

        foreach ($salons as $salon) {
            // Uzmi usluge ovog salona
            $salonServices = Service::where('salon_id', $salon->id)->get();
            if ($salonServices->isEmpty()) continue;

            // Kreiramo 10 zakazivanja po salonu (5 prošlih, 5 budućih)
            for ($i = 0; $i < 10; $i++) {
                $client = $clients[array_rand($clients)];
                $service = $salonServices->random();
                
                $isPast = $i < 5;
                $date = $isPast 
                    ? Carbon::now()->subDays(rand(1, 30))->setHour(rand(9, 17))->setMinute(0)
                    : Carbon::now()->addDays(rand(1, 14))->setHour(rand(9, 17))->setMinute(0);
                
                $status = $isPast ? 'completed' : 'confirmed';

                $appointment = Appointment::create([
                    'salon_id' => $salon->id,
                    'service_id' => $service->id,
                    'client_id' => $client->id,
                    'scheduled_at' => $date,
                    'ends_at' => (clone $date)->addMinutes($service->duration_minutes),
                    'status' => $status,
                    'note' => $isPast ? 'Sve je bilo super.' : 'Molim vas pazite na osetljivu kožu.',
                ]);

                // Ako je termin prošao, dodajemo recenziju
                if ($isPast) {
                    $rating = rand(3, 5); // Dajemo uglavnom dobre ocene
                    
                    // 1. Klijent ocenjuje uslugu/salon
                    Review::create([
                        'appointment_id' => $appointment->id,
                        'salon_id' => $salon->id,
                        'service_id' => $service->id,
                        'client_id' => $client->id,
                        'rating' => $rating,
                        'service_rating' => $rating,
                        'salon_rating' => $rating,
                        'type' => 'service', // Default
                        'comment' => $reviewComments[array_rand($reviewComments)],
                        'created_at' => $date->addHours(2),
                    ]);

                    // 2. Salon (vlasnik) ocenjuje klijenta (User Rating) - 50% sanse
                    if (rand(0, 1)) {
                        $clientRating = rand(4, 5);
                        Review::create([
                            'appointment_id' => $appointment->id,
                            'salon_id' => $salon->id, // Context
                            'service_id' => null, // Not reviewing service
                            'client_id' => $salon->owner_id, // Reviewer is owner
                            'reviewed_user_id' => $client->id, // Reviewed is client
                            'rating' => $clientRating,
                            'type' => 'user',
                            'comment' => 'Odličan klijent, došao na vreme.',
                            'created_at' => $date->addHours(3),
                        ]);
                    }
                }
            }
            
            // Ažuriranje prosečne ocene salona
            $avgRating = Review::where('salon_id', $salon->id)->where('type', 'service')->avg('rating');
            $countReviews = Review::where('salon_id', $salon->id)->where('type', 'service')->count();
            $salon->update([
                'average_rating' => $avgRating ?? 0,
                'reviews_count' => $countReviews
            ]);
        }
        
        // Ažuriranje ocena usluga (Services)
        foreach($allServices as $service) {
            $avgRating = Review::where('service_id', $service->id)->avg('service_rating');
            $countReviews = Review::where('service_id', $service->id)->count();
            if($countReviews > 0) {
                 $service->update([
                    'average_rating' => $avgRating ?? 0,
                    'reviews_count' => $countReviews
                ]);
            }
        }
        
        // Ažuriranje ocena klijenata (Users)
        foreach ($clients as $client) {
            $avgRating = Review::where('reviewed_user_id', $client->id)->where('type', 'user')->avg('rating');
            $countReviews = Review::where('reviewed_user_id', $client->id)->where('type', 'user')->count();
            
            if ($countReviews > 0) {
                $client->update([
                    'average_rating' => $avgRating ?? 0,
                    'reviews_count' => $countReviews
                ]);
            }
        }
        
        // ==========================================
        // KREIRANJE PORUKA
        // ==========================================
        $this->command->info('Kreiranje poruka...');
        
        // Razgovor 1: Klijent 1 i Vlasnik 1
        $msgClient = $clients[0];
        $msgOwner = $owners[0];
        
        Message::create([
            'sender_id' => $msgClient->id,
            'receiver_id' => $msgOwner->id,
            'content' => 'Poštovani, da li imate slobodan termin u subotu popodne?',
            'created_at' => Carbon::now()->subHours(2),
            'read_at' => Carbon::now()->subHours(1),
        ]);
        
        Message::create([
            'sender_id' => $msgOwner->id,
            'receiver_id' => $msgClient->id,
            'content' => 'Poštovana, imamo u 16h slobodno kod Marka.',
            'created_at' => Carbon::now()->subHours(1),
            'read_at' => null, // Nepročitano
        ]);

        // Razgovor 2: Klijent 2 i Vlasnik 2 (Arhiviran/Završen)
        $msgClient2 = $clients[1];
        $msgOwner2 = $owners[1];

        Message::create([
            'sender_id' => $msgClient2->id,
            'receiver_id' => $msgOwner2->id,
            'content' => 'Kasniću 10 minuta, izvinite.',
            'created_at' => Carbon::now()->subDays(2),
            'read_at' => Carbon::now()->subDays(2),
        ]);

        Message::create([
            'sender_id' => $msgOwner2->id,
            'receiver_id' => $msgClient2->id,
            'content' => 'U redu, nema problema. Vidimo se.',
            'created_at' => Carbon::now()->subDays(2)->addMinutes(5),
            'read_at' => Carbon::now()->subDays(2)->addMinutes(10),
        ]);


        // ==========================================
        // KREIRANJE OBAVESTENJA
        // ==========================================
        $this->command->info('Kreiranje obaveštenja...');
        
        // GLOBALNO OBAVESTENJE (za sve)
        DB::table('notifications')->insert([
            'user_id' => null, // Globalno
            'title' => 'Srećni praznici!',
            'content' => 'HairCare tim vam želi srećne praznike i uspešnu novu godinu.',
            'type' => 'info',
            'is_visible' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // OBAVEŠTENJE O SISTEMSKIM RADOVIMA (Globalno)
        DB::table('notifications')->insert([
            'user_id' => null, // Globalno
            'title' => 'Održavanje sistema',
            'content' => 'Sistem neće biti dostupan noćas od 02:00 do 04:00 zbog redovnog održavanja.',
            'type' => 'warning',
            'is_visible' => true,
            'created_at' => Carbon::now()->subHours(5),
            'updated_at' => Carbon::now(),
        ]);
        
        // Obaveštenje za klijenta o potvrđenom terminu
        DB::table('notifications')->insert([
            'user_id' => $clients[0]->id,
            'title' => 'Termin potvrđen',
            'content' => 'Vaš termin u salonu Studio Lepote SJAJ je potvrđen.',
            'type' => 'info',
            'read_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
        // Obaveštenje za vlasnika o novom zakazivanju
        DB::table('notifications')->insert([
            'user_id' => $owners[0]->id,
            'title' => 'Novo zakazivanje',
            'content' => 'Korisnik Ana Anić je zakazao termin.',
            'type' => 'info',
            'read_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // ==========================================
        // FILE EXPORT
        // ==========================================
        $fileContent = "DEMO PODACI - LOGINOVI\n";
        $fileContent .= "Generisano: " . Carbon::now()->toDateTimeString() . "\n";
        $fileContent .= "=================================\n\n";
        
        foreach ($logins as $loginLine) {
            $fileContent .= $loginLine . "\n";
        }
        
        $fileContent .= "\nNAPOMENA: Sifre za sve nologe su 'password'.\n";
        $fileContent .= "Slike salona su placeholderi. Da ih promenite, editujte 'DemoDataSeeder.php' i promenite URL-ove.\n";
        
        File::put(base_path('seed-logins.txt'), $fileContent);
        
        $this->command->info('Uspešno kreirani demo podaci!');
        $this->command->info('Login podaci sačuvani u: seed-logins.txt');
    }
}
