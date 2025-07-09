<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::firstOrCreate(
            ['name' => 'PT Lapi Laboratories'],
            ['address' => 'Jl.Gedong Panjang No 32', 'state' => 'Jakarta Barat'
            , 'country' => 'Indonesia', 'postcode' => '11240']
        );

        $branches = [
            'PLB' => 'Palembang','LBU' => 'Lubuklinggau','PBM' => 'Prabumulih','LHT' => 'Lahat','PDG' => 'Padang', 'BKT' => 'Bukittinggi',
            'SLK' => 'Solok','PYK' => 'Payakumbuh','PTK' => 'Pontianak','BJM' => 'Banjarmasin','BPN' => 'Balikpapan','SDA' => 'Samarinda','PKA' => 'Palangkaraya',
            'SBY' => 'Surabaya','MLG' => 'Malang','MDU' => 'Madiun','MJK' => 'Mojokerto','KDR' => 'Kediri','PBO' => 'Probolinggo','PSR' => 'Pasuruan',
            'JBR' => 'Jember','DPA' => 'Denpasar','SGA' => 'Singaraja',
            'JKT' => 'Jakarta','BKS' => 'Bekasi','CKR' => 'Cikarang','BDG' => 'Bandung','BGR' => 'Bogor',
            'CRB' => 'Cirebon','SKB' => 'Sukabumi','GRT' => 'Garut','TSA' => 'Tasikmalaya','SDG' => 'Sumedang','MDN' => 'Medan','PMS' => 'Pematangsiantar',
            'BJI' => 'Binjai','KSR' => 'Kisaran','PKB' => 'Pekanbaru','BTM' => 'Batam','TPN' => 'Tanjungpinang','SRG' => 'Serang',
            'CGN' => 'Cilegon','TGR' => 'Tangerang','SMG' => 'Semarang','SLO' => 'Solo','MGL' => 'Magelang','STG' => 'Salatiga','PWT' => 'Purwokerto',
            'TGL' => 'Tegal','CLP' => 'Cilacap','KDS' => 'Kudus','PTI' => 'Pati','MKS' => 'Makassar','MDO' => 'Manado',
            'PLU' => 'Palu','KDI' => 'Kendari','GTO' => 'Gorontalo', 
        ];
        
        foreach ($branches as $code => $name) {
            $branch = Branch::firstOrCreate(
                ['name' => $name, 'company_id' => $company->id],
                ['code' => $code]
            );
        
            if (!$branch->code) {
                $branch->code = $code;
                $branch->save();
            }
        
            // $this->command->info("Created branch: $name ($code)");
        }

        $structure = [
            'Marketing' => ['OTC Marketing','NSM OTC', 'SM OTC', 'AM OTC', 'SPV OTC', 'SPO OTC'],
            'Technology' => ['Manager IT', 'App.Dev Manager','Programmer', 'Admin IT'],
            'Sales' => ['Salesman'],
            'Finance' => ['Finance'],
        ];

        $branchList = Branch::all();
        foreach ($branchList as $branch) {
            foreach ($structure as $deptName => $positions) {
                $department = Department::firstOrCreate([
                    'branch_id' => $branch->id,
                    'name' => $deptName
                ]);
        
                foreach ($positions as $posName) {
                    Position::firstOrCreate([
                        'department_id' => $department->id,
                        'name' => $posName
                    ]);
                }
            }
        }
    }
}
