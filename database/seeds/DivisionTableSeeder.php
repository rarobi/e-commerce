<?php

use App\Modules\Settings\Models\Division;
use Illuminate\Database\Seeder;

class DivisionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $divisions = array(
            array('id' => '1','name' => 'Chattagram','bn_name' => 'চট্টগ্রাম','url' => 'www.chittagongdiv.gov.bd', 'status' => 1),
            array('id' => '2','name' => 'Rajshahi','bn_name' => 'রাজশাহী','url' => 'www.rajshahidiv.gov.bd', 'status' => 1),
            array('id' => '3','name' => 'Khulna','bn_name' => 'খুলনা','url' => 'www.khulnadiv.gov.bd', 'status' => 1),
            array('id' => '4','name' => 'Barisal','bn_name' => 'বরিশাল','url' => 'www.barisaldiv.gov.bd', 'status' => 1),
            array('id' => '5','name' => 'Sylhet','bn_name' => 'সিলেট','url' => 'www.sylhetdiv.gov.bd', 'status' => 1),
            array('id' => '6','name' => 'Dhaka','bn_name' => 'ঢাকা','url' => 'www.dhakadiv.gov.bd', 'status' => 1),
            array('id' => '7','name' => 'Rangpur','bn_name' => 'রংপুর','url' => 'www.rangpurdiv.gov.bd', 'status' => 1),
            array('id' => '8','name' => 'Mymensingh','bn_name' => 'ময়মনসিংহ','url' => 'www.mymensinghdiv.gov.bd','status' => 1)
        );

        Division::insert($divisions);
    }
}
