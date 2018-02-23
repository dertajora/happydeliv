<?php

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //seeder installation_modes
		DB::table('roles')->truncate();
		$roles = array(
			array('name' => 'Consument'),
			array('name' => 'Courrier'),
			array('name' => 'Staff Admin'),
			array('name' => 'Owner')			
		);
		
		foreach($roles as $row)
			DB::table('roles')->insert($row);
    }
}
