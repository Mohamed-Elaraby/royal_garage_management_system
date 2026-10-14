<?php

use App\Models\CarSize;
use Illuminate\Database\Seeder;

class CarSizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $sizes = ['116i','118i','120i','130i','120i','125i','128i','135i','135is','116i','118i','120i','130i','118i','120i','125i','128i','135i','114i','116i','118i','120i','125i','M135i','M135i Xdrive','M140i','M140i Xdrive','114i','116i','118i','120i','125i','M135i','M135i Xdrive','M140i','M140i Xdrive','118i','M135i Xdrive','118i','120i','125i','218i','220i','228i','228i xDrive','230i','230i xDrive','M235i','M235i xDrive','M240i','M240i xDrive','218i','220i','228i','228i xDrive','230i','230i xDrive','M235i','M235i xDrive','M240i','M240i xDrive','218i','228i xDrive','M235i xDrive','216i','218i','220i','225i','225i xDrive','225xe iperformance','216i','218i','220i','316i','318i','320i','320si','323i','325i','325Xi','328i','328Xi','330i','330xi','335i','335xi','M3','M3 GT','316i','318i','320i','323i','325i','325Xi','328i','328iX','328Xi','330i','330xi','335i','335iX','335xi','316i','318i','320i','323i','325i','325i Xdrive','325Xi','328i','328i Xdrive','328Xi','330i','330i Xdrive','330xi','335i','335i Xdrive','335is','335xi','M3','318i','320i','323i','325i','328i','330i','335i','335is','M3','316i','318i','320i','320i Xdrive','328i','328i Xdrive','330i','330i Xdrive','335i','335i Xdrive','340i','340i Xdrive','316i','318i','320i','320i Xdrive','328i','328i Xdrive','330i','330i Xdrive','335i','335i Xdrive','340i','340i Xdrive','320i','320i Xdrive','328i','328i Xdrive','330i','330i Xdrive','335i','335i Xdrive','340i','340i Xdrive','316Li','318Li','320li','320li Xdrive','328li','328li Xdrive','330li','330li Xdrive','335li','M3','318i','320i','320i Xdrive','325i','330e','330e Xdrive','330i','330i Xdrive','M340i','M340i Xdrive','318i','320i','330i','330i Xdrive','M340i Xdrive','320li','325li','325li Xdrive','330li Xdrive','418i','420i','420i Xdrive','428i','428i Xdrive','430i','430i Xdrive','435i','435i Xdrive','440i','440i Xdrive','420i','428i','430i','430i Xdrive','435i','435i Xdrive','440i','440i Xdrive','418i','420i','420i Xdrive','428i','428i Xdrive','430i','430i Xdrive','435i','435i Xdrive','440i','440i Xdrive','M4','M4 CS','M4 GTS','M4','520li','520i','523li','523i','525li','525i','525Xi','528i','528Xi','530li','530i','530Xi','535i','535Xi','540i','545i','550i','M5','520i','523i','525i','525Xi','530i','530Xi','535Xi','545i','550i','M5','528i','535i','535i Xdrive','550i','550i Xdrive','520i','523i','528i','528i Xdrive','530i','535i','535i Xdrive','550i','550i Xdrive','M5','520i','523i','528i','528i Xdrive','530i','535i','535i Xdrive','550i','520li','523li','525li','528li','528li Xdrive','530le','530li','535li','M5','520i','530i','530i Xdrive','540i','540i Xdrive','550i Xdrive','520i','530i','530i Xdrive','540i Xdrive','525li','528lii','530li','540li','630i','645ci','650i','M5','630i','645ci','650i','M6','640i','640i Xdrive','650i','650i Xdrive','M6','640i','640i Xdrive','650i','650i Xdrive','M6','640i','640i Xdrive','650i','650i Xdrive','M6','630i','630i Xdrive','640i','640i Xdrive','730i','735i','740i','745i','750i','750i Alpina B7','760i','730Li','735Li','740Li','745Li','750Li','760Li','H7','730i','740i','750i','750i Alpina B7','750i Xdrive','750i Xdrive Alpina B7','760i','730li','740li','740li Xdrive','750li','750li Alpina B7','750li Xdrive','750li Xdrive Alpina B7','760li','730i','740i','745e','750i','750i Xdrive','730li','740li','740li Xdrive','745le','745le Xdrive','750li','750li Xdrive','760li Xdrive','M8','M8','M8','840i','840i Xdrive','850i Xdrive','840i','840i Xdrive','850i Xdrive','840i','840i Xdrive','850i Xdrive','i8','i8','Cooper','Cooper ALL4','Cooper D','Cooper S','Cooper S ALL4','Cooper SD','Cooper SD ALL4','John Cooper Works ALL4','One','One D','Cooper','Cooper D','Cooper S','Cooper SD','One','One D','Cooper','Cooper D','Cooper S','John Cooper Works','John Cooper Works GP','One','One D','Cooper','Cooper D','Cooper S','Cooper SD','John Cooper Works','One','Cooper','Cooper ALL4','Cooper D','Cooper D ALL4','Cooper S','Cooper S ALL4','Cooper SE ALL4','Cooper SD','Cooper SD ALL4','John Cooper Works ALL4','One','One D','Cooper','Cooper D','Cooper S','Cooper SD','John Cooper Works','One','One D','Cooper','Cooper D','Cooper S','Cooper SD','John Cooper Works','One','One D','Cooper','Cooper D','Cooper S','Cooper SD','John Cooper Works','One','Cooper','Cooper S','Cooper SD','John Cooper Works','Cooper','Cooper S','Cooper SD','John Cooper Works','Cooper','Cooper ALL4','Cooper D','Cooper D ALL4','Cooper S','Cooper S ALL4','Cooper SD','Cooper SD ALL4','John Cooper Works','One','One D','Cooper','Cooper ALL4','Cooper D','Cooper D ALL4','Cooper S','Cooper S ALL4','Cooper SD','Cooper SD ALL4','John Cooper Works','Phantom','Phantom Extend Wheelbase','Phantom','Phantom Extend Wheelbase','Phantom Drophead coupe','Phantom coupe','Cullinan','Cullinan Black Badge','GHOST','GHOST Extend Wheelbase','WRAITH','DAWN','X5 3.0i','X5 4.4i','X5 4.6is','X5 4.8is','X5 3.0si','X5 4.8i','X5 M','X5 Xdrive35i','X5 Xdrive40i','X5 Xdrive50i','X6 M','X6 Xdrive35i','X6 Xdrive40i','X6 Xdrive50i','X3 2.0i','X3 2.5i','X3 2.5si','X3 3.0i','X3 3.0si','X1 sDrive16i','X1 sDrive18i','X1 sDrive20i','X1 sDrive28i','X1 xDrive20i','X1 xDrive25i','X1 xDrive28i','X1 xDrive35i','X5 sDrive35i','X5 xDrive28i','X5 xDrive35i','X5 xDrive50i','X6 sdrive35i','X6 Xdrive28i','X6 Xdrive35i','X6 Xdrive50i','X3 28iX','X3 sdrive20i','X3 sdrive28i','X3 Xdrive28i','X3 Xdrive20i','X3 Xdrive28i','X3 Xdrive35i','X4 m40i','X4 Xdrive20i','X4 Xdrive28i','X4 Xdrive35i','X2 M35i','X2 sDrive18i','X2 sDrive20i','X2 sDrive25i','X2 sDrive28i','X2 XDrive20i','X2 XDrive25i','X2 XDrive28i','X1 sDrive18i','X1 sDrive20i','X1 sDrive28i','X1 xDrive20i','X1 xDrive25i','X1 xDrive28i','X1 sDrive18Li','X1 sDrive20Li','X1 sDrive25Li','X1 xDrive20Li','X1 xDrive25Li','X5 M','X6 M','X5 M','X6 M','X3 M','X4 M','X3 M40i','X3 sdrive20i','X3 sdrive30i','X3 Xdrive20i','X3 Xdrive30e','X3 Xdrive30i','X4 M40i','X4 sdrive20i','X4 sdrive30i','X6 M50i','X6 sdrive40i','X6 Xdrive30i','X6 Xdrive40i','X7 M50i','X7 sdrive40i','X7 Xdrive30i','X3 Xdrive25i','X3 Xdrive28i','X3 Xdrive30i','Z8','Z4 2.0i','Z4 2.2i','Z4 2.5i','Z4 2.5si','Z4 3.0i','Z4 3.0si','Z4 M 3.2','Z4 3.0si','Z4 M 3.2','Z4 SDrive18i','Z4 SDrive20i','Z4 SDrive23i','Z4 SDrive28i','Z4 SDrive30i','Z4 SDrive35i','Z4 SDrive35is','Z4 M40i','Z4 SDrive20i','Z4 SDrive30i'];
        $bmw_sizes = [
            'SERIES 1', //      id=1
            'SERIES 2', //      id=2
            'SERIES 3', //      id=3
            'SERIES 4', //      id=4
            'SERIES 5', //      id=5
            'SERIES 6', //      id=6
            'SERIES 7', //      id=7
            'SERIES 8', //      id=8
            'SERIES I', //      id=9
            'SERIES X', //      id=10
            'SERIES Z', //      id=11
        ];
        foreach ($bmw_sizes as $size)
        {
            CarSize::create([
                'name' => $size,
                'car_type_id' => 1, // BMW
            ]);
        }

        CarSize::create([
            'name' => 'MINI', // id 12
            'car_type_id' => 2, // id=2 => MINI
        ]);

        CarSize::create([
            'name' => 'Rolls-Royce', // id 13
            'car_type_id' => 3, // id=3 => Rolls-Royce
        ]);
    }
}
