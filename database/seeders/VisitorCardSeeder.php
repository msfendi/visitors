<?php

namespace Database\Seeders;

use App\Models\Visitor;
use App\Models\VisitorCard;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VisitorCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //  make seeder for visitor cards
        VisitorCard::create([
            'rfid' => '1234567890',
            'visitor_code' => '1',
            'status_card' => 'available',
            'void' => 'false',
        ]);
        VisitorCard::create([
            'rfid' => '0987654321',
            'visitor_code' => '2',
            'status_card' => 'available',
            'void' => 'false',
        ]);
        VisitorCard::create([
            'rfid' => '1122334455',
            'visitor_code' => '3',
            'status_card' => 'available',
            'void' => 'false',
        ]);
        VisitorCard::create([
            'rfid' => '7867566789',
            'visitor_code' => '4',
            'status_card' => 'available',
            'void' => 'false',
        ]);
        VisitorCard::create([
            'rfid' => '76754678976',
            'visitor_code' => '5',
            'status_card' => 'available',
            'void' => 'false',
        ]);
        VisitorCard::create([
            'rfid' => '6756465678767',
            'visitor_code' => '6',
            'status_card' => 'available',
            'void' => 'false',
        ]);
        VisitorCard::create([
            'rfid' => '8675645346576',
            'visitor_code' => '7',
            'status_card' => 'available',
            'void' => 'false',
        ]);
        VisitorCard::create([
            'rfid' => '7867564565',
            'visitor_code' => '8',
            'status_card' => 'available',
            'void' => 'false',
        ]);
    }
}
