<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = [
            // United States
            ['country_iso' => 'USA', 'name' => 'Alabama', 'abbreviation' => 'AL'],
            ['country_iso' => 'USA', 'name' => 'Alaska', 'abbreviation' => 'AK'],
            ['country_iso' => 'USA', 'name' => 'Arizona', 'abbreviation' => 'AZ'],
            ['country_iso' => 'USA', 'name' => 'Arkansas', 'abbreviation' => 'AR'],
            ['country_iso' => 'USA', 'name' => 'California', 'abbreviation' => 'CA'],
            ['country_iso' => 'USA', 'name' => 'Colorado', 'abbreviation' => 'CO'],
            ['country_iso' => 'USA', 'name' => 'Connecticut', 'abbreviation' => 'CT'],
            ['country_iso' => 'USA', 'name' => 'Delaware', 'abbreviation' => 'DE'],
            ['country_iso' => 'USA', 'name' => 'Florida', 'abbreviation' => 'FL'],
            ['country_iso' => 'USA', 'name' => 'Georgia', 'abbreviation' => 'GA'],
            ['country_iso' => 'USA', 'name' => 'Hawaii', 'abbreviation' => 'HI'],
            ['country_iso' => 'USA', 'name' => 'Idaho', 'abbreviation' => 'ID'],
            ['country_iso' => 'USA', 'name' => 'Illinois', 'abbreviation' => 'IL'],
            ['country_iso' => 'USA', 'name' => 'Indiana', 'abbreviation' => 'IN'],
            ['country_iso' => 'USA', 'name' => 'Iowa', 'abbreviation' => 'IA'],
            ['country_iso' => 'USA', 'name' => 'Kansas', 'abbreviation' => 'KS'],
            ['country_iso' => 'USA', 'name' => 'Kentucky', 'abbreviation' => 'KY'],
            ['country_iso' => 'USA', 'name' => 'Louisiana', 'abbreviation' => 'LA'],
            ['country_iso' => 'USA', 'name' => 'Maine', 'abbreviation' => 'ME'],
            ['country_iso' => 'USA', 'name' => 'Maryland', 'abbreviation' => 'MD'],
            ['country_iso' => 'USA', 'name' => 'Massachusetts', 'abbreviation' => 'MA'],
            ['country_iso' => 'USA', 'name' => 'Michigan', 'abbreviation' => 'MI'],
            ['country_iso' => 'USA', 'name' => 'Minnesota', 'abbreviation' => 'MN'],
            ['country_iso' => 'USA', 'name' => 'Mississippi', 'abbreviation' => 'MS'],
            ['country_iso' => 'USA', 'name' => 'Missouri', 'abbreviation' => 'MO'],
            ['country_iso' => 'USA', 'name' => 'Montana', 'abbreviation' => 'MT'],
            ['country_iso' => 'USA', 'name' => 'Nebraska', 'abbreviation' => 'NE'],
            ['country_iso' => 'USA', 'name' => 'Nevada', 'abbreviation' => 'NV'],
            ['country_iso' => 'USA', 'name' => 'New Hampshire', 'abbreviation' => 'NH'],
            ['country_iso' => 'USA', 'name' => 'New Jersey', 'abbreviation' => 'NJ'],
            ['country_iso' => 'USA', 'name' => 'New Mexico', 'abbreviation' => 'NM'],
            ['country_iso' => 'USA', 'name' => 'New York', 'abbreviation' => 'NY'],
            ['country_iso' => 'USA', 'name' => 'North Carolina', 'abbreviation' => 'NC'],
            ['country_iso' => 'USA', 'name' => 'North Dakota', 'abbreviation' => 'ND'],
            ['country_iso' => 'USA', 'name' => 'Ohio', 'abbreviation' => 'OH'],
            ['country_iso' => 'USA', 'name' => 'Oklahoma', 'abbreviation' => 'OK'],
            ['country_iso' => 'USA', 'name' => 'Oregon', 'abbreviation' => 'OR'],
            ['country_iso' => 'USA', 'name' => 'Pennsylvania', 'abbreviation' => 'PA'],
            ['country_iso' => 'USA', 'name' => 'Rhode Island', 'abbreviation' => 'RI'],
            ['country_iso' => 'USA', 'name' => 'South Carolina', 'abbreviation' => 'SC'],
            ['country_iso' => 'USA', 'name' => 'South Dakota', 'abbreviation' => 'SD'],
            ['country_iso' => 'USA', 'name' => 'Tennessee', 'abbreviation' => 'TN'],
            ['country_iso' => 'USA', 'name' => 'Texas', 'abbreviation' => 'TX'],
            ['country_iso' => 'USA', 'name' => 'Utah', 'abbreviation' => 'UT'],
            ['country_iso' => 'USA', 'name' => 'Vermont', 'abbreviation' => 'VT'],
            ['country_iso' => 'USA', 'name' => 'Virginia', 'abbreviation' => 'VA'],
            ['country_iso' => 'USA', 'name' => 'Washington', 'abbreviation' => 'WA'],
            ['country_iso' => 'USA', 'name' => 'West Virginia', 'abbreviation' => 'WV'],
            ['country_iso' => 'USA', 'name' => 'Wisconsin', 'abbreviation' => 'WI'],
            ['country_iso' => 'USA', 'name' => 'Wyoming', 'abbreviation' => 'WY'],

            // Canada
            ['country_iso' => 'CAN', 'name' => 'Alberta', 'abbreviation' => 'AB'],
            ['country_iso' => 'CAN', 'name' => 'British Columbia', 'abbreviation' => 'BC'],
            ['country_iso' => 'CAN', 'name' => 'Manitoba', 'abbreviation' => 'MB'],
            ['country_iso' => 'CAN', 'name' => 'New Brunswick', 'abbreviation' => 'NB'],
            ['country_iso' => 'CAN', 'name' => 'Newfoundland and Labrador', 'abbreviation' => 'NL'],
            ['country_iso' => 'CAN', 'name' => 'Nova Scotia', 'abbreviation' => 'NS'],
            ['country_iso' => 'CAN', 'name' => 'Ontario', 'abbreviation' => 'ON'],
            ['country_iso' => 'CAN', 'name' => 'Prince Edward Island', 'abbreviation' => 'PE'],
            ['country_iso' => 'CAN', 'name' => 'Quebec', 'abbreviation' => 'QC'],
            ['country_iso' => 'CAN', 'name' => 'Saskatchewan', 'abbreviation' => 'SK'],
            ['country_iso' => 'CAN', 'name' => 'Northwest Territories', 'abbreviation' => 'NT'],
            ['country_iso' => 'CAN', 'name' => 'Nunavut', 'abbreviation' => 'NU'],
            ['country_iso' => 'CAN', 'name' => 'Yukon', 'abbreviation' => 'YT'],

            // Australia
            ['country_iso' => 'AUS', 'name' => 'New South Wales', 'abbreviation' => 'NSW'],
            ['country_iso' => 'AUS', 'name' => 'Victoria', 'abbreviation' => 'VIC'],
            ['country_iso' => 'AUS', 'name' => 'Queensland', 'abbreviation' => 'QLD'],
            ['country_iso' => 'AUS', 'name' => 'South Australia', 'abbreviation' => 'SA'],
            ['country_iso' => 'AUS', 'name' => 'Western Australia', 'abbreviation' => 'WA'],
            ['country_iso' => 'AUS', 'name' => 'Tasmania', 'abbreviation' => 'TAS'],
            ['country_iso' => 'AUS', 'name' => 'Northern Territory', 'abbreviation' => 'NT'],
            ['country_iso' => 'AUS', 'name' => 'Australian Capital Territory', 'abbreviation' => 'ACT'],

            // India
            ['country_iso' => 'IND', 'name' => 'Andhra Pradesh', 'abbreviation' => 'AP'],
            ['country_iso' => 'IND', 'name' => 'Arunachal Pradesh', 'abbreviation' => 'AR'],
            ['country_iso' => 'IND', 'name' => 'Assam', 'abbreviation' => 'AS'],
            ['country_iso' => 'IND', 'name' => 'Bihar', 'abbreviation' => 'BR'],
            ['country_iso' => 'IND', 'name' => 'Chhattisgarh', 'abbreviation' => 'CT'],
            ['country_iso' => 'IND', 'name' => 'Goa', 'abbreviation' => 'GA'],
            ['country_iso' => 'IND', 'name' => 'Gujarat', 'abbreviation' => 'GJ'],
            ['country_iso' => 'IND', 'name' => 'Haryana', 'abbreviation' => 'HR'],
            ['country_iso' => 'IND', 'name' => 'Himachal Pradesh', 'abbreviation' => 'HP'],
            ['country_iso' => 'IND', 'name' => 'Jharkhand', 'abbreviation' => 'JH'],
            ['country_iso' => 'IND', 'name' => 'Karnataka', 'abbreviation' => 'KA'],
            ['country_iso' => 'IND', 'name' => 'Kerala', 'abbreviation' => 'KL'],
            ['country_iso' => 'IND', 'name' => 'Madhya Pradesh', 'abbreviation' => 'MP'],
            ['country_iso' => 'IND', 'name' => 'Maharashtra', 'abbreviation' => 'MH'],
            ['country_iso' => 'IND', 'name' => 'Manipur', 'abbreviation' => 'MN'],
            ['country_iso' => 'IND', 'name' => 'Meghalaya', 'abbreviation' => 'ML'],
            ['country_iso' => 'IND', 'name' => 'Mizoram', 'abbreviation' => 'MZ'],
            ['country_iso' => 'IND', 'name' => 'Nagaland', 'abbreviation' => 'NL'],
            ['country_iso' => 'IND', 'name' => 'Odisha', 'abbreviation' => 'OR'],
            ['country_iso' => 'IND', 'name' => 'Punjab', 'abbreviation' => 'PB'],
            ['country_iso' => 'IND', 'name' => 'Rajasthan', 'abbreviation' => 'RJ'],
            ['country_iso' => 'IND', 'name' => 'Sikkim', 'abbreviation' => 'SK'],
            ['country_iso' => 'IND', 'name' => 'Tamil Nadu', 'abbreviation' => 'TN'],
            ['country_iso' => 'IND', 'name' => 'Telangana', 'abbreviation' => 'TG'],
            ['country_iso' => 'IND', 'name' => 'Tripura', 'abbreviation' => 'TR'],
            ['country_iso' => 'IND', 'name' => 'Uttar Pradesh', 'abbreviation' => 'UP'],
            ['country_iso' => 'IND', 'name' => 'Uttarakhand', 'abbreviation' => 'UK'],
            ['country_iso' => 'IND', 'name' => 'West Bengal', 'abbreviation' => 'WB'],

            // Add more states or regions as needed
        ];

        foreach ($states as $state) {
            $country = Country::where('iso_code', $state['country_iso'])->first();

            if ($country) {
                State::create([
                    'country_id' => $country->id,
                    'name' => $state['name'],
                    'abbreviation' => $state['abbreviation'],
                ]);
            }
        }
    }
}
