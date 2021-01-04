<?php
namespace AwemaPL\Storage\User\Sections\Products\Services;

use AwemaPL\Storage\User\Sections\Products\Services\Contracts\Availability as AvailabilityContract;
use ReflectionClass;

class Availability implements AvailabilityContract
{
    const IMMEDIATE = 'immediate';
    const ONE_DAY = '1';
    const TWO_DAYS = '2';
    const THREE_DAYS = '3';
    const FOUR_DAYS = '4';
    const FIVE_DAYS = '5';
    const SIX_DAYS = '6';
    const SEVEN_DAYS = '7';
    const EIGHT_DAYS = '8';
    const NINE_DAYS = '9';
    const TEN_DAYS = '10';
    const ELEVEN_DAYS = '11';
    const TWELVE_DAYS = '12';
    const THIRTEEN_DAYS = '13';
    const FOURTEEN_DAYS = '14';
    const TWENTY_ONE_DAYS = '21';
    const THIRTY_DAYS = '30';
    const SIXTY_DAYS = '60';
    const NINETY_DAYS = '90';
    const TO_TWO_DAYS = 'to_2';
    const TO_THREE_DAYS ='to_3';
    const TO_FOUR_DAYS ='to_4';
    const TO_FIVE_DAYS ='to_5';
    const TO_SIX_DAYS ='to_6';
    const TO_SEVEN_DAYS ='to_7';
    const TO_FOURTEEN_DAYS ='to_14';
    const TO_TWENTY_ONE_DAYS ='to_21';
    const TO_THIRTY_DAYS ='to_30';
    const FROM_ONE_TO_TWO_DAYS = 'from_1_to_2';
    const FROM_ONE_TO_THREE_DAYS = 'from_1_to_3';
    const FROM_TWO_TO_THREE_DAYS = 'from_2_to_3';
    const FROM_TWO_TO_FOUR_DAYS = 'from_2_to_4';
    const FROM_TWO_TO_FIVE_DAYS = 'from_2_to_5';
    const FROM_THREE_TO_FOUR_DAYS = 'from_3_to_4';
    const FROM_THREE_TO_FIVE_DAYS = 'from_3_to_5';
    const FROM_FIVE_TO_SEVEN_DAYS = 'from_5_to_7';
    const PHONE = 'phone';
    const UNAVAILABLE = 'unavailable';

    /**
     * Get availabilities
     *
     * @return array
     */
    public function getAvailabilities():array  {
        $reflectionClass = new ReflectionClass(Availability::class);
        $data = [];
        foreach ($reflectionClass->getConstants() as $key => $availability){
            $key = mb_strtolower($key);
            array_push($data, [
                'name' =>  _p('storage::pages.user.product.availabilities.' . $key, str_replace('_', ' ', $key)),
                'availability' => $availability,
            ]);
        }
        return $data;
    }

    /**
     * Get availability name
     *
     * @param $value
     * @return string
     */
    public function getAvailabilityName($value):string{
        foreach ($this->getAvailabilities() as $availability){
            if ($value === $availability['availability']){
               return $availability['name'];
            }
        }
        return $value;
    }

    /**
     * Get default
     *
     * @return string
     */
    public function getDefault(): string{
        return self::IMMEDIATE;
    }
}
