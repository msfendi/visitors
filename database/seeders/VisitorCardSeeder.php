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
            'visitor_code' => 'VST00001',
            'visitor_number' => '1',
            'status_card' => 'available',
            'void' => 'false',
        ]);
        VisitorCard::create([
            'rfid' => '0987654321',
            'visitor_code' => 'VST00002',
            'visitor_number' => '2',
            'status_card' => 'available',
            'void' => 'false',
        ]);
        VisitorCard::create([
            'rfid' => '1122334455',
            'visitor_code' => 'VST00003',
            'visitor_number' => '3',
            'status_card' => 'available',
            'void' => 'false',
        ]);
        VisitorCard::create([
            'rfid' => '7867566789',
            'visitor_code' => 'VST00004',
            'visitor_number' => '4',
            'status_card' => 'available',
            'void' => 'false',
        ]);
        VisitorCard::create([
            'rfid' => '76754678976',
            'visitor_code' => 'VST00005',
            'visitor_number' => '5',
            'status_card' => 'available',
            'void' => 'false',
        ]);
        VisitorCard::create([
            'rfid' => '6756465678767',
            'visitor_code' => 'VST00006',
            'visitor_number' => '6',
            'status_card' => 'available',
            'void' => 'false',
        ]);
        VisitorCard::create([
            'rfid' => '8675645346576',
            'visitor_code' => 'VST00007',
            'visitor_number' => '7',
            'status_card' => 'available',
            'void' => 'false',
        ]);
        VisitorCard::create([
            'rfid' => '7867564565',
            'visitor_code' => 'VST00008',
            'visitor_number' => '8',
            'status_card' => 'available',
            'void' => 'false',
        ]);
    }
}
