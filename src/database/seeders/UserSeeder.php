<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use App\Models\Employee;
use App\Models\Client;
use App\Models\Department;
use App\Models\Position;
use App\Models\Sales;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::where('name', 'super_admin')
        ->where('guard_name', 'web')  // contoh guard_name
        ->firstOrFail();
    
        $salesRole = Role::where('name', 'sales')
            ->where('guard_name', 'sales_guard')
            ->firstOrFail();
        
        $clientRole = Role::where('name', 'client')
            ->where('guard_name', 'client_guard')
            ->firstOrFail();

        $branchJKT = Branch::where('code', 'JKT')->firstOrFail();
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            ['name' => 'Super Admin', 'password' => Hash::make('password')]
        );
        $adminUser->assignRole($superAdminRole);
        $adminDept = Department::where('branch_id', $branchJKT->id)->where('name', 'Technology')->firstOrFail();
        $adminPos = Position::where('department_id', $adminDept->id)->where('name', 'Admin IT')->firstOrFail();
        Employee::firstOrCreate(
            ['user_id' => $adminUser->id],
            [
                'branch_id' => $branchJKT->id,
                'department_id' => $adminDept->id,
                'position_id' => $adminPos->id,
                'employee_code' => 'EMP-00001',
            ]
        );

        $clients = [
            [
                'code' => 'PLB-Client-1453',
                'branch_code' => 'PLB',
                'sales_email' => 'andrew@admin.com',
                'email' => 'client1@admin.com',
                'name' => 'Ap. Ayah Bunda',
                'address' => 'Jl. Raya No. 123',
                'state' => 'Palembang',
                'country' => 'Indonesia',
                'postcode' => '10110',
                'contact_person' => 'Andi',
                'phone' => '+62 21 555 1234',
            ],
            [
                'code' => 'PTK-Client-1146',
                'branch_code' => 'PTK',
                'sales_email' => 'abdul@admin.com',
                'email' => 'client2@admin.com',
                'name' => 'Ap. Sejahtera',
                'address' => 'Jl. Sudirman No. 190',
                'state' => 'Pontianak',
                'country' => 'Indonesia',
                'postcode' => '43321',
                'contact_person' => 'Opung',
                'phone' => '+62 21 555 1235',
            ],
            [
                'code' => 'SBY-Client-1083',
                'branch_code' => 'SBY',
                'sales_email' => 'adi@admin.com',
                'email' => 'client3@admin.com',
                'name' => 'Klinik Anggrek',
                'address' => 'Jl. Merdeka No. 45',
                'state' => 'Surabaya',
                'country' => 'Indonesia',
                'postcode' => '40123',
                'contact_person' => 'Rina',
                'phone' => '+62 22 888 9999',
            ],
            [
                'code' => 'TSA-Client-1312',
                'branch_code' => 'TSA',
                'sales_email' => 'totok@admin.com',
                'email' => 'client4@admin.com',
                'name' => 'Apotek Kenangan',
                'address' => 'Jl. Raya Bogor Km 20',
                'state' => 'Tasikmalaya',
                'country' => 'Indonesia',
                'postcode' => '16110',
                'contact_person' => 'Akbar',
                'phone' => '+62 251 111 2233',
            ],
            [
                'code' => 'BKS-Client-1293',
                'branch_code' => 'BKS',
                'sales_email' => 'andika@admin.com',
                'email' => 'client5@admin.com',
                'name' => 'RSU Aulia',
                'address' => 'Jl. Trikora No. 1',
                'state' => 'Bekasi',
                'country' => 'Indonesia',
                'postcode' => '70714',
                'contact_person' => 'Aulia',
                'phone' => '+62 511 778 1234',
            ],
            [
                'code' => 'BDG-Client-1830',
                'branch_code' => 'BDG',
                'sales_email' => 'angly@admin.com',
                'email' => 'client6@admin.com',
                'name' => 'Ap. Melati',
                'address' => 'Jl. Raya Pekayon No. 10',
                'state' => 'Bandung',
                'country' => 'Indonesia',
                'postcode' => '17148',
                'contact_person' => 'Hendra',
                'phone' => '+62 21 777 1234',
            ],
            [
                'code' => 'MDN-Client-1538',
                'branch_code' => 'MDN',
                'sales_email' => 'budi@admin.com',
                'email' => 'client7@admin.com',
                'name' => 'Ap. Sirih',
                'address' => 'Jl. Lintas Timur No. 18',
                'state' => 'Medan',
                'country' => 'Indonesia',
                'postcode' => '33415',
                'contact_person' => 'Agus',
                'phone' => '+62 719 456 7890',
            ],
            [
                'code' => 'JKT-Client-1249',
                'branch_code' => 'JKT',
                'sales_email' => 'sulaeman@admin.com',
                'email' => 'client8@admin.com',
                'name' => 'Apotek Citra',
                'address' => 'Jl. MT Haryono No. 5',
                'state' => 'Jakarta',
                'country' => 'Indonesia',
                'postcode' => '76112',
                'contact_person' => 'Citra',
                'phone' => '+62 542 888 2222',
            ],
            [
                'code' => 'SMG-Client-1165',
                'branch_code' => 'SMG',
                'sales_email' => 'chandra@admin.com',
                'email' => 'client9@admin.com',
                'name' => 'Klinik Ahmad',
                'address' => 'Jl. Ahmad Yani No. 33',
                'state' => 'Semarang',
                'country' => 'Indonesia',
                'postcode' => '29444',
                'contact_person' => 'Ahmad',
                'phone' => '+62 778 123 4567',
            ],
            [
                'code' => 'SBY-Client-1166',
                'branch_code' => 'SBY',
                'sales_email' => 'adi@admin.com',
                'email' => 'client10@admin.com',
                'name' => 'RS Indah',
                'address' => 'Jl. KH Noer Ali No. 25',
                'state' => 'Surabaya',
                'country' => 'Indonesia',
                'postcode' => '17145',
                'contact_person' => 'Indah',
                'phone' => '+62 21 222 5566',
            ],
        ];

        foreach ($clients as $clt) {
            $branch = Branch::where('code', $clt['branch_code'])->first();
            if (!$branch) continue;

            $user = User::firstOrCreate(['email' => $clt['email']], [
                'name' => $clt['name'],
                'password' => Hash::make('password'),
            ]);
            $user->assignRole($clientRole);

            Client::firstOrCreate(['user_id' => $user->id], [
                'code' => $clt['code'],
                'branch_id' => $branch->id,
                'address' => $clt['address'],
                'state' => $clt['state'],
                'country' => $clt['country'],
                'postcode' => $clt['postcode'],
                'contact_person' => $clt['contact_person'],
                'phone' => $clt['phone'],
            ]);
        }

        $salesEntries = [
            ['email' => 'SLS-PTK-1@admin.com', 'name' => 'Gafur', 'employee_code' => 'EMP-00004', 'code' => 'PTK', 'phone' => '081200000002'],
            ['email' => 'SLS-BKS-2@admin.com', 'name' => 'Sanjaya', 'employee_code' => 'EMP-00005', 'code' => 'BKS', 'phone' => '081200000004'],
            ['email' => 'SLS-BDG-3@admin.com', 'name' => 'Sofyan', 'employee_code' => 'EMP-00006', 'code' => 'BDG', 'phone' => '081200000007'],
            ['email' => 'SLS-MDN-4@admin.com', 'name' => 'Manurung', 'employee_code' => 'EMP-00007', 'code' => 'MDN', 'phone' => '081200000011'],
            ['email' => 'SLS-SMG-5@admin.com', 'name' => 'Edhi', 'employee_code' => 'EMP-00008', 'code' => 'SMG', 'phone' => '081200000015'],
            ['email' => 'SLS-BDG-6@admin.com', 'name' => 'Sulaeman', 'employee_code' => 'EMP-00009', 'code' => 'BDG', 'phone' => '081200000018'],
            ['email' => 'SLS-SBY-7@admin.com', 'name' => 'Yuniarti', 'employee_code' => 'EMP-00010', 'code' => 'SBY', 'phone' => '081200000022'],
            ['email' => 'SLS-BDG-8@admin.com', 'name' => 'Yusuf', 'employee_code' => 'EMP-00011', 'code' => 'BDG', 'phone' => '081200000027'],
            ['email' => 'SLS-MKS-9@admin.com', 'name' => 'Irwandi', 'employee_code' => 'EMP-00012', 'code' => 'MKS', 'phone' => '081200000029'],
            ['email' => 'SLS-MDN-10@admin.com', 'name' => 'Gunadi', 'employee_code' => 'EMP-00013', 'code' => 'MDN', 'phone' => '081200000034'],
        ];

        foreach ($salesEntries as $entry) {
            $user = User::firstOrCreate(
                ['email' => $entry['email']],
                ['name' => $entry['name'], 'password' => Hash::make('password')]
            );
            $user->assignRole($salesRole);

            $branch = Branch::where('code', $entry['code'])->firstOrFail();

            $department = Department::firstOrCreate(
                ['branch_id' => $branch->id, 'name' => 'Sales']
            );

            $position = Position::firstOrCreate(
                ['department_id' => $department->id, 'name' => 'Salesman']
            );

            $employee = Employee::firstOrCreate(['user_id' => $user->id], [
                'branch_id' => $branch->id,
                'department_id' => $department->id,
                'position_id' => $position->id,
                'employee_code' => $entry['employee_code'],
            ]);

            Sales::updateOrCreate(
                ['user_id' => $user->id, 'employee_id' => $employee->id],
                [
                    'branch_id' => $branch->id,
                    'department_id' => $department->id,
                    'position_id' => $position->id,
                    'phone' => $entry['phone'],
                ]
            );
        }
    }
}

