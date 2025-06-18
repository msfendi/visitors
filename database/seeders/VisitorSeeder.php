<?php

namespace Database\Seeders;

use App\Models\Visitor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //  make seeder for visitors
        Visitor::create([
            'name' => 'John Doe',
            'phone' => '081234567890',
            'instansi' => 'Company A',
            'identity_number' => '1234567890',
            'number_plate' => 'B 1234 CD',
            'void' => 'false',
        ]);
        Visitor::create([
            'name' => 'Jane Smith',
            'phone' => '081234567891',
            'instansi' => 'Company B',
            'identity_number' => '0987654321',
            'number_plate' => 'B 5678 EF',
            'void' => 'false',
        ]);
        Visitor::create([
            'name' => 'Alice Johnson',
            'phone' => '081234567892',
            'instansi' => 'Company C',
            'identity_number' => '1122334455',
            'number_plate' => 'B 9101 GH',
            'void' => 'false',
        ]);
        Visitor::create([
            'name' => 'Bob Brown',
            'phone' => '081234567893',
            'instansi' => 'Company D',
            'identity_number' => '7867566789',
            'number_plate' => 'B 1213 IJ',
            'void' => 'false',
        ]);
        Visitor::create([
            'name' => 'Charlie Green',
            'phone' => '081234567894',
            'instansi' => 'Company E',
            'identity_number' => '76754678976',
            'number_plate' => 'B 1415 KL',
            'void' => 'false',
        ]);
        Visitor::create([
            'name' => 'David White',
            'phone' => '081234567895',
            'instansi' => 'Company F',
            'identity_number' => '6756465678767',
            'number_plate' => 'B 1617 MN',
            'void' => 'false',
        ]);
        Visitor::create([
            'name' => 'Eva Black',
            'phone' => '081234567896',
            'instansi' => 'Company G',
            'identity_number' => '8675645346576',
            'number_plate' => 'B 1819 OP',
            'void' => 'false',
        ]);
    }
}
